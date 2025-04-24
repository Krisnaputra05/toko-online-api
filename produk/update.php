<?php
include '../config/database.php';
$data = json_decode(file_get_contents("php://input"), true);

$query = "UPDATE produk SET nama = ?, harga = ?, stok = ?, kategori_id = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$data['nama'], $data['harga'], $data['stok'], $data['kategori_id'], $data['id']]);

echo json_encode(["message" => "Produk berhasil diupdate"]);
?>
    