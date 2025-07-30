$(document).ready(function() {
    console.log('Simple delete script loaded'); // Debug log
    
    // Test if buttons exist
    console.log('Delete buttons found:', $('.delete-user-btn').length);
    
    // Simple delete handler
    $('.delete-user-btn').on('click', function(e) {
        e.preventDefault();
        console.log('Delete button clicked!'); // Debug log
        
        var userId = $(this).data('user-id');
        var username = $(this).data('username');
        var $btn = $(this);
        var $row = $(this).closest('tr');
        
        console.log('User ID:', userId, 'Username:', username); // Debug log
        
        if (!userId) {
            alert('ID user tidak ditemukan');
            return;
        }
        
        if (confirm('Hapus user "' + username + '"?')) {
            // Show loading
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Hapus...').prop('disabled', true);
            
            console.log('Sending AJAX request...'); // Debug log
            
            // AJAX request
            $.ajax({
                url: 'delete-user.php',
                type: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function(response) {
                    console.log('AJAX success:', response); // Debug log
                    
                    if (response.success) {
                        // Remove row
                        $row.fadeOut(function() {
                            $(this).remove();
                            // Update row numbers
                            $('.user-table tbody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });
                        });
                        
                        // Show success message
                        alert('✅ ' + response.message);
                    } else {
                        alert('❌ ' + response.message);
                        $btn.html('<i class="fa fa-trash"></i> Hapus').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', error); // Debug log
                    console.log('Response text:', xhr.responseText); // Debug log
                    
                    alert('❌ Terjadi kesalahan: ' + error);
                    $btn.html('<i class="fa fa-trash"></i> Hapus').prop('disabled', false);
                }
            });
        }
    });
});
