<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$pesan = '';

$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data mata kuliah tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $dosen = $_POST['dosen'];
    $semester = (int)$_POST['semester'];

    $stmt_update = $conn->prepare("UPDATE mata_kuliah SET kode=?, nama=?, dosen=?, semester=? WHERE id=?");
    $stmt_update->bind_param("sssii", $kode, $nama, $dosen, $semester, $id);
    
    try {
        $stmt_update->execute();
        if ($stmt_update->affected_rows > 0) {
            $pesan = "<div style='color: green; font-weight: bold;'>Data berhasil diubah.</div>";
            
            $data['kode'] = $kode; $data['nama'] = $nama; $data['dosen'] = $dosen; $data['semester'] = $semester;
        } elseif ($stmt_update->affected_rows === 0) {
            
            $pesan = "<div style='color: blue;'>Tidak ada data yang berubah.</div>"; 
        }
    } catch (mysqli_sql_exception $e) {
        $pesan = "<div style='color: red;'>Error: Kode mata kuliah mungkin sudah digunakan.</div>";
    }
    $stmt_update->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Mata Kuliah</title></head>
<body>
    <h2>Edit Mata Kuliah</h2>
    <?= $pesan ?><br>
    <form method="POST">
        <label>Kode mata kuliah:</label><br>
        <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>" required maxlength="20"><br><br>
        <label>Nama mata kuliah:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required maxlength="100"><br><br>
        <label>Dosen pengampu:</label><br>
        <input type="text" name="dosen" value="<?= htmlspecialchars($data['dosen']) ?>" required maxlength="100"><br><br>
        <label>Semester (1-14):</label><br>
        <input type="number" name="semester" value="<?= htmlspecialchars($data['semester']) ?>" required min="1" max="14"><br><br>
        <button type="submit">Simpan Perubahan</button>
        <a href="daftar_mata_kuliah.php"><button type="button">Kembali</button></a>
    </form>
</body>
</html>