<?php
// Mulai session
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect ke halaman login jika belum login atau bukan admin
    header("Location: login.php");
    exit;
}

// Include file koneksi database dan functions
require_once 'koneksi.php';
require_once 'includes/admin-users-functions.php';

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

// Handle hapus user via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_delete']) && $_POST['ajax_delete'] == 1) {
    // Set content type untuk response
    header('Content-Type: text/plain; charset=utf-8');
    
    // Debug logging
    error_log("=== ADMIN USER DELETE DEBUG ===");
    error_log("POST data: " . print_r($_POST, true));
    error_log("Session user_id: " . $_SESSION['user_id']);
    
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    error_log("Received user_id: " . $user_id);
    
    if ($user_id <= 0) {
        error_log("ERROR: Invalid user ID");
        echo "User ID tidak valid.";
        exit;
    }
    if ($user_id == $_SESSION['user_id']) {
        error_log("ERROR: Attempting to delete own account");
        echo "Anda tidak dapat menghapus akun yang sedang Anda gunakan.";
        exit;
    }
    try {
        $conn = check_connection($conn);
        $stmt = $conn->prepare("SELECT id, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        error_log("User lookup query executed. Rows found: " . $result->num_rows);
        
        if ($result->num_rows === 0) {
            error_log("ERROR: User not found in database");
            echo "User tidak ditemukan.";
            $stmt->close();
            exit;
        } else {
            $user_data = $result->fetch_assoc();
            error_log("Found user data: " . print_r($user_data, true));
            
            if ($user_data['role'] === 'admin') {
                $admin_count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'admin' AND id != ?");
                $admin_count_stmt->bind_param("i", $user_id);
                $admin_count_stmt->execute();
                $admin_count_result = $admin_count_stmt->get_result();
                $admin_count = $admin_count_result->fetch_assoc()['total'];
                
                error_log("Admin count check: " . $admin_count);
                
                if ($admin_count < 1) {
                    error_log("ERROR: Cannot delete last admin");
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
                        error_log("SUCCESS: Admin user deleted successfully");
                        echo "User berhasil dihapus.";
                        $del_stmt->close();
                        exit;
                    } else {
                        error_log("ERROR: Failed to delete admin user: " . $conn->error);
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
                    error_log("SUCCESS: Regular user deleted successfully");
                    echo "User berhasil dihapus.";
                    $del_stmt->close();
                    exit;
                } else {
                    error_log("ERROR: Failed to delete regular user: " . $conn->error);
                    echo "Gagal menghapus user: " . $conn->error;
                    $del_stmt->close();
                    exit;
                }
            }
        }
    } catch (Exception $e) {
        error_log("EXCEPTION: " . $e->getMessage());
        echo "Terjadi kesalahan: " . $e->getMessage();
        exit;
    }
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
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/admin-users.css" rel="stylesheet" />
</head>

<body>
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>

    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <?php
            $is_logged_in = isset($_SESSION['user_id']);
            $is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
            if ($is_logged_in): ?>
                <li><a href="monev.php">Monev</a></li>
                <li><a href="layanan.php">Layanan</a></li>
                <li><a href="dokumentasi.php">Dokumentasi</a></li>
                <li><a href="harmoni.php">Harmoni</a></li>
                <?php if ($is_admin): ?>
                    <li class="admin-menu active"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                    <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                    <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                    <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
                <?php endif; ?>
                <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
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
                                                        <button type="submit" class="btn btn-danger btn-sm delete-user-btn" 
                                                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                                data-user-id="<?php echo $user['id']; ?>">
                                                            <i class="fa fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
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

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/admin-users.js"></script>
</body>

</html>