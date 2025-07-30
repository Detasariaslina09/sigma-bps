// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
} else {
    console.log('jQuery version:', jQuery.fn.jquery);
}

// Preview image before upload
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#preview-image').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm delete
function confirmDelete(id) {
    console.log('confirmDelete function called with ID:', id);
    try {
        if (confirm("Apakah Anda yakin ingin menghapus profil ini?")) {
            console.log('User confirmed delete, redirecting...');
            window.location.href = "admin-profil.php?delete=" + id;
        } else {
            console.log('User cancelled delete');
        }
    } catch (error) {
        console.error('Error in confirmDelete function:', error);
        alert('Error: Gagal menjalankan operasi hapus profil.');
    }
}

// Edit profile
function editProfile(profile) {
    console.log('editProfile function called with:', profile);
    
    try {
        // Set modal title
        $('#profileModalLabel').text('Edit Profil');
        
        // Fill form with profile data
        $('#profile_id').val(profile.id);
        $('#nama').val(profile.nama);
        $('#jabatan').val(profile.jabatan);
        $('#link').val(profile.link || '');
        $('#old_foto').val(profile.foto);
        $('#preview-image').attr('src', 'img/staff/' + profile.foto);
        
        console.log('Form fields filled, showing modal');
        
        // Show modal
        $('#profileModal').modal('show');
    } catch (error) {
        console.error('Error in editProfile function:', error);
        alert('Error: Gagal memuat data profil untuk diedit.');
    }
}

// Document ready function
$(document).ready(function() {
    console.log('Admin profil script loaded');
    
    // Reset form when modal is closed
    $('#profileModal').on('hidden.bs.modal', function () {
        console.log('Modal closed, resetting form');
        $('#profileForm')[0].reset();
        $('#profile_id').val('');
        $('#old_foto').val('');
        $('#preview-image').attr('src', 'img/staff/default-male.jpg');
        $('#profileModalLabel').text('Tambah Profil Baru');
    });

    // Trigger modal for add new profile
    $('.btn-add-profile-table').click(function() {
        console.log('Add profile button clicked');
        $('#profileModalLabel').text('Tambah Profil Baru');
        $('#profileModal').modal('show');
    });
    
    // Handle file input change for image preview
    $('#foto').on('change', function() {
        console.log('File input changed');
        previewImage(this);
    });
    
    // Handle edit profile buttons
    $(document).on('click', '.btn-edit-profile', function(e) {
        e.preventDefault();
        console.log('Edit button clicked');
        
        try {
            var profileData = $(this).data('profile');
            console.log('Profile data:', profileData);
            
            if (profileData) {
                editProfile(profileData);
            } else {
                console.error('No profile data found');
                alert('Error: Data profil tidak ditemukan.');
            }
        } catch (error) {
            console.error('Error processing profile data:', error);
            alert('Error: Gagal memproses data profil.');
        }
    });
    
    // Handle delete profile buttons
    $(document).on('click', '.btn-delete-profile', function(e) {
        e.preventDefault();
        console.log('Delete button clicked');
        
        var profileId = $(this).data('id');
        console.log('Profile ID:', profileId);
        
        if (profileId) {
            confirmDelete(profileId);
        } else {
            console.error('No profile ID found');
            alert('Error: ID profil tidak ditemukan.');
        }
    });
});