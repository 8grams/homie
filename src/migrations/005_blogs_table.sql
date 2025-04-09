-- tags table
CREATE TABLE tags (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tag VARCHAR(255)
);
CREATE UNIQUE INDEX idx_tag ON tags(tag);

-- categories table
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category VARCHAR(255)
);
CREATE UNIQUE INDEX idx_category ON categories(category);

-- blogs table
CREATE TABLE blogs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    hero_image VARCHAR(255),
    content TEXT NOT NULL,
    slug VARCHAR(255) NOT NULL,
    excerpt TEXT NOT NULL,
    header_code_injection TEXT,
    footer_code_injection TEXT,
    -- meta tags
    meta_title VARCHAR(255),
    meta_description VARCHAR(255),
    meta_keywords VARCHAR(255),
    meta_image VARCHAR(255),
    meta_url VARCHAR(255),
    category_id INTEGER,
    language VARCHAR(3),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);  
CREATE INDEX idx_category_id ON blogs(category_id);
CREATE UNIQUE INDEX idx_slug ON blogs(slug);

-- tags_blogs table
CREATE TABLE tags_blogs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tag_id INTEGER,
    blog_id INTEGER,
    FOREIGN KEY (tag_id) REFERENCES tags(id),
    FOREIGN KEY (blog_id) REFERENCES blogs(id)
);
CREATE INDEX idx_tag_id ON tags_blogs(tag_id);
CREATE INDEX idx_blog_id ON tags_blogs(blog_id);

