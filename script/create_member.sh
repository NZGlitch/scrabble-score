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

# Generate random 100-character salt, then hash
SALT=$(head -c 200 /dev/urandom | tr -dc 'A-Za-z0-9' | head -c 100 | md5sum | awk '{print $1}')
COMBINED="${SALT}__${PASSWORD}"
HASH=$(echo -n "$COMBINED" | md5sum | awk '{print $1}')

# Timestamp
NOW=$(date '+%Y-%m-%d %H:%M:%S')

# Path to SQLite DB
DB_PATH="data/scrabble.db"

# Insert into member table
sqlite3 "$DB_PATH" <<SQL
INSERT INTO member (first_name, last_name, email, password_salt, password_hash, created_at, updated_at)
VALUES ('$FIRST_NAME', '$LAST_NAME', '$EMAIL', '$SALT', '$HASH', '$NOW', '$NOW');
SQL

echo "✅ Member '$FIRST_NAME $LAST_NAME' added successfully."
