<?php
$servername = "127.0.0.1";  // menggunakan IP langsung daripada localhost
$username   = "root";
$password   = "";
$dbname     = "sigap";
$port       = 3306;         // menambahkan port eksplisit

// Buat koneksi baru
$conn = new mysqli($servername, $username, $password);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tingkatkan timeout koneksi
$conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

// Buat database jika belum ada
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

// Pilih database
$conn->select_db($dbname);

// Set wait_timeout lebih besar
$conn->query("SET session wait_timeout=600");
?>
