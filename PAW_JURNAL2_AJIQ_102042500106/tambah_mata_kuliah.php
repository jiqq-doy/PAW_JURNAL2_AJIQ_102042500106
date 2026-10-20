<?php
require 'koneksi.php';

$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $dosen = $_POST['dosen'];
    $semester = (int)$_POST['semester'];

    
    if ($semester < 1 || $semester > 14) {
        $pesan_error = "Semester harus berupa angka antara 1 sampai 14.";
    } else {
       
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ?");
        $stmt_cek->bind_param("s", $kode);
        $stmt_cek->execute();
        $hasil_cek = $stmt_cek->get_result();

        if ($hasil_cek->num_rows > 0) {
            $pesan_error = "Data belum tersimpan. Kode mata kuliah sudah digunakan.";
        } else {
            
            $stmt_insert = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt_insert->bind_param("sssi", $kode, $nama, $dosen, $semester);
            
            if ($stmt_insert->execute()) {
                $pesan_sukses = "Mata kuliah berhasil ditambahkan.";
                
                $pesan_error = "Terjadi kesalahan saat menyimpan data.";
            }
            $stmt_insert->close();
        }
        $stmt_cek->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mata Kuliah</title>
    <style>
        .error { color: red; margin-bottom: 10px; font-weight: bold; border-left: 3px solid red; padding-left: 5px;}
        .sukses { color: green; margin-bottom: 10px; font-weight: bold; border-left: 3px solid green; padding-left: 5px;}
    </style>
</head>
<body>
    <h2>Tambah Mata Kuliah</h2>
    
    <?php if ($pesan_error): ?>
        <div class="error"><?= htmlspecialchars($pesan_error) ?></div>
    <?php endif; ?>
    <?php if ($pesan_sukses): ?>
        <div class="sukses"><?= htmlspecialchars($pesan_sukses) ?></div>
    <?php endif; ?>

    <form action="tambah_mata_kuliah.php" method="POST">
        <label for="kode">Kode mata kuliah (Misal: DBW-101):</label><br>
        <input type="text" id="kode" name="kode" required maxlength="20" pattern="[A-Za-z0-9\-]+" title="Huruf, angka, atau tanda minus"><br><br>

        <label for="nama">Nama mata kuliah:</label><br>
        <input type="text" id="nama" name="nama" required maxlength="100"><br><br>

        <label for="dosen">Dosen pengampu:</label><br>
        <input type="text" id="dosen" name="dosen" required maxlength="100"><br><br>

        <label for="semester">Semester (1-14):</label><br>
        <input type="number" id="semester" name="semester" required min="1" max="14"><br><br>

        <button type="submit">Simpan data</button>
        <a href="daftar_mata_kuliah.php"><button type="button">Batal / Kembali</button></a>
    </form>
</body>
</html>