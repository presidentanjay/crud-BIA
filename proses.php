<?php
// Include koneksi database
require_once 'koneksi.php';

// Aktifkan error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konstanta untuk konfigurasi upload
define('UPLOAD_DIR', 'img/');
define('MAX_FILE_SIZE', 500000); // 500KB
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Fungsi untuk validasi input
function validateInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fungsi untuk handle upload file
function handleFileUpload($file) {
    // Validasi direktori upload
    if (!file_exists(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0777, true)) {
            throw new Exception("Gagal membuat direktori upload.");
        }
    }

    // Validasi file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Error upload file: " . $file['error']);
    }

    $fileInfo = pathinfo($file['name']);
    $fileExtension = strtolower($fileInfo['extension']);

    // Validasi tipe file
    if (!in_array($fileExtension, ALLOWED_TYPES)) {
        throw new Exception("Hanya file " . implode(', ', ALLOWED_TYPES) . " yang diizinkan.");
    }

    // Validasi ukuran file
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception("Ukuran file maksimal " . (MAX_FILE_SIZE/1000) . "KB.");
    }

    // Generate nama file unik
    $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
    $targetPath = UPLOAD_DIR . $fileName;

    // Upload file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception("Gagal mengupload file.");
    }

    return $fileName;
}

// Handler untuk menambah data karyawan
if (isset($_POST['Aksi']) && $_POST['Aksi'] === "Add") {
    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // Validasi input
        $required_fields = ['jabatan', 'nama_karyawan', 'jenis_kelamin', 'alamat'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field harus diisi!");
            }
        }

        // Sanitasi input
        $jabatan = validateInput($_POST['jabatan']);
        $nama_karyawan = validateInput($_POST['nama_karyawan']);
        $jenis_kelamin = validateInput($_POST['jenis_kelamin']);
        $alamat = validateInput($_POST['alamat']);
        $foto_karyawan = "";

        // Handle upload foto jika ada
        if (isset($_FILES['foto_karyawan']) && $_FILES['foto_karyawan']['error'] !== UPLOAD_ERR_NO_FILE) {
            $foto_karyawan = handleFileUpload($_FILES['foto_karyawan']);
        }

        // Prepare statement untuk insert
        $query = "INSERT INTO tb_karyawan (jabatan, nama_karyawan, jenis_kelamin, foto_karyawan, alamat) 
                 VALUES (?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            throw new Exception("Error dalam persiapan query: " . mysqli_error($conn));
        }

        // Bind parameter dan eksekusi
        mysqli_stmt_bind_param($stmt, "sssss", $jabatan, $nama_karyawan, $jenis_kelamin, $foto_karyawan, $alamat);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error eksekusi query: " . mysqli_stmt_error($stmt));
        }

        // Commit transaction
        mysqli_commit($conn);
        mysqli_stmt_close($stmt);

        // Redirect dengan pesan sukses
        header("Location: index.php?status=success&message=" . urlencode("Data berhasil ditambahkan"));
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);

        // Hapus file yang sudah diupload jika ada error
        if (!empty($foto_karyawan) && file_exists(UPLOAD_DIR . $foto_karyawan)) {
            unlink(UPLOAD_DIR . $foto_karyawan);
        }

        // Redirect dengan pesan error
        header("Location: index.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// Handler untuk menghapus data karyawan
if (isset($_GET['Aksi']) && $_GET['Aksi'] === "Delete" && isset($_GET['id'])) {
    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // Validasi ID
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID tidak valid");
        }

        // Ambil informasi foto
        $stmt = mysqli_prepare($conn, "SELECT foto_karyawan FROM tb_karyawan WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Error dalam persiapan query: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error eksekusi query: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Hapus data dari database
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM tb_karyawan WHERE id = ?");
        if (!$delete_stmt) {
            throw new Exception("Error dalam persiapan query delete: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        if (!mysqli_stmt_execute($delete_stmt)) {
            throw new Exception("Error eksekusi query delete: " . mysqli_stmt_error($delete_stmt));
        }

        // Hapus file foto jika ada
        if (!empty($data['foto_karyawan'])) {
            $file_path = UPLOAD_DIR . $data['foto_karyawan'];
            if (file_exists($file_path)) {
                if (!unlink($file_path)) {
                    throw new Exception("Gagal menghapus file foto");
                }
            }
        }

        // Commit transaction
        mysqli_commit($conn);
        mysqli_stmt_close($delete_stmt);

        // Redirect dengan pesan sukses
        header("Location: index.php?status=success&message=" . urlencode("Data berhasil dihapus"));
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);

        // Redirect dengan pesan error
        header("Location: index.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>