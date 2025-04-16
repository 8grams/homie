CREATE TABLE links (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    link VARCHAR(255),
    locale VARCHAR(255),
    url_hash VARCHAR(255) DEFAULT NULL,
    value VARCHAR(255)
);

CREATE UNIQUE INDEX idx_link_locale ON links(link, locale, url_hash);