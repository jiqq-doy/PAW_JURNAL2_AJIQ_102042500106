<?php
require 'koneksi.php';

$kata_kunci = $_GET['cari'] ?? '';
$search_param = "%" . $kata_kunci . "%";

$stmt = $conn->prepare('SELECT id, kode, nama, dosen, semester FROM mata_kuliah WHERE kode LIKE ? OR nama LIKE ? ORDER BY nama');
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$hasil = $stmt->get_result();
$jumlah_data = $hasil->num_rows;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Mata kuliah</h2>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <span>Menampilkan <?= $jumlah_data ?> data.</span>
        <a href="tambah_mata_kuliah.php" class="btn-primary">Tambah Mata Kuliah</a>
    </div>

    <form method="GET" action="daftar_mata_kuliah.php" class="search-container">
        <input type="text" name="cari" value="<?= htmlspecialchars($kata_kunci) ?>" placeholder="Cari kode atau nama...">
        <button type="submit" class="btn-primary">Cari</button>
        <a href="daftar_mata_kuliah.php" class="btn-outline">Reset</a>
    </form>

    <?php if($jumlah_data > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Mata kuliah</th>
                    <th>Dosen / Semester</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode'] . ' - ' . $row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['dosen'] . ' / Semester ' . $row['semester']) ?></td>
                    <td>
                        <a href="detail_mata_kuliah.php?id=<?= $row['id'] ?>" class="btn-aksi">Detail</a>
                        <a href="edit_mata_kuliah.php?id=<?= $row['id'] ?>" class="btn-aksi">Edit</a>
                        <a href="hapus_mata_kuliah.php?id=<?= $row['id'] ?>" class="btn-hapus">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">Data tidak ditemukan.</div>
    <?php endif; ?>
    
    <br>
    <a href="index.php" style="color: #3498db; font-weight: bold; text-decoration: none;">&larr; Kembali ke Beranda</a>
</body>
</html>