<?php
session_start();
require_once 'koneksi.php'; // Pastikan file koneksi.php sudah benar dan berfungsi

// Halaman ini adalah halaman publik, tidak perlu cek login
// $is_logged_in = isset($_SESSION['user_id']);
// $is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Fungsi untuk memeriksa koneksi database dan melakukan reconnect jika terputus
function check_connection($conn) {
    // Pastikan $conn adalah objek mysqli sebelum memanggil ping()
    if ($conn instanceof mysqli && !$conn->ping()) {
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
// Variabel $conn seharusnya sudah tersedia dari require_once 'koneksi.php';
// Jika koneksi.php hanya mendefinisikan variabel tanpa membuat koneksi,
// Anda perlu membuatnya di sini atau di koneksi.php
// Contoh asumsi $conn sudah ada dari koneksi.php:
if (!isset($conn) || !$conn instanceof mysqli) {
    // Jika koneksi belum dibuat atau bukan objek mysqli yang valid
    $servername = "127.0.0.1";
    $username   = "root";
    $password   = "";
    $dbname     = "sigap";
    $port       = 3306;
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Initial Connection failed: " . $conn->connect_error);
    }
}

$conn = check_connection($conn);

// Ambil data profil dari database
$profiles = [];
$kepala = null;
$kasubbag = null;
$staff = [];

$sql = "SELECT * FROM profil ORDER BY id ASC";
$result = $conn->query($sql);

if ($result) { // Pastikan query berhasil
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // --- Bagian Debugging Jabatan dari DB ---
            // echo "Processing DB row: ID=" . $row['id'] . ", Nama=" . htmlspecialchars($row['nama']) . ", Jabatan Asli DB='" . htmlspecialchars($row['jabatan']) . "'<br>";
            // --- Akhir Bagian Debugging Jabatan dari DB ---

            $jabatan_lower_trimmed = trim(strtolower($row['jabatan']));

            // Pisahkan data berdasarkan jabatan
            if (strpos($jabatan_lower_trimmed, 'kepala bps kota bandar lampung') !== false) {
                $kepala = $row;
                // echo "--> Masuk KEPALA<br>"; // Debugging
            } else if (strpos($jabatan_lower_trimmed, 'kepala subbagian umum') !== false || strpos($jabatan_lower_trimmed, 'kasubbag umum') !== false) {
                // Penambahan `trim()` dan opsi `kasubbag umum` untuk fleksibilitas
                $kasubbag = $row;
                // echo "--> Masuk KASUBBAG<br>"; // Debugging
            } else {
                $staff[] = $row;
                // echo "--> Masuk STAFF<br>"; // Debugging
            }
        }
    }
} else {
    // echo "Error dalam query: " . $conn->error . "<br>"; // Debugging jika query gagal
}


// Jika tidak ada data kepala atau kasubbag dari database, gunakan data default
if (!$kepala) {
    $kepala = [
        'id' => 0,
        'nama' => 'Dr. Hady Suryono M.Si.',
        'jabatan' => 'Kepala BPS Kota Bandar Lampung',
        'foto' => 'kepala.jpg',
        'link' => ''
    ];
    // echo "Menggunakan data KEPALA default.<br>"; // Debugging
}

if (!$kasubbag) {
    $kasubbag = [
        'id' => 0,
        'nama' => 'Gun Gun Nugraha S.Si, M.S.E',
        'jabatan' => 'Kepala Subbagian Umum',
        'foto' => 'kasubbag.jpg',
        'link' => ''
    ];
    // echo "Menggunakan data KASUBBAG default.<br>"; // Debugging
}

// Tutup koneksi database
if ($conn instanceof mysqli) {
    $conn->close();
}


// Cek status login
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Set default full_name jika tidak ada dalam session
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengguna';
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
            border-left: 2px solid #ff9800;
            width: calc(16.666% - 8px);
            min-width: 160px;
            max-width: 180px;
            margin: 0 auto 8px auto;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 8px;
            text-align: center;
        }
        
        .org-chart-head:hover {
            background-color: #234b87;
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .org-chart-head h4, .org-chart-head p {
            color: white;
        }
        
        .org-chart-subhead {
            background-color: #2c5aa0;
            color: white;
            border-left: 2px solid #ff9800;
            width: calc(16.666% - 8px);
            min-width: 160px;
            max-width: 180px;
            margin: 0 8px 8px 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 8px;
            text-align: center;
        }
        
        .org-chart-subhead:hover {
            background-color: #3468b5;
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .org-chart-subhead h4, .org-chart-subhead p {
            color: white;
        }
        
        .org-chart-staff {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
        
        .staff-box {
            flex: 0 0 calc(16.666% - 8px);
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            text-align: center;
            background-color: #f9f9f9;
            border-left: 2px solid #2c5aa0;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 8px;
        }
        
        .staff-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.12);
            background-color: #f0f7ff;
            border-left-color: #ff9800;
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
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 10px;
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
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/logo.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li class="active"><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            
            <?php if ($is_logged_in): ?>
                <li><a href="monev.php">Monev</a></li>
                <li><a href="about.php">Layanan</a></li>
                <li><a href="pricing.php">Dokumentasi</a></li>
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
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="org-chart-box org-chart-head" onclick="window.location.href='view-profile.php?<?php echo $kepala['id'] > 0 ? 'id=' . $kepala['id'] : 'position=kepala'; ?>'">
                                <div class="leader-photo">
                                    <img src="img/staff/<?php echo htmlspecialchars($kepala['foto']); ?>" alt="Kepala BPS Kota Bandar Lampung">
                                </div>
                                <h4><?php echo htmlspecialchars($kepala['jabatan']); ?></h4>
                                <p><?php echo htmlspecialchars($kepala['nama']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <div class="org-chart-box org-chart-subhead" onclick="window.location.href='view-profile.php?<?php echo $kasubbag['id'] > 0 ? 'id=' . $kasubbag['id'] : 'position=kasubbag'; ?>'">
                                <div class="leader-photo">
                                    <img src="img/staff/<?php echo htmlspecialchars($kasubbag['foto']); ?>" alt="Kepala Subbagian Umum">
                                </div>
                                <h4><?php echo htmlspecialchars($kasubbag['jabatan']); ?></h4>
                                <p><?php echo htmlspecialchars($kasubbag['nama']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="org-chart-staff">
                                <?php
                                if (empty($staff)) {
                                    echo '<div class="col-md-12 text-center"><p>Belum ada data pegawai.</p></div>';
                                } else {
                                    // 6 pertama sebagai ketua tim
                                    $ketua_tim = array_slice($staff, 0, 6);
                                    // Sisanya sebagai anggota, diurutkan abjad nama
                                    $anggota = array_slice($staff, 6);
                                    usort($anggota, function($a, $b) {
                                        return strcmp(strtolower($a['nama']), strtolower($b['nama']));
                                    });

                                    // Tampilkan 6 kotak pertama (ketua tim)
                                    echo '<div class="org-chart-staff" style="margin-bottom:30px;">';
                                    foreach ($ketua_tim as $profile) {
                                        echo '<div class="staff-box" onclick="window.location.href=\'view-profile.php?id=' . $profile['id'] . '\'">';
                                        echo '<div class="staff-photo"><img src="img/staff/' . htmlspecialchars($profile['foto']) . '" alt="' . htmlspecialchars($profile['nama']) . '"></div>';
                                        echo '<h4>' . htmlspecialchars($profile['nama']) . '</h4>';
                                        echo '<p>' . htmlspecialchars($profile['jabatan']) . '</p>';
                                        echo '</div>';
                                    }
                                    echo '</div>';

                                    // Tampilkan kotak anggota di bawahnya
                                    echo '<div class="org-chart-staff">';
                                    foreach ($anggota as $profile) {
                                        echo '<div class="staff-box" onclick="window.location.href=\'view-profile.php?id=' . $profile['id'] . '\'">';
                                        echo '<div class="staff-photo"><img src="img/staff/' . htmlspecialchars($profile['foto']) . '" alt="' . htmlspecialchars($profile['nama']) . '"></div>';
                                        echo '<h4>' . htmlspecialchars($profile['nama']) . '</h4>';
                                        echo '<p>' . htmlspecialchars($profile['jabatan']) . '</p>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                ?>
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
    // Tambahkan style cursor pointer ke semua box yang bisa diklik
    $(document).ready(function() {
        $('.org-chart-head, .org-chart-subhead, .staff-box').css('cursor', 'pointer');
    });
    </script>
</body>
</html>