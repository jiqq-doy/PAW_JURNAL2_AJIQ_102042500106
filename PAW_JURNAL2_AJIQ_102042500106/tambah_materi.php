<?php
require 'koneksi.php';

$pesan_error = '';
$pesan_sukses = '';

$mk_query = $conn->query("SELECT id, nama FROM mata_kuliah ORDER BY nama");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mata_kuliah_id = (int)$_POST['mata_kuliah_id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tautan = $_POST['tautan'];

    if (!preg_match("/^(http:\/\/|https:\/\/)/", $tautan)) {
        $pesan_error = "Gunakan tautan http/https yang valid.";
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO materi (mata_kuliah_id, judul, isi, tautan) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("isss", $mata_kuliah_id, $judul, $isi, $tautan);
        
        if ($stmt_insert->execute()) {
            $pesan_sukses = "Materi berhasil disimpan.";
        } else {
            $pesan_error = "Terjadi kesalahan saat menyimpan data.";
        }
        $stmt_insert->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Materi</title>
    <style>
        .error { color: red; margin-bottom: 10px; border-left: 3px solid red; padding-left: 5px; }
        .sukses { color: green; margin-bottom: 10px; border-left: 3px solid green; padding-left: 5px; }
    </style>
</head>
<body>
    <h2>Tambah Materi</h2>
    
    <?php if ($pesan_error): ?><div class="error"><?= htmlspecialchars($pesan_error) ?></div><?php endif; ?>
    <?php if ($pesan_sukses): ?><div class="sukses"><?= htmlspecialchars($pesan_sukses) ?></div><?php endif; ?>

    <form method="POST">
        <label>Mata Kuliah:</label><br>
        <select name="mata_kuliah_id" required>
            <option value="">Pilih Mata kuliah</option>
            <?php while($mk = $mk_query->fetch_assoc()): ?>
                <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['nama']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Judul materi:</label><br>
        <input type="text" name="judul" required minlength="3" maxlength="150"><br><br>

        <label>Isi materi:</label><br>
        <textarea name="isi" required minlength="3" maxlength="2000" rows="5" cols="40"></textarea><br><br>

        <label>Tautan referensi:</label><br>
        <input type="text" name="tautan" required maxlength="255"><br><br>

        <button type="submit">Simpan data</button>
        <a href="daftar_materi.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>