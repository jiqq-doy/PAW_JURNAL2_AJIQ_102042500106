<?php
require 'koneksi.php';

$pesan_error = '';
$pesan_sukses = '';

// Ambil daftar mata kuliah
$mk_query = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = (int)$_POST['mata_kuliah_id'];
    $judul = $_POST['judul'];
    $instruksi = $_POST['instruksi'];
    $tenggat = $_POST['tenggat'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $mata_kuliah_id, $judul, $instruksi, $tenggat, $status);
    
    if ($stmt->execute()) {
        $pesan_sukses = "Tugas berhasil disimpan.";
    } else {
        $pesan_error = "Terjadi kesalahan saat menyimpan data.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Tugas</title></head>
<body>
    <h2>Tambah Tugas</h2>
    <?php if ($pesan_error): ?><div style="color: red;"><?= htmlspecialchars($pesan_error) ?></div><?php endif; ?>
    <?php if ($pesan_sukses): ?><div style="color: green;"><?= htmlspecialchars($pesan_sukses) ?></div><?php endif; ?>

    <form method="POST">
        <label>Mata Kuliah:</label><br>
        <select name="mata_kuliah_id" required>
            <option value="">Pilih Mata kuliah</option>
            <?php while($mk = $mk_query->fetch_assoc()): ?>
                <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['nama']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Judul tugas:</label><br>
        <input type="text" name="judul" required minlength="3" maxlength="150"><br><br>

        <label>Instruksi tugas:</label><br>
        <textarea name="instruksi" required minlength="3" maxlength="2000" rows="5" cols="40"></textarea><br><br>

        <label>Tenggat:</label><br>
        <input type="date" name="tenggat" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="Draf">Draf</option>
            <option value="Aktif">Aktif</option>
            <option value="Selesai">Selesai</option>
        </select><br><br>

        <button type="submit">Simpan data</button>
        <a href="daftar_tugas.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>