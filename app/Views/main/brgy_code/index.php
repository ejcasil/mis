<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Barangay Code</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Barangay Code</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">

                <!-- CONTENT -->
                <!-- Button to Open Modal for Adding a Barangay -->
                <button class="btn btn-primary ms-auto mb-3" id="addBarangayBtn"><i class='bx bx-plus me-2'></i>Add Barangay Code</button>
                <hr class='text-secondary'>
                <!-- Barangay Table -->
                <table id="barangayTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Barangay Name</th>
                            <th>Code</th>
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

<!-- Barangay Modal Form -->
<div class="modal fade" id="barangayModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="barangayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="barangayModalLabel">Add Barangay Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="barangayForm">
                <div class="modal-body">
                    <div class="alert alert-danger collapse">asdf</div>
                    <input type="hidden" id="id" name="id">
                    <div class="form-group mb-2">
                        <label for="barangay">Barangay Name</label><span class="text-danger ms-2">*</span>
                        <input type="text" class="form-control" name="barangay" id="barangay" placeholder="Enter your barangay name">
                    </div>
                    <div class="form-group mb-2">
                        <label for="code">Code</label><span class="text-danger ms-2">*</span>
                        <input type="text" id="code" name="code" class="form-control" placeholder="Enter code" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="status" id="status" style="width:100%">
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
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
<!-- Barangay Modal Form -->
<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {

        // Function to initialize tooltips
        function initializeTooltips() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }

        // Initialize the DataTable
        var table = $('#barangayTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/BrgyCode/getBarangayData3') ?>",
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
                }, // barangay name
                {
                    "data": 2
                }, // code
                {
                    "data": 3
                }, // Status
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

        // Open Modal for Adding a Barangay
        $('#addBarangayBtn').click(function() {
            $('#barangayModalLabel').text('Add Barangay Code');
            $('#barangayForm')[0].reset();
            $('#id').val('');
            $('#barangayModal').modal('show');
        });

        // Handle form submit
        $('#barangayForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('/BrgyCode/saveBrgyCode3') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#barangayForm')[0].reset();
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#barangayModalLabel").text() === 'Edit Barangay Code') {
                            $('#barangayModal').modal('hide');
                            showAlert('Successfully updated barangay code');
                        } else {
                            showAlert('Successfully inserted barangay code');
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
        $('#barangayTable').on('click', '.editBtn', function() {
            var userId = $(this).data('id');

            $.ajax({
                url: "<?= site_url('/BrgyCode/getBarangay3') ?>/" + userId,
                type: "GET",
                success: function(response) {
                    $('#barangayModalLabel').text('Edit Barangay Code');
                    $('#id').val(response.id);
                    $('#barangay').val(response.brgy_name);
                    $('#code').val(response.code);
                    $('#status').val(response.status).trigger('change');
                    $('#barangayModal').modal('show');
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle modal hidden event
        $('#barangayModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
        });

        // Handle modal shown event
        $('#barangayModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        $('.form-select').select2({
            placeholder: 'Select an option',
            dropdownParent: '#barangayModal'
        })
    });
</script>
<?= $this->endSection('my_script') ?>