-- Author: Beulah Nwokotubo
-- File Name: initDB.sql
-- Date Created: 23 November 2023
-- Description: SQL script for creating all database objects.

-- Drop the existing database if it exists
DROP DATABASE IF EXISTS task_management;

-- Create a new database
CREATE DATABASE task_management;

-- Grant privileges to the root user
GRANT ALL PRIVILEGES ON task_management.* TO 'root'@'localhost' IDENTIFIED BY 'MIGNONette99?';

-- Flush privileges
FLUSH PRIVILEGES;

-- Use the task_management database
USE task_management;

-- Create a table for tasks
CREATE TABLE IF NOT EXISTS tasks (
    taskId INT AUTO_INCREMENT PRIMARY KEY,
    taskName VARCHAR(255) NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL,
    dueDate DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT 
);

-- Create a table for users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL
);

