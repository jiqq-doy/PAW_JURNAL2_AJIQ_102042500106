<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

// Ambil data untuk ditampilkan di konfirmasi
$stmt = $conn->prepare("SELECT judul FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data materi tidak ditemukan.");

// Penghapusan dieksekusi hanya jika POST (tombol Ya diklik)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus'])) {
        $stmt_del = $conn->prepare("DELETE FROM materi WHERE id = ?");
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();
        
        header("Location: daftar_materi.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hapus Materi</title>
</head>
<body>
    <h2>Hapus Materi</h2>
    <p>Hapus materi <b><?= htmlspecialchars($data['judul']) ?></b>?</p>
    <p>Data baru dihapus setelah tombol konfirmasi dikirim.</p>
    
    <form method="POST">
        <button type="submit" name="hapus" style="background-color: #800000; color: white; padding: 5px 15px; border: none;">Ya, hapus data</button>
        <a href="daftar_materi.php"><button type="button" style="padding: 5px 15px;">Batal</button></a>
    </form>
</body>
</html>