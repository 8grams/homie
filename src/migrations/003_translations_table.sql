CREATE TABLE translation (
    Label VARCHAR(255),
    Locale VARCHAR(255),
    Value BLOB
);

CREATE UNIQUE INDEX idx_label_locale ON translation(Label, Locale);