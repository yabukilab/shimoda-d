CREATE DATABASE IF NOT EXISTS game_database;

USE game_database;

CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    introduction TEXT NOT NULL,
    user_code VARCHAR(50) NOT NULL,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id INT NOT NULL,
    comment TEXT NOT NULL,
    user_name VARCHAR(255) NOT NULL DEFAULT '名無し',
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (game_id) REFERENCES games(id)
);
