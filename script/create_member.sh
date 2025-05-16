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

# Set paths
DB_PATH="data/scrabble.db"
PHP_INIT_SCRIPT="includes/db.php"

# Initialize DB if missing
if [ ! -f "$DB_PATH" ]; then
    echo "📦 Database not found. Initializing via PHP..."
    php -r "require_once '$PHP_INIT_SCRIPT';"
    if [ ! -f "$DB_PATH" ]; then
        echo "❌ Failed to create database."
        exit 1
    fi
fi

# Generate salt and hash
SALT=$(LC_CTYPE=C head -c 200 /dev/urandom | tr -dc 'A-Za-z0-9' | head -c 100 | md5sum | awk '{print $1}')
COMBINED="${SALT}__${PASSWORD}"
HASH=$(echo -n "$COMBINED" | md5sum | awk '{print $1}')
NOW=$(date '+%Y-%m-%d %H:%M:%S')

# Insert into SQLite
sqlite3 "$DB_PATH" <<SQL
INSERT INTO member (first_name, last_name, email, password_salt, password_hash, created_at, updated_at)
VALUES ('$FIRST_NAME', '$LAST_NAME', '$EMAIL', '$SALT', '$HASH', '$NOW', '$NOW');
SQL

echo "✅ Member '$FIRST_NAME $LAST_NAME' added successfully."
