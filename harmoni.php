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
<title>Layanan Harmoni</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Layanan Harmoni untuk menyampaikan keluhan dan saran" />
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
    .harmoni-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 40px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin: 30px 0;
    }

    .harmoni-image {
        width: 40%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .harmoni-content {
        width: 55%;
        padding-left: 40px;
    }

    .harmoni-content h3 {
        color: #1a3c6e;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .harmoni-content p {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .harmoni-cta {
        text-align: left;
    }

    .btn-harmoni {
        background: #ff9800;
        color: #ffffff !important;
        padding: 12px 25px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        font-size: 16px;
    }

    .btn-harmoni:hover {
        background: #e65100;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230,81,0,0.2);
        color: #ffffff !important;
    }

    .btn-harmoni i {
        margin-right: 10px;
        font-size: 18px;
        color: #ffffff !important;
    }

    .btn-harmoni span {
        color: #ffffff !important;
    }

    .harmoni-cta a {
        color: #ffffff !important;
        text-decoration: none;
    }

    .harmoni-cta a:hover {
        color: #ffffff !important;
        text-decoration: none;
    }

    /* Mobile menu toggle */
    
    
    @media (max-width: 768px) {
        .harmoni-container {
            flex-direction: column;
            padding: 20px;
        }
        
        .harmoni-image {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .harmoni-content {
            width: 100%;
            padding-left: 0;
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
            <li class="active"><a href="harmoni.php">Harmoni</a></li>
            
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
	        <h2>Layanan Harmoni</h2>
	        <p>Platform Aspirasi dan Komunikasi BPS Kota Bandar Lampung</p>
	    </div>
	    
	    <div class="harmoni-container">
	        <img src="img/2pengaduan.png" alt="Layanan Harmoni" class="harmoni-image">
	        
	        <div class="harmoni-content">
	            <h3>Tentang Layanan Harmoni</h3>
	            <p>Harmoni adalah platform komunikasi terpadu yang disediakan oleh BPS Kota Bandar Lampung untuk memfasilitasi aspirasi, saran, dan komunikasi antar pegawai. Platform ini dirancang untuk menciptakan lingkungan kerja yang harmonis dan kolaboratif.</p>
	            
	            <div class="harmoni-cta">
	                <a href="https://sipena.bps.go.id" target="_blank" class="btn-harmoni">
	                    <i class="fa fa-comments"></i><span>Akses Harmoni</span>
	                </a>
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