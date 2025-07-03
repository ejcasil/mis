<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Banner</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Banner</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->
                <button class="btn btn-primary ms-auto mb-3" id="addBannerBtn"><i class='bx bx-plus me-2'></i>Add Banner</button>
                <hr class='text-secondary'>
                <!-- Table -->
                <table id="bannerTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
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

<div class="modal fade modal-lg" id="bannerModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerModalLabel">Add Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bannerForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                    <input type="hidden" id="id" name="id">

                    <div class="form-group mb-2">
                        <label for="title">Title</label><span class="text-danger ms-2">*</span>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter banner title" required>
                    </div>

                    <div class="form-floating mb-2">
                        <textarea class="form-control" placeholder="Enter banner description" id="description" name="description" style="height: 100px"></textarea>
                        <label for="description">Enter banner description</label>
                    </div>

        
                    <div class="form-group container-select2 mb-2">
                        <label for="status">Status</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="status" id="status" style="width:100%" required>
                           <option value="" selected>Select option</option>
                           <option value="ACTIVE">ACTIVE</option>
                           <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="image">Image</label><span class="text-danger ms-2">Size: 1190x460</span>
                        <input type="file" class="form-control" name="image" id="image">
                        <input type="hidden" name="img_path" id="img_path">
                    </div>
                    <div class="form-group mb-2">
                        <img src="" alt="image" id="img" name="img" class="img-thumbnail h-50 w-50">
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

        // Update profile pic when the user selected a file
        $('#image').on('change', function(event) {
            let file = event.target.files[0]; // Get the selected file

            if (file) {
                const reader = new FileReader();

                // Define what happens when the file is read
                reader.onload = function(e) {
                    $('#img').attr('src', e.target.result).show(); // Set the image source and show it
                };

                reader.readAsDataURL(file); // Read the file as a data URL
            }
        });

        // Initialize the DataTable
        var table = $('#bannerTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/banner/getBannerData') ?>",
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
                    "data": null,
                    "render": function(data, type, row) {
                        return "<img src='" + row[5] + "' class='img-thumbnail' style='width:150px;height:150px;'>";
                    }
                }, // Image
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return "<label class='clamped'>" + row[1] + "</label>";
                    }
                }, // Title
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return "<label class='clamped'>" + row[2] + "</label>";
                    }
                }, // Description
                {
                    "data": 4
                }, // status
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
        $('#addBannerBtn').click(function() {
            $('#bannerModalLabel').text('Add Banner');
            $('#bannerForm')[0].reset();+
            $('#img').attr('src', '');
            $('#id').val('');
            $('#bannerModal').modal('show');
        });

        // Handle form submit
        $('#bannerForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = new FormData(this); 

            $.ajax({
                url: "<?= site_url('/banner/saveBanner') ?>",
                type: "POST",
                data: formData,
                contentType: false, 
                processData: false, 
                success: function(response) {
                    if (response.success) {
                        $('#bannerForm')[0].reset();
                        $("#status").val('').trigger('change.select2');
                        $("#img").attr("src", "");
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#bannerModalLabel").text() === 'Edit Banner') {
                            $('#bannerModal').modal('hide');
                            showAlert('Successfully updated banner');
                        } else {
                            showAlert('Successfully inserted banner');
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
                        $("#bannerModal").scrollTop(0);

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
        $('#bannerTable').on('click', '.editBtn', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "<?= site_url('/banner/getBanner') ?>/" + id,
                type: "GET",
                success: function(response) {
                    $('#bannerModalLabel').text('Edit Banner');
                    $('#id').val(response.id);
                    $("#title").val(response.title);
                    $("#description").val(response.description);
                    $('#status').val(response.status).trigger('change.select2');
                    $('#img_path').val(response.img_path);
                    $("#img").attr("src", "<?= base_url('writable/uploads/') ?>" + response.img_path);
                    
                    $('#bannerModal').modal('show');
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle modal hidden event
        $('#bannerModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
            $("#img").attr("src", "");
            $("#status").val('').trigger('change.select2');
        });

        // Handle modal shown event
        $('#bannerModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        // Initialize Select2 on the select element
        $('#status').select2({
            dropdownParent: $('.container-select2'), // Use the modal as dropdown parent
        });

    });
</script>
<?= $this->endSection('my_script') ?>