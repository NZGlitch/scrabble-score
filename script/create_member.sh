#!/bin/bash

# Usage check
if [ "$#" -ne 4 ]; then
    echo "Usage: $0 FIRST_NAME LAST_NAME EMAIL PASSWORD"
    exit 1
fi

FIRST_NAME="$1"
LAST_NAME="$2"
EMAIL="$3"
PASSWORD="$4"

# Paths
DB_PATH="data/scrabble.db"
PHP_INIT_SCRIPT="include/db.php"

# Ensure DB is initialized
if [ ! -f "$DB_PATH" ]; then
    echo "📦 Database not found. Initializing via PHP..."
    php -r "require_once '$PHP_INIT_SCRIPT';"
    if [ ! -f "$DB_PATH" ]; then
        echo "❌ Failed to create database."
        exit 1
    fi
fi

# Generate 100-character alphanumeric salt
generate_salt() {
    local SALT=""
    while [ ${#SALT} -lt 100 ]; do
        CHUNK=$(openssl rand -base64 48 | tr -dc 'A-Za-z0-9' | head -c 100)
        SALT="${SALT}${CHUNK}"
    done
    echo "${SALT:0:100}"
}

SALT=$(generate_salt)
COMBINED="${SALT}__${PASSWORD}"
HASH=$(echo -n "$COMBINED" | openssl dgst -sha256 | awk '{print $2}')
NOW=$(date '+%Y-%m-%d %H:%M:%S')

# Insert into SQLite
sqlite3 "$DB_PATH" <<SQL
INSERT INTO member (first_name, last_name, email, password_salt, password_hash, created_at, updated_at)
VALUES ('$FIRST_NAME', '$LAST_NAME', '$EMAIL', '$SALT', '$HASH', '$NOW', '$NOW');
SQL

echo "✅ Member '$FIRST_NAME $LAST_NAME' added successfully."
