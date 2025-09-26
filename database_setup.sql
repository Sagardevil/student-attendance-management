-- Create database
CREATE DATABASE IF NOT EXISTS attendance_system;

-- Use the database
USE attendance_system;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    class VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, date)
);

-- Insert sample data for testing
INSERT IGNORE INTO students (student_id, name, class) VALUES
('ST001', 'John Smith', '10A'),
('ST002', 'Emma Johnson', '10A'),
('ST003', 'Michael Brown', '10B'),
('ST004', 'Sarah Davis', '10B'),
('ST005', 'David Wilson', '11A');

-- Insert sample attendance data for today
INSERT IGNORE INTO attendance (student_id, date, status) VALUES
('ST001', CURDATE(), 'Present'),
('ST002', CURDATE(), 'Present'),
('ST003', CURDATE(), 'Absent'),
('ST004', CURDATE(), 'Present'),
('ST005', CURDATE(), 'Present');

-- Insert some historical attendance data
INSERT IGNORE INTO attendance (student_id, date, status) VALUES
('ST001', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Present'),
('ST002', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Absent'),
('ST003', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Present'),
('ST004', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Present'),
('ST005', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Present'),

('ST001', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Present'),
('ST002', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Present'),
('ST003', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Present'),
('ST004', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Absent'),
('ST005', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Present');

-- Show tables structure
DESCRIBE students;
DESCRIBE attendance;

-- Display sample data
SELECT 'Students Table:' as Info;
SELECT * FROM students;

SELECT 'Attendance Table:' as Info;
SELECT a.student_id, s.name, s.class, a.date, a.status 
FROM attendance a 
JOIN students s ON a.student_id = s.student_id 
ORDER BY a.date DESC, s.name;