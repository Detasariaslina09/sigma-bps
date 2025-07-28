<?php
// Mulai session
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect ke halaman login jika belum login atau bukan admin
    header("Location: login.php");
    exit;
}

// Include file koneksi database
require_once 'koneksi.php';

// Fungsi untuk memeriksa koneksi database dan melakukan reconnect jika terputus
function check_connection($conn)
{
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
// Asumsi $conn sudah ada dari require_once 'koneksi.php';
// Jika tidak, Anda perlu membuatnya di sini atau di koneksi.php
if (!isset($conn) || !$conn instanceof mysqli) {
    // Fallback jika $conn belum terdefinisi atau bukan objek mysqli
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

// Inisialisasi variabel untuk pesan
$success_msg = '';
$error_msg = '';
if (isset($_GET['success'])) {
    $success_msg = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error_msg = $_GET['error'];
}

// Handle form tambah user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        $error_msg = "Username, password, dan role harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Password dan konfirmasi password tidak cocok.";
    } else {
        try {
            // Pastikan koneksi aktif
            $conn = check_connection($conn);

            // Cek apakah username sudah ada
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_msg = "Username sudah digunakan. Silakan pilih username lain.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user baru (hanya username, password, role)
                // created_at akan otomatis diisi oleh MySQL jika kolom didefinisikan dengan DEFAULT CURRENT_TIMESTAMP
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hashed_password, $role);

                if ($stmt->execute()) {
                    $success_msg = "User baru berhasil ditambahkan.";
                } else {
                    $error_msg = "Gagal menambahkan user: " . $conn->error;
                }
            }
            $stmt->close(); // Tutup statement setelah selesai
        } catch (Exception $e) {
            $error_msg = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

// Handle hapus user
// Handle hapus user via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_delete']) && $_POST['ajax_delete'] == 1) {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    if ($user_id <= 0) {
        echo "User ID tidak valid.";
        exit;
    }
    if ($user_id == $_SESSION['user_id']) {
        echo "Anda tidak dapat menghapus akun yang sedang Anda gunakan.";
        exit;
    }
    try {
        $conn = check_connection($conn);
        $stmt = $conn->prepare("SELECT id, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo "User tidak ditemukan.";
            $stmt->close();
            exit;
        } else {
            $user_data = $result->fetch_assoc();
            if ($user_data['role'] === 'admin') {
                $admin_count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'admin' AND id != ?");
                $admin_count_stmt->bind_param("i", $user_id);
                $admin_count_stmt->execute();
                $admin_count_result = $admin_count_stmt->get_result();
                $admin_count = $admin_count_result->fetch_assoc()['total'];
                if ($admin_count < 1) {
                    echo "Tidak dapat menghapus admin terakhir. Minimal harus ada satu admin.";
                    $stmt->close();
                    $admin_count_stmt->close();
                    exit;
                } else {
                    $stmt->close();
                    $admin_count_stmt->close();
                    $del_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                    $del_stmt->bind_param("i", $user_id);
                    if ($del_stmt->execute()) {
                        echo "User berhasil dihapus.";
                        $del_stmt->close();
                        exit;
                    } else {
                        echo "Gagal menghapus user: " . $conn->error;
                        $del_stmt->close();
                        exit;
                    }
                }
            } else {
                $stmt->close();
                $del_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $del_stmt->bind_param("i", $user_id);
                if ($del_stmt->execute()) {
                    echo "User berhasil dihapus.";
                    $del_stmt->close();
                    exit;
                } else {
                    echo "Gagal menghapus user: " . $conn->error;
                    $del_stmt->close();
                    exit;
                }
            }
        }
    } catch (Exception $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
        exit;
    }
// ...existing code...
// Penutup blok hapus user via AJAX sudah ada di atas, hapus kurung tutup yang tidak perlu
}

// Ambil semua data user untuk ditampilkan
$users = [];
try {
    // Pastikan koneksi aktif
    $conn = check_connection($conn);

    // Query untuk mengambil data users sesuai struktur database
    $result = $conn->query("SELECT id, username, role, created_at FROM users ORDER BY id ASC");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
} catch (Exception $e) {
    $error_msg = "Gagal mengambil data user: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Manajemen User - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Halaman Manajemen User - BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="css/jcarousel.css" rel="stylesheet" />
    <link href="css/flexslider.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/sidebar.css" rel="stylesheet" />
    <style>
        .admin-content {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .admin-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .admin-header h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-top: 0;
        }

        .admin-header p {
            color: #777;
            margin-bottom: 0;
        }

        .btn-add-user {
            background: #ff9800;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-add-user:hover {
            background: #e65100;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.15);
        }

        .user-table th,
        .user-table td {
            vertical-align: middle;
        }

        .action-buttons .btn {
            padding: 5px 10px;
            margin-right: 5px;
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: none;
            padding: 20px;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            border-top: none;
            padding: 0 30px 30px;
        }

        .label-primary {
            background-color: #ff9800;
        }

        .btn-primary {
            background-color: #ff9800;
            border-color: #ff9800;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #e65100;
            border-color: #e65100;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
</head>

<body>
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>

    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="sigma" /></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="harmoni.php">Harmoni</a></li>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="admin-menu active"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
            <?php endif; ?>

            <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        </ul>
    </div>

    <div id="wrapper">
        <section id="content">
            <div class="container">
                <div class="admin-content">
                    <div class="admin-header clearfix">
                        <div class="row">
                            <div class="col-md-6">
                                <h2><i class="fa fa-users"></i> Manajemen User</h2>
                                <p>Kelola data pengguna sistem</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-add-user" data-toggle="modal" data-target="#addUserModal"><i class="fa fa-plus"></i> Tambah User</button>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($success_msg)): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fa fa-check-circle"></i> <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fa fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped user-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Username</th>
                                    <th width="15%">Role</th>
                                    <th width="30%">Tanggal Dibuat</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data user</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $i = 1;
                                    foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td>
                                                <?php if ($user['role'] == 'admin'): ?>
                                                    <span class="label label-primary">Admin</span>
                                                <?php else: ?>
                                                    <span class="label label-default">User</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?></td>
                                            <td class="action-buttons">
                                                <?php if ($user['id'] != $_SESSION['user_id']): // Jangan tampilkan tombol hapus untuk user yang sedang login ?>
                                                    <?php if (isset($user['id']) && isset($user['username'])): ?>
                                                    <form method="post" action="admin-users.php" class="delete-user-form" style="display:inline;">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm delete-user-btn" data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                            <i class="fa fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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

    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tambah User</h4>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="post" action="admin-users.php">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSaveUser">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
        <!-- Modal hapus user dihapus, digantikan alert JS dan AJAX -->
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
    <script src="js/sidebar.js"></script>
    <script>
        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Handle delete user button click (alert + AJAX)
            $('.delete-user-btn').on('click', function(e) {
                var username = $(this).data('username');
                if (!window.confirm('⚠️ Konfirmasi Hapus\n\nApakah Anda yakin ingin menghapus user "' + username + '"?\nData yang dihapus tidak dapat dikembalikan!')) {
                    e.preventDefault();
                }
            });

            // Validasi form tambah user
            $('#addUserForm').on('submit', function(e) {
                var password = $('#password').val();
                var confirmPassword = $('#confirm_password').val();

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok!');
                }
            });
        });
    </script>
</body>

</html>