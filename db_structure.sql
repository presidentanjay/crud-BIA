-- Buat database
CREATE DATABASE IF NOT EXISTS db_aviation;
USE db_aviation;

-- Buat tabel karyawan
CREATE TABLE IF NOT EXISTS karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jabatan VARCHAR(100) NOT NULL,
    nama_karyawan VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-Laki', 'Perempuan') NOT NULL,
    foto_karyawan VARCHAR(255) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);