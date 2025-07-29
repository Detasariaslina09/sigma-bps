<?php
// Mulai session
session_start();

// Periksa status login - jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
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
<title>Monev Tim Statistik Sektoral - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Monitoring dan Evaluasi Tim Statistik Sektoral BPS Kota Bandar Lampung" />
<meta name="author" content="" />
<!-- css -->
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link href="../css/fancybox/jquery.fancybox.css" rel="stylesheet">
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
        transition: all 0.3s ease;
    }
    
    .service-category-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .service-category-content {
        padding: 20px;
        text-align: center;
    }
    
    .service-category-content h3 {
        color: #333;
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
    }
    
    .service-category-content p {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .service-category-content .btn {
        background: #ff9800;
        color: #fff;
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
        font-weight: 600;
    }
    
    .service-category-content .btn:hover {
        background: #e65100;
        color: #fff;
        text-decoration: none;
        transform: scale(1.05);
    }
    
    .service-icon {
        font-size: 48px;
        color: #ff9800;
        margin-bottom: 20px;
    }
    
    .monev-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 40px 0;
        margin-bottom: 40px;
        border-radius: 10px;
    }
    
    .monev-header h1 {
        color: #fff;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .monev-header p {
        color: rgba(255,255,255,0.9);
        font-size: 18px;
        margin-bottom: 0;
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
        <a class="navbar-brand" href="../index.php"><img src="../img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="../index.php">Beranda</a></li>
            <li><a href="../profil.php">Profil dan Roadmap</a></li>
            <li class="active"><a href="../monev.php">Monev</a></li>
            <li><a href="../about.php">Layanan</a></li>
            <li><a href="../services.php">Pusat Aplikasi</a></li>
            <li><a href="../pricing.php">Dokumentasi</a></li>
            <li><a href="../harmoni.php">Harmoni</a></li>
            
            <?php if ($is_admin): ?>
            <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
            <li class="admin-menu"><a href="../admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
            <li class="admin-menu"><a href="../admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
            <li class="admin-menu"><a href="../admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
            <li class="admin-menu"><a href="../admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
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
            <!-- Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="monev-header text-center">
                        <h1>Monitoring dan Evaluasi</h1>
                        <p>Tim Statistik Sektoral</p>
                    </div>
                </div>
            </div>
            
            <!-- Konten Tim Statistik Sektoral -->
            <div class="row service-category-container">
                <!-- Statistik Ekonomi -->
                <div class="col-md-6">
                    <div class="service-category-item">
                        <div class="service-category-content">
                            <div class="service-icon">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <h3>Statistik Ekonomi</h3>
                            <p>Monitoring dan evaluasi kegiatan statistik ekonomi, termasuk survei ekonomi, harga, dan perdagangan.</p>
                            <a href="#" class="btn">Detail Monev</a>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Pertanian -->
                <div class="col-md-6">
                    <div class="service-category-item">
                        <div class="service-category-content">
                            <div class="service-icon">
                                <i class="fa fa-leaf"></i>
                            </div>
                            <h3>Statistik Pertanian</h3>
                            <p>Monitoring dan evaluasi kegiatan statistik pertanian, hortikultura, dan perkebunan.</p>
                            <a href="#" class="btn">Detail Monev</a>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Industri -->
                <div class="col-md-6">
                    <div class="service-category-item">
                        <div class="service-category-content">
                            <div class="service-icon">
                                <i class="fa fa-industry"></i>
                            </div>
                            <h3>Statistik Industri</h3>
                            <p>Monitoring dan evaluasi kegiatan statistik industri pengolahan dan manufaktur.</p>
                            <a href="#" class="btn">Detail Monev</a>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Perdagangan -->
                <div class="col-md-6">
                    <div class="service-category-item">
                        <div class="service-category-content">
                            <div class="service-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <h3>Statistik Perdagangan</h3>
                            <p>Monitoring dan evaluasi kegiatan statistik perdagangan dalam dan luar negeri.</p>
                            <a href="#" class="btn">Detail Monev</a>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Transportasi -->
                <div class="col-md-6">
                    <div class="service-category-item">
                        <div class="service-category-content">
                            <div class="service-icon">
                                <i class="fa fa-truck"></i>
                            </div>
                            <h3>Statistik Transportasi</h3>
                            <p>Monitoring dan evaluasi kegiatan statistik transportasi dan komunikasi.</p>
                            <a href="#" class="btn">Detail Monev</a>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Pariwisata -->
                <div class="col-md-6">
                    <div class="service-category-item">
                        <div class="service-category-content">
                            <div class="service-icon">
                                <i class="fa fa-camera"></i>
                            </div>
                            <h3>Statistik Pariwisata</h3>
                            <p>Monitoring dan evaluasi kegiatan statistik pariwisata dan budaya.</p>
                            <a href="#" class="btn">Detail Monev</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Tambahan -->
            <div class="row" style="margin-top: 40px;">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Informasi Tim Statistik Sektoral</h4>
                        <p>Tim Statistik Sektoral bertanggung jawab untuk monitoring dan evaluasi berbagai kegiatan statistik sektoral yang mencakup ekonomi, pertanian, industri, perdagangan, transportasi, dan pariwisata. Tim ini memastikan kualitas data statistik sektor-sektor penting dalam perekonomian daerah.</p>
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
            window.location.href = '../index.php';
        });
        
        // Mobile menu toggle
        $('.mobile-menu-toggle').on('click', function() {
            $('.sidebar').toggleClass('open');
        });
        
        // Hover effects untuk service items
        $('.service-category-item').hover(
            function() {
                $(this).find('.service-icon i').addClass('animated bounce');
            },
            function() {
                $(this).find('.service-icon i').removeClass('animated bounce');
            }
        );
    });
    
    // Cek apakah sudah login
    function checkLogin() {
        var isLoggedIn = sessionStorage.getItem('isLoggedIn');
        if (!isLoggedIn) {
            // Jika belum login, alihkan ke halaman utama
            window.location.href = '../index.php';
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
</script>
</body>
</html>
