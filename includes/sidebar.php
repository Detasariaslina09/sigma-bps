<?php
// includes/sidebar.php - File untuk sidebar yang digunakan di semua halaman

// Pastikan variabel yang dibutuhkan tersedia
if (!isset($is_logged_in)) {
    $is_logged_in = isset($_SESSION['user_id']);
}
if (!isset($is_admin)) {
    $is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
}

// Tentukan halaman aktif berdasarkan nama file yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar menu -->
<button class="mobile-menu-toggle">     
    <i class="fa fa-bars"></i> Menu
</button>

<div class="sidebar">
    <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
    <ul class="nav navbar-nav">
        <li<?php echo ($current_page == 'index.php') ? ' class="active"' : ''; ?>><a href="index.php">Beranda</a></li>
        <li<?php echo ($current_page == 'profil.php') ? ' class="active"' : ''; ?>><a href="profil.php">Profil dan Roadmap</a></li>
        <li<?php echo ($current_page == 'services.php') ? ' class="active"' : ''; ?>><a href="services.php">Pusat Aplikasi</a></li>
        
        <?php if ($is_logged_in): ?>
            <li<?php echo ($current_page == 'monev.php') ? ' class="active"' : ''; ?>><a href="monev.php">Monev</a></li>
            <li<?php echo ($current_page == 'layanan.php') ? ' class="active"' : ''; ?>><a href="layanan.php">Layanan</a></li>
            <li<?php echo ($current_page == 'dokumentasi.php') ? ' class="active"' : ''; ?>><a href="dokumentasi.php">Dokumentasi</a></li>
            <li<?php echo ($current_page == 'harmoni.php') ? ' class="active"' : ''; ?>><a href="harmoni.php">Harmoni</a></li>
            
            <?php if ($is_admin): ?>
                <li class="admin-menu<?php echo ($current_page == 'admin-users.php') ? ' active' : ''; ?>"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                <li class="admin-menu<?php echo ($current_page == 'admin-services.php') ? ' active' : ''; ?>"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                <li class="admin-menu<?php echo ($current_page == 'admin-content.php') ? ' active' : ''; ?>"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                <li class="admin-menu<?php echo ($current_page == 'admin-profil.php') ? ' active' : ''; ?>"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
            <?php endif; ?>
            
            <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        <?php else: ?>
            <li<?php echo ($current_page == 'login.php') ? ' class="active"' : ''; ?>><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
        <?php endif; ?>
    </ul>
</div>
