-- SQL script to set up the Enomy‑Finances database schema
CREATE DATABASE IF NOT EXISTS enomy_finances;
USE enomy_finances;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('client','staff','admin') NOT NULL DEFAULT 'client',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS client_profiles (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    address VARCHAR(200),
    phone VARCHAR(20),
    email VARCHAR(100),
    date_of_birth DATE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS mortgages (
    mortgage_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    property_address VARCHAR(200),
    principal_amount DECIMAL(12,2),
    interest_rate DECIMAL(5,2),
    term_years INT,
    status ENUM('submitted','under_review','approved','rejected') DEFAULT 'submitted',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(client_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS investments (
    investment_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    product_type VARCHAR(50),
    amount DECIMAL(12,2),
    interest_rate DECIMAL(5,2),
    maturity_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(client_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    staff_id INT NOT NULL,
    appointment_date DATETIME,
    details TEXT,
    FOREIGN KEY (client_id) REFERENCES client_profiles(client_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT,
    sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS currency_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    from_currency VARCHAR(10) NOT NULL,
    to_currency VARCHAR(10) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    converted_amount DECIMAL(12,2) NOT NULL,
    fee_percent DECIMAL(5,2) NOT NULL,
    fee_amount DECIMAL(12,2) NOT NULL,
    final_amount DECIMAL(12,2) NOT NULL,
    exchange_rate DECIMAL(12,6) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(client_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS investment_quotes (
    quote_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    investment_type VARCHAR(100) NOT NULL,
    initial_lump_sum DECIMAL(12,2) NOT NULL,
    monthly_investment DECIMAL(12,2) NOT NULL,
    quote_data TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client_profiles(client_id) ON DELETE CASCADE
);