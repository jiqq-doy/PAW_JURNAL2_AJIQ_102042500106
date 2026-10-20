<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$stmt = $conn->prepare("SELECT judul FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data tugas tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus'])) {
        $stmt_del = $conn->prepare("DELETE FROM tugas WHERE id = ?");
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();
        
        header("Location: daftar_tugas.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Hapus Tugas</title></head>
<body>
    <h2>Hapus Tugas</h2>
    <p>Hapus tugas <b><?= htmlspecialchars($data['judul']) ?></b>?</p>
    <p>Data baru dihapus setelah tombol konfirmasi dikirim.</p>
    
    <form method="POST">
        <button type="submit" name="hapus" style="background-color: #800000; color: white;">Ya, hapus data</button>
        <a href="daftar_tugas.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>