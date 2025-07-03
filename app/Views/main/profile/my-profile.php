<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">My Account</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">My Account</a></li>
    </ul>

    <div class="bg-white p-4 shadow-sm m-2" style="width:750px;max-width:100%">
        <form id="userForm" method="POST" enctype="multipart/form-data">
            <div>
                <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                <input type="hidden" id="id" name="id" value="<?= $profile->id ?? ''; ?>">

                <div class="row">
                    <div class="col-4">
                        <div class="profile-container border p-2">
                            <img src="<?= $profile->image ? base_url('writable/uploads/' . $profile->image) : base_url('public/assets/images/logo.png'); ?>" alt="image" id="img" name="img" class="profile-img">
                            <i class='bx bx-upload profile-icon' id="btn-upload-profile"></i>
                            <input type="file" class="form-control collapse" name="image" id="file-image">
                            <input type="hidden" name="img_path" id="img_path" value="<?= $profile->image ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="alert alert-info">
                            <i class="fi fi-rs-info me-2"></i><b>Note:</b> Password should have alphanumeric characters, symbols, and atleast 10 character long.
                        </div>
                        <div class="form-group mb-2">
                            <label for="username">Username</label><span class="text-danger ms-2">*</span>
                            <input type="text" class="form-control" name="username" id="username" value="<?= $profile->username ?? ''; ?>" placeholder="Enter Username" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="old_password">Old Password</label><span class="text-danger ms-2">*</span>
                            <input type="password" class="form-control" name="old_password" id="old_password" value="<?= session()->has('verification_code') ? 'VERIFICATION CODE' : ''; ?>" placeholder="Enter old password" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="new_password">New Password</label><span class="text-danger ms-2">*</span>
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter new password" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="rnew_password">Confirm Password</label><span class="text-danger ms-2">*</span>
                            <input type="password" class="form-control" name="rnew_password" id="rnew_password" placeholder="Enter new password" required>
                        </div>
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <a href="<?= base_url(); ?>main/dashboard" class="btn btn-light w-100"><i class='bx bx-chevron-left me-2'></i>Back</a>
                            <button type="submit" class="btn btn-primary w-100"><i class='bx bx-check me-2'></i>Update Profile</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

</main>

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {
        /* date time picker */
        function enable_datepicker() {
            flatpickr(".datetimepicker", {
                dateFormat: "m-d-Y", // Set date format
                enableTime: false, // Enable time selection
            });
        }
        enable_datepicker();

        // Handle form submit
        $('#userForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = new FormData(this); // 'this' refers to the form element

            $.ajax({
                url: "<?= site_url('/profile/saveUser3') ?>",
                type: "POST",
                data: formData,
                contentType: false, // Important: Prevent jQuery from setting content type
                processData: false, // Important: Prevent jQuery from processing the data
                success: function(response) {
                    if (response.success) {
                        $('#userForm')[0].reset();
                        $('.alert-danger').hide();
                        showAlert('Successfully updated account');
                    } else {
                        var errors = response.errors;
                        var errorMessages = '';

                        if (Array.isArray(response.errors)) {
                            $.each(errors, function(field, message) {
                                errorMessages += '<p>' + message + '</p>';
                            });
                        } else if (response.errors && typeof response.errors === 'object') {
                            $.each(response.errors, function(field, message) {
                                errorMessages += '<p>' + message + '</p>';
                            });
                        } else {
                            errorMessages = response.errors;
                        }

                        $('.alert-danger').html(errorMessages);
                        $('.alert-danger').show();

                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Initialize Select2 on the select element
        $('#gender').select2({
            dropdownParent: $('.container-gender'), // Use the modal as dropdown parent
        });

        $('#cstatus').select2({
            dropdownParent: $('.container-cstatus'), // Use the modal as dropdown parent
        });


        $('#status').select2({
            dropdownParent: $('.container-status'), // Use the modal as dropdown parent
        });
    })
</script>
<?= $this->endSection('my_script') ?>