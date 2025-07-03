<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">User Management</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">User Management</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->
                <button class="btn btn-primary ms-auto mb-3" id="addUserBtn"><i class='bx bx-plus me-2'></i>Create Admin Account</button>
                <hr class='text-secondary'>
                <!-- Table -->
                <table id="userTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>User Role</th>
                            <th>Status</th>
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

<div class="modal fade modal-lg" id="userModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Create Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                    <input type="hidden" id="id" name="id">

                    <div class="row">
                        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">

                            <div class="profile-container border p-2">
                                <img src="<?= base_url('public/assets/images/logo.png'); ?>" alt="image" id="img" name="img" class="profile-img">
                                <i class='bx bx-upload profile-icon' id="btn-upload-profile" ></i>
                                <input type="file" class="form-control collapse" name="image" id="image">
                                <input type="hidden" name="img_path" id="img_path">
                            </div>

                            <div class="form-group container-status mb-2">
                                <label for="status">Status</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" name="status" id="status" style="width:100%" required>
                                    <option value="" selected>Select option</option>
                                    <option value="ACTIVE">ACTIVE</option>
                                    <option value="INACTIVE">INACTIVE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9">
                            <div class="form-group mb-2">
                                <label for="username">Username</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="lname">Last Name</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="lname" id="lname" placeholder="Enter Last Name" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="fname">First Name</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter First Name" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" name="mname" id="mname" placeholder="Enter Middle Name">
                            </div>

                            <div class="form-group mb-2">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" name="suffix" id="suffix" placeholder="Enter Suffix">
                            </div>

                            <div class="form-group mb-2 container-gender">
                                <label for="gender">Gender</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" name="gender" id="gender">
                                    <option value="" selected>Select option</option>
                                    <option value="MALE">MALE</option>
                                    <option value="FEMALE">FEMALE</option>
                                </select>
                            </div>

                            <div class="form-group mb-2">
                                <label for="username">Birthday</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control datetimepicker" name="bday" id="bday" placeholder="Enter birthday" required>
                            </div>

                            <div class="form-group mb-2 container-cstatus">
                                <label for="cstatus">Civil Status</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" name="cstatus" id="cstatus">
                                    <option value="" selected>Select option</option>
                                    <?php if ($cstatus) : ?>
                                        <?php foreach ($cstatus as $row) : ?>
                                            <option value="<?= $row->id ?? ''; ?>"><?= $row->description ?? ''; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group mb-2">
                                <label for="email">Email</label><span class="text-danger ms-2">*</span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="cp">Contact no.</label><span class="text-danger ms-2">*</span>
                                <input type="text" class="form-control" name="cp" id="cp" placeholder="Enter contact no." required>
                            </div>

                            <div class="form-group mb-2 container-barangay">
                                <label for="barangay">Barangay</label><span class="text-danger ms-2">*</span>
                                <select class="form-select" id="barangay" name="barangay" required>
                                <option value="" selected>Select option</option>
                                    <?php if ($barangay) : ?>
                                        <?php foreach ($barangay as $row) : ?>
                                            <option value="<?= $row->id ?? ''; ?>"><?= $row->brgy_name ?? ''; ?></option>
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
        var userTable = $('#userTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/user_management/getUserData3') ?>",
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
                }, // Username
                {
                    "data": 2
                }, // Name
                {
                    "data": 3
                }, // Barangay
                {
                    "data": 4
                }, // User Role
                {
                    "data": 5
                }, // Status
                {
                    "data": null,
                    "render": function(data, type, row) {
                        // Construct the URLs using a template literal for better readability
                        var id = row[0]; // Ensure 'id' is the correct key
                        var baseUrl = '<?= base_url() ?>'; // Assuming PHP is used for base URL
                        var status = row[5];
                        var btn = '';
                        if (status && status == "PENDING") {
                            btn = `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}user_management/approve3/${id}' role='button'>
                                            </i><span class='icon-text'>Approve</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}user_management/decline3/${id}' role='button'>
                                            <span class='icon-text'>Decline</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `;
                        } else if (status && status == "DECLINED") {
                            btn = `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}user_management/approve3/${id}' role='button'>
                                            <span class='icon-text'>Approve</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `;
                        }

                        // Construct the button group HTML

                        return btn;
                    }
                }
            ],
            "drawCallback": function(settings) {
                // Initialize tooltips after DataTable redraws
                initializeTooltips();
            }
        });

        // Open Modal for Adding a Official
        $('#addUserBtn').click(function() {
            $('#postModalLabel').text('Create Admin Account');
            $('#userForm')[0].reset(); +
            $('#img').attr('src', '<?= base_url('public/assets/images/logo.png'); ?>');
            $('#id').val('');
            $('#userModal').modal('show');
        });

        // Handle form submit
        $('#userForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = new FormData(this); // 'this' refers to the form element

            $.ajax({
                url: "<?= site_url('/user_management/saveUser3') ?>",
                type: "POST",
                data: formData,
                contentType: false, // Important: Prevent jQuery from setting content type
                processData: false, // Important: Prevent jQuery from processing the data
                success: function(response) {
                    if (response.success) {
                        $('#userForm')[0].reset();
                        $("#img").attr("src", "<?= base_url('public/assets/images/logo.png'); ?>");
                        $("#cstatus").val('').trigger('change.select2');
                        $("#gender").val('').trigger('change.select2');
                        $("#status").val('').trigger('change.select2');
                        $("#barangay").val('').trigger('change.select2');
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#userModalLabel").text() === 'Edit Admin Account') {
                            $('#userModal').modal('hide');
                            showAlert('Successfully updated account');
                        } else {
                            showAlert('Successfully inserted account');
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
                        $("#userModal").scrollTop(0);

                    }
                    // $('#barangayModal').modal('hide');
                    userTable.ajax.reload(null, false); // Reload DataTable without resetting pagination
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle modal hidden event
        $('#userModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
            $("#img").attr("src", "");
            $("#gender").val('').trigger('change.select2');
            $("#cstatus").val('').trigger('change.select2');
            $("#status").val('').trigger('change.select2');
            $("#barangay").val('').trigger('change.select2');
        });

        // Handle modal shown event
        $('#userModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        // Initialize Select2 on the select element
        $('#gender').select2({
            dropdownParent: $('.container-gender'), 
        });

        $('#cstatus').select2({
            dropdownParent: $('.container-cstatus'), 
        });

        $('#status').select2({
            dropdownParent: $('.container-status'), 
        });

        $('#barangay').select2({
            dropdownParent: $('.container-barangay'), 
        });

    });
</script>
<?= $this->endSection('my_script') ?>