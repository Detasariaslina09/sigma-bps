<?php
// Mulai session
session_start();

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
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/app-card.css" rel="stylesheet" />
</head>
<body>
    <?php include_once 'includes/sidebar.php'; ?>
<div id="wrapper">
    <section id="content">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Pusat SDM</h2>
                <p>Badan Pusat Statistik RI</p>
            </div>
            
            <div class="row">
                <?php
                // Load data aplikasi dari file konfigurasi
                $apps = require 'config/apps-data.php';
                
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
    <?php include_once 'includes/footer.php'; ?>
</div>

<a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/animate.js"></script>
<script src="js/custom.js"></script>
<script src="js/auth.js"></script>
</body>
</html>