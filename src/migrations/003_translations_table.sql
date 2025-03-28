CREATE TABLE translations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label VARCHAR(255),
    locale VARCHAR(255),
    url_hash VARCHAR(255) DEFAULT NULL,
    value BLOB
);

CREATE UNIQUE INDEX idx_label_locale ON translations(label, locale, url_hash);