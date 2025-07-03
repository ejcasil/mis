<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Official's Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Official's Profile</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->
                <!-- Button to Open Modal for Adding a Official -->
                <button class="btn btn-primary ms-auto mb-3" id="addOfficialBtn"><i class='bx bx-plus me-2'></i>Add Barangay Official</button>
                <hr class='text-secondary'>
                <!-- Official Table -->
                <table id="officialTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Official Name</th>
                            <th>Position</th>
                            <th>Term</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- CONTENT -->

        </div>
    </div>
    </div>
</main>

<!-- Official Modal Form -->
<div class="modal fade modal-lg" id="officialModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="officialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="officialModalLabel">Add Barangay Official</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="officialForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                    <input type="hidden" id="id" name="id">

                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

                            <div class="profile-container border p-2">
                                <img src="<?= base_url('public/assets/images/logo.png'); ?>" alt="image" id="prof_img" name="prof_img" class="profile-img">
                                <i class='bx bx-upload profile-icon' id="btn-upload-profile"></i>
                                <input type="file" class="form-control collapse" name="image" id="image">

                            </div>

                            <div class="form-group mb-2">
                                <input type="hidden" name="img_path" id="img_path">
                            </div>

                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                            <div class="form-group mb-2">
                                <label for="lname">Last Name</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="lname" id="lname" placeholder="Enter your last name" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="fname">First Name</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter your first name" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" name="mname" id="mname" placeholder="Enter your middle name">
                            </div>
                            <div class="form-group mb-2">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" name="suffix" id="suffix" placeholder="Enter your suffix">
                            </div>
                            <div class="form-group mb-2">
                                <label for="bday">Birthday</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control datetimepicker" name="bday" id="bday" placeholder="Enter your birthday" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="email">Email Address</label><span class="text-danger ms-2">*</span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="cp">Contact No.</label><span class="text-danger ms-2">*</span>
                                <input type="tel" class="form-control" name="cp" id="cp" placeholder="Enter your contact no." required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="term">Term</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="term" id="term" placeholder="Enter term" required>
                            </div>
                            <div class="form-group container-select2">
                                <label for="position">Position</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" name="position" id="position" style="width:100%" required>
                                    <option value="">Select option</option>
                                    <?php if ($position) : ?>
                                        <?php foreach ($position as $row): ?>
                                            <option value="<?= $row->id ?>"><?= htmlspecialchars($row->description) ?></option>
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

        // Function to initialize tooltips
        function initializeTooltips() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }

        /* date time picker */
        function enable_datepicker() {
            flatpickr(".datetimepicker", {
                dateFormat: "m-d-Y", // Set date format
                enableTime: false, // Enable time selection
            });
        }
        enable_datepicker();

        // Initialize the DataTable
        var table = $('#officialTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/official/getOfficialData') ?>",
                "type": "GET",
                "dataSrc": "data", // Ensure this matches the server response structure
                "error": function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            },
            "columns": [{
                    "data": 0
                }, // ID
                {
                    "data": 1
                }, // official name
                {
                    "data": 2
                }, // position
                {
                    "data": 3
                }, // term
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return '<button class="btn btn-info editBtn" data-id="' + row[0] + '"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i class="bx bx-edit"></i></button>';
                    }
                }
            ],
            "drawCallback": function(settings) {
                // Initialize tooltips after DataTable redraws
                initializeTooltips();
            }
        });

        // Open Modal for Adding a Official
        $('#addOfficialBtn').click(function() {
            $('#officialModalLabel').text('Add Barangay Official');
            $('#officialForm')[0].reset();
            $('#id').val('');
            $("#prof_img").attr("src", "<?= base_url('public/assets/images/logo.png'); ?>");
            $('#officialModal').modal('show');
        });

        // Handle form submit
        $('#officialForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = new FormData(this); // 'this' refers to the form element

            $.ajax({
                url: "<?= site_url('/official/saveOfficial') ?>",
                type: "POST",
                data: formData,
                contentType: false, // Important: Prevent jQuery from setting content type
                processData: false, // Important: Prevent jQuery from processing the data
                success: function(response) {
                    if (response.success) {
                        $('#officialForm')[0].reset();
                        $("#prof_img").attr("src", "<?= base_url('public/assets/images/logo.png'); ?>");
                        $("#position").val('').trigger('change.select2');
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#officialModalLabel").text() === 'Edit Barangay Official') {
                            $('#officialModal').modal('hide');
                            showAlert('Successfully updated barangay official');
                        } else {
                            showAlert('Successfully inserted barangay official');
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
                        $("#officialModal").scrollTop(0);

                    }
                    // $('#barangayModal').modal('hide');
                    table.ajax.reload(null, false); // Reload DataTable without resetting pagination
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle Edit button click
        $('#officialTable').on('click', '.editBtn', function() {
            var userId = $(this).data('id');

            $.ajax({
                url: "<?= site_url('/official/getOfficial') ?>/" + userId,
                type: "GET",
                success: function(response) {
                    $('#officialModalLabel').text('Edit Barangay Official');
                    $('#id').val(response.id);
                    $("#lname").val(response.lname);
                    $("#fname").val(response.fname);
                    $("#mname").val(response.mname);
                    $("#suffix").val(response.suffix);
                    $('#bday').val(response.bday);
                    $('#email').val(response.email);
                    $('#cp').val(response.cp);
                    $('#term').val(response.term);
                    $('#position').val(response.position_id).trigger('change');
                    $('#img_path').val(response.img_path);
                    $("#prof_img").attr("src", "<?= base_url('writable/uploads/') ?>" + response.img_path);

                    $('#officialModal').modal('show');
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle modal hidden event
        $('#officialModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
            $("#prof_img").attr("src", "<?= base_url('public/assets/images/logo.png'); ?>");
            $("#position").val('').trigger('change.select2');
        });

        // Handle modal shown event
        $('#officialModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        // Initialize Select2 on the select element
        $('#position').select2({
            dropdownParent: $('.container-select2')
        });

    });
</script>
<?= $this->endSection('my_script') ?>