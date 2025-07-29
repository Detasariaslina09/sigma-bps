$(document).ready(function() {
    // Mobile menu toggle
    $('.mobile-menu-toggle').click(function() {
        $('.sidebar').toggleClass('open');
    });
    
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Form validation enhancement
    $('#login-form').on('submit', function(e) {
        var username = $('#username').val().trim();
        var password = $('#password').val().trim();
        
        if (username === '' || password === '') {
            e.preventDefault();
            
            // Remove existing error styling
            $('.form-control').removeClass('error');
            
            // Add error styling to empty fields
            if (username === '') {
                $('#username').addClass('error');
            }
            if (password === '') {
                $('#password').addClass('error');
            }
            
            // Show error message if not already present
            if ($('.alert-danger').length === 0) {
                var errorHtml = '<div class="alert alert-danger" role="alert">' +
                               '<i class="fa fa-exclamation-circle"></i> Username dan password harus diisi' +
                               '</div>';
                $('.login-title').after(errorHtml);
                
                // Auto hide error after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
            }
        }
    });
    
    // Remove error styling when user starts typing
    $('.form-control').on('input', function() {
        $(this).removeClass('error');
    });
    
    // Add loading state to login button
    $('#login-form').on('submit', function() {
        var $btn = $('.btn-login');
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $btn.prop('disabled', true);
    });
});
