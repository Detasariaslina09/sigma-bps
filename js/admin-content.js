$(document).ready(function() {
    // Simpan informasi login di sessionStorage
    sessionStorage.setItem('isLoggedIn', 'true');
    sessionStorage.setItem('username', $('meta[name="username"]').attr('content') || '');
    sessionStorage.setItem('userRole', $('meta[name="role"]').attr('content') || '');
    
    // Preview gambar saat dipilih
    $('#image').change(function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Trigger file input when clicking on preview container
    $('#imagePreviewContainer').on('click', function() {
        $('#image').click();
    });
    
    // Add visual feedback when hovering over preview
    $('#imagePreviewContainer').on('mouseenter', function() {
        $(this).find('img').css('opacity', '0.8');
    }).on('mouseleave', function() {
        $(this).find('img').css('opacity', '1');
    });
    
    // File input label update
    $('.file-upload-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        if(fileName) {
            $('.file-upload-button').text(fileName);
        } else {
            $('.file-upload-button').text('Pilih File Gambar');
        }
    });
    
    // Form submission with loading overlay
    $('#contentForm').on('submit', function() {
        $('#loadingOverlay').fadeIn(300);
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').slideUp();
    }, 5000);
    
    // Initialize TinyMCE if available
    if(typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
        });
    }
});
