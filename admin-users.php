<?php
session_start(); // Mulai session

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");// Redirect ke halaman login jika belum login atau bukan admin
    exit;
}
require_once 'koneksi.php';
require_once 'includes/admin-users-functions.php'; // Include file koneksi database dan functions

// Handle AJAX delete request
if (isset($_POST['ajax_delete']) && $_POST['ajax_delete'] == 1) {
    header('Content-Type: application/json');
    
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    
    if ($user_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
        exit;
    }
    
    
    if ($user_id == $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Tidak bisa hapus akun sendiri']);
        exit;
    }
    
    // Simple delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND id != ?");
    $stmt->bind_param("ii", $user_id, $_SESSION['user_id']);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'User berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus user: ' . $conn->error]);
    }
    
    $stmt->close();
    exit;
}

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
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?"); // Cek apakah username sudah ada
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_msg = "Username sudah digunakan. Silakan pilih username lain.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user baru (hanya username, password, role)
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

$users = []; // Ambil semua data user untuk ditampilkan
try {
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
    <link href="css/admin-alerts.css" rel="stylesheet" />
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
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-check-circle"></i> <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                    <button type="button" class="btn btn-danger btn-sm delete-user-btn" 
                                                            data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                            data-user-id="<?php echo $user['id']; ?>">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
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

        <?php include_once 'includes/footer.php'; ?>
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
                            <label for="role">Role <span style="color: red;">*</span></label>
                            <select class="form-control" id="role" name="role" required style="background-color: white !important; color: #333 !important;">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            <small class="help-block" style="color: #666;">Pilih peran untuk user baru</small>
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
    <script src="js/admin-users.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>