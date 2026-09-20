#!/bin/sh
# Loads the reconstructed schema from database/*.sql on first boot of an empty
# data directory. Mounted read-only at /schema by the compose files.
#
# tables.sql, if present, is treated as the authoritative full schema and the
# per-table files are skipped. Foreign key checks are disabled during load so
# file order does not matter.
set -eu

SCHEMA_DIR=/schema
[ -d "$SCHEMA_DIR" ] || { echo "No $SCHEMA_DIR mounted, skipping schema load."; exit 0; }

load() {
    echo "  -> $(basename "$1")"
    mysql --user=root --password="${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" < "$1"
}

echo "Loading schema into ${MYSQL_DATABASE}..."
mysql --user=root --password="${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" \
    -e "SET FOREIGN_KEY_CHECKS=0;"

if [ -f "$SCHEMA_DIR/tables.sql" ]; then
    load "$SCHEMA_DIR/tables.sql"
else
    for f in "$SCHEMA_DIR"/*.sql; do
        [ -e "$f" ] || continue
        load "$f"
    done
fi

mysql --user=root --password="${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" \
    -e "SET FOREIGN_KEY_CHECKS=1;"
echo "Schema loaded."
