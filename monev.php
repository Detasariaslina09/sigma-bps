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
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/monev-card.css" rel="stylesheet" />
 
</head>
<body>
   <?php include_once 'includes/sidebar.php'; ?>
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
                
                <!-- Tim Statistik Sektoral -->
                <div class="col-md-4 col-sm-6">
                    <div class="monev-card" style="background-image: url('img/Monev/statistik.png');">
                        <div class="monev-content">
                            <h3>Tim Statistik Sektoral</h3>
                            <a href="monev/monev-sektoral.php" class="btn-monev">Lihat</a>
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
    <?php include_once 'includes/footer.php'; ?>
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
</body>
</html> 