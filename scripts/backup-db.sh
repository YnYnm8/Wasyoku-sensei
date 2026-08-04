#!/usr/bin/env bash
# データベースをバックアップする（mysqldump） / Sauvegarde de la base de données (mysqldump)
# 使い方 / Usage : ./scripts/backup-db.sh
set -euo pipefail

cd "$(dirname "$0")/.."

MYSQL_DATABASE="${MYSQL_DATABASE:-app}"
MYSQL_USER="${MYSQL_USER:-app}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-!ChangeMe!}"

BACKUP_DIR="backups"
mkdir -p "$BACKUP_DIR"

TIMESTAMP="$(date +%Y%m%d_%H%M%S)"
BACKUP_FILE="${BACKUP_DIR}/backup_${TIMESTAMP}.sql"

echo "データベース '${MYSQL_DATABASE}' をバックアップ中... / Sauvegarde de la base '${MYSQL_DATABASE}' en cours..."

docker compose exec -T database mysqldump \
    --no-tablespaces \
    -u"${MYSQL_USER}" \
    -p"${MYSQL_PASSWORD}" \
    "${MYSQL_DATABASE}" > "${BACKUP_FILE}"

echo "バックアップ完了 / Sauvegarde terminée : ${BACKUP_FILE}"
