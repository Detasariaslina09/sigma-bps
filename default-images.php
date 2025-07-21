<?php
// Tambahkan header HTML untuk tampilan yang lebih baik
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Buat Gambar Default - BPS Kota Bandar Lampung</title>
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
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
        }
        .image-item {
            text-align: center;
        }
        .image-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #1a3c6e;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buat Gambar Default</h1>';

// Buat direktori staff jika belum ada
$staff_dir = 'img/staff/';
if (!is_dir($staff_dir)) {
    if (mkdir($staff_dir, 0755, true)) {
        echo '<div class="success">Direktori img/staff/ berhasil dibuat.</div>';
    } else {
        echo '<div class="error">Error membuat direktori img/staff/.</div>';
    }
}

// Buat file gambar default
$default_images = [
    'default-male.jpg' => [30, 144, 255], // Biru
    'default-female.jpg' => [255, 105, 180], // Pink
    'kepala.jpg' => [0, 128, 0], // Hijau
    'kasubbag.jpg' => [128, 0, 128] // Ungu
];

echo '<div class="image-preview">';

foreach ($default_images as $image => $color) {
    $image_path = $staff_dir . $image;
    
    // Buat gambar sederhana dengan warna yang ditentukan
    $img = imagecreatetruecolor(200, 200);
    $bg_color = imagecolorallocate($img, $color[0], $color[1], $color[2]);
    $text_color = imagecolorallocate($img, 255, 255, 255);
    
    // Isi background
    imagefill($img, 0, 0, $bg_color);
    
    // Gambar lingkaran untuk avatar
    $circle_color = imagecolorallocate($img, 255, 255, 255);
    imagefilledellipse($img, 100, 80, 80, 80, $circle_color);
    
    // Gambar bentuk badan
    imagefilledrectangle($img, 70, 120, 130, 180, $circle_color);
    
    // Tambahkan teks
    $text = str_replace('.jpg', '', $image);
    imagestring($img, 3, 60, 185, $text, $text_color);
    
    // Simpan gambar
    imagejpeg($img, $image_path, 90);
    imagedestroy($img);
    
    echo '<div class="image-item">';
    echo '<img src="' . $image_path . '?' . time() . '" alt="' . $text . '">';
    echo '<p>' . $text . '</p>';
    echo '</div>';
    
    echo '<div class="success">File gambar ' . $image . ' berhasil dibuat.</div>';
}

echo '</div>';

echo '<div class="success">Semua gambar default berhasil dibuat!</div>';
echo '<a href="admin-profil.php" class="btn">Kembali ke halaman admin profil</a>';
echo '<a href="profil.php" class="btn" style="margin-left: 10px;">Lihat halaman profil</a>';

echo '</div></body></html>';
?> 