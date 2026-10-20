<?php
require 'koneksi.php';

$count_mk = $conn->query("SELECT COUNT(*) as total FROM mata_kuliah")->fetch_assoc()['total'];
$count_materi = $conn->query("SELECT COUNT(*) as total FROM materi")->fetch_assoc()['total'];
$count_tugas = $conn->query("SELECT COUNT(*) as total FROM tugas")->fetch_assoc()['total'];

$list_mk = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY kode");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div style="max-width: 900px; margin: 0 auto; padding-top: 40px;">
        
       
        <h1 style="font-size: 48px; margin-bottom: 10px;">Learning Management System</h1>
        <p style="margin-bottom: 30px; color: #333; font-size: 16px;">Pengelolaan mata kuliah, materi pembelajaran, dan tugas.</p>
        
        <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 20px;">

        <div style="display: flex; gap: 80px; margin-bottom: 20px;">
            <div>
                <div style="color: #333; margin-bottom: 10px; font-size: 16px;">Mata kuliah</div>
                <div style="font-size: 28px; font-weight: bold;"><?= htmlspecialchars($count_mk) ?></div>
            </div>
            <div>
                <div style="color: #333; margin-bottom: 10px; font-size: 16px;">Materi</div>
                <div style="font-size: 28px; font-weight: bold;"><?= htmlspecialchars($count_materi) ?></div>
            </div>
            <div>
                <div style="color: #333; margin-bottom: 10px; font-size: 16px;">Tugas</div>
                <div style="font-size: 28px; font-weight: bold;"><?= htmlspecialchars($count_tugas) ?></div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 30px;">

        <h2>Mata kuliah tersedia</h2>
        <ul style="margin-bottom: 50px; line-height: 1.8; font-size: 16px;">
            <?php while($row = $list_mk->fetch_assoc()): ?>
                <li><?= htmlspecialchars($row['kode']) ?> - <?= htmlspecialchars($row['nama']) ?></li>
            <?php endwhile; ?>
        </ul>

        
        <nav style="display: flex; gap: 15px; margin-bottom: 40px;">
            <a href="daftar_mata_kuliah.php" style="background-color: #1a4b6e; color: white; padding: 12px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Kelola Mata Kuliah</a>
            <a href="daftar_materi.php" style="background-color: #1a4b6e; color: white; padding: 12px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Kelola Materi</a>
            <a href="daftar_tugas.php" style="background-color: #1a4b6e; color: white; padding: 12px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Kelola Tugas</a>
        </nav>
        
    </div>
</body>
</html>