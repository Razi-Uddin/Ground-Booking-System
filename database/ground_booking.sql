-- Create Database
CREATE DATABASE IF NOT EXISTS ground_booking;
USE ground_booking;

-- ---------------------------
-- 1. USERS TABLE (Super Admin, Admin, Staff, Customer)
-- ---------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin','admin','staff','customer') NOT NULL,
    phone VARCHAR(20),
    status TINYINT(1) DEFAULT 1, -- 1 active, 0 inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Super Admin
INSERT INTO users (name, email, password, role, phone) VALUES
('Super Admin', 'superadmin@gmail.com', MD5('super123'), 'superadmin', '0000000000');

-- ---------------------------
-- 2. GROUNDS TABLE
-- ---------------------------
CREATE TABLE grounds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    location TEXT,
    per_hour_charge DECIMAL(10,2) NOT NULL,
    opening_time TIME,
    closing_time TIME,
    image VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------
-- 3. GROUND ITEMS TABLE
-- ---------------------------
CREATE TABLE ground_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ground_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE CASCADE
);

-- ---------------------------
-- 4. BOOKINGS TABLE
-- ---------------------------
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ground_id INT NOT NULL,
    customer_id INT NOT NULL,
    staff_id INT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_hours DECIMAL(4,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ---------------------------
-- 5. PAYMENTS TABLE
-- ---------------------------
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash','card','online') NOT NULL,
    payment_status ENUM('paid','unpaid') DEFAULT 'unpaid',
    paid_at TIMESTAMP NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

-- ---------------------------
-- 6. WALK-IN BOOKINGS TABLE
-- ---------------------------
CREATE TABLE walkin_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ground_id INT NOT NULL,
    staff_id INT NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(20),
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    total_hours DECIMAL(4,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ground_id) REFERENCES grounds(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------
-- 7. SYSTEM SETTINGS TABLE (for Super Admin control)
-- ---------------------------
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
