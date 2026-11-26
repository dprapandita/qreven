#!/usr/bin/env bash
if [ -z "${BASH_VERSION:-}" ]; then exec bash "$0" "$@"; fi
source "$(dirname "${BASH_SOURCE[0]}")/common.sh" || exit 2

title "Laravel -> database"
is_running "$APP_CONTAINER" || { bad "$APP_CONTAINER tidak running"; exit 1; }
if ! out=$("$CONTAINER_ENGINE" exec "$APP_CONTAINER" php artisan migrate:status --no-ansi 2>&1); then
    bad "Artisan gagal"
    printf '%s\n' "$out" | tail -n 5
    exit 1
fi
ok "app konek DB lewat artisan"
pending=$(awk '$NF == "Pending" { n++ } END { print n+0 }' <<< "$out")
if (( pending )); then bad "$pending migrasi pending"; else ok "semua migrasi sudah jalan"; fi
exit "$FAIL"
