<?php
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$nama = $data['nama'] ?? null;

if (!$id || !$nama) {
    echo json_encode(['error' => 'ID dan nama kategori wajib diisi']);
    exit;
}

$query = "UPDATE kategori SET nama = :nama WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->execute([
    'nama' => $nama,
    'id' => $id
]);

echo json_encode(['message' => 'Kategori berhasil diupdate']);
?>
