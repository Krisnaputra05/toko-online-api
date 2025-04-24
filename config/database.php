<?php
$host = "localhost";
$db_name = "db_toko_online";
$username = "root";
$password = "";

try {
    // Membuat koneksi dengan PDO
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    
    // Mengatur mode error menjadi exception untuk menangani error dengan lebih baik
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    // Menampilkan pesan error jika koneksi gagal
    echo "Koneksi gagal: " . $exception->getMessage();
    die();
}
?>
