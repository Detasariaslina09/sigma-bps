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
<title>Monev Tim Distribusi - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Monitoring dan Evaluasi Tim Distribusi BPS Kota Bandar Lampung" />
<meta name="author" content="" />
<!-- css -->
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link href="../css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../css/jcarousel.css" rel="stylesheet" />
<link href="../css/flexslider.css" rel="stylesheet" />
<link href="../css/style.css" rel="stylesheet" />
<link href="../css/custom-styles.css" rel="stylesheet" />
<link href="../css/font-awesome.css" rel="stylesheet" />
 
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<style>
    .app-list {
        margin-top: 30px;
    }
    
    .service-category-container {
        margin-top: 30px;
    }
    
    .service-category-item {
        margin-bottom: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .service-category-content {
        display: flex;
        flex-direction: row;
    }
    
    .service-category-info {
        flex: 1;
        padding: 20px;
    }
    
    .service-category-info h3 {
        color: #ff9800;
        margin-top: 0;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #eee;
    }
    
    .service-links-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .service-links-list li {
        margin-bottom: 10px;
    }
    
    .service-links-list li a {
        color: #111 !important;
        text-decoration: none;
        display: block;
        padding: 8px 10px;
        background: #f9f9f9;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .service-links-list li a:hover {
        background: #ff9800;
        color: #fff !important;
        padding-left: 15px;
    }
    
    .header-back {
        margin-bottom: 30px;
    }
    
    .btn-back {
        background: #f5f5f5;
        color: #333;
        padding: 8px 15px;
        border-radius: 4px;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-back:hover {
        background: #e0e0e0;
        color: #333;
        text-decoration: none;
    }
    
    .btn-back i {
        margin-right: 5px;
    }
    
    /* Media query for mobile */
    @media (max-width: 768px) {
        .service-category-content {
            flex-direction: column;
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
        <a class="navbar-brand" href="../index.php"><img src="../img/logoo.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="../index.php">Beranda</a></li>
            <li class="active"><a href="../monev.php">Monev</a></li>
            <li><a href="../about.php">Layanan</a></li>
            <li><a href="../services.php">Pusat Aplikasi</a></li>
            <li><a href="../pricing.php">Dokumentasi</a></li>
            <li><a href="../contact.php">Pengaduan</a></li>
            
            <?php if ($is_admin): ?>
            <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
            <li class="admin-menu"><a href="../admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
            <li class="admin-menu"><a href="../admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
            <li class="admin-menu"><a href="../admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
            <?php endif; ?>
            
            <?php if ($is_logged_in): ?>
                <li class="logout-menu"><a href="../logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="../login.php"><i class="fa fa-sign-in"></i> Login</a></li>
            <?php endif; ?>
        </ul>
    </div>

<div id="wrapper">
    <section id="content">
        <div class="container">
            <!-- Header & Back Button -->
            <div class="row header-back">
                <div class="col-md-6">
                    <h2><i class="fa fa-truck"></i> Monitoring dan Evaluasi Tim Distribusi</h2>
                </div>
                <div class="col-md-6 text-right">
                    <a href="../monev.php" class="btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Tim</a>
                </div>
            </div>
            
            <!-- Apps List -->
            <div class="service-category-container">
                <!-- Aplikasi 1: SIMDIS -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMDIS</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simdis" target="_blank">Sistem Informasi Monitoring Distribusi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Aplikasi 2: SIMDAG -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMDAG</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simdag" target="_blank">Sistem Informasi Monitoring Perdagangan</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Aplikasi 3: SIMHARGA -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMHARGA</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simharga" target="_blank">Sistem Informasi Monitoring Harga</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Aplikasi 4: SIMINDAG -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMINDAG</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simindag" target="_blank">Sistem Informasi Monitoring Indeks Harga</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Aplikasi 5: SIMEKS -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMEKS</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simeks" target="_blank">Sistem Informasi Monitoring Ekspor dan Impor</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Aplikasi 6: SIMPAR -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMPAR</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simpar" target="_blank">Sistem Informasi Monitoring Pariwisata</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Aplikasi 7: SIMTRANS -->
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>SIMTRANS</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simtrans" target="_blank">Sistem Informasi Monitoring Transportasi</a></li>
                            </ul>
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
<script src="../js/jquery.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.fancybox.pack.js"></script>
<script src="../js/jquery.fancybox-media.js"></script> 
<script src="../js/portfolio/jquery.quicksand.js"></script>
<script src="../js/portfolio/setting.js"></script>
<script src="../js/jquery.flexslider.js"></script>
<script src="../js/animate.js"></script>
<script src="../js/custom.js"></script>
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