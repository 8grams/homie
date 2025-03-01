CREATE TABLE cache (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    created_time INT,
    expired_time INT,
    key VARCHAR(255) UNIQUE,
    value BLOB
);