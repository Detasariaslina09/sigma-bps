<?php
// Koneksi ke database
require_once 'koneksi.php';

// Fungsi untuk memeriksa koneksi database dan melakukan reconnect jika terputus
function check_connection($conn) {
    if (!$conn->ping()) {
        // Reconnect jika koneksi terputus
        $conn->close();
        $servername = "127.0.0.1";
        $username   = "root";
        $password   = "";
        $dbname     = "sigap";
        $port       = 3306;
        
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

// Pastikan koneksi aktif
$conn = check_connection($conn);

// Buat tabel users jika belum ada sesuai struktur yang diberikan oleh user
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Tabel users berhasil dibuat atau sudah ada<br>";
    
    // Pastikan koneksi masih aktif
    $conn = check_connection($conn);
    
    // Cek apakah sudah ada admin
    $check_admin = $conn->query("SELECT * FROM users WHERE username='admin'");
    
    if ($check_admin->num_rows == 0) {
        // Tambahkan user admin default dengan password di-hash
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql_admin = "INSERT INTO users (username, password, role) 
                      VALUES ('admin', '$admin_password', 'admin')";
        
        if ($conn->query($sql_admin) === TRUE) {
            echo "User admin default berhasil ditambahkan<br>";
            echo "Username: admin<br>";
            echo "Password: admin123<br>";
        } else {
            echo "Error menambahkan user admin: " . $conn->error . "<br>";
        }
        
        // Tambahkan user biasa default dengan password di-hash
        $user_password = password_hash('user123', PASSWORD_DEFAULT);
        $sql_user = "INSERT INTO users (username, password, role) 
                    VALUES ('user', '$user_password', 'user')";
        
        if ($conn->query($sql_user) === TRUE) {
            echo "User biasa default berhasil ditambahkan<br>";
            echo "Username: user<br>";
            echo "Password: user123<br>";
        } else {
            echo "Error menambahkan user biasa: " . $conn->error . "<br>";
        }
    } else {
        echo "User admin sudah ada<br>";
    }
} else {
    echo "Error membuat tabel users: " . $conn->error . "<br>";
}

// Tutup koneksi
$conn->close();
?> 