<?php
require 'koneksi.php';

$kata_kunci = $_GET['cari'] ?? '';
$filter_mk = $_GET['mata_kuliah_id'] ?? '';
$mk_query = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");
$search_param = "%" . $kata_kunci . "%";

if ($filter_mk !== '') {
    $stmt = $conn->prepare('SELECT m.id, m.judul, mk.nama AS nama_mk FROM materi m JOIN mata_kuliah mk ON m.mata_kuliah_id = mk.id WHERE m.judul LIKE ? AND m.mata_kuliah_id = ? ORDER BY m.judul');
    $stmt->bind_param("si", $search_param, $filter_mk);
} else {
    $stmt = $conn->prepare('SELECT m.id, m.judul, mk.nama AS nama_mk FROM materi m JOIN mata_kuliah mk ON m.mata_kuliah_id = mk.id WHERE m.judul LIKE ? ORDER BY m.judul');
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$hasil = $stmt->get_result();
$jumlah_data = $hasil->num_rows;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Materi</h2>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <span>Menampilkan <?= $jumlah_data ?> data.</span>
        <a href="tambah_materi.php" class="btn-primary">Tambah materi</a>
    </div>

    <form method="GET" action="daftar_materi.php" class="search-container">
        <input type="text" name="cari" value="<?= htmlspecialchars($kata_kunci) ?>" placeholder="Cari judul materi...">
        <select name="mata_kuliah_id">
            <option value="">Semua mata kuliah</option>
            <?php while($mk = $mk_query->fetch_assoc()): ?>
                <option value="<?= $mk['id'] ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : '' ?>><?= htmlspecialchars($mk['nama']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn-primary">Cari</button>
        <a href="daftar_materi.php" class="btn-outline">Reset</a>
    </form>

    <?php if($jumlah_data > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Mata kuliah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $hasil->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                    <td>
                        <a href="detail_materi.php?id=<?= $row['id'] ?>" class="btn-aksi">Detail</a>
                        <a href="edit_materi.php?id=<?= $row['id'] ?>" class="btn-aksi">Edit</a>
                        <a href="hapus_materi.php?id=<?= $row['id'] ?>" class="btn-hapus">Hapus</a>
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