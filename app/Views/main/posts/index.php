<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Posts</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Posts</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->
                <button class="btn btn-primary ms-auto mb-3" id="addPostBtn"><i class='bx bx-plus me-2'></i>Add Post</button>
                <hr class='text-secondary'>
                <!-- Table -->
                <table id="postTable" class="table table-striped table-bordered table-hover" style="width:100%">
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

<div class="modal fade modal-lg" id="postModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Add Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="postForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                    <input type="hidden" id="id" name="id">

                    <div class="form-group mb-2">
                        <label for="title">Title</label><span class="text-danger ms-2">*</span>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter post title" required>
                    </div>

                    <div class="form-floating mb-2">
                        <textarea class="form-control" placeholder="Enter post description" id="description" name="description" style="height: 100px"></textarea>
                        <label for="description">Enter post description</label>
                    </div>

                    <div class="form-group category-container mb-2">
                        <label for="status">Category</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="category" id="category" style="width:100%" required>
                           <option value="" selected>Select option</option>
                          <?php if ($categories): ?>
                            <?php foreach($categories AS $category): ?>
                                <option value="<?= $category->id ?? '' ?>"><?= $category->description ?? '' ?></option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group status-container mb-2">
                        <label for="status">Status</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="status" id="status" style="width:100%" required>
                           <option value="" selected>Select option</option>
                           <option value="ACTIVE">ACTIVE</option>
                           <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="image">Image</label><span class="text-danger ms-2">*</span>
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
        var table = $('#postTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/post/getPostData') ?>",
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
        $('#addPostBtn').click(function() {
            $('#postModalLabel').text('Add Post');
            $('#postForm')[0].reset();+
            $('#img').attr('src', '');
            $('#id').val('');
            $('#postModal').modal('show');
        });

        // Handle form submit
        $('#postForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = new FormData(this); // 'this' refers to the form element

            $.ajax({
                url: "<?= site_url('/post/savePost') ?>",
                type: "POST",
                data: formData,
                contentType: false, // Important: Prevent jQuery from setting content type
                processData: false, // Important: Prevent jQuery from processing the data
                success: function(response) {
                    if (response.success) {
                        $('#postForm')[0].reset();
                        $("#img").attr("src", "");
                        $("#status").val('').trigger('change.select2');
                        $("#category").val('').trigger('change.select2');
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#postModalLabel").text() === 'Edit Post') {
                            $('#postModal').modal('hide');
                            showAlert('Successfully updated post');
                        } else {
                            showAlert('Successfully inserted post');
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
                        $("#postModal").scrollTop(0);

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
        $('#postTable').on('click', '.editBtn', function() {
            var id = $(this).data('id');

            $.ajax({
                url: "<?= site_url('/post/getPost') ?>/" + id,
                type: "GET",
                success: function(response) {
                    $('#postModalLabel').text('Edit Post');
                    $('#id').val(response.id);
                    $("#title").val(response.title);
                    $("#description").val(response.description);
                    $('#category').val(response.category_id).trigger('change.select2');
                    $('#status').val(response.status).trigger('change.select2');
                    $('#img_path').val(response.img_path);
                    $("#img").attr("src", "<?= base_url('writable/uploads/') ?>" + response.img_path);
                    
                    $('#postModal').modal('show');
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle modal hidden event
        $('#postModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
            $("#img").attr("src", "");
            $("#status").val('').trigger('change.select2');
            $("#category").val('').trigger('change.select2');
        });

        // Handle modal shown event
        $('#postModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        // Initialize Select2 on the select element
        $('#status').select2({
            dropdownParent: $('.status-container'), // Use the modal as dropdown parent
        });

        $('#category').select2({
            dropdownParent: $('.category-container'), // Use the modal as dropdown parent
        });

    });
</script>
<?= $this->endSection('my_script') ?>