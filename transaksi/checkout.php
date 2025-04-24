<?php
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);
$query = "INSERT INTO transaksi (tanggal, total_harga) VALUES (NOW(), ?)";
$stmt = $conn->prepare($query);
$stmt->execute([$data['total_harga']]);
$transaksi_id = $conn->lastInsertId();

// Simpan detail produk dari keranjang
foreach ($data['produk'] as $item) {
    $query = "INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$transaksi_id, $item['produk_id'], $item['jumlah']]);
}

// Kosongkan keranjang
$conn->exec("DELETE FROM keranjang");

echo json_encode(["message" => "Checkout berhasil"]);
?>

