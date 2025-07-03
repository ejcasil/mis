<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Barangay Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Barangay Profile</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->
                <div class="container text-end my-2">
                    <button type="button" class="btn btn-light my-2 border" id="updateProfile">
                        <i class='bx bxs-edit me-2'></i>Update Info
                    </button>
                </div>
                <div class="container d-flex gap-4 flex-sm-column flex-md-column flex-lg-row flex-xl-row">
                    <img src="<?= isset($profile['logo']) ? $profile['logo'] : ''; ?>" class="img-fluid p-2 border rounded shadow-sm lblLogo" style="width:200px;height:200px">
                    <div class="brgy-info w-100">

                        <div class="row border border-light my-2 rounded shadow-sm">
                            <div class="col-sm-12 col-md-3 col-xl-3 bg-light d-flex justify-content-center align-items-center">
                                <label class="text-secondary">Barangay</label>
                            </div>
                            <div class="col-sm-12 col-md-9 col-xl-9 p-2">
                                <label class="fw-bold lblBarangay"><?= isset($profile['brgy_name']) ? $profile['brgy_name'] : ''; ?></label>
                            </div>
                        </div>

                        <div class="row border border-light my-2 rounded shadow-sm">
                            <div class="col-sm-12 col-md-3 col-xl-3 bg-light d-flex justify-content-center align-items-center">
                                <label class="text-secondary">Municipality</label>
                            </div>
                            <div class="col-sm-12 col-md-9 col-xl-9 p-2">
                                <label class="fw-bold lblMunicipality"><?= isset($profile['municipality']) ? $profile['municipality'] : ''; ?></label>
                            </div>
                        </div>

                        <div class="row border border-light my-2 rounded shadow-sm">
                            <div class="col-sm-12 col-md-3 col-xl-3 bg-light d-flex justify-content-center align-items-center">
                                <label class="text-secondary">Province</label>
                            </div>
                            <div class="col-sm-12 col-md-9 col-xl-9 p-2">
                                <label class="fw-bold lblProvince"><?= isset($profile['province']) ? $profile['province'] : ''; ?></label>
                            </div>
                        </div>

                        <div class="row border border-light my-2 rounded shadow-sm">
                            <div class="col-sm-12 col-md-3 col-xl-3 bg-light d-flex justify-content-center align-items-center">
                                <label class="text-secondary">Region</label>
                            </div>
                            <div class="col-sm-12 col-md-9 col-xl-9 p-2">
                                <label class="fw-bold lblRegion"><?= isset($profile['region']) ? $profile['region'] : ''; ?></label>
                            </div>
                        </div>

                        <div class="row border border-light my-2 rounded shadow-sm">
                            <div class="col-sm-12 col-md-3 col-xl-3 bg-light d-flex justify-content-center align-items-center">
                                <label class="text-secondary">Present Brgy Captain / OIC-Brgy Captain</label>
                            </div>
                            <div class="col-sm-12 col-md-9 col-xl-9 p-2">
                                <label class="fw-bold lblOfficial"><?= isset($profile['official_name']) ? $profile['official_name'] : ''; ?></label>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- CONTENT -->
            </div>



        </div>
    </div>
    </div>
</main>

<!-- Modal Form -->
<div class="modal fade modal-lg" id="profileModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">Update Barangay Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="profileForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                    <input type="hidden" id="id" name="id" value="<?= $profile['id'] ?? ''; ?>">

                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

                            <div class="profile-container border p-2">
                                <img src="" alt="image" id="prof_img" name="prof_img" class="profile-img">
                                <i class='bx bx-upload profile-icon' id="btn-upload-profile" ></i>
                                <input type="file" class="form-control collapse" name="image" id="image">
                            </div>
                          
                            <div class="form-group mb-2">
                                
                                <input type="hidden" name="img_path" id="img_path">
                            </div>

                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                            <div class="form-group container-select2-barangay">
                                <label for="barangay">Barangay</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" name="barangay" id="barangay" style="width:100%">
                                    <option value="" selected>Select option</option>
                                    <?php if ($barangay) : ?>
                                        <?php foreach ($barangay as $row): ?>
                                            <option value="<?= $row->id ?>" selected><?= htmlspecialchars($row->brgy_name) ?></option> <!-- Escape output for security -->
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="municipality">Municipality</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="municipality" id="municipality" placeholder="Enter your municipality" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="province">Province</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="province" id="province" placeholder="Enter your province" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="region">Region</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="region" id="region" placeholder="Enter your region">
                            </div>

                            <div class="form-group container-select2-official">
                                <label for="official">Present Brgy Captain/Officer-In-Charge</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" name="official" id="official" style="width:100%" required>
                                    <option value="" selected>Select option</option>
                                    <?php if ($official) : ?>
                                        <?php foreach ($official as $row): ?>
                                            <option value="<?= $row->id ?>"><?= htmlspecialchars($row->fullname) . "(" . $row->term . ")"; ?></option> <!-- Escape output for security -->
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class='bx bx-check me-2'></i>Save</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Form -->
<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
      $(document).ready(function() {
        // Handle form submit
        $('#profileForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = new FormData(this); // 'this' refers to the form element

            $.ajax({
                url: "<?= site_url('/brgy_profile/saveProfile') ?>",
                type: "POST",
                data: formData,
                contentType: false, // Important: Prevent jQuery from setting content type
                processData: false, // Important: Prevent jQuery from processing the data
                success: function(response) {
                    if (response.success) {
                        // Display data
                        let data = response.data;
                        $(".lblBarangay").html(data.brgy_name);
                        $(".lblMunicipality").html(data.municipality);
                        $(".lblProvince").html(data.province);
                        $(".lblRegion").html(data.region);
                        $(".lblOfficial").html(data.official_name);
                        $(".lblLogo").attr("src", data.logo);

                        $('#profileForm')[0].reset();
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#profileModalLabel").text() === 'Edit Barangay Profile') {
                            $('#profileModal').modal('hide');
                            showAlert('Successfully updated barangay profile');
                        } else {
                            showAlert('Successfully inserted barangay profile');
                        }
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



                        $('.alert').html(errorMessages);
                        $('.alert').show();
                        $("#profileModal").scrollTop(0);

                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle Edit button click
        $(document).on('click', '#updateProfile', function() {
            let brgy_id = $('#id').val();
            if (brgy_id === "") {
                $('#profileModal').modal('show');
            } else {
                $.ajax({
                    url: "<?= site_url('/brgy_profile/getProfile') ?>",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            let data = response.data;

                            $('#profileModalLabel').text('Edit Barangay Profile');
                            $('#id').val(data.id);
                            $('#barangay').val(data.brgy_id).trigger('change');
                            $('#municipality').val(data.municipality);
                            $('#province').val(data.province);
                            $('#region').val(data.region);
                            $('#official').val(data.official_id).trigger('change');
                            $('#img_path').val(data.logo);
                            $("#prof_img").attr("src", "<?= base_url('writable/uploads/') ?>" + data.logo);
                            $('#profileModal').modal('show');
                        } else {
                            // Profile not found
                        }

                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error:', error);
                        console.error('XHR:', xhr);
                    }
                });
            }

        });

        // Handle modal hidden event
        $('#profileModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
        });

        // Handle modal shown event
        $('#profileModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        $('#official').select2({
            dropdownParent: '.container-select2-official'
        })

        $('#barangay').select2({
            dropdownParent: '.container-select2-barangay'
        })

    });
</script>
<?= $this->endSection('my_script') ?>