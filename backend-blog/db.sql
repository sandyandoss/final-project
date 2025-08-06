CREATE DATABASE blog_api;
USE blog_api;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100)
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    post_id INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

--  data
INSERT INTO users (name, email) VALUES
('Sandyyyy', 'sandy@gmail.com'),
('Kate', 'kate@gmail.com');

INSERT INTO posts (title, content, user_id) VALUES
('First Post', 'This is the first post content.', 1),
('Second Post', 'Another blog post here.', 2);

INSERT INTO comments (content, post_id, user_id) VALUES
('Nice post!', 1, 2),
('Thanks for sharing!', 1, 1),
('Interesting thoughts.', 2, 1);
