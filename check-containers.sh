#!/usr/bin/env bash
if [ -z "${BASH_VERSION:-}" ]; then exec bash "$0" "$@"; fi
source "$(dirname "${BASH_SOURCE[0]}")/common.sh" || exit 2

title "Status container"
for container in "$DB_CONTAINER" "$APP_CONTAINER"; do
    if ! state=$(get_state "$container"); then bad "Gagal memeriksa $container"
    elif [[ $state == running ]]; then ok "$container running"
    else bad "$container status: $state"
    fi
done
exit "$FAIL"
