<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$pesan_error = '';

// Ambil data untuk ditampilkan di konfirmasi
$stmt = $conn->prepare("SELECT nama FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data mata kuliah tidak ditemukan.");

// Penghapusan dieksekusi hanya jika POST (tombol konfirmasi diklik)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus'])) {
        try {
            $stmt_del = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
            $stmt_del->bind_param("i", $id);
            $stmt_del->execute();
            
            // Redirect ke daftar jika sukses
            header("Location: daftar_mata_kuliah.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            // Menangkap error relasi tabel (Error J03)
            $pesan_error = "Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hapus Mata Kuliah</title>
    <style>.error { color: maroon; background: #ffe6e6; padding: 10px; border-left: 4px solid red; margin-bottom: 15px; }</style>
</head>
<body>
    <h2>Mata kuliah</h2>
    
    <?php if ($pesan_error): ?>
        <div class="error"><?= htmlspecialchars($pesan_error) ?></div>
    <?php endif; ?>
    
    <p>Hapus <b><?= htmlspecialchars($data['nama']) ?></b>?</p>
    <p>Data baru dihapus setelah tombol konfirmasi dikirim.</p>
    
    <form method="POST">
        <button type="submit" name="hapus" style="background-color: #800000; color: white; padding: 5px 15px; border: none; cursor: pointer;">Ya, hapus data</button>
        <a href="daftar_mata_kuliah.php"><button type="button" style="padding: 5px 15px;">Batal</button></a>
    </form>
</body>
</html>