CREATE DATABASE IF NOT EXISTS web_clinic_final CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE web_clinic_final;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','staff') NOT NULL DEFAULT 'staff', status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, id_card VARCHAR(50) NOT NULL, phone VARCHAR(30), date_of_birth DATE, address TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
    UNIQUE KEY unique_patient_idcard (id_card), INDEX idx_patients_created_at (created_at)
);
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY, appointment_code VARCHAR(50) NOT NULL, patient_id INT NOT NULL, doctor_name VARCHAR(100) NOT NULL,
    appointment_time DATETIME NOT NULL, status ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
    UNIQUE KEY unique_appointment_code (appointment_code), INDEX idx_appointments_created_at (created_at), INDEX idx_appointments_status_created_at (status, created_at)
);