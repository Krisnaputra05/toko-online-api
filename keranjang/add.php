<?php
include '../config/database.php';
$data = json_decode(file_get_contents("php://input"), true);

$query = "INSERT INTO keranjang (produk_id, jumlah) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->execute([$data['produk_id'], $data['jumlah']]);

echo json_encode(["message" => "Produk ditambahkan ke keranjang"]);
?>
