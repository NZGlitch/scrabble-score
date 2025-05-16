CREATE TABLE IF NOT EXISTS member (
    member_number INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT,
    phone TEXT,
    grade CHAR(1),
    password_salt TEXT,
    password_hash TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);
