<?php
session_start(); // Mulai session

// Include file koneksi yang telah diperbaiki
require_once 'koneksi.php';
$title = "Selamat Datang di BPS Kota Bandar Lampung"; // Default content jika database tidak tersedia
$description = "Silakan isi konten halaman utama website.";
$image = "konten.webp";

try {     // Cek apakah tabel konten ada
    $table_check = $conn->query("SHOW TABLES LIKE 'konten'");
    if ($table_check && $table_check->num_rows > 0) {
        $sql = "SELECT * FROM konten WHERE id = 1 LIMIT 1";         // Ambil data konten terbaru
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {             // Jika data ditemukan, gunakan data dari database
            $row = $result->fetch_assoc();
            $title = $row["title"];
            $description = $row["description"];
            $image = $row["image"];
        }
    }
} catch (Exception $e) {     // Jika terjadi error, gunakan data default
}
$image_path = "img/" . $image;
if (!file_exists($image_path)) {
    $image_path = "img/konten.webp"; // Gambar default jika gambar tidak ditemukan
}

// Tutup koneksi database
$conn->close();

// Cek status login
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';

// Set default full_name jika tidak ada dalam session
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = isset($_SESSION['username']) ? $_SESSION['username'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Website Resmi BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="css/jcarousel.css" rel="stylesheet" />
    <link href="css/flexslider.css" rel="stylesheet" />
    <link href="js/owl-carousel/owl.carousel.css" rel="stylesheet"> 
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/home-styles.css" rel="stylesheet" />
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSession('<?php echo addslashes($_SESSION['username']); ?>', '<?php echo addslashes($_SESSION['role']); ?>');
        });
    </script>
</head>
<body>
    <button class="mobile-menu-toggle">     
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <!-- Sidebar menu -->
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            
            <?php if ($is_logged_in): ?>
                <li><a href="monev.php">Monev</a></li>
                <li><a href="layanan.php">Layanan</a></li>
                <li><a href="dokumentasi.php">Dokumentasi</a></li>
                <li><a href="harmoni.php">Harmoni</a></li>
                
                <?php if ($is_admin): ?>
                    <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                    <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                    <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                    <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
                <?php endif; ?>
                
                <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="wrapper">
        <section id="featured">
            <div id="main-slider" class="flexslider">
                <ul class="slides">
                    <li>
                        <img src="img/kantorr.jpeg" alt="" />
                        <div class="flex-caption">
                            <h3>BPS Kota Bandar Lampung</h3> 
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <section>
            <div class="container text-center">
                <h3><?php echo $title; ?></h3>
                <p><?php echo $description; ?></p>
                <div class="row service-v1 margin-bottom-40">
                    <div class="col-md-4 md-margin-bottom-40 center-block" style="float: none; margin: 0 auto;">
                        <img class="img-responsive" src="<?php echo $image_path; ?>" alt="<?php echo $title; ?>">   
                    </div>
                </div>
            </div>
        </section>

        <section id="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="latest-post-wrap">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php include_once 'includes/footer.php'; ?>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
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
    <script src="js/owl-carousel/owl.carousel.js"></script>
</body>
</html> 