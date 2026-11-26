#!/usr/bin/env bash
if [ -z "${BASH_VERSION:-}" ]; then exec bash "$0" "$@"; fi
source "$(dirname "${BASH_SOURCE[0]}")/common.sh" || exit 2

title "Database"
is_running "$DB_CONTAINER" || { bad "$DB_CONTAINER tidak running"; exit 1; }
set -o pipefail
health=$("$CONTAINER_ENGINE" container inspect "$DB_CONTAINER" | python3 -c '
import json, sys
state = json.load(sys.stdin)[0]["State"]
health = state.get("Health") or state.get("Healthcheck") or {}
print(health.get("Status") or "none")
') || { bad "Gagal membaca healthcheck"; exit 1; }
case "$health" in
    healthy) ok "healthcheck: healthy" ;;
    starting) warn "healthcheck: masih starting" ;;
    none) warn "container tidak punya healthcheck" ;;
    *) bad "healthcheck: $health" ;;
esac

if ! out=$(db_query "SELECT 1;") || [[ $out != 1 ]]; then
    bad "Gagal login ke database aplikasi"
    exit 1
fi
ok "bisa login sebagai user aplikasi"
tables=$(db_query "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE();") || { bad "Gagal menghitung tabel"; exit 1; }
if [[ $tables =~ ^[1-9][0-9]*$ ]]; then ok "jumlah tabel: $tables"; else bad "Database kosong atau jumlah tabel tidak valid"; fi

echo "  Jumlah baris:"
read -r -a table_names <<< "${TABLES:-users events ticket_types ticket_orders tickets booths booth_bookings rundowns migrations}"
for t in "${table_names[@]}"; do
    [[ $t =~ ^[a-zA-Z0-9_]+$ ]] || { bad "Nama tabel tidak valid: $t"; continue; }
    exists=$(db_query "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '$t';") || { bad "Gagal memeriksa $t"; continue; }
    [[ $exists == 1 ]] || continue
    count=$(db_query "SELECT COUNT(*) FROM \`$t\`;") || { bad "Gagal menghitung baris $t"; continue; }
    printf "    %-16s %s\n" "$t" "$count"
done
exit "$FAIL"
