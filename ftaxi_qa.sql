-- F-Taxi Telecaller Q&A Database
-- IMPORTANT: Select the database `ftaxi_qa` in phpMyAdmin before importing.
-- This file does NOT create or select a database.

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS stage2_results;
DROP TABLE IF EXISTS stage2_answers;
DROP TABLE IF EXISTS stage2_questions;
DROP TABLE IF EXISTS stage1_attempts;
DROP TABLE IF EXISTS stage1_questions;
DROP TABLE IF EXISTS training_videos;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS admins;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(30),
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE training_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stage TINYINT NOT NULL,
    title VARCHAR(200) NOT NULL,
    video_path VARCHAR(255) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stage1_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_option CHAR(1) NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stage1_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    score INT NOT NULL,
    total INT NOT NULL,
    percentage DECIMAL(5,2) NOT NULL,
    status ENUM('PASSED','FAILED') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stage2_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stage2_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    question_id INT NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES stage2_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE stage2_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    status ENUM('PENDING','PASSED','FAILED') DEFAULT 'PENDING',
    evaluated_by INT NULL,
    evaluated_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluated_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (name, username, password) VALUES
('F-Taxi Admin', 'admin', '$2y$10$lLJWZv.WZes6PsM05NTvzu14hLQOPdtCOnaP7eHhBL2bRi2ipwtcq');

INSERT INTO employees (name, email, phone, password) VALUES
('Demo Employee', 'employee@ftaxi.in', '9876543210', '$2y$10$GJiUiThjUy61I1d7gc3jyOnm2o59ZTb3Sc6dT8UtkMLyKbJq.3nj.');

INSERT INTO stage1_questions
(question, option_a, option_b, option_c, option_d, correct_option) VALUES
('What should a telecaller do first when answering a customer call?',
 'Greet the customer professionally', 'End the call', 'Ask for payment', 'Transfer immediately', 'A'),
('Which details are important for a taxi booking?',
 'Pickup and destination', 'Only customer name', 'Only vehicle color', 'Only driver age', 'A');

INSERT INTO stage2_questions (question) VALUES
('A customer says the driver has not arrived after 15 minutes. What would you say and do?'),
('A customer is angry about a booking delay. Explain how you would handle the call professionally.');
