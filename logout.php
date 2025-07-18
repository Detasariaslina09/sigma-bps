<?php
// Mulai session
session_start();

// Hapus semua data session
$_SESSION = array();

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hapus cookie remember me jika ada
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Akhiri session
session_destroy();

// Tambahkan script untuk menghapus sessionStorage sebelum redirect
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <script>
        // Hapus data login dari sessionStorage
        sessionStorage.removeItem('isLoggedIn');
        sessionStorage.removeItem('username');
        sessionStorage.removeItem('userRole');
        // Redirect ke halaman login
        window.location.href = 'login.php';
    </script>
</head>
<body>
    <p>Logging out...</p>
</body>
</html> 