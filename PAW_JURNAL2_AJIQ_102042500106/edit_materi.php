<?php
require 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$pesan = '';


$mk_query = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");


$stmt = $conn->prepare("SELECT * FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) die("Data materi tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = (int)$_POST['mata_kuliah_id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tautan = $_POST['tautan'];

    if (!preg_match("/^(http:\/\/|https:\/\/)/", $tautan)) {
        $pesan = "<div style='color: red;'>Gunakan tautan http/https yang valid.</div>";
    } else {
        $stmt_update = $conn->prepare("UPDATE materi SET mata_kuliah_id=?, judul=?, isi=?, tautan=? WHERE id=?");
        $stmt_update->bind_param("isssi", $mata_kuliah_id, $judul, $isi, $tautan, $id);
        $stmt_update->execute();

        if ($stmt_update->affected_rows > 0) {
            $pesan = "<div style='color: green; font-weight: bold;'>Data berhasil diubah.</div>";
            $data['mata_kuliah_id'] = $mata_kuliah_id; $data['judul'] = $judul; $data['isi'] = $isi; $data['tautan'] = $tautan;
        } elseif ($stmt_update->affected_rows === 0) {
            $pesan = "<div style='color: blue;'>Tidak ada data yang berubah.</div>";
        }
        $stmt_update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Edit Materi</title></head>
<body>
    <h2>Edit Materi</h2>
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

        <label>Judul materi:</label><br>
        <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required minlength="3" maxlength="150"><br><br>

        <label>Isi materi:</label><br>
        <textarea name="isi" required minlength="3" maxlength="2000" rows="5" cols="40"><?= htmlspecialchars($data['isi']) ?></textarea><br><br>

        <label>Tautan referensi:</label><br>
        <input type="text" name="tautan" value="<?= htmlspecialchars($data['tautan']) ?>" required maxlength="255"><br><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="detail_materi.php?id=<?= $id ?>"><button type="button">Kembali</button></a>
    </form>
</body>
</html>