$(document).ready(function() {
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Handle delete user button click with AJAX (using event delegation for dynamic content)
    $(document).on('click', '.delete-user-btn', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var $form = $btn.closest('form');
        var username = $btn.data('username');
        
        // Try to get user ID from multiple sources
        var userId = $form.find('input[name="user_id"]').val() || $btn.data('user-id') || $btn.attr('data-user-id');
        
        // Debug logging
        console.log('=== DELETE USER DEBUG ===');
        console.log('Button clicked:', $btn);
        console.log('Form found:', $form.length, 'forms');
        console.log('Username from data attribute:', username);
        console.log('User ID from hidden input:', $form.find('input[name="user_id"]').val());
        console.log('User ID from data-user-id:', $btn.data('user-id'));
        console.log('User ID final value:', userId);
        console.log('Form HTML:', $form.html());
        console.log('Button HTML:', $btn[0].outerHTML);
        console.log('========================');
        
        // Tambahan debug dengan alert untuk memastikan kita bisa lihat
        alert('DEBUG INFO:\nUsername: ' + username + '\nUser ID: ' + userId + '\nForm found: ' + $form.length);
        
        // Validate data
        if (!userId || userId === '' || userId === 'undefined') {
            console.error('ERROR: User ID is empty or undefined!');
            showAlert('danger', 'Error: ID user tidak ditemukan! User ID: ' + userId);
            return;
        }
        
        if (!username || username === '') {
            console.error('ERROR: Username is empty or undefined!');
            showAlert('danger', 'Error: Username tidak ditemukan!');
            return;
        }
        
        if (window.confirm('⚠️ Konfirmasi Hapus\n\nApakah Anda yakin ingin menghapus user "' + username + '" (ID: ' + userId + ')?\nData yang dihapus tidak dapat dikembalikan!')) {
            // Show loading state
            var originalHtml = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Menghapus...').prop('disabled', true);
            
            console.log('Sending AJAX request to delete user ID:', userId);
            
            // Send AJAX request
            $.ajax({
                url: 'admin-users.php',
                type: 'POST',
                data: {
                    ajax_delete: 1,
                    user_id: userId
                },
                beforeSend: function() {
                    console.log('AJAX request starting...');
                },
                success: function(response) {
                    console.log('Raw server response:', JSON.stringify(response));
                    console.log('Response length:', response.length);
                    console.log('Response trimmed:', JSON.stringify(response.trim()));
                    
                    // Debug alert untuk response
                    alert('Server Response: "' + response.trim() + '"');
                    
                    if (response.trim() === 'User berhasil dihapus.') {
                        console.log('Success! Removing row...');
                        // Remove the row from table
                        $btn.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                            // Update row numbers
                            updateRowNumbers();
                            // Show success message
                            showAlert('success', 'User berhasil dihapus.');
                        });
                    } else {
                        console.log('Delete failed. Showing error message...');
                        // Show error message
                        showAlert('danger', response.trim());
                        $btn.html(originalHtml).prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error Details:');
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Status Code:', xhr.status);
                    
                    alert('AJAX Error: ' + status + ' - ' + error);
                    showAlert('danger', 'Terjadi kesalahan saat menghapus user. Status: ' + status);
                    $btn.html(originalHtml).prop('disabled', false);
                },
                complete: function() {
                    console.log('AJAX request completed.');
                }
            });
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
        
        var alertHtml = '<div class="alert ' + alertClass + '" role="alert">' +
                       '<i class="fa ' + icon + '"></i> ' + message +
                       '</div>';
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of admin-content
        $('.admin-header').after(alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    }
});