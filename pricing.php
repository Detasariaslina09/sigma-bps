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
<title>Dokumentasi Rapat - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Dokumentasi foto kegiatan rapat dan notulensi" />
<meta name="author" content="" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/sidebar.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />

<style>
    .category-container {
        margin-top: 50px;
    }
    
    .category-item {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    
    .category-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    
    .category-image {
        height: 300px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .category-image::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }
    
    .category-content {
        padding: 30px;
        text-align: center;
    }
    
    .category-content h3 {
        font-size: 24px;
        margin-top: 0;
        margin-bottom: 15px;
        color: #333;
    }
    
    .category-content p {
        color: #666;
        margin-bottom: 20px;
    }
    
    .btn-category {
        background: #ff9800 !important;
        color: #fff !important;
        padding: 12px 25px;
        border-radius: 30px;
        display: inline-block;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .btn-category:hover {
        background: #e65100 !important;
        color: #fff !important;
        text-decoration: none;
        transform: scale(1.05);
    }
    
    .btn-category i {
        margin-left: 5px;
        color: #fff !important;
    }
    
    /* Mobile menu toggle - REMOVED to use global styling */
    
    @media (max-width: 768px) {
        
        
        
        
        
        
        
    }
</style>
 
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

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
            <li class="active"><a href="pricing.php">Dokumentasi</a></li>
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
    		<div class="section-title">
    			<h2>Dokumentasi Kegiatan</h2>
    			<p>Foto Kegiatan dan Notulensi Rapat</p>
    		</div>
    		
    		<div class="row category-container">
                <!-- Dokumentasi -->
                <div class="col-md-6">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/dokument.png');"></div>
                        <div class="category-content">
                            <h3>Dokumentasi Foto Kegiatan</h3>
                            <p>Akses ke seluruh dokumentasi foto kegiatan rapat dan acara BPS Kota Bandar Lampung</p>
                            <a href="https://drive.google.com/drive/folders/1234567890abcdefghijklmnopqrstuvwxyz" target="_blank" class="btn-category">Lihat Dokumentasi <i class="fa fa-camera"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Notulensi -->
                <div class="col-md-6">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/notulen.png');"></div>
                        <div class="category-content">
                            <h3>Notulensi Rapat</h3>
                            <p>Akses ke seluruh notulensi dan hasil-hasil rapat BPS Kota Bandar Lampung</p>
                            <a href="https://drive.google.com/drive/folders/abcdefghijklmnopqrstuvwxyz1234567890" target="_blank" class="btn-category">Lihat Notulensi <i class="fa fa-file-text"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Dokumentasi Humas -->
                <div class="col-md-6">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/humas.png');"></div>
                        <div class="category-content">
                            <h3>Dokumentasi Humas</h3>
                            <p>Akses ke seluruh dokumentasi kegiatan kehumasan dan publikasi BPS Kota Bandar Lampung</p>
                            <a href="https://drive.google.com/drive/folders/humas12345" target="_blank" class="btn-category">Lihat Dokumentasi Humas <i class="fa fa-bullhorn"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Link Quotes -->
                <div class="col-md-6">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/quote.png');"></div>
                        <div class="category-content">
                            <h3>Link Quotes</h3>
                            <p>Kumpulan quotes inspiratif dan motivasi untuk pegawai BPS Kota Bandar Lampung</p>
                            <a href="https://docs.google.com/spreadsheets/d/1Mc9ECxVqkr5Sm2IGWhenOnS3lSErxouZEDNHe6REkX8/edit?gid=0#gid=0" target="_blank" class="btn-category">Lihat Quotes <i class="fa fa-quote-right"></i></a>
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
                $('.sidebar').toggleClass('open');
            });
        });
    </script>
</body>
</html>