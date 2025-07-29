// Pricing/Dokumentasi Page JavaScript
$(document).ready(function() {
    // Sembunyikan menu admin secara default
    $('.admin-menu').hide();
    
    // Cek login saat halaman dimuat
    checkLogin();
    
    // Handle logout button
    $('#logout-btn').on('click', function(e) {
        e.preventDefault();
        
        // Hapus session storage
        sessionStorage.removeItem('isLoggedIn');
        sessionStorage.removeItem('username');
        sessionStorage.removeItem('userRole');
        
        // Alihkan ke halaman utama
        window.location.href = 'index.php';
    });
    
    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function() {
        $('.sidebar').toggleClass('open');
    });
    
    // Smooth animation for category items
    $('.category-item').hover(
        function() {
            $(this).find('.btn-category').addClass('animated pulse');
        },
        function() {
            $(this).find('.btn-category').removeClass('animated pulse');
        }
    );
});

// Cek apakah sudah login
function checkLogin() {
    var isLoggedIn = sessionStorage.getItem('isLoggedIn');
    if (!isLoggedIn) {
        // Jika belum login, alihkan ke halaman utama
        window.location.href = 'index.php';
    } else {
        // Jika sudah login, cek role untuk menampilkan/sembunyikan menu admin
        showHideAdminMenu();
    }
}

// Fungsi untuk menampilkan/menyembunyikan menu admin berdasarkan role
function showHideAdminMenu() {
    var role = sessionStorage.getItem('userRole');
    if (role === 'admin') {
        // Tampilkan menu admin
        $('.admin-menu').show();
    } else {
        // Sembunyikan menu admin
        $('.admin-menu').hide();
    }
}
