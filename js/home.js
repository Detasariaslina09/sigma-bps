// Handle session storage for login state
function initializeSession(username, userRole) {
    sessionStorage.setItem('isLoggedIn', 'true');
    sessionStorage.setItem('username', username);
    sessionStorage.setItem('userRole', userRole);
}

// Function to check login status
function checkLogin() {
    const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
    const publicPages = ['index.php', 'login.php', 'profil.php', 'services.php'];
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    
    if (publicPages.includes(currentPage.toLowerCase())) {
        return;
    }

    if (!isLoggedIn) {
        window.location.href = 'login.php';
        return;
    }
    showHideAdminMenu();
}

// Function to show/hide admin menu based on role
function showHideAdminMenu() {
    var role = sessionStorage.getItem('userRole');
    if (role === 'admin') {
        $('.admin-menu').show();
    } else {
        $('.admin-menu').hide();
    }
}

// Document ready function
$(document).ready(function() {
    // Hide admin menu by default
    $('.admin-menu').hide();
    
    // Check login status when page loads
    checkLogin();
    
    // Handle logout button
    $('.logout-link').on('click', function(e) {
        e.preventDefault();
        
        // Clear session storage
        sessionStorage.removeItem('isLoggedIn');
        sessionStorage.removeItem('username');
        sessionStorage.removeItem('userRole');
        
        // Redirect to logout page
        window.location.href = 'logout.php';
    });

    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function() {
        $('.sidebar').toggleClass('open');
    });
});
