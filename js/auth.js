// Auth Class untuk mengelola autentikasi
class Auth {
    constructor() {
        this.publicPages = ['index.php', 'login.php', 'profil.php', 'services.php'];
        this.init();
    }

    init() {
        this.checkLogin();
        this.setupEventListeners();
    }

    // Cek status login
    checkLogin() {
        const isLoggedIn = this.isLoggedIn();
        const currentPage = this.getCurrentPage();
        
        if (this.isPublicPage(currentPage)) {
            this.showHideAdminMenu();
            return;
        }

        if (!isLoggedIn) {
            window.location.href = 'login.php';
            return;
        }
        
        this.showHideAdminMenu();
    }

    // Mendapatkan halaman saat ini
    getCurrentPage() {
        return window.location.pathname.split('/').pop() || 'index.php';
    }

    // Cek apakah halaman publik
    isPublicPage(page) {
        return this.publicPages.includes(page.toLowerCase());
    }

    // Cek status login dari sessionStorage
    isLoggedIn() {
        return sessionStorage.getItem('isLoggedIn') === 'true';
    }

    // Cek apakah user adalah admin
    isAdmin() {
        return sessionStorage.getItem('userRole') === 'admin';
    }

    // Menampilkan/menyembunyikan menu admin
    showHideAdminMenu() {
        if (this.isAdmin()) {
            $('.admin-menu').show();
        } else {
            $('.admin-menu').hide();
        }
    }

    // Setup event listeners
    setupEventListeners() {
        // Handle logout
        $('.logout-link').on('click', (e) => {
            e.preventDefault();
            this.logout();
        });
    }

    // Proses logout
    logout() {
        // Hapus semua data session
        sessionStorage.removeItem('isLoggedIn');
        sessionStorage.removeItem('username');
        sessionStorage.removeItem('userRole');
        
        // Redirect ke halaman utama
        window.location.href = 'index.php';
    }

    // Set user session setelah login berhasil
    static setUserSession(username, role) {
        sessionStorage.setItem('isLoggedIn', 'true');
        sessionStorage.setItem('username', username);
        sessionStorage.setItem('userRole', role);
    }
}

// Inisialisasi auth saat dokumen ready
$(document).ready(() => {
    // Sembunyikan menu admin secara default
    $('.admin-menu').hide();
    
    // Inisialisasi Auth
    const auth = new Auth();
});
