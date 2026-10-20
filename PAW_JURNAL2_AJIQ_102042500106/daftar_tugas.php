<?php
require 'koneksi.php';

$kata_kunci = $_GET['cari'] ?? '';
$filter_mk = $_GET['mata_kuliah_id'] ?? '';
$mk_query = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");
$search_param = "%" . $kata_kunci . "%";

if ($filter_mk !== '') {
    $stmt = $conn->prepare('SELECT t.id, t.judul, t.tenggat, t.status, mk.nama AS nama_mk FROM tugas t JOIN mata_kuliah mk ON t.mata_kuliah_id = mk.id WHERE t.judul LIKE ? AND t.mata_kuliah_id = ? ORDER BY t.tenggat ASC');
    $stmt->bind_param("si", $search_param, $filter_mk);
} else {
    $stmt = $conn->prepare('SELECT t.id, t.judul, t.tenggat, t.status, mk.nama AS nama_mk FROM tugas t JOIN mata_kuliah mk ON t.mata_kuliah_id = mk.id WHERE t.judul LIKE ? ORDER BY t.tenggat ASC');
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$hasil = $stmt->get_result();
$jumlah_data = $hasil->num_rows;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Tugas</h2>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <span>Menampilkan <?= $jumlah_data ?> data.</span>
        <a href="tambah_tugas.php" class="btn-primary">Tambah Tugas</a>
    </div>

    <form method="GET" action="daftar_tugas.php" class="search-container">
        <input type="text" name="cari" value="<?= htmlspecialchars($kata_kunci) ?>" placeholder="Cari judul tugas...">
        <select name="mata_kuliah_id">
            <option value="">Semua mata kuliah</option>
            <?php while($mk = $mk_query->fetch_assoc()): ?>
                <option value="<?= $mk['id'] ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : '' ?>><?= htmlspecialchars($mk['nama']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn-primary">Cari</button>
        <a href="daftar_tugas.php" class="btn-outline">Reset</a>
    </form>

    <?php if($jumlah_data > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Judul Tugas</th>
                    <th>Mata Kuliah</th>
                    <th>Tenggat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                    <td><?= htmlspecialchars($row['tenggat']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <a href="detail_tugas.php?id=<?= $row['id'] ?>" class="btn-aksi">Detail</a>
                        <a href="edit_tugas.php?id=<?= $row['id'] ?>" class="btn-aksi">Edit</a>
                        <a href="hapus_tugas.php?id=<?= $row['id'] ?>" class="btn-hapus">Hapus</a>
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