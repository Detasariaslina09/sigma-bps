<?php
function get_database_params() { // Fungsi untuk mendapatkan parameter koneksi database
    return [
        'servername' => "127.0.0.1",
        'username'   => "root",
        'password'   => "",
        'dbname'     => "sigap",
        'port'       => 3306
    ];
}

// Fungsi untuk memeriksa dan memperbaiki koneksi database
function check_connection($conn = null) {
    if ($conn === null || !($conn instanceof mysqli) || !$conn->ping()) { // Jika koneksi tidak diberikan atau koneksi tidak aktif
        if ($conn instanceof mysqli) {         // Tutup koneksi lama jika ada
            $conn->close();
        }
        
        // Ambil parameter koneksi
        $params = get_database_params();
        $conn = new mysqli(       // Buat koneksi baru
            $params['servername'], 
            $params['username'], 
            $params['password'], 
            $params['dbname'], 
            $params['port']
        );
        if ($conn->connect_error) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }
    }
    return $conn;
}
// Fungsi alternatif untuk mendapatkan koneksi database baru
function get_database_connection() {
    return check_connection(null);
}