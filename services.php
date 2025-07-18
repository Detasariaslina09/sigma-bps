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
<title>Pusat Layanan Aplikasi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Pusat layanan aplikasi yang menyediakan akses ke berbagai aplikasi penting" />
<meta name="author" content="http://webthemez.com" />
 
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
 
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<style>
.app-card .btn-app {
  background: #ff9800;
  color: #fff !important;
  border: none;
  padding: 8px 20px;
  border-radius: 5px;
  font-weight: 600;
  transition: background 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(255,152,0,0.08);
}
.app-card .btn-app:hover {
  background: #e65100;
  color: #fff !important;
  box-shadow: 0 4px 16px rgba(230,81,0,0.12);
}
.app-card .btn-app, .app-card .btn-app:visited, .app-card .btn-app:active, .app-card .btn-app:focus {
  color: #fff !important;
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
            <li><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li class="active"><a href="services.php">Pusat Aplikasi</a></li>
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
            <div class="section-title">
                <h2>Layanan Pusat SDM</h2>
                <p>Badan Pusat Statistik RI</p>
            </div>
            
            <div class="row">
                <!-- App 1 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/bps.png" alt="Aplikasi 1" class="app-logo">
                        <h3>SIMPEG</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://simpeg.bps.go.id/data/" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 2 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/sipecut.png" alt="Aplikasi 2" class="app-logo">
                        <h3>SIPECUT</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://sipecut.bps.go.id/app/index.php/pegawai" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 3 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/KipApp.png" alt="Aplikasi 3" class="app-logo">
                        <h3>KipApp</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://webapps.bps.go.id/kipapp/#/auth/login" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- App 4 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/Siimut.png" alt="Aplikasi 4" class="app-logo">
                        <h3>SiImut</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://sdm.bps.go.id/siimut/web/" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 5 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/bps.png" alt="Aplikasi 5" class="app-logo">
                        <h3>SIJAFUNG</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://jafung.bps.go.id/main" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 6 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/bos.png" alt="Aplikasi 6" class="app-logo">
                        <h3>BOS</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://backoffice.bps.go.id/" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- App 7 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/bps.png" alt="Aplikasi 7" class="app-logo">
                        <h3>TK Online</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="http://tkonline.bps.go.id/" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 8 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/warkop.png" alt="Aplikasi 8" class="app-logo">
                        <h3>Warkop BPS</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://lms.bps.go.id/login/index.php" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 9 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/gojas.png" alt="Aplikasi 9" class="app-logo">
                        <h3>GOJAGS</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://gojags.web.bps.go.id/" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- App 10 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/couns.png" alt="Aplikasi 10" class="app-logo">
                        <h3>Counseling Centerr BPS</h3>
                        <p>Deskripsi singkat tentang aplikasi ini dan fungsinya.</p>
                        <a href="https://counseling.web.bps.go.id/" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 11 -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/bps.png" alt="Portal SDM1800" class="app-logo">
                        <h3>Portal SDM1800</h3>
                        <p>Portal layanan SDM BPS Provinsi Lampung.</p>
                        <a href="https://sites.google.com/view/layanansdm1800" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                
                <!-- App 12 (placeholder) -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/placeholder.png" alt="Aplikasi 12" class="app-logo">
                        <h3>Parabola</h3>
                        <p>Deskripsi singkat aplikasi ke-12.</p>
                        <a href="#" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- App 13 (placeholder) -->
                <div class="col-md-4 col-sm-6">
                    <div class="app-card">
                        <img src="img/lyn/placeholder.png" alt="Aplikasi 13" class="app-logo">
                        <h3>sivitas</h3>
                        <p>Deskripsi singkat aplikasi ke-13.</p>
                        <a href="#" class="btn-app">Buka Aplikasi</a>
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
            window.location.href = 'index.php';
        });
    });
</script>
</body>
</html>