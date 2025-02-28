CREATE TABLE cache (
    CreatedTime INT,
    ExpiredTime INT,
    Key VARCHAR(255) PRIMARY Key UNIQUE,
    Value BLOB
);