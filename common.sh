#!/usr/bin/env bash
# Muat dari skrip pemeriksaan yang dijalankan dengan Bash.
if [ -z "${BASH_VERSION:-}" ]; then
    echo 'Gunakan bash untuk menjalankan skrip pemeriksaan.' >&2
    return 2
fi

CONTAINER_ENGINE="${CONTAINER_ENGINE:-auto}"  # auto, docker, atau podman
FAIL=0
title() { printf '== %s ==\n' "$*"; }
ok()    { printf '  [OK] %s\n' "$*"; }
warn()  { printf '  [WARN] %s\n' "$*"; }
bad()   { printf '  [FAIL] %s\n' "$*" >&2; FAIL=1; }

select_engine() {
    local engine
    local -a found=()
    if [[ $CONTAINER_ENGINE == auto ]]; then
        for engine in docker podman; do
            if command -v "$engine" >/dev/null; then found+=("$engine"); fi
        done
        case ${#found[@]} in
            0) bad "Docker dan Podman tidak ditemukan di PATH shell ini."; return 2 ;;
            1) CONTAINER_ENGINE=${found[0]} ;;
            *) bad "Kedua CLI tersedia. Pilih docker atau podman di common.sh."; return 2 ;;
        esac
    fi
    case $CONTAINER_ENGINE in
        docker|podman) ;;
        *) bad "Engine tidak valid: $CONTAINER_ENGINE"; return 2 ;;
    esac
    command -v "$CONTAINER_ENGINE" >/dev/null || { bad "$CONTAINER_ENGINE tidak ditemukan di PATH"; return 2; }
    "$CONTAINER_ENGINE" info >/dev/null || { bad "Gagal terhubung ke $CONTAINER_ENGINE"; return 2; }
}

get_state() { "$CONTAINER_ENGINE" container inspect -f '{{.State.Status}}' "$1"; }
is_running() { [[ $(get_state "$1") == running ]]; }
db_query() {
    "$CONTAINER_ENGINE" exec -i "$DB_CONTAINER" sh -c 'exec mariadb -N -B --user="$MARIADB_USER" --password="$MARIADB_PASSWORD" "$MARIADB_DATABASE"' <<< "$1"
}

find_container() {
    local service="$1" ids
    ids=$("$CONTAINER_ENGINE" compose ps -a -q "$service") || return 1
    if [[ -z "$ids" ]]; then
        printf "Container untuk service '%s' belum ditemukan.\n" "$service" >&2
        return 1
    fi
    if [[ "$ids" == *$'\n'* ]]; then
        printf "Service '%s' punya beberapa container. Tentukan ID secara eksplisit.\n" "$service" >&2
        return 1
    fi
    printf '%s\n' "$ids"
}

select_engine || return 2

if [[ -z ${APP_CONTAINER:-} ]]; then
    APP_CONTAINER=$(find_container "${APP_SERVICE:-app}") || return 1
fi
if [[ -z ${DB_CONTAINER:-} ]]; then
    DB_CONTAINER=$(find_container "${DB_SERVICE:-db}") || return 1
fi
