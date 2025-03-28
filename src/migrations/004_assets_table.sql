CREATE TABLE assets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    key VARCHAR(255),
    src VARCHAR(255),
    url_hash VARCHAR(255),
    value BLOB
);

CREATE UNIQUE INDEX idx_key ON assets(key, url_hash);