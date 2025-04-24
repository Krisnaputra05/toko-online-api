<?php
include '../config/database.php';

// Ambil data dari request body (POST)
$nama = $_POST['nama'] ?? null;

// Cek jika nama kosong
if (!$nama) {
    echo json_encode(['error' => 'Nama kategori wajib diisi']);
    exit;
}

$query = "INSERT INTO kategori (nama) VALUES (:nama)";
$stmt = $conn->prepare($query);
$stmt->execute(['nama' => $nama]);

echo json_encode(['message' => 'Kategori berhasil ditambahkan']);
?>
    