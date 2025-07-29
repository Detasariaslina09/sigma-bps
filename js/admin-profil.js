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
    if (confirm("Apakah Anda yakin ingin menghapus profil ini?")) {
        window.location.href = "admin-profil.php?delete=" + id;
    }
}

// Edit profile
function editProfile(profile) {
    // Set modal title
    $('#profileModalLabel').text('Edit Profil');
    
    // Fill form with profile data
    $('#profile_id').val(profile.id);
    $('#nama').val(profile.nama);
    $('#jabatan').val(profile.jabatan);
    $('#link').val(profile.link);
    $('#old_foto').val(profile.foto);
    $('#preview-image').attr('src', 'img/staff/' + profile.foto);
    
    // Show modal
    $('#profileModal').modal('show');
}

// Document ready function
$(document).ready(function() {
    // Reset form when modal is closed
    $('#profileModal').on('hidden.bs.modal', function () {
        $('#profileForm')[0].reset();
        $('#profile_id').val('');
        $('#old_foto').val('');
        $('#preview-image').attr('src', 'img/staff/default-male.jpg');
        $('#profileModalLabel').text('Tambah Profil Baru');
    });

    // Trigger modal for add new profile
    $('.btn-add-profile-table').click(function() {
        $('#profileModalLabel').text('Tambah Profil Baru');
        $('#profileModal').modal('show');
    });
});