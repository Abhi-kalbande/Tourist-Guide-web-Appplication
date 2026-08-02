CREATE DATABASE travel_tale;
USE travel_tale;
-- ADMIN TABLE -- 
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    password VARCHAR(255)
);
-- USER TABLE --
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);
-- DESTINATION TABLE --
CREATE TABLE destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    description TEXT,
    image VARCHAR(255),
    price INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- BOOKING TABLE --
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    whatsapp_number VARCHAR(20) NOT NULL,
    tour_name VARCHAR(150) NOT NULL,
    number_of_members INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- ADMIN PASSWORD --
INSERT INTO admin (username, password)
VALUES ('admin', 'admin123');

 -- USER INFORMATION TABLE --
CREATE TABLE user_information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
