CREATE TABLE asset (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key VARCHAR(255),
    src VARCHAR(255),
    value BLOB
);

CREATE UNIQUE INDEX idx_key ON asset(key);