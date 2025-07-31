$(document).ready(function() {
    console.log('Admin Users JS Initialized');
    
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Pastikan event handlers untuk delete user tetap berfungsi
    // dengan menambahkan namespace khusus (.adminUsers)
    $(document).off('click.adminUsers', '.delete-user-btn');
    $(document).on('click.adminUsers', '.delete-user-btn', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Mencegah event bubbling
        
        var $btn = $(this);
        var username = $btn.data('username');
        var userId = $btn.data('user-id');
        var $row = $btn.closest('tr');
        
        // Debug logging
        console.log('Delete button clicked!');
        console.log('Username:', username);
        console.log('User ID:', userId);
        console.log('Button data attributes:', $btn.data());
        
        // Validate data
        if (!userId || userId === '' || userId === 'undefined') {
            console.error('User ID validation failed:', userId);
            showAlert('danger', 'Error: ID user tidak ditemukan! User ID: ' + userId);
            return false;
        }
        
        if (!username || username === '') {
            console.error('Username validation failed:', username);
            showAlert('danger', 'Error: Username tidak ditemukan!');
            return false;
        }
        
        if (confirm('Apakah Anda yakin ingin menghapus user "' + username + '"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
            // Show loading state
            var originalHtml = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Menghapus...').prop('disabled', true);
            
            console.log('Sending AJAX request with data:', {
                ajax_delete: 1,
                user_id: userId
            });
            
            // Send AJAX request
            $.ajax({
                url: 'admin-users.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    ajax_delete: 1,
                    user_id: userId
                },
                success: function(response) {
                    console.log('AJAX Success:', response);
                    if (response && response.success) {
                        // Remove the table row with animation
                        $row.fadeOut('slow', function() {
                            $(this).remove();
                            updateRowNumbers();
                        });
                        
                        // Show success notification
                        showAlert('success', response.message);
                    } else {
                        showAlert('danger', response.message || 'Gagal menghapus user');
                        // Reset button
                        $btn.html(originalHtml).prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', error);
                    console.log('Response Text:', xhr.responseText);
                    
                    var errorMessage = 'Terjadi kesalahan saat menghapus user.';
                    
                    // Coba parse response jika ada
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response && response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.log('Error parsing response:', e);
                    }
                    
                    showAlert('danger', errorMessage);
                    
                    // Reset button
                    $btn.html(originalHtml).prop('disabled', false);
                }
            });
        }
        
        return false;
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

    // Function to update row numbers after deletion
    function updateRowNumbers() {
        $('.user-table tbody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Function to show alert messages
    function showAlert(type, message) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        console.log('Showing alert:', type, message);
        
        var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible alert-fixed" role="alert">' +
                       '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                       '<i class="fa ' + icon + '"></i> <strong>' + (type === 'success' ? 'Sukses!' : 'Error!') + '</strong> ' + message +
                       '</div>';
        
        // Remove existing alerts
        $('.alert-fixed').remove();
        
        // Add new alert at the top of the page
        $('body').prepend(alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            $('.alert-fixed').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
        
        // Scroll to top to show notification
        $('html, body').animate({
            scrollTop: 0
        }, 300);
    }
});