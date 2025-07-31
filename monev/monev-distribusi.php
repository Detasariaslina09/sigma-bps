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
<link href="../css/monev-styles.css" rel="stylesheet" />

</head>
<body>
<div id="wrapper" style="margin-left: 0; width: 100%;">
    <section id="content">
        <div class="container">
            <div class="row header-back">
                <div class="col-md-6">
                    <h2><i class="fa fa-truck"></i> Monitoring dan Evaluasi Tim Distribusi</h2>
                </div>
                <div class="col-md-6 text-right">
                    <a href="../monev.php" class="btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Tim</a>
                </div>
            </div>  
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Monitoring Statistik Distribusi 2025</h3>
                            <ul class="service-links-list">
                                <li><a href="http://s.bps.go.id/modis2025" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Webentry Survei Harga Konsumen</h3>
                            <ul class="service-links-list">
                                <li><a href="https://shp.web.bps.go.id/" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Webentry Survei Harga Produsen</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webentry.bps.go.id/shpb" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Webentry Survei Harga Perdagangan Besar</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webentry.bps.go.id/shpb" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Webentry Survei Harga Kemahalan Kontruksi</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webentry.bps.go.id/shkk" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include_once '../includes/footer.php'; ?>
</div>
<a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
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
<script src="../js/monev-scripts.js"></script>
</body>
</html> 