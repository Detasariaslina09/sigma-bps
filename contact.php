<?php
// Mulai session
session_start();

// Periksa status login - jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek status login dan role
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Layanan Pengaduan</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Layanan pengaduan untuk menyampaikan keluhan dan saran" />
<meta name="author" content="http://webthemez.com" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/sidebar.css" rel="stylesheet" />

<style>
    .pengaduan-container {
        margin-top: 30px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .pengaduan-image {
        width: 100%;
        height: 200px;
        object-fit: contain;
        padding: 20px;
        background-color: #f5f5f5;
    }
    
    .pengaduan-content {
        padding: 30px;
    }
    
    .pengaduan-content h3 {
        color: #ff9800;
        margin-top: 0;
        margin-bottom: 15px;
        text-align: center;
        font-size: 20px;
    }
    
    .pengaduan-content p {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    
    .pengaduan-cta {
        text-align: center;
        margin-top: 20px;
    }
    
    .btn-pengaduan {
        background: #ff9800;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-pengaduan:hover {
        background: #ff9800;
        color: white;
        text-decoration: none;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }
    
    .btn-pengaduan i {
        margin-right: 6px;
    }
    
    /* Mobile menu toggle */
    
    
    @media (max-width: 768px) {
        
        
        
        
        
        
        
        
        .pengaduan-content {
            padding: 15px;
        }
    }
</style>
</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <!-- Sidebar menu -->
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/logoo.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li class="active"><a href="contact.php">Pengaduan</a></li>
            
            <?php if ($is_admin): ?>
            <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
            <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
            <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
            <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
            <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-id-card"></i> Manajemen Profil</a></li>
            <?php endif; ?>
            
            <?php if ($is_logged_in): ?>
                <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
            <?php endif; ?>
        </ul>
    </div>

<div id="wrapper">
	<section id="content">
	<div class="container">
	    <div class="section-title">
	        <h2>Layanan Pengaduan</h2>
	        <p>Sampaikan keluhan, saran, dan aspirasi Anda</p>
	    </div>
	    
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="pengaduan-container">
	                <!-- Gambar di bagian atas -->
	                <img src="img/2pengaduan.png" alt="Layanan Pengaduan" class="pengaduan-image">
	                
	                <!-- Konten penjelasan -->
	                <div class="pengaduan-content">
	                    <h3>Tentang Layanan Pengaduan</h3>
	                    <p>Layanan Pengaduan adalah platform yang disediakan oleh BPS Kota Bandar Lampung untuk menerima dan mengelola berbagai pengaduan, keluhan, saran, serta aspirasi dari seluruh pegawai.</p>
	                    
	                    <p>Identitas pelapor akan kami jaga kerahasiaannya sesuai dengan peraturan yang berlaku.</p>
	                    
	                    <!-- Button menuju link pengaduan -->
	                    <div class="pengaduan-cta">
	                        <a href="https://sipena.bps.go.id" target="_blank" class="btn-pengaduan">
	                            <i class="fa fa-envelope"></i> Buat Pengaduan
	                        </a>
	                    </div>
	                </div>
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
                    <div class="copyright">
                        <p>Hak Cipta © 2025 Badan Pusat Statistik Kota Bandar Lampung<br>
                        Semua Hak Dilindungi</p>
                    </div>
                </div>
            </div>
        </div>
        </footer>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.fancybox.pack.js"></script>
<script src="js/jquery.fancybox-media.js"></script> 
<script src="js/portfolio/jquery.quicksand.js"></script>
<script src="js/portfolio/setting.js"></script>
<script src="js/jquery.flexslider.js"></script>
<script src="js/animate.js"></script>
<script src="js/custom.js"></script>
    <script src="js/sidebar.js"></script>
<script src="js/validate.js"></script>
<script>
    // Cek apakah sudah login
    function checkLogin() {
        var isLoggedIn = sessionStorage.getItem('isLoggedIn');
        if (!isLoggedIn) {
            // Jika belum login, alihkan ke halaman utama
            window.location.href = 'index.php';
        } else {
            // Jika sudah login, cek role untuk menampilkan/sembunyikan menu admin
            showHideAdminMenu();
        }
    }
    
    // Fungsi untuk menampilkan/menyembunyikan menu admin berdasarkan role
    function showHideAdminMenu() {
        var role = sessionStorage.getItem('userRole');
        if (role === 'admin') {
            // Tampilkan menu admin
            $('.admin-menu').show();
        } else {
            // Sembunyikan menu admin
            $('.admin-menu').hide();
        }
    }
    
    $(document).ready(function() {
        // Sembunyikan menu admin secara default
        $('.admin-menu').hide();
        
        // Cek login saat halaman dimuat
        checkLogin();
        
        // Handle logout button
        $('#logout-btn').on('click', function(e) {
            e.preventDefault();
            
            // Hapus session storage
            sessionStorage.removeItem('isLoggedIn');
            sessionStorage.removeItem('username');
            sessionStorage.removeItem('userRole');
            
            // Alihkan ke halaman utama
            window.location.href = 'index.php';
        });
        
        // Mobile menu toggle
        $('.mobile-menu-toggle').on('click', function() {
            console.log("Button Pressed, adding class");
            $('.sidebar').toggleClass('open');
        });
    });
</script>
</body>
</html>