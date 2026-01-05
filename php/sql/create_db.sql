-- Database and tables for medical_clinic
CREATE DATABASE IF NOT EXISTS `medical_clinic` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `medical_clinic`;

CREATE TABLE IF NOT EXISTS `authentication` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `birthdate` DATE DEFAULT NULL,
  `gender` VARCHAR(10) DEFAULT NULL,
  `requested_service` VARCHAR(255) DEFAULT NULL,
  `preferred_date` DATE DEFAULT NULL,
  `preferred_time` TIME DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `allergies_history` TEXT DEFAULT NULL,
  `selected_doctor` VARCHAR(255) DEFAULT NULL,
  `medical_file` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
