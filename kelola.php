<?php
include 'koneksi.php'; // Pastikan file koneksi benar
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bandung Internasional Aviation</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-light bg-light mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Bandung Internasional Aviation</a>
        </div>
    </nav>
    <div class="container">
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="proses.php" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="ex: Manager" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama" class="col-sm-2 col-form-label">Nama Karyawan</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama" name="nama_karyawan" placeholder="ex: Johan" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                <div class="col-sm-10">
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                        <option value="" selected disabled>Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="foto" class="col-sm-2 col-form-label">Foto Karyawan</label>
                <div class="col-sm-10">
                    <input class="form-control" type="file" id="foto" name="foto_karyawan" accept="image/*" required>
                    <small class="text-muted">Format: JPG, JPEG, PNG, GIF (Max. 500KB)</small>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="alamat" class="col-sm-2 col-form-label">Alamat Lengkap</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="ex: Jl. Merdeka No. 1" required></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-10 offset-sm-2">
                    <input type="hidden" name="Aksi" value="Add">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Tambahkan
                    </button>
                    <a href="index.php" class="btn btn-danger">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>