-- Quick insert script for creating initial admin and staff accounts
-- Run this in phpMyAdmin or your MySQL client
USE enomy_finances;

-- Create an admin account
-- Username: admin
-- Password: admin123 (CHANGE THIS AFTER FIRST LOGIN!)
INSERT INTO users (username, password_hash, role) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'admin');

-- Create a staff account
-- Username: staff
-- Password: staff123 (CHANGE THIS AFTER FIRST LOGIN!)
INSERT INTO users (username, password_hash, role) 
VALUES ('staff', '$2y$10$YourHashedPasswordHere', 'staff');

-- To generate password hashes, use the add_admin_staff.php web interface
-- OR run this PHP command:
-- php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
