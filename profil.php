<?php
session_start();
require_once 'koneksi.php';
require_once 'includes/database.php';

$conn = get_database_connection();

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
        $jabatan_lower_trimmed = trim(strtolower($row['jabatan']));

        if (strpos($jabatan_lower_trimmed, 'kepala bps kota bandar lampung') !== false) {
            $kepala = $row;
        } else if (strpos($jabatan_lower_trimmed, 'kepala subbagian umum') !== false || 
                   strpos($jabatan_lower_trimmed, 'kasubbag umum') !== false) {
            $kasubbag = $row;
        } else {
            $staff[] = $row;
        }
    }
}


if (!$kepala) {
    $kepala = [
        'id' => 0,
        'nama' => 'Dr. Hady Suryono M.Si.',
        'jabatan' => 'Kepala BPS Kota Bandar Lampung',
        'foto' => 'kepala.jpg',
        'link' => ''
    ];
}

if (!$kasubbag) {
    $kasubbag = [
        'id' => 0,
        'nama' => 'Gun Gun Nugraha S.Si, M.S.E',
        'jabatan' => 'Kepala Subbagian Umum',
        'foto' => 'kasubbag.jpg',
        'link' => ''
    ];
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
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/org-chart.css" rel="stylesheet" />
    <style>
        /* Custom styling untuk halaman profil */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        #wrapper {
            background: transparent;
        }
        
        #content {
            padding-top: 20px;
            background: transparent;
        }
        
        .container {
            padding-top: 10px;
        }
        .section-title {
            margin-bottom: 0;
            margin-top: 0;
        }
        .section-title h2 {
            margin-bottom: 0;
            padding-bottom: 0;
            color: #1a3c6e;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-title h2:after {
            display: none;
        }
        .section-title p {
            margin-bottom: 5px;
        }
        .org-chart {
            margin-top: 0;
        }
        .org-chart-head {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .org-chart .row:first-child {
            margin-bottom: 5px;
        }
        .org-chart .row:nth-child(2) {
            margin-bottom: 5px;
        }
        
        /* Enhanced styling untuk kotak pegawai */
        .org-chart-box, .staff-box {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.8);
        }
        
        .org-chart-box:hover, .staff-box:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
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
                    <div class="col-md-12 text-center">
                        <div class="section-title">
                            <h2>Profil dan Roadmap</h2>
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
                
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="section-actions text-center" style="margin-top: 40px;">
                            <a href="#" class="download-btn">
                                <i class="fa fa-download"></i> Download Publikasi Peta SDM
                            </a>
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
    <script src="js/animate.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/org-chart.js"></script>
</body>
</html>