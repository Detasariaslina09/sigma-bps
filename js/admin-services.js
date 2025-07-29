$(document).ready(function() {
    // Simpan informasi login di sessionStorage
    sessionStorage.setItem('isLoggedIn', 'true');
    sessionStorage.setItem('username', $('meta[name="username"]').attr('content') || '');
    sessionStorage.setItem('userRole', $('meta[name="role"]').attr('content') || '');
    
    // Otomatis sembunyikan pesan alert setelah 3 detik
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
