<?php
include '../config/database.php';
$data = json_decode(file_get_contents("php://input"), true);

$query = "DELETE FROM keranjang WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$data['id']]);

echo json_encode(["message" => "Produk dihapus dari keranjang"]);
?>
