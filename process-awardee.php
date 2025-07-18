<?php
// File: process-awardee.php
// Deskripsi: File untuk memproses penambahan/edit/hapus data awardee

// Koneksi database
$servername = "localhost";
$username = "root";  // Sesuaikan dengan username database Anda
$password = "";     // Sesuaikan dengan password database Anda
$dbname = "sigap"; // Sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set header untuk response JSON
header('Content-Type: application/json');

// Fungsi untuk membuat response JSON
function sendResponse($status, $message, $data = null) {
    $response = array(
        'status' => $status,
        'message' => $message
    );
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

// Memeriksa apakah ada request POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    // Tindakan berdasarkan jenis action
    switch ($action) {
        case 'add':
            addAwardee($conn);
            break;
            
        case 'edit':
            editAwardee($conn);
            break;
            
        case 'delete':
            deleteAwardee($conn);
            break;
            
        default:
            sendResponse('error', 'Action tidak dikenali');
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Untuk request GET (mengambil data)
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    switch ($action) {
        case 'getAll':
            getAllAwardees($conn);
            break;
            
        case 'getOne':
            getOneAwardee($conn);
            break;
            
        default:
            sendResponse('error', 'Action tidak dikenali');
    }
} else {
    sendResponse('error', 'Method tidak didukung');
}

// Fungsi untuk menambah awardee baru
function addAwardee($conn) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    // Validasi input
    if (empty($title) || empty($description)) {
        sendResponse('error', 'Judul dan deskripsi harus diisi');
    }
    
    // Upload gambar
    $image_path = uploadImage();
    
    // Query untuk menambah data
    $sql = "INSERT INTO konten (title, description, image_path) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $description, $image_path);
    
    if ($stmt->execute()) {
        sendResponse('success', 'Data awardee berhasil ditambahkan', ['id' => $conn->insert_id]);
    } else {
        sendResponse('error', 'Gagal menambahkan data: ' . $stmt->error);
    }
    
    $stmt->close();
}

// Fungsi untuk mengedit awardee
function editAwardee($conn) {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    // Validasi input
    if (empty($title) || empty($description) || empty($id)) {
        sendResponse('error', 'ID, Judul dan deskripsi harus diisi');
    }
    
    // Cek apakah ada upload gambar baru
    if (!empty($_FILES['image']['name'])) {
        // Upload gambar baru
        $image_path = uploadImage();
        
        // Update data dengan gambar baru
        $sql = "UPDATE konten SET title = ?, description = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $image_path, $id);
    } else {
        // Update data tanpa mengubah gambar
        $sql = "UPDATE konten SET title = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $description, $id);
    }
    
    if ($stmt->execute()) {
        sendResponse('success', 'Data awardee berhasil diperbarui');
    } else {
        sendResponse('error', 'Gagal memperbarui data: ' . $stmt->error);
    }
    
    $stmt->close();
}

// Fungsi untuk menghapus awardee
function deleteAwardee($conn) {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    
    // Validasi input
    if (empty($id)) {
        sendResponse('error', 'ID harus diisi');
    }
    
    // Hapus data
    $sql = "DELETE FROM konten WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        sendResponse('success', 'Data awardee berhasil dihapus');
    } else {
        sendResponse('error', 'Gagal menghapus data: ' . $stmt->error);
    }
    
    $stmt->close();
}

// Fungsi untuk mengambil semua data awardee
function getAllAwardees($conn) {
    $sql = "SELECT * FROM konten ORDER BY id DESC";
    $result = $conn->query($sql);
    
    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        sendResponse('success', 'Data berhasil diambil', $data);
    } else {
        sendResponse('success', 'Tidak ada data', []);
    }
}

// Fungsi untuk mengambil satu data awardee berdasarkan ID
function getOneAwardee($conn) {
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    
    // Validasi input
    if (empty($id)) {
        sendResponse('error', 'ID harus diisi');
    }
    
    $sql = "SELECT * FROM awardee WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        sendResponse('success', 'Data berhasil diambil', $data);
    } else {
        sendResponse('error', 'Data tidak ditemukan');
    }
    
    $stmt->close();
}

// Fungsi untuk upload gambar
function uploadImage() {
    // Direktori untuk upload
    $target_dir = "img/awardee/";
    
    // Pastikan direktori ada, jika tidak buat
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Nama file dengan timestamp untuk menghindari duplikasi
    $timestamp = time();
    $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . "awardee_" . $timestamp . "." . $file_extension;
    
    // Validasi ukuran file (max 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        sendResponse('error', 'Ukuran file terlalu besar (maksimal 2MB)');
    }
    
    // Validasi format file
    if ($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg" && $file_extension != "webp") {
        sendResponse('error', 'Format file tidak didukung (gunakan JPG, PNG, atau WebP)');
    }
    
    // Upload file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        sendResponse('error', 'Gagal mengupload file');
    }
}

// Tutup koneksi
$conn->close();
?> 