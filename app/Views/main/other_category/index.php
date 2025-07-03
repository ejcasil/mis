<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Other Categories</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Other Categories</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">

                <!-- CONTENT -->
                <!-- Button to Open Modal for Adding a Category -->
                <button class="btn btn-primary ms-auto mb-3" id="addCategoryBtn"><i class='bx bx-plus me-2'></i>Add Category</button>
                <hr class='text-secondary'>
                <!-- Category Table -->
                <table id="categoryTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Description</th>
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

<!-- Category Modal Form -->
<div class="modal fade" id="categoryModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <div class="alert alert-danger collapse">asdf</div>
                    <input type="hidden" id="id" name="id">
                    <div class="form-group mb-2 category-container">
                        <label for="category">Category</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="category" id="category" style="width:100%">
                            <option value="">Select option</option>
                            <option value="amach">Agricultural Machinery</option>
                            <option value="alive">Agricultural Livestock</option>
                            <option value="app">Appliance/gadget</option>
                            <option value="amenities">Building Amenity</option>
                            <option value="bldgtype">Building Type</option>
                            <option value="cstatus">Civil Status</option>
                            <option value="comm">Communication Line</option>
                            <option value="course">Course</option>
                            <option value="dialect">Dialect</option>
                            <option value="doctype">Document Type</option>
                            <option value="educ">Educational Attainment</option>
                            <option value="ethnic">Ethnicity</option>
                            <option value="gprograms">Gov't program/assistance</option>
                            <option value="webCategory">Manage Website</option>
                            <option value="occ">Occupation</option>
                            <option value="power">Power Source</option>
                            <option value="position">Position/Designation</option>
                            <option value="rel">Religion</option>
                            <option value="relation">Relationship</option>
                            <option value="san">Sanitation (toilet facility)</option>
                            <option value="sincome">Source of Income</option>
                            <option value="training">Training/skill</option>
                            <option value="comor">Type of Comorbidity</option>
                            <option value="disability">Type of Disability</option>
                            <option value="vhcl">Type of Vehicle</option>
                            <option value="water">Water Source</option>
                            <option value="cook">Way of Cooking</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">Description</label><span class="text-danger ms-2">*</span>
                        <input type="text" id="description" name="description" class="form-control" placeholder="Enter description" required>
                    </div>
                    <div class="form-group status-container">
                        <label for="status">Status</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="status" id="status" style="width:100%">
                            <option value="">Select option</option>
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
<!-- Category Modal Form -->
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
        var table = $('#categoryTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/OtherCategories/getCategoryData3') ?>",
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
                }, // Category
                {
                    "data": 2
                }, // Description
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

        // Open Modal for Adding a Category
        $('#addCategoryBtn').click(function() {
            $('#categoryModalLabel').text('Add Category');
            $('#categoryForm')[0].reset();
            $('#id').val('');
            $('#categoryModal').modal('show');
        });

        // Handle form submit
        $('#categoryForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('/OtherCategories/saveCategory3') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#categoryForm')[0].reset();
                        $('.alert').hide();
                        $("#category").val('').trigger("change.select2");
                        $("#status").val('').trigger("change.select2");
                        // hide modal if updated 
                        if ($("#categoryModalLabel").text() === 'Edit Category') {
                            $('#categoryModal').modal('hide');
                            showAlert('Successfully updated category');
                        } else {
                            showAlert('Successfully inserted category');
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
                    // $('#categoryModal').modal('hide');
                    table.ajax.reload(null, false); // Reload DataTable without resetting pagination
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle Edit button click
        $('#categoryTable').on('click', '.editBtn', function() {
            var userId = $(this).data('id');

            $.ajax({
                url: "<?= site_url('/OtherCategories/getCategory3') ?>/" + userId,
                type: "GET",
                success: function(response) {
                    $('#categoryModalLabel').text('Edit Category');
                    $('#id').val(response.id);
                    $('#category').val(response.category).trigger('change');
                    $('#description').val(response.description);
                    $('#status').val(response.status).trigger('change');
                    $('#categoryModal').modal('show');
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle modal hidden event
        $('#categoryModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
            $("#category").val('').trigger("change.select2");
            $("#status").val('').trigger("change.select2");
        });

        // Handle modal shown event
        $('#categoryModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        $('#category').select2({
            dropdownParent: '.category-container'
        })

        $('#status').select2({
            dropdownParent: '.status-container'
        })

        
    });
</script>
<?= $this->endSection('my_script') ?>