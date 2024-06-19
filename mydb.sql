-- データベースが存在する場合は削除します
DROP DATABASE IF EXISTS mydb;

-- 新しいデータベースを作成します
CREATE DATABASE mydb;

USE mydb;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS games;

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    introduction TEXT NOT NULL,
    user_code VARCHAR(50) NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    comment TEXT NOT NULL,
    user_name VARCHAR(255) NOT NULL DEFAULT '名無し',
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id)
);