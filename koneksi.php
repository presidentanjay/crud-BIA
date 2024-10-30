<?php
// Aktifkan pelaporan error untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_BIA');

// Buat koneksi dengan penanganan error
try {
    // Buat koneksi
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Periksa koneksi
    if (!$conn) {
        throw new Exception("Koneksi database gagal: " . mysqli_connect_error());
    }
    
    // Set charset ke UTF-8
    if (!mysqli_set_charset($conn, "utf8mb4")) {
        throw new Exception("Error setting charset: " . mysqli_error($conn));
    }
    
    // Set timezone jika diperlukan
    date_default_timezone_set('Asia/Jakarta');
    
} catch (Exception $e) {
    // Tangani error dengan lebih aman
    die("Error koneksi database: " . $e->getMessage());
}

// Fungsi untuk menutup koneksi database
function closeConnection($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}

// Fungsi untuk escaping string (gunakan sebelum query jika tidak menggunakan prepared statements)
function escapeString($conn, $string) {
    return mysqli_real_escape_string($conn, $string);
}

// Register shutdown function untuk memastikan koneksi ditutup
register_shutdown_function('closeConnection', $conn);
?>