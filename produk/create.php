<?php
include '../config/database.php';
$data = json_decode(file_get_contents("php://input"), true);

$query = "INSERT INTO produk (nama, harga, stok, kategori_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->execute([$data['nama'], $data['harga'], $data['stok'], $data['kategori_id']]);

echo json_encode(["message" => "Produk berhasil ditambahkan"]);
?>
