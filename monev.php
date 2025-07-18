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
<title>Monitoring dan Evaluasi - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Monitoring dan Evaluasi Tim BPS Kota Bandar Lampung" />
<meta name="author" content="" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/sidebar.css" rel="stylesheet" />
 
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<style>
    .monev-container {
        margin-top: 30px;
    }
    
    .monev-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .monev-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    
    .monev-icon {
        width: 100px;
        height: 100px;
        margin: 20px auto;
        background: #f5f5f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 0;
    }
    
    .monev-icon img {
        width: 60px;
        height: 60px;
        object-fit: contain;
    }
    
    .monev-icon i {
        font-size: 40px;
        color: #ff9800;
    }
    
    .monev-content {
        padding: 20px;
        text-align: center;
    }
    
    .monev-content h3 {
        color: #333;
        font-size: 22px;
        margin-bottom: 15px;
    }
    
    .monev-content p {
        color: #777;
        font-size: 14px;
        margin-bottom: 20px;
        min-height: 60px;
    }
    
    .btn-monev {
        background: #ff9800;
        color: white !important;
        padding: 8px 20px;
        border-radius: 30px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 10px;
        display: inline-block;
    }
    
    .btn-monev:hover {
        background: #e65100;
        color: white !important;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    }
    
    .btn-monev i {
        color: white !important;
    }
    
    /* Perbaikan khusus untuk teks tombol */
    .monev-content .btn-monev,
    .monev-content .btn-monev:hover,
    .monev-content .btn-monev:focus,
    .monev-content .btn-monev:active {
        color: white !important;
    }
    
    .monev-content .btn-monev i {
        color: white !important;
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
            <li class="active"><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="contact.php">Pengaduan</a></li>
            
            <?php if ($is_admin): ?>
            <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
            <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
            <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
            <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
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
            <!-- Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h2>Monitoring dan Evaluasi</h2>
                        <p>Sistem Monitoring dan Evaluasi Tim BPS Kota Bandar Lampung</p>
                    </div>
                </div>
            </div>
            
            <!-- Tim Cards -->
            <div class="row monev-container">
                <!-- Tim Umum -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card">
                    <div class="monev-icon">
                        <img src="img/Monev/umum.png">
                    </div>
                        <div class="monev-content">
                            <h3>Tim Umum</h3>
                            <p>Monitoring dan evaluasi untuk Bagian Umum BPS Kota Bandar Lampung.</p>
                            <a href="monev/monev-umum.php" class="btn-monev" style="color: white !important;"><span style="color: white !important;">Lihat</span> <i class="fa fa-arrow-right" style="color: white !important;"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Sosial -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card">
                        <div class="monev-icon">
                            <img src="img/Monev/sosial.png">
                        </div>
                        <div class="monev-content">
                            <h3>Tim Sosial</h3>
                            <p>Monitoring dan evaluasi untuk Tim Statistik Sosial BPS Kota Bandar Lampung.</p>
                            <a href="monev/monev-sosial.php" class="btn-monev" style="color: white !important;"><span style="color: white !important;">Lihat</span> <i class="fa fa-arrow-right" style="color: white !important;"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Produksi -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card">
                        <div class="monev-icon">
                            <img src="img/Monev/produksi.png">
                        </div>
                        <div class="monev-content">
                            <h3>Tim Produksi</h3>
                            <p>Monitoring dan evaluasi untuk Tim Statistik Produksi BPS Kota Bandar Lampung.</p>
                            <a href="monev/monev-produksi.php" class="btn-monev" style="color: white !important;"><span style="color: white !important;">Lihat</span> <i class="fa fa-arrow-right" style="color: white !important;"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Distribusi -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card">
                        <div class="monev-icon">
                            <img src="img/Monev/distri.png">
                        </div>
                        <div class="monev-content">
                            <h3>Tim Distribusi</h3>
                            <p>Monitoring dan evaluasi untuk Tim Statistik Distribusi BPS Kota Bandar Lampung.</p>
                            <a href="monev/monev-distribusi.php" class="btn-monev" style="color: white !important;"><span style="color: white !important;">Lihat</span> <i class="fa fa-arrow-right" style="color: white !important;"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Nerwilis -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card">
                        <div class="monev-icon">
                            <img src="img/Monev/nerwilis.png">
                        </div>
                        <div class="monev-content">
                            <h3>Tim Nerwilis</h3>
                            <p>Monitoring dan evaluasi untuk Tim Neraca dan Analisis Statistik BPS Kota Bandar Lampung.</p>
                            <a href="monev/monev-nerwilis.php" class="btn-monev" style="color: white !important;"><span style="color: white !important;">Lihat</span> <i class="fa fa-arrow-right" style="color: white !important;"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim PTID -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card">
                        <div class="monev-icon">
                            <img src="img/Monev/ptid.png">
                        </div>
                        <div class="monev-content">
                            <h3>Tim PTID</h3>
                            <p>Monitoring dan evaluasi untuk Tim Pengolahan dan Diseminasi Informasi Statistik.</p>
                            <a href="monev/monev-ptid.php" class="btn-monev" style="color: white !important;"><span style="color: white !important;">Lihat</span> <i class="fa fa-arrow-right" style="color: white !important;"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>Badan Pusat Statistik Kota Bandar Lampung</h4>
                    <address>
                        <i class="fa fa-map-marker"></i> Jl. Sutan Syahrir No. 30, Pahoman<br>
                        Bandar Lampung, 35215<br>
                        <i class="fa fa-phone"></i> Telp: (0721) 255980<br>
                        <i class="fa fa-envelope"></i> Email: <a href="mailto:bps1871@bps.go.id">bps1871@bps.go.id</a>
                    </address>
                </div>
                <div class="col-md-4">
                    <h4>Tautan Cepat</h4>
                    <ul class="link-list">
                        <li><a href="index.php"><i class="fa fa-angle-right"></i> Beranda</a></li>
                        <li><a href="monev.php"><i class="fa fa-angle-right"></i> Monev</a></li>
                        <li><a href="about.php"><i class="fa fa-angle-right"></i> Layanan</a></li>
                        <li><a href="services.php"><i class="fa fa-angle-right"></i> Pusat Aplikasi</a></li>
                        <li><a href="contact.php"><i class="fa fa-angle-right"></i> Pengaduan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4>Ikuti Kami</h4>
                    <ul class="social-network">
                        <li><a href="#" data-placement="top" title="Facebook"><i class="fa fa-facebook fa-2x"></i></a></li>
                        <li><a href="#" data-placement="top" title="Twitter"><i class="fa fa-twitter fa-2x"></i></a></li>
                        <li><a href="#" data-placement="top" title="Instagram"><i class="fa fa-instagram fa-2x"></i></a></li>
                        <li><a href="#" data-placement="top" title="Youtube"><i class="fa fa-youtube fa-2x"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        </footer>
        <div id="sub-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="copyright">
                            <p>Hak Cipta © 2025 Badan Pusat Statistik Kota Bandar Lampung. Semua Hak Dilindungi.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="social-network">
                            <li><a href="#" data-placement="top" title="Kembali ke Atas"><i class="fa fa-arrow-up"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
        $('.logout-link').on('click', function(e) {
            e.preventDefault();
            
            // Hapus session storage
            sessionStorage.removeItem('isLoggedIn');
            sessionStorage.removeItem('username');
            sessionStorage.removeItem('userRole');
            
            // Alihkan ke halaman utama
            window.location.href = 'logout.php';
        });
    });
</script>
</body>
</html> 