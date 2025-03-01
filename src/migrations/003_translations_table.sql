CREATE TABLE translation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label VARCHAR(255),
    locale VARCHAR(255),
    value BLOB
);

CREATE UNIQUE INDEX idx_label_locale ON translation(Label, Locale);