<?php
include '../config/database.php';

$query = "SELECT keranjang.*, produk.nama, produk.harga FROM keranjang
    LEFT JOIN produk ON keranjang.produk_id = produk.id";
$stmt = $conn->prepare($query);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
?>
