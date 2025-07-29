<?php
// Mulai session
session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Include file koneksi database
require_once 'koneksi.php';

// Inisialisasi variabel untuk pesan error/sukses
$login_error = '';
$success_msg = '';

// Proses form login jika ada POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validasi input sederhana
    if (empty($username) || empty($password)) {
        $login_error = "Username dan password harus diisi";
    } else {
        try {
            // Cari user di database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    // Password cocok, buat session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Tambahkan script untuk sessionStorage sebelum redirect
                    echo "<script>
                        sessionStorage.setItem('isLoggedIn', 'true');
                        sessionStorage.setItem('username', '" . addslashes($user['username']) . "');
                        sessionStorage.setItem('userRole', '" . addslashes($user['role']) . "');
                        window.location.href = 'index.php';
                    </script>";
                    exit;
                } else {
                    $login_error = "Password salah";
                }
            } else {
                $login_error = "Username tidak ditemukan";
            }
        } catch (Exception $e) {
            $login_error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Halaman login BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/login.css" rel="stylesheet" />
</head>
<body class="login-background">
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <!-- Sidebar menu -->
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li class="active"><a href="login.php">Login</a></li>
        </ul>
    </div>

    <div id="wrapper">
        <section id="content">
            <div class="container">
                <div class="login-container">
                    <div class="login-logo">
                        <img src="img/sigma.png" alt="BPS Logo">
                    </div>
                    <div class="login-title">
                        Login SIGMA
                    </div>
                    <div class="login-subtitle">
                        Sistem Informasi Pegawai dan Manajemen Aktivitas
                    </div>
                    
                    <?php if (!empty($login_error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fa fa-exclamation-circle"></i> <?php echo $login_error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fa fa-check-circle"></i> <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="login-form" class="login-form" method="post" action="login.php">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Ingat saya
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn-login">
                            <i class="fa fa-sign-in"></i> Login
                        </button>
                    </form>
                    
                    <div class="login-footer">
                        <small>© 2025 BPS Kota Bandar Lampung. Semua Hak Dilindungi.</small>
                    </div>
                </div>
            </div>
        </section>
        
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h4>Badan Pusat Statistik Kota Bandar Lampung</h4>
                        <address>
                            Jl. Sutan Syahrir No. 30, Pahoman, Bandar Lampung, 35215<br>
                            Telp. (0721) 255980. Mailbox : bps1871@bps.go.id
                        </address>
                        <div class="text-center">
                            <p>Hak Cipta © 2025 Badan Pusat Statistik Kota Bandar Lampung</p>
                            <p>Semua Hak Dilindungi</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>

    <!-- javascript -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/login.js"></script>
</body>
</html> 