<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$pesan = '';
$mk_query = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");

$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data tugas tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = (int)$_POST['mata_kuliah_id'];
    $judul = $_POST['judul'];
    $instruksi = $_POST['instruksi'];
    $tenggat = $_POST['tenggat'];
    $status = $_POST['status'];

    $stmt_upd = $conn->prepare("UPDATE tugas SET mata_kuliah_id=?, judul=?, instruksi=?, tenggat=?, status=? WHERE id=?");
    $stmt_upd->bind_param("issssi", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status, $id);
    $stmt_upd->execute();

    if ($stmt_upd->affected_rows > 0) {
        $pesan = "<div style='color: green;'>Data berhasil diubah.</div>";
        $data['mata_kuliah_id'] = $mata_kuliah_id; $data['judul'] = $judul; 
        $data['instruksi'] = $instruksi; $data['tenggat'] = $tenggat; $data['status'] = $status;
    } elseif ($stmt_upd->affected_rows === 0) {
        $pesan = "<div style='color: blue;'>Tidak ada data yang berubah.</div>";
    }
    $stmt_upd->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Tugas</title></head>
<body>
    <h2>Edit Tugas</h2>
    <?= $pesan ?><br>
    <form method="POST">
        <label>Mata Kuliah:</label><br>
        <select name="mata_kuliah_id" required>
            <?php while($mk = $mk_query->fetch_assoc()): ?>
                <option value="<?= $mk['id'] ?>" <?= ($data['mata_kuliah_id'] == $mk['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($mk['nama']) ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Judul tugas:</label><br>
        <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required maxlength="150"><br><br>

        <label>Instruksi:</label><br>
        <textarea name="instruksi" required maxlength="2000" rows="5" cols="40"><?= htmlspecialchars($data['instruksi']) ?></textarea><br><br>

        <label>Tenggat:</label><br>
        <input type="date" name="tenggat" value="<?= htmlspecialchars($data['tenggat']) ?>" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="Draf" <?= ($data['status'] == 'Draf') ? 'selected' : '' ?>>Draf</option>
            <option value="Aktif" <?= ($data['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
            <option value="Selesai" <?= ($data['status'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
        </select><br><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="detail_tugas.php?id=<?= $id ?>"><button type="button">Kembali</button></a>
    </form>
</body>
</html>