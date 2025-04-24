<?php
include '../config/database.php';

$query = "SELECT produk.*, kategori.nama AS kategori FROM produk
          LEFT JOIN kategori ON produk.kategori_id = kategori.id";
$stmt = $conn->prepare($query);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
?>
