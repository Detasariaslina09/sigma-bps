<?php
// Tambahkan header HTML untuk tampilan yang lebih baik
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Setup Database Profil - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1a3c6e;
            text-align: center;
        }
        .success {
            color: green;
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1a3c6e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #0d2b5a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Setup Database Profil</h1>';

// Koneksi ke database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sigap";
$port = 3306;

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Cek koneksi
if ($conn->connect_error) {
    echo '<div class="error">Koneksi gagal: ' . $conn->connect_error . '</div>';
    die();
}

// SQL untuk membuat tabel profil
$sql = "CREATE TABLE IF NOT EXISTS profil (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    foto VARCHAR(255) DEFAULT 'default-male.jpg',
    link VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    echo '<div class="success">Tabel profil berhasil dibuat atau sudah ada.</div>';
    
    // Tambahkan data default untuk Kepala BPS dan Kepala Subbagian jika belum ada
    $check_kepala = $conn->query("SELECT id FROM profil WHERE jabatan LIKE '%Kepala BPS%' LIMIT 1");
    if ($check_kepala->num_rows == 0) {
        $insert_kepala = "INSERT INTO profil (nama, jabatan, foto, link) VALUES 
            ('Dr. Suhariyanto, M.Si.', 'Kepala BPS Kota Bandar Lampung', 'kepala.jpg', '')";
        if ($conn->query($insert_kepala) === TRUE) {
            echo '<div class="success">Data Kepala BPS berhasil ditambahkan.</div>';
        } else {
            echo '<div class="error">Error menambahkan data Kepala BPS: ' . $conn->error . '</div>';
        }
    }
    
    $check_kasubbag = $conn->query("SELECT id FROM profil WHERE jabatan LIKE '%Kepala Sub Bagian%' LIMIT 1");
    if ($check_kasubbag->num_rows == 0) {
        $insert_kasubbag = "INSERT INTO profil (nama, jabatan, foto, link) VALUES 
            ('Dra. Maryam Hayati, M.M.', 'Kepala Sub Bagian Tata Usaha', 'kasubbag.jpg', '')";
        if ($conn->query($insert_kasubbag) === TRUE) {
            echo '<div class="success">Data Kepala Sub Bagian berhasil ditambahkan.</div>';
        } else {
            echo '<div class="error">Error menambahkan data Kepala Sub Bagian: ' . $conn->error . '</div>';
        }
    }
    
    // Tambahkan data pegawai contoh jika belum ada
    $check_pegawai = $conn->query("SELECT COUNT(*) as total FROM profil");
    $row = $check_pegawai->fetch_assoc();
    if ($row['total'] < 3) { // Jika hanya ada 2 data (kepala dan kasubbag)
        $insert_pegawai = "INSERT INTO profil (nama, jabatan, foto, link) VALUES 
            ('Agus Setiawan', 'Statistisi Ahli Muda', 'default-male.jpg', 'https://www.canva.com/design/DAFxyz123/embed'),
            ('Budi Santoso', 'Statistisi Ahli Muda', 'default-male.jpg', 'https://www.canva.com/design/DAFxyz124/embed'),
            ('Citra Dewi', 'Statistisi Ahli Pertama', 'default-female.jpg', 'https://www.canva.com/design/DAFxyz125/embed'),
            ('Dian Purnama', 'Pranata Komputer', 'default-female.jpg', 'https://www.canva.com/design/DAFxyz126/embed')";
        if ($conn->query($insert_pegawai) === TRUE) {
            echo '<div class="success">Data pegawai contoh berhasil ditambahkan.</div>';
        } else {
            echo '<div class="error">Error menambahkan data pegawai contoh: ' . $conn->error . '</div>';
        }
    }
    
} else {
    echo '<div class="error">Error membuat tabel: ' . $conn->error . '</div>';
}

// Buat file gambar default jika belum ada
$default_images = [
    'default-male.jpg',
    'default-female.jpg',
    'kepala.jpg',
    'kasubbag.jpg'
];

$staff_dir = 'img/staff/';
if (!is_dir($staff_dir)) {
    if (mkdir($staff_dir, 0755, true)) {
        echo '<div class="success">Direktori img/staff/ berhasil dibuat.</div>';
    } else {
        echo '<div class="error">Error membuat direktori img/staff/.</div>';
    }
}

foreach ($default_images as $image) {
    $image_path = $staff_dir . $image;
    if (!file_exists($image_path)) {
        // Buat file gambar placeholder sederhana
        $placeholder = imagecreatetruecolor(200, 200);
        $bg_color = imagecolorallocate($placeholder, 240, 240, 240);
        $text_color = imagecolorallocate($placeholder, 50, 50, 50);
        imagefill($placeholder, 0, 0, $bg_color);
        imagestring($placeholder, 5, 40, 90, 'Placeholder ' . $image, $text_color);
        
        // Simpan gambar
        imagejpeg($placeholder, $image_path, 90);
        imagedestroy($placeholder);
        
        echo '<div class="success">File gambar placeholder ' . $image . ' berhasil dibuat.</div>';
    }
}

echo '<div class="success">Setup selesai!</div>';
echo '<a href="admin-profil.php" class="btn">Kembali ke halaman admin profil</a>';
echo '<a href="profil.php" class="btn" style="margin-left: 10px;">Lihat halaman profil</a>';

$conn->close();

echo '</div></body></html>';
?> 