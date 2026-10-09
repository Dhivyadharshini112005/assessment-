CREATE DATABASE IF NOT EXISTS ftaxi_qa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ftaxi_qa;

CREATE TABLE admins (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 username VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 phone VARCHAR(30),
 password VARCHAR(255) NOT NULL,
 status ENUM('active','inactive') DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE training_videos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 stage TINYINT NOT NULL,
 title VARCHAR(200) NOT NULL,
 video_path VARCHAR(255) NOT NULL,
 active TINYINT(1) DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
);

CREATE TABLE stage1_attempts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 employee_id INT NOT NULL,
 score INT NOT NULL,
 total INT NOT NULL,
 percentage DECIMAL(5,2) NOT NULL,
 status ENUM('PASSED','FAILED') NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE stage2_questions (
 id INT AUTO_INCREMENT PRIMARY KEY,
 question TEXT NOT NULL,
 active TINYINT(1) DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stage2_answers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 employee_id INT NOT NULL,
 question_id INT NOT NULL,
 answer TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE CASCADE,
 FOREIGN KEY(question_id) REFERENCES stage2_questions(id) ON DELETE CASCADE
);

CREATE TABLE stage2_results (
 id INT AUTO_INCREMENT PRIMARY KEY,
 employee_id INT NOT NULL,
 status ENUM('PENDING','PASSED','FAILED') DEFAULT 'PENDING',
 evaluated_by INT NULL,
 evaluated_at DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE CASCADE,
 FOREIGN KEY(evaluated_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- Demo admin: username admin, password admin123
INSERT INTO admins(name,username,password) VALUES
('F-Taxi Admin','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8p7e7wQ7Qz0yqf3a6K');

-- Demo employee: employee@ftaxi.in, password employee123
INSERT INTO employees(name,email,phone,password) VALUES
('Demo Employee','employee@ftaxi.in','9876543210','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8p7e7wQ7Qz0yqf3a6K');

INSERT INTO stage1_questions(question,option_a,option_b,option_c,option_d,correct_option) VALUES
('What should a telecaller do first when answering a customer call?','Greet the customer professionally','End the call','Ask for payment','Transfer immediately','A'),
('Which details are important for a taxi booking?','Pickup and destination','Only customer name','Only vehicle color','Only driver age','A');

INSERT INTO stage2_questions(question) VALUES
('A customer says the driver has not arrived after 15 minutes. What would you say and do?'),
('A customer is angry about a booking delay. Explain how you would handle the call professionally.');

-- Add real F-Taxi training videos through Admin > Training Videos.
