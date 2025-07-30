<?php
// Simple delete handler - file terpisah untuk menghindari konflik
session_start();

// Debug log
error_log("Delete user request received");
error_log("POST data: " . print_r($_POST, true));
error_log("Session: " . print_r($_SESSION, true));

// Cek auth
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    error_log("Auth failed");
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Set JSON header
header('Content-Type: application/json');

// Get user ID
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
error_log("User ID to delete: " . $user_id);

if ($user_id <= 0) {
    error_log("Invalid user ID");
    echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
    exit;
}

if ($user_id == $_SESSION['user_id']) {
    error_log("Cannot delete own account");
    echo json_encode(['success' => false, 'message' => 'Tidak bisa hapus akun sendiri']);
    exit;
}

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "sigap", 3306);
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

error_log("Database connected successfully");

// Simple delete query
$stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND id != ?");
$stmt->bind_param("ii", $user_id, $_SESSION['user_id']);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    error_log("User deleted successfully");
    echo json_encode(['success' => true, 'message' => 'User berhasil dihapus']);
} else {
    error_log("Delete failed. Affected rows: " . $stmt->affected_rows);
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus user']);
}

$stmt->close();
$conn->close();
?>
