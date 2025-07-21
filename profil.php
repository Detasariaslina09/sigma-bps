<?php
session_start();
require_once 'koneksi.php';

// Periksa status login - jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fungsi untuk memeriksa koneksi database dan melakukan reconnect jika terputus
function check_connection($conn) {
    if (!$conn->ping()) {
        // Reconnect jika koneksi terputus
        $conn->close();
        $servername = "127.0.0.1";
        $username   = "root";
        $password   = "";
        $dbname     = "sigap";
        $port       = 3306;
        
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

// Pastikan koneksi aktif
$conn = check_connection($conn);

// Ambil data profil dari database
$profiles = [];
$kepala = null;
$kasubbag = null;
$staff = [];

$sql = "SELECT * FROM profil ORDER BY id ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Pisahkan data berdasarkan jabatan
        if (strpos(strtolower($row['jabatan']), 'kepala bps') !== false) {
            $kepala = $row;
        } else if (strpos(strtolower($row['jabatan']), 'kepala sub bagian') !== false) {
            $kasubbag = $row;
        } else {
            $staff[] = $row;
        }
    }
}

// Jika tidak ada data kepala atau kasubbag, gunakan data default
if (!$kepala) {
    $kepala = [
        'nama' => 'Dr. Suhariyanto, M.Si.',
        'jabatan' => 'Kepala BPS Kota Bandar Lampung',
        'foto' => 'kepala.jpg',
        'link' => ''
    ];
}

if (!$kasubbag) {
    $kasubbag = [
        'nama' => 'Dra. Maryam Hayati, M.M.',
        'jabatan' => 'Kepala Sub Bagian Tata Usaha',
        'foto' => 'kasubbag.jpg',
        'link' => ''
    ];
}

// Tutup koneksi database
$conn->close();

// Cek status login
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';

// Set default full_name jika tidak ada dalam session
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Profil dan Roadmap - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Profil dan Roadmap BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="css/jcarousel.css" rel="stylesheet" />
    <link href="css/flexslider.css" rel="stylesheet" />
    <link href="js/owl-carousel/owl.carousel.css" rel="stylesheet"> 
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    
    <style>
        /* Styling untuk struktur organisasi */
        .org-chart {
            margin: 30px auto;
            max-width: 1000px;
        }
        
        .org-chart-box {
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #fff;
            border-left: 5px solid #1a3c6e;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .org-chart-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .org-chart-box h4 {
            margin: 10px 0 5px;
            color: #1a3c6e;
            font-weight: 600;
            font-size: 16px;
        }
        
        .org-chart-box p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .org-chart-head {
            background-color: #1a3c6e;
            color: white;
            border-left: 5px solid #ff9800;
            width: 220px;
            margin: 0 auto 30px;
        }
        
        .org-chart-head h4, .org-chart-head p {
            color: white;
        }
        
        .org-chart-subhead {
            background-color: #2c5aa0;
            color: white;
            border-left: 5px solid #ff9800;
            width: 200px;
            margin-left: auto;
            margin-right: 100px;
        }
        
        .org-chart-subhead h4, .org-chart-subhead p {
            color: white;
        }
        
        .org-chart-staff {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        
        .staff-box {
            flex: 0 0 calc(16.666% - 15px);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            background-color: #f9f9f9;
            border-left: 3px solid #2c5aa0;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 15px;
        }
        
        .staff-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.15);
            background-color: #f0f7ff;
        }
        
        .staff-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
            overflow: hidden;
            border: 3px solid #1a3c6e;
        }
        
        .leader-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 5px;
            overflow: hidden;
            border: 3px solid #ff9800;
        }
        
        .staff-photo img, .leader-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .staff-box h4 {
            font-size: 14px;
            margin: 0;
            color: #1a3c6e;
        }
        
        .staff-box p {
            font-size: 12px;
            margin: 5px 0 0;
            color: #666;
        }
        
        .download-btn {
            display: inline-block;
            background-color: #1a3c6e;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            border: 2px solid #1a3c6e;
        }
        
        .download-btn:hover {
            background-color: #ff9800;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-color: #ff9800;
        }
        
        .download-btn i {
            margin-right: 8px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .section-title h2 {
            font-size: 32px;
            font-weight: 600;
            position: relative;
            margin-bottom: 20px;
            padding-bottom: 20px;
            text-transform: uppercase;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            display: block;
            width: 60px;
            height: 4px;
            margin: 20px auto 0;
            background: #ff9800;
            border-radius: 2px;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .section-actions {
            text-align: center;
            margin-bottom: 30px;
        }
        
        /* Modal untuk profil pegawai */
        .modal-profile {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
        }
        
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: modalopen 0.4s;
        }
        
        .modal-header {
            padding: 15px;
            background-color: #1a3c6e;
            color: white;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        
        .modal-body {
            padding: 0;
            height: 80vh;
        }
        
        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .close-modal {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close-modal:hover {
            color: #ff9800;
        }
        
        @keyframes modalopen {
            from {opacity: 0; transform: translateY(-50px);}
            to {opacity: 1; transform: translateY(0);}
        }
        
        @media (max-width: 992px) {
            .staff-box {
                flex: 0 0 calc(33.333% - 15px);
            }
            
            .org-chart-subhead {
                margin-right: 50px;
            }
        }
        
        @media (max-width: 768px) {
            .staff-box {
                flex: 0 0 calc(50% - 15px);
            }
            
            .org-chart-subhead {
                margin-right: auto;
                margin-left: auto;
            }
        }
        
        @media (max-width: 480px) {
            .staff-box {
                flex: 0 0 100%;
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
            <li class="active"><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="contact.php">Pengaduan</a></li>
            
            <?php if ($is_admin): ?>
                <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
                <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-id-card"></i> Manajemen Profil</a></li>
                <?php endif; ?>
            
            <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        </ul>
    </div>

    <div id="wrapper">
        <section id="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>Profil dan Roadmap</h2>
                            <p>Struktur Kepegawaian BPS Kota Bandar Lampung</p>
                        </div>
                        <div class="section-actions">
                            <a href="#" class="download-btn">
                                <i class="fa fa-download"></i> Download Publikasi Peta SDM
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="org-chart">
                    <!-- Kepala BPS -->
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="org-chart-box org-chart-head">
                                <div class="leader-photo">
                                    <img src="img/staff/<?php echo htmlspecialchars($kepala['foto']); ?>" alt="Kepala BPS">
                                </div>
                                <h4><?php echo htmlspecialchars($kepala['jabatan']); ?></h4>
                                <p><?php echo htmlspecialchars($kepala['nama']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kepala Sub Bagian -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <div class="org-chart-box org-chart-subhead">
                                <div class="leader-photo">
                                    <img src="img/staff/<?php echo htmlspecialchars($kasubbag['foto']); ?>" alt="Kepala Sub Bagian">
                                </div>
                                <h4><?php echo htmlspecialchars($kasubbag['jabatan']); ?></h4>
                                <p><?php echo htmlspecialchars($kasubbag['nama']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Staff -->
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center">Pegawai</h3>
                            <div class="org-chart-staff">
                                <?php if (empty($staff)): ?>
                                <div class="col-md-12 text-center">
                                    <p>Belum ada data pegawai.</p>
                                </div>
                                <?php else: ?>
                                    <?php foreach ($staff as $profile): ?>
                                    <div class="staff-box" data-profile="<?php echo !empty($profile['link']) ? htmlspecialchars($profile['link']) : '#'; ?>">
                                        <div class="staff-photo">
                                            <img src="img/staff/<?php echo htmlspecialchars($profile['foto']); ?>" alt="<?php echo htmlspecialchars($profile['nama']); ?>">
                                        </div>
                                        <h4><?php echo htmlspecialchars($profile['nama']); ?></h4>
                                        <p><?php echo htmlspecialchars($profile['jabatan']); ?></p>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal untuk profil pegawai -->
                    <div id="profileModal" class="modal-profile">
                        <div class="modal-content">
                            <div class="modal-header">
                                <span class="close-modal">&times;</span>
                                <h2 id="modalTitle">Profil Pegawai</h2>
                            </div>
                            <div class="modal-body">
                                <iframe id="canvaFrame" src="" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Download Button di bawah (dihapus) -->
                    <!-- <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="#" class="download-btn">
                                <i class="fa fa-download"></i> Download Publikasi Peta SDM
                            </a>
                        </div>
                    </div> -->
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
    <script src="js/owl-carousel/owl.carousel.js"></script>

    <script>
    // Script untuk modal profil pegawai
    $(document).ready(function() {
        // Ambil elemen modal
        var modal = document.getElementById("profileModal");
        var modalTitle = document.getElementById("modalTitle");
        var canvaFrame = document.getElementById("canvaFrame");
        var closeBtn = document.getElementsByClassName("close-modal")[0];
        
        // Ketika kotak staff diklik
        $(".staff-box").click(function() {
            var name = $(this).find("h4").text();
            var profileUrl = $(this).data("profile");
            
            // Hanya tampilkan modal jika ada URL profil
            if (profileUrl && profileUrl !== '#') {
                // Set judul modal dan URL iframe
                modalTitle.innerText = "Profil " + name;
                canvaFrame.src = profileUrl;
                
                // Tampilkan modal
                modal.style.display = "block";
                
                // Nonaktifkan scroll pada body
                $("body").css("overflow", "hidden");
            }
        });
        
        // Tutup modal ketika tombol close diklik
        closeBtn.onclick = function() {
            modal.style.display = "none";
            canvaFrame.src = "";
            $("body").css("overflow", "auto");
        }
        
        // Tutup modal ketika klik di luar modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                canvaFrame.src = "";
                $("body").css("overflow", "auto");
            }
        }
    });
    </script>
</body>
</html> 