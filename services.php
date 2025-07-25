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
.app-card {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    text-align: center;
    margin-bottom: 25px;
    transition: all 0.3s ease;
    height: 220px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
}

.app-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.app-logo {
    width: 60px;
    height: 60px;
    object-fit: contain;
    margin: 0 auto 12px;
}

.app-card h3 {
    color: #333;
    font-size: 15px;
    margin: 0 0 8px;
    font-weight: 600;
    line-height: 1.3;
}

.app-card p {
    color: #666;
    font-size: 12px;
    margin: 0 0 12px;
    line-height: 1.4;
    flex-grow: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    max-width: 90%;
}

.app-card .btn-app {
    background: #ff9800;
    color: #fff !important;
    border: none;
    padding: 6px 15px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 12px;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
    margin-top: auto;
}

.app-card .btn-app:hover {
    background: #e65100;
    transform: translateY(-1px);
}

.section-title {
    margin-bottom: 40px;
}

.section-title h2 {
    color: #333;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 10px;
}

.section-title p {
    color: #666;
    font-size: 16px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -12px;
}

.col-md-3 {
    width: 25%;
    padding: 0 12px;
}

@media (max-width: 992px) {
    .col-md-3 {
        width: 33.333%;
    }
    .app-card {
        height: 200px;
    }
    .app-logo {
        width: 50px;
        height: 50px;
    }
}

@media (max-width: 768px) {
    .col-md-3 {
        width: 50%;
    }
    .section-title h2 {
        font-size: 24px;
    }
    .section-title p {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .col-md-3 {
        width: 100%;
    }
    .app-card {
        height: 180px;
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
            <li class="active"><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="harmoni.php">Harmoni</a></li>
        
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
                <h2>Layanan Pusat SDM</h2>
                <p>Badan Pusat Statistik RI</p>
            </div>
            
            <div class="row">
                <?php
                $apps = [
                    ['img' => 'bps.png', 'title' => 'SIMPEG', 'desc' => 'Sistem Informasi Kepegawaian BPS', 'url' => 'https://simpeg.bps.go.id/data/'],
                    ['img' => 'sipecut.png', 'title' => 'SIPECUT', 'desc' => 'Sistem Informasi Pelayanan Cuti', 'url' => 'https://sipecut.bps.go.id/app/index.php/pegawai'],
                    ['img' => 'KipApp.png', 'title' => 'KipApp', 'desc' => 'Aplikasi Kinerja Pegawai', 'url' => 'https://webapps.bps.go.id/kipapp/#/auth/login'],
                    ['img' => 'Siimut.png', 'title' => 'SiImut', 'desc' => 'Sistem Informasi Mutasi', 'url' => 'https://sdm.bps.go.id/siimut/web/'],
                    ['img' => 'bbps.png', 'title' => 'SIJAFUNG', 'desc' => 'Sistem Informasi Jabatan Fungsional', 'url' => 'https://jafung.bps.go.id/main'],
                    ['img' => 'bos.png', 'title' => 'BOS', 'desc' => 'Back Office System', 'url' => 'https://backoffice.bps.go.id/'],
                    ['img' => 'bbps.png', 'title' => 'TK Online', 'desc' => 'Sistem Tata Kelola Online', 'url' => 'http://tkonline.bps.go.id/'],
                    ['img' => 'warkop.png', 'title' => 'Warkop BPS', 'desc' => 'Learning Management System', 'url' => 'https://lms.bps.go.id/login/index.php'],
                    ['img' => 'gojas.png', 'title' => 'GOJAGS', 'desc' => 'Sistem Informasi Jabatan Fungsional', 'url' => 'https://gojags.web.bps.go.id/'],
                    ['img' => 'couns.png', 'title' => 'Counseling Center BPS', 'desc' => 'Layanan Konseling BPS', 'url' => 'https://counseling.web.bps.go.id/'],
                    ['img' => 'bbps.png', 'title' => 'Portal SDM1800', 'desc' => 'Portal Layanan SDM BPS Provinsi Lampung', 'url' => 'https://sites.google.com/view/layanansdm1800'],
                    ['img' => 'bbps.png', 'title' => 'Parabola', 'desc' => 'Sistem Informasi Parabola', 'url' => '#'],
                    ['img' => 'bbps.png', 'title' => 'Sivitas', 'desc' => 'Sistem Informasi Sivitas', 'url' => '#'],
                    ['img' => 'bbps.png', 'title' => 'Digital Signature', 'desc' => 'Layanan Tanda Tangan Digital BPS', 'url' => 'https://sign.bps.go.id/'],
                    ['img' => 'bbps.png', 'title' => 'SIADIN', 'desc' => 'Sistem Informasi Administrasi Internal', 'url' => 'https://siadin.bps.go.id/'],
                    ['img' => 'bbps.png', 'title' => 'Website BPS', 'desc' => 'Portal Resmi BPS', 'url' => 'https://www.bps.go.id/'],
                    ['img' => 'bbps.png', 'title' => 'PPID BPS', 'desc' => 'Pejabat Pengelola Informasi dan Dokumentasi', 'url' => 'https://ppid.bps.go.id/'],
                    ['img' => 'bbps.png', 'title' => 'BPS Kota Bandar Lampung', 'desc' => 'Website Resmi BPS Kota Bandar Lampung', 'url' => 'https://bandarlampungkota.bps.go.id/']
                ];

                foreach ($apps as $app):
                ?>
                <div class="col-md-3">
                    <div class="app-card">
                        <img src="img/lyn/<?php echo $app['img']; ?>" alt="<?php echo $app['title']; ?>" class="app-logo">
                        <h3><?php echo $app['title']; ?></h3>
                        <p><?php echo $app['desc']; ?></p>
                        <a href="<?php echo $app['url']; ?>" class="btn-app">Buka Aplikasi</a>
                    </div>
                </div>
                <?php endforeach; ?>
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