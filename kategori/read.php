<?php
include 'C:/xampp/htdocs/toko-online-api/config/database.php';


$query = "SELECT * FROM kategori";
$stmt = $conn->prepare($query);
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);
?>
