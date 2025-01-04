CREATE DATABASE sse3150;

USE sse3150;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- 存储加密后的密码
);

-- 添加一些测试用户
INSERT INTO users (username, password)
VALUES ('test', MD5('password')); -- 使用 MD5 加密
