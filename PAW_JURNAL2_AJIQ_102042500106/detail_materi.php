<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$stmt = $conn->prepare('
    SELECT m.*, mk.nama AS nama_mk 
    FROM materi m 
    JOIN mata_kuliah mk ON m.mata_kuliah_id = mk.id 
    WHERE m.id = ?
');
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data materi tidak ditemukan.");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Materi</title>
</head>
<body>
    <h2>Detail Materi</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Mata kuliah</th>
            <td><?= htmlspecialchars($data['nama_mk']) ?></td>
        </tr>
        <tr>
            <th>Judul materi</th>
            <td><?= htmlspecialchars($data['judul']) ?></td>
        </tr>
        <tr>
            <th>Isi materi</th>
            <td><?= nl2br(htmlspecialchars($data['isi'])) ?></td>
        </tr>
        <tr>
            <th>Tautan referensi</th>
            <td><a href="<?= htmlspecialchars($data['tautan']) ?>" target="_blank"><?= htmlspecialchars($data['tautan']) ?></a></td>
        </tr>
    </table>
    <br>
    <a href="edit_materi.php?id=<?= $data['id'] ?>"><button>Edit data</button></a>
    <a href="daftar_materi.php"><button>Kembali</button></a>
</body>
</html>