<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Certification / Clearances</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Certification / Clearances</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->

                <button class="btn btn-primary ms-auto mb-3" id="addCertificateBtn"><i class='bx bx-plus me-2'></i>Create Certificate / Clearance</button>
                <hr class='text-secondary'>

                <table id="certificateTable" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date requested</th>
                            <th>Control No</th>
                            <th>Resident Name</th>
                            <th>Document Type</th>
                            <th>Application Status</th>
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


<?= $this->include('administrator/include/certificateModal'); ?>

<?= $this->include('administrator/include/pay-modal'); ?>
<!-- UPLOAD DOCUMENT -->
<?= $this->include('include/upload-document'); ?>

<?= $this->endSection('content') ?>

<?= $this->section('additional_dropzone') ?>
<script>
Dropzone.autoDiscover = false;  // Disable auto-discovery, we initialize it manually

const myDropzone = new Dropzone("#myDropzone", {
    url: "<?= site_url('certification/upload_file') ?>",  // The endpoint where files will be uploaded
    paramName: "file",  // The name for the uploaded file (it will be used on the server side)
    maxFilesize: 1,  // Maximum file size in MB
    acceptedFiles: "image/*",  // Allowed file types
    addRemoveLinks: true,  // Allow users to remove files before upload
    dictDefaultMessage: "",
    dictFallbackMessage: "Your browser doesn't support drag and drop file uploads.",
    dictInvalidFileType: "You cannot upload files of this type.",
    dictFileTooBig: "File is too big. Max size: 5MB",
    dictResponseError: "Server responded with {{statusCode}} code.",
    init: function() {
        // Triggered when a file is successfully uploaded
        this.on("success", function(file, response) {
            showAlert("Successfully uploaded file");
            $("#upload-document").modal("hide");
            // Reset Dropzone and remove all files
            myDropzone.removeAllFiles(true);
        });

        // Triggered when the upload fails
        this.on("error", function(file, errorMessage) {
            console.error("Upload error:", errorMessage);
        });
    }
});
</script>
<?= $this->endSection('additional_dropzone') ?>

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
        var certificateTable = $('#certificateTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/certification/getCertificateData') ?>",
                "type": "GET",
                "dataSrc": "data", // Ensure this matches the server response structure
                "error": function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            },
            "columns": [{
                    "data": 6
                }, // date
                {
                    "data": 5
                }, // Control Number
                {
                    "data": 1
                }, // resident name
                {
                    "data": 2
                }, // document type
                {
                    "data": 3
                }, // application status
                {
                    "data": 4
                }, // status
                {
                    "data": null,
                    "render": function(data, type, row) {
                        // Construct the URLs using a template literal for better readability
                        var id = row[0]; 
                        var baseUrl = '<?= base_url() ?>'; 
                        var status = row[4];
                        var hasFile = row[7];
                        var view_file = '<?= site_url('certification/viewFile/') ?>' + hasFile;
                        var btn = '';
                        if (status && status == "FOR PAYMENT") {
                            btn = `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <button type='button' class='dropdown-item btn btn-transparent btn-sm editBtn' data-id='${id}'>
                                            </i><span class='icon-text'>Update</span>
                                        </button>
                                        <button type='button' class='dropdown-item btn btn-transparent btn-sm btn-pay' data-id='${id}'>
                                            </i><span class='icon-text'>Pay</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        `;
                        } else if (status && status == "FOR ISSUANCE") {
                            btn = `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <button type='button' class='dropdown-item btn btn-transparent btn-sm editBtn' data-id='${id}'>
                                            </i><span class='icon-text'>Update</span>
                                        </button>
                                        <a class='dropdown-item' href='${baseUrl}certification/issue/${id}' role='button'>
                                            <span class='icon-text'>Issue</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `;
                        } else if (status && status == "ISSUED") {
                            btn = hasFile ? `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}certification/print/${id}' role='button'>
                                            <span class='icon-text'>Print</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href='${view_file}' target='_blank' class='dropdown-item btn btn-transparent btn-sm'>
                                            View File
                                        </a>
                                    </li>
                                    <li>
                                        <button type='button' class='dropdown-item btn btn-transparent btn-sm uploadBtn' data-id='${id}'>
                                            <span class='icon-text'>Re-Upload File</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        ` : `<div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}certification/print/${id}' role='button'>
                                            <span class='icon-text'>Print</span>
                                        </a>
                                    </li>
                                    <li>
                                        <button type='button' class='dropdown-item btn btn-transparent btn-sm uploadBtn' data-id='${id}'>
                                            <span class='icon-text'>Upload File</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>`;
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


        $('#addCertificateBtn').click(function() {
            $('#certificateModalLabel').text('Create Certification/Clearance');
            $('#certificateForm')[0].reset();
            $('#id').val('');
            $("#document_type").val('').trigger('change.select2');
            $("#resident").val('').trigger('change.select2');

            $("#certificateModal").modal('show');
        });

        // Handle form submit
        $('#certificateForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('/certification/saveCertificate') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#certificateForm')[0].reset();
                        $("#resident").val('').trigger('change.select2');
                        $("#document_type").val('').trigger('change.select2');
                        $('.alert').hide();
                        // hide modal if updated 
                        if ($("#certificateModalLabel").text() === 'Update Certification/Clearance') {
                            $('#certificateModal').modal('hide');
                            showAlert('Successfully updated certification/clearance');
                        } else {
                            showAlert('Successfully inserted certification/clearance');
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
                    certificateTable.ajax.reload(null, false); // Reload DataTable without resetting pagination
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Handle Edit button click
        $('#certificateTable').on('click', '.editBtn', function() {
            var userId = $(this).data('id');

            $.ajax({
                url: "<?= site_url('/certification/getCertificate') ?>/" + userId,
                type: "GET",
                success: function(response) {
                    $('#certificateModalLabel').text('Update Certification/Clearance');
                    $('#id').val(response.id);
                    $("#resident").val(response.res_id).trigger('change.select2');
                    $("#business_name").val(response.business_name);
                    $("#purpose").val(response.purpose);
                    $("#document_type").val(response.document_type).trigger('change.select2');
                    $("#document_type").change();
                    $("#ctc_no").val(response.ctc_no);
                    $("#ctc_date").val(response.ctc_date);
                    $("#document_fee").val(response.document_fee);
                    $('#certificateModal').modal('show');
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // Document type changed
        $(document).on("change", "#document_type", function() {
            try {
                let document_type = $(this).val().trim();

                //CHECK IF THE SELECTED DOCUMENT TYPE IS BUSINESS CLEARANCE
                if (document_type === "BsC") {
                    $("#bsc-name-container").show();
                } else {
                    $("#bsc-name-container").hide();
                }

                if (document_type) {
                    $.ajax({
                        url: "<?= site_url('/certification/getDocumentFee') ?>/" + document_type,
                        type: "GET",
                        success: function(response) {
                            if (response.success) {
                                $("#document_fee").val(response.fee);
                            }
                        },
                        error: function(xhr, error, thrown) {
                            console.error('Error:', error);
                            console.error('XHR:', xhr);
                        }
                    })
                }
            } catch (err) {
                console.error(err);
            }
        })

        // Payment button 
        $(document).on("click", ".btn-pay", function(e) {
            e.preventDefault();
            try {
                // GET PAYMENT DETAILS
                var id = $(this).data('id');

                $.ajax({
                    url: "<?= site_url('/certification/getDetails') ?>/" + id,
                    type: "GET",
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            $("#certification-id").val(response.id);
                            $("#resident_detail").val(response.resident_name);
                            $("#document_type_detail").val(response.document_type);
                            $("#document_fee_detail").val(response.document_fee);
                        } else {
                            $(".alert").html(response.message);
                            $(".alert").show();
                            $(".alert").fadeOut(8000);
                        }
                        $("#payModal").modal("show");
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error:', error);
                        console.error('XHR:', xhr);
                    }
                });
            } catch (err) {
                console.error(err);
            }
        })

        // SAVE PAYMENT
        $("#savePayment").submit(function(e) {
            e.preventDefault();

            try {
                 // Create a FormData object from the form
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('/certification/savePayment') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#savePayment')[0].reset();
                        $('.alert').hide();
                        $("#payModal").modal("hide");
                        certificateTable.ajax.reload(null, false);
                    } else {
                        $('.alert').html(response.message);
                        $('.alert').show();
                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
            } catch (err) {
                console.error(err);
            }
        })

        // UPLOAD DOCUMENT
        $(document).on("click", ".uploadBtn", function (e) {
            $("#upload-document").modal('show');
            $("#document_id").val($(this).attr("data-id"));
        })

        // Handle modal hidden event
        $('#certificateModal').on('hidden.bs.modal', function() {
            // Hide all alerts and destroy Select2
            $('.alert').hide();
        });

        // Handle modal shown event
        $('#certificateModal').on('shown.bs.modal', function() {
            // Reinitialize Select2
            $('.alert').hide();
        });

        // Initialize Select2 on the select element
        $('#resident').select2({
            dropdownParent: $('.container-resident')
        });

        $('#document_type').select2({
            dropdownParent: $('.container-document-type')
        });

        $('#location').select2({
            dropdownParent: $('.container-location')
        });


    });
</script>
<?= $this->endSection('my_script') ?>