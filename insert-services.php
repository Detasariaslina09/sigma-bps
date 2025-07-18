<?php
// Include koneksi database
require_once 'koneksi.php';

// Mulai output buffering
ob_start();

// Data layanan yang akan dimasukkan
$services = [
    1 => "Pengembangan Kompetensi",
    2 => "Kenaikan Pangkat",
    3 => "Peta Karir",
    4 => "Angka Kredit"
];

// Fungsi untuk menampilkan pesan
function showMessage($message) {
    echo $message . "\n";
    // Pastikan output terlihat di terminal
    flush();
    ob_flush();
}

showMessage("Memulai proses pengisian data layanan...");

// Cek apakah tabel services sudah ada data
$result = $conn->query("SELECT COUNT(*) as total FROM services");
if ($result) {
    $row = $result->fetch_assoc();
    
    if ($row['total'] > 0) {
        showMessage("Tabel services sudah memiliki data. Menghapus data lama...");
        $conn->query("DELETE FROM services");
        showMessage("Data lama berhasil dihapus.");
    }
} else {
    showMessage("Error: " . $conn->error);
}

// Reset auto increment
$conn->query("ALTER TABLE services AUTO_INCREMENT = 1");
showMessage("Auto increment di-reset ke 1.");

// Masukkan data baru
foreach ($services as $id => $name) {
    $sql = "INSERT INTO services (id, name) VALUES ($id, '$name')";
    
    if ($conn->query($sql) === TRUE) {
        showMessage("Layanan '$name' berhasil ditambahkan dengan ID $id.");
    } else {
        showMessage("Error menambahkan layanan: " . $conn->error);
    }
}

showMessage("Proses pengisian data selesai.");

// Tampilkan data yang sudah dimasukkan
showMessage("\nData yang sudah dimasukkan:");
$result = $conn->query("SELECT * FROM services ORDER BY id");

if ($result && $result->num_rows > 0) {
    showMessage("ID | Nama Layanan");
    showMessage("-------------------");
    
    while ($row = $result->fetch_assoc()) {
        showMessage($row['id'] . " | " . $row['name']);
    }
} else {
    showMessage("Tidak ada data dalam tabel services.");
}

// Simpan output ke file untuk memastikan
$output = ob_get_contents();
file_put_contents('services-insert-log.txt', $output);

showMessage("\nSekarang Anda dapat mengakses admin-services.php untuk mengelola layanan.");
showMessage("Log juga disimpan di services-insert-log.txt");
?> 