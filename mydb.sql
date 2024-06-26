-- 新しいデータベースを作成します
CREATE DATABASE IF NOT EXISTS shimodad;

-- shimodadデータベースを使用します
USE shimodad;

-- gamesテーブルを作成します
CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image MEDIUMBLOB NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    introduction TEXT NOT NULL,
    user_code VARCHAR(50) NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- commentsテーブルを作成します
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    comment TEXT NOT NULL,
    user_name VARCHAR(255) NOT NULL DEFAULT '名無し',
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id)
);
