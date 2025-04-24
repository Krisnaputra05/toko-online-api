<?php
// Include database connection
include_once('../config/database.php');

// Get database connection
// Gunakan koneksi yang sudah didefinisikan di file database.php
$conn = new PDO("mysql:host=localhost;dbname=db_toko_online", "root", "");

// Get raw POST data
$data = json_decode(file_get_contents("php://input"));

// Check if items are provided in the request
if (isset($data->items)) {
    // Mulai transaksi untuk memastikan data konsisten
    $conn->beginTransaction();

    try {
        // Loop melalui setiap produk di keranjang
        foreach ($data->items as $item) {
            // Query untuk mengurangi stok produk
            $query = "UPDATE produk SET stok = stok - :jumlah WHERE id = :id AND stok >= :jumlah";
            $stmt = $conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(":id", $item->id);
            $stmt->bindParam(":jumlah", $item->jumlah);

            // Eksekusi query
            if (!$stmt->execute()) {
                throw new Exception("Gagal memperbarui stok untuk produk ID: " . $item->id);
            }
        }

        // Commit transaksi jika semua produk berhasil diproses
        $conn->commit();

        // Menyampaikan respon sukses
        echo json_encode(["message" => "Transaksi berhasil! Stok produk telah diperbarui."]);
    } catch (Exception $e) {
        // Rollback transaksi jika ada kesalahan
        $conn->rollBack();
        // Menyampaikan pesan error
        echo json_encode(["message" => "Terjadi kesalahan: " . $e->getMessage()]);
    }
} else {
    // Jika data transaksi tidak lengkap
    echo json_encode(["message" => "Data transaksi tidak lengkap."]);
}
?>
