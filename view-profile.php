<?php
session_start();
require_once 'koneksi.php';

// Halaman ini adalah halaman publik, tidak perlu cek login
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Ambil ID profil dari parameter URL
$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fungsi untuk memeriksa koneksi database
function check_connection($conn) {
    if (!$conn->ping()) {
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

// Ambil data profil
$profile = null;

if ($profile_id > 0) {
    // Jika ID valid, ambil dari database
    $stmt = $conn->prepare("SELECT * FROM profil WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();
    }
    $stmt->close();
} else {
    // Jika ID adalah 0, cek parameter posisi untuk data default
    $position = isset($_GET['position']) ? $_GET['position'] : '';
    
    if ($position === 'kepala') {
        $profile = [
            'id' => 0,
            'nama' => 'Dr. Hady Suryono M.Si.',
            'jabatan' => 'Kepala BPS Kota Bandar Lampung',
            'foto' => 'kepala.jpg',
            'link' => ''
        ];
    } elseif ($position === 'kasubbag') {
        $profile = [
            'id' => 0,
            'nama' => 'Gun Gun Nugraha S.Si, M.S.E',
            'jabatan' => 'Kepala Subbagian Umum',
            'foto' => 'kasubbag.jpg',
            'link' => ''
        ];
    }
}

// Jika profil tidak ditemukan, redirect ke halaman profil
if (!$profile) {
    header("Location: profil.php");
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Profil <?php echo htmlspecialchars($profile['nama']); ?> - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Profil <?php echo htmlspecialchars($profile['nama']); ?> - BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .profile-header {
            background-color: #1a3c6e;
            color: white;
            padding: 15px 0;
            margin-bottom: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            height: 80px;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #1a3c6e;
            color: white;
            border-radius: 50%;
            border: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 101;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .back-button:hover {
            background-color: #2c5aa0;
            transform: scale(1.1);
        }

        .back-button i {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .back-button {
                top: 15px;
                left: 15px;
                width: 35px;
                height: 35px;
            }

            .back-button i {
                font-size: 18px;
            }
        }
        
        .profile-container {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            bottom: 0;
            background: #f9f9f9;
            z-index: 40;
            overflow: hidden;
        }

        .profile-frame {
            width: 100%;
            height: calc(100vh - 80px);
            border: none;
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 45;
            background: #fff;
        }

        .profile-unavailable {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
            background-color: #fff;
            z-index: 45;
        }

        .profile-unavailable h3 {
            color: #1a3c6e;
            font-size: 28px;
            margin: 0 0 10px 0;
            font-weight: 600;
        }

        .profile-unavailable p.jabatan {
            color: #2c5aa0;
            font-size: 18px;
            margin: 0 0 40px 0;
            font-weight: 500;
        }

        .profile-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
            padding-top: 40px;
            border-top: 1px solid rgba(44, 90, 160, 0.15);
        }

        .profile-status i {
            font-size: 24px;
            color: #2c5aa0;
        }

        .profile-status p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin: 0;
            opacity: 0.9;
        }

        .profile-status p.hint {
            color: #1a3c6e;
            font-style: italic;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .profile-header {
                height: 70px;
                padding: 10px 0;
            }

            .profile-container,
            .profile-frame,
            .profile-unavailable {
                top: 70px;
            }

            .profile-frame {
                height: calc(100vh - 70px);
            }

            .profile-unavailable h3 {
                font-size: 24px;
            }

            .profile-unavailable p.jabatan {
                font-size: 16px;
                margin-bottom: 30px;
            }

            .profile-status {
                padding-top: 30px;
                gap: 10px;
            }

            .profile-status i {
                font-size: 20px;
            }

            .profile-status p {
                font-size: 14px;
            }
        }

        /* Ensure no scrolling on body when frame is active */
        body.has-frame {
            overflow: hidden;
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            flex: 1;
        }
        
        .profile-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ff9800;
            flex-shrink: 0;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .profile-text {
            flex: 1;
            min-width: 0;
        }
        
        .profile-text h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .profile-text small {
            display: block;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-status {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(44, 90, 160, 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .profile-status i {
            font-size: 24px;
            color: #2c5aa0;
            margin-bottom: 5px;
        }

        .profile-status p {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
        }

        .admin-hint {
            margin-top: 15px;
            padding: 15px;
            background: rgba(44, 90, 160, 0.05);
            border-radius: 8px;
            width: 100%;
        }

        .admin-hint p {
            color: #2c5aa0;
            font-size: 14px;
            font-style: italic;
        }

        .profile-unavailable.staff .jabatan {
            color: #2c5aa0;
            font-size: 18px;
            font-weight: 500;
            margin: 0 0 5px 0;
        }

        .profile-unavailable.staff .profile-unavailable-card {
            padding: 40px;
        }

        @media (max-width: 768px) {
            .profile-status {
                margin-top: 20px;
                padding-top: 20px;
            }

            .profile-status i {
                font-size: 20px;
            }

            .profile-status p {
                font-size: 14px;
            }

            .admin-hint {
                padding: 12px;
            }

            .admin-hint p {
                font-size: 13px;
            }

            .profile-unavailable.staff .jabatan {
                font-size: 16px;
            }

            .profile-unavailable.staff .profile-unavailable-card {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-header">
        <div class="container">
            <div class="header-content">
                <a href="profil.php" class="back-button" title="Kembali ke Struktur Organisasi">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <div class="profile-info">
                    <div class="profile-photo">
                        <img src="img/staff/<?php echo htmlspecialchars($profile['foto']); ?>" 
                             alt="<?php echo htmlspecialchars($profile['nama']); ?>">
                    </div>
                    <div class="profile-text">
                        <h2><?php echo htmlspecialchars($profile['nama']); ?></h2>
                        <small><?php echo htmlspecialchars($profile['jabatan']); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="profile-container">
        <a href="javascript:history.back()" class="back-button">
            <i class="fa fa-arrow-left"></i>
        </a>
        <?php if (!empty($profile['link']) && filter_var($profile['link'], FILTER_VALIDATE_URL)): ?>
            <iframe class="profile-frame" 
                    src="<?php echo htmlspecialchars($profile['link']); ?>" 
                    allowfullscreen>
            </iframe>
        <?php else: ?>
            <div class="profile-unavailable">
                <h3><?php echo htmlspecialchars($profile['nama']); ?></h3>
                <p class="jabatan"><?php echo htmlspecialchars($profile['jabatan']); ?></p>
                <div class="profile-status">
                    <i class="fa fa-info-circle"></i>
                    <p>Profil pegawai ini belum memiliki konten yang dapat ditampilkan.</p>
                    <p class="hint">Silahkan hubungi admin untuk unggah profil.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <!-- javascript -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        // Add class to body when frame is active
        $(document).ready(function() {
            if ($('.profile-frame').length > 0) {
                $('body').addClass('has-frame');
            }
        });
    </script>
</body>
</html> 