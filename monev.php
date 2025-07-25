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
    .section-title {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        padding-bottom: 20px;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(to right, #ff9800, #e65100);
        border-radius: 2px;
    }

    .section-title h2 {
        color: #1a3c6e;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .section-title p {
        color: #666;
        font-size: 16px;
    }

    .monev-container {
        margin-top: 30px;
        position: relative;
    }

    .monev-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 200px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    .monev-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        border-color: rgba(255, 152, 0, 0.3);
    }
    
    .monev-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, 
            rgba(0,0,0,0), 
            rgba(0,0,0,0.2) 50%,
            rgba(0,0,0,0.7));
        z-index: 1;
        transition: all 0.3s ease;
    }

    .monev-card:hover::before {
        background: linear-gradient(to bottom, 
            rgba(0,0,0,0), 
            rgba(0,0,0,0.3) 50%,
            rgba(0,0,0,0.8));
    }
    
    .monev-content {
        padding: 15px;
        text-align: center;
        position: relative;
        z-index: 2;
        background: transparent;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .monev-card:hover .monev-content {
        padding-bottom: 20px;
    }
    
    .monev-content h3 {
        color: #fff;
        font-size: 18px;
        margin: 0;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        transition: all 0.3s ease;
    }

    .monev-card:hover .monev-content h3 {
        transform: translateY(-2px);
    }
    
    .monev-content p {
        color: #fff;
        font-size: 13px;
        margin-bottom: 15px;
        min-height: 40px;
        opacity: 0.95;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    .btn-monev {
        background: #ff9800;
        color: white !important;
        padding: 6px 25px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 5px;
        display: inline-block;
        position: relative;
        z-index: 2;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-monev i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }
    
    .btn-monev:hover {
        background: #e65100;
        color: white !important;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230,81,0,0.3);
    }

    .btn-monev:hover i {
        transform: translateX(3px);
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

    @media (max-width: 768px) {
        .section-title h2 {
            font-size: 28px;
        }

        .section-title p {
            font-size: 14px;
        }

        .monev-card {
            height: 180px;
        }

        .monev-content {
            padding: 15px 12px;
        }

        .monev-content h3 {
            font-size: 16px;
        }

        .monev-content p {
            font-size: 12px;
            margin-bottom: 12px;
            min-height: 35px;
        }

        .btn-monev {
            padding: 5px 20px;
            font-size: 12px;
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
            <li class="active"><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
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
                    <div class="monev-card" style="background-image: url('img/Monev/umum.png');">
                        <div class="monev-content">
                            <h3>Tim Umum</h3>
                            <a href="monev/monev-umum.php" class="btn-monev">Lihat</a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Sosial -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card" style="background-image: url('img/Monev/sosial.png');">
                        <div class="monev-content">
                            <h3>Tim Sosial</h3>
                            <a href="monev/monev-sosial.php" class="btn-monev">Lihat</a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Produksi -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card" style="background-image: url('img/Monev/produksi.png');">
                        <div class="monev-content">
                            <h3>Tim Produksi</h3>
                            <a href="monev/monev-produksi.php" class="btn-monev">Lihat</a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Distribusi -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card" style="background-image: url('img/Monev/distri.png');">
                        <div class="monev-content">
                            <h3>Tim Distribusi</h3>
                            <a href="monev/monev-distribusi.php" class="btn-monev">Lihat</a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim Nerwilis -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card" style="background-image: url('img/Monev/nerwilis.png');">
                        <div class="monev-content">
                            <h3>Tim Nerwilis</h3>
                            <a href="monev/monev-nerwilis.php" class="btn-monev">Lihat</a>
                        </div>
                    </div>
                </div>
                
                <!-- Tim PTID -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card" style="background-image: url('img/Monev/ptid.png');">
                        <div class="monev-content">
                            <h3>Tim PTID</h3>
                            <a href="monev/monev-ptid.php" class="btn-monev">Lihat</a>
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