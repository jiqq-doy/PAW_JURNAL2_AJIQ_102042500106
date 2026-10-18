-- Membuat database
CREATE DATABASE lms_paw;
USE lms_paw;

-- Membuat tabel mata_kuliah sesuai aturan jurnal
CREATE TABLE mata_kuliah (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    dosen VARCHAR(100) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL
);

-- Membuat tabel materi dengan foreign key
CREATE TABLE materi (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT UNSIGNED NOT NULL,
    judul VARCHAR(150) NOT NULL,
    isi TEXT NOT NULL,
    tautan VARCHAR(255),
    FOREIGN KEY(mata_kuliah_id) REFERENCES mata_kuliah (id) ON DELETE RESTRICT
);

-- Membuat tabel tugas dengan foreign key
CREATE TABLE tugas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT UNSIGNED NOT NULL,
    judul VARCHAR(150) NOT NULL,
    instruksi TEXT NOT NULL,
    tenggat DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY(mata_kuliah_id) REFERENCES mata_kuliah (id) ON DELETE RESTRICT
);

-- Memasukkan masing-masing dua data awal sesuai instruksi jurnal
INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES 
('ALP-101', 'Algoritma dan Pemrograman', 'Dosen ALP', 1),
('PAW-101', 'Pengembangan Aplikasi Website', 'Dosen PAW', 3);

INSERT INTO materi (mata_kuliah_id, judul, isi, tautan) VALUES 
(1, 'Logika Dasar', 'Mempelajari if-else dan loop', 'https://example.com/alp'),
(2, 'Transaksi database', 'Membahas pengelolaan perubahan data pada database aplikasi website.', 'https://www.php.net/manual/en/book.mysqli.php');

INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES 
(1, 'Tugas Looping', 'Buat program piramida bintang', '2026-11-01', 'Aktif'),
(2, 'Latihan CRUD', 'Buat LMS sederhana', '2026-11-05', 'Draf');