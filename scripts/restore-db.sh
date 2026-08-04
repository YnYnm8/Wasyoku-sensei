#!/usr/bin/env bash
# バックアップファイルからデータベースを復元する / Restauration de la base de données depuis un fichier de sauvegarde
# 使い方 / Usage : ./scripts/restore-db.sh backups/backup_YYYYMMDD_HHMMSS.sql
set -euo pipefail

cd "$(dirname "$0")/.."

if [ $# -ne 1 ]; then
    echo "使い方 / Usage : $0 <backups/xxx.sql>"
    exit 1
fi

BACKUP_FILE="$1"

if [ ! -f "$BACKUP_FILE" ]; then
    echo "エラー：ファイルが見つかりません / Erreur : fichier introuvable : ${BACKUP_FILE}"
    exit 1
fi

MYSQL_DATABASE="${MYSQL_DATABASE:-app}"
MYSQL_USER="${MYSQL_USER:-app}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-!ChangeMe!}"

echo "警告：この操作はデータベース '${MYSQL_DATABASE}' の現在のデータを上書きします。"
echo "Attention : cette opération va écraser les données actuelles de la base '${MYSQL_DATABASE}'."
read -r -p "続行しますか？ / Continuer ? (o/N) " confirm
if [[ "$confirm" != "o" && "$confirm" != "O" ]]; then
    echo "中断しました / Annulé."
    exit 0
fi

echo "'${BACKUP_FILE}' から復元中... / Restauration depuis '${BACKUP_FILE}' en cours..."

docker compose exec -T database mysql \
    -u"${MYSQL_USER}" \
    -p"${MYSQL_PASSWORD}" \
    "${MYSQL_DATABASE}" < "${BACKUP_FILE}"

echo "復元完了 / Restauration terminée."
