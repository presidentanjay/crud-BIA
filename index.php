<?php
include 'koneksi.php'; // Memasukkan file koneksi ke database

// Mengambil data dari tabel tb_karyawan
$query = "SELECT * FROM tb_karyawan";
$sql = mysqli_query($conn, $query);

// Memeriksa apakah query berhasil
if (!$sql) {
    die("Query Error: " . mysqli_error($conn));
}

// Memastikan hapus data jika ada permintaan
if (isset($_GET['hapus'])) {
    $id_karyawan = $_GET['hapus']; // Mengambil ID karyawan dari query string

    // Query DELETE
    $delete_query = "DELETE FROM tb_karyawan WHERE id_karyawan = '$id_karyawan'";
    $delete_sql = mysqli_query($conn, $delete_query);

    if ($delete_sql) {
        // Redirect ke halaman yang sama setelah berhasil dihapus
        header("Location: index.php?msg=Data berhasil dihapus");
        exit(); // Penting untuk menghentikan eksekusi skrip setelah redirect
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Menangani penambahan data
if (isset($_POST['Aksi']) && $_POST['Aksi'] == "Add") {
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $nama_karyawan = mysqli_real_escape_string($conn, $_POST['nama_karyawan']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $foto_karyawan = 'img5.jpg'; // Gantilah sesuai dengan mekanisme upload file jika diperlukan
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Query INSERT
    $insert_query = "INSERT INTO tb_karyawan (jabatan, nama_karyawan, jenis_kelamin, foto_karyawan, alamat) VALUES ('$jabatan', '$nama_karyawan', '$jenis_kelamin', '$foto_karyawan', '$alamat')";

    // Eksekusi query
    if (mysqli_query($conn, $insert_query)) {
        header("Location: index.php?msg=Data berhasil ditambahkan");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bandung Internasional Aviation</title>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Bandung Internasional Aviation
            </a>
        </div>
    </nav> 

    <div class="container">
        <h1 class="mt-5">Data Karyawan</h1> 
        <figure>
            <blockquote class="blockquote">
                <p>Data Karyawan yang sudah disimpan</p>
            </blockquote>
            <figcaption class="blockquote-footer">
                CRUD <cite title="Source Title">Create Read Update Delete</cite>
            </figcaption>
        </figure> 
        <a href="kelola.php" class="btn btn-primary mb-3">
            <i class="fa fa-plus"></i> Tambah Data
        </a>
        <div class="table-responsive">
            <table class="table align-middle table-bordered table-hover">
                <thead>
                    <tr>
                        <th><center>No</center></th>
                        <th>Jabatan</th>
                        <th>Nama Karyawan</th>
                        <th>Jenis Kelamin</th>
                        <th>Foto Karyawan</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inisialisasi nomor urut
                    $no = 1;
                    // Menampilkan data karyawan dari database
                    while ($result = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?>.</td>
                        <td><?php echo htmlspecialchars($result['jabatan']); ?></td>
                        <td><?php echo htmlspecialchars($result['nama_karyawan']); ?></td>
                        <td><?php echo htmlspecialchars($result['jenis_kelamin']); ?></td>
                        <td>
                            <img src="img/<?php echo htmlspecialchars($result['foto_karyawan']); ?>" style="width: 150px;">
                        </td>
                        <td><?php echo htmlspecialchars($result['alamat']); ?></td>
                        <td>
                            <a href="kelola.php?Edit=<?php echo $result['id_karyawan']; ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-pencil-alt"></i> Edit
                            </a>
                            <a href="index.php?hapus=<?php echo $result['id_karyawan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div> 
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
