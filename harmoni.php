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
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/harmoni.css" rel="stylesheet" />

</head>
<body>
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
            
            <?php if ($is_logged_in): ?>
                <li><a href="monev.php">Monev</a></li>
                <li><a href="about.php">Layanan</a></li>
                <li><a href="pricing.php">Dokumentasi</a></li>
                <li class="active"><a href="harmoni.php">Harmoni</a></li>
                
                <?php if ($is_admin): ?>
                    <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                    <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                    <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                    <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
                <?php endif; ?>
                
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
            <img src="img/pengaduan.png" alt="Layanan Harmoni" class="harmoni-image">
            
            <div class="harmoni-content">
                <h3>Hamparan Saran dan Komentar untuk Kantor Nyaman BPS Kota Bandar Lampung</h3>
                <p>Harmoni adalah platform komunikasi terpadu yang disediakan oleh BPS Kota Bandar Lampung untuk memfasilitasi aspirasi, saran, dan komunikasi antar pegawai. Platform ini dirancang untuk menciptakan lingkungan kerja yang harmonis dan kolaboratif.</p>
                
                <div class="harmoni-cta">
                    <a href="http://s.bps.go.id/Harmony1871" target="_blank" class="btn-harmoni">
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
<script src="js/harmoni.js"></script>
</body>
</html>