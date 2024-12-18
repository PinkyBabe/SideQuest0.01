-- Create the database
CREATE DATABASE IF NOT EXISTS sidequest_db;
USE sidequest_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'faculty', 'student') NOT NULL,
    room_number VARCHAR(50) AFTER role,
    office_name VARCHAR(100) AFTER room_number,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    faculty_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES users(id)
);

-- Tasks/Quests table
CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    points INT DEFAULT 0,
    status ENUM('available', 'in_progress', 'completed') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student Progress table
CREATE TABLE student_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    task_id INT,
    progress INT DEFAULT 0,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id)
);

-- Achievements table
CREATE TABLE achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    points_required INT DEFAULT 0
);

-- Student Achievements table
CREATE TABLE student_achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    achievement_id INT,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (achievement_id) REFERENCES achievements(id)
);

-- Activity Log table
CREATE TABLE activity_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- First, clear any existing admin users (optional)
DELETE FROM users WHERE email = 'admin@example.com';

-- Then insert the new admin user
INSERT INTO users (first_name, last_name, email, password, role) 
VALUES (
    'Admin', 
    'User', 
    'admin@sidequest.com', 
    'password', -- We'll replace this with a proper hash
    'admin'
); 