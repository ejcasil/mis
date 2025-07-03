<?= $this->extend('resident/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Dashboard</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Resident's Dashboard</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
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
        </div>
    </div>
</main>

<div class="modal fade modal-lg" id="certificateModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificateModalLabel">Create Certification/Clearance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="certificateForm" method="POST">
                <div class="modal-body">
                    <div class="alert alert-danger collapse" role="alert"></div> <!-- Updated for accessibility -->
                    <input type="hidden" id="id" name="id">

                    <div class="row">
                        <div class="col">
                            <div class="container-resident form-group mb-2">
                                <input type="hidden" name="resident" id="resident" value="<?= session()->get('res_id') ?>">
                            </div>
                            <div class="col">
                                <div class="container-document-type form-group mb-2">
                                    <label for="document_type">Document Type</label><span class="text-danger ms-2">*</span>
                                    <select class="form-select" name="document_type" id="document_type" required>
                                        <option value="" selected>Select option</option>
                                        <option value="BC">Barangay Clearance</option>
                                        <option value="BsC">Business Clearance</option>
                                        <option value="CI">Certificate of Indigency</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2 collapse" id="bsc-name-container">
                            <!-- BUSINESS CLEARANCE -->
                            <label>Business Name Registered</label>
                            <input type="text" class="form-control" placeholder="Business Name Registered" name="business_name" id="business_name">

                            <label>Business Location</label>
                            <div class="container-location">
                                <select class="form-select" name="location" id="location">
                                    <option value="" selected>Select option</option>
                                    <?php if (isset($barangays)) : ?>
                                        <?php foreach($barangays AS $row) : ?>
                                            <option value="<?= $row->id ?>"><?= $row->brgy_name ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-floating mb-2">
                            <textarea class="form-control" placeholder="Enter banner description" id="purpose" name="purpose" style="height: 100px" required></textarea>
                            <label for="purpose">Enter purpose</label>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label for="ctc_no">Community Tax No.</label>
                                    <input type="text" class="form-control" name="ctc_no" id="ctc_no" placeholder="Enter your community tax no." required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label for="ctc_date">Date Issued</label>
                                    <input type="text" class="form-control datetimepicker" name="ctc_date" id="ctc_date" placeholder="Enter your community tax no." required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2 collapse">
                            <label for="document_fee">Document Fee</label>
                            <input type="text" class="form-control" name="document_fee" id="document_fee">
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

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {

        let session_success = "<?= session()->getFlashdata('success'); ?>";
        let ok = session_success ? showAlert(session_success) : "";

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
                "url": "<?= site_url('/certification/getCertificateData2') ?>",
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
                        var id = row[0]; 
                        var baseUrl = '<?= base_url() ?>';

                        var file_name = row[7];
                        var file_path = '<?= site_url('certification/download/') ?>' + file_name;
                        var status = row[4];
                        var btn = '';

                        if (status && status == "ISSUED") {
                            btn = file_name ? `
                                <div class='btn-group' role='group'>
                                    <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fi fi-rs-burger-menu'></i>
                                    </button>
                                    <ul class='dropdown-menu'>
                                        <li>
                                            <a href="${file_path}" class='dropdown-item btn btn-transparent btn-sm'>
                                                <span class='icon-text'>Download File</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>` : `<span class="badge rounded-pill text-bg-info">No file</span>`;
                        }

                        return btn; // Return the button HTML to be rendered
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
            // $("#resident").val('').trigger('change.select2');

            $("#certificateModal").modal('show');
        });

        // Handle form submit
        $('#certificateForm').submit(function(e) {
            e.preventDefault();

            // Create a FormData object from the form
            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('/certification/saveCertificate2') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#certificateForm')[0].reset();
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
                url: "<?= site_url('/certification/getCertificate2') ?>/" + userId,
                type: "GET",
                success: function(response) {
                    $('#certificateModalLabel').text('Update Certification/Clearance');
                    $('#id').val(response.id);
                    // $("#resident").val(response.res_id).trigger('change.select2');
                    $("#purpose").val(response.purpose);
                    $("#document_type").val(response.document_type).trigger('change.select2');
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

        // // Payment button 
        // $(document).on("click", ".btn-pay", function(e) {
        //     e.preventDefault();
        //     try {
        //         // GET PAYMENT DETAILS
        //         var id = $(this).data('id');

        //         $.ajax({
        //             url: "<?= site_url('/certification/getDetails') ?>/" + id,
        //             type: "GET",
        //             success: function(response) {
        //                 console.log(response);
        //                 if (response.success) {
        //                     $("#certification-id").val(response.id);
        //                     $("#resident_detail").val(response.resident_name);
        //                     $("#document_type_detail").val(response.document_type);
        //                     $("#document_fee_detail").val(response.document_fee);
        //                 } else {
        //                     $(".alert").html(response.message);
        //                     $(".alert").show();
        //                     $(".alert").fadeOut(8000);
        //                 }
        //                 $("#payModal").modal("show");
        //             },
        //             error: function(xhr, error, thrown) {
        //                 console.error('Error:', error);
        //                 console.error('XHR:', xhr);
        //             }
        //         });
        //     } catch (err) {
        //         console.error(err);
        //     }
        // })

        // // SAVE PAYMENT
        // $("#savePayment").submit(function(e) {
        //     e.preventDefault();

        //     try {
        //          // Create a FormData object from the form
        //     var formData = $(this).serialize();
        //     $.ajax({
        //         url: "<?= site_url('/certification/savePayment') ?>",
        //         type: "POST",
        //         data: formData,
        //         success: function(response) {
        //             console.log(response);
        //             if (response.success) {
        //                 $('#savePayment')[0].reset();
        //                 $('.alert').hide();
        //                 $("#payModal").modal("hide");
        //                 certificateTable.ajax.reload(null, false);
        //             } else {
        //                 $('.alert').html(response.message);
        //                 $('.alert').show();
        //             }
        //         },
        //         error: function(xhr, error, thrown) {
        //             console.error('Error:', error);
        //             console.error('XHR:', xhr);
        //         }
        //     });
        //     } catch (err) {
        //         console.error(err);
        //     }
        // })

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
        // $('#resident').select2({
        //     dropdownParent: $('.container-resident')
        // });

        $('#document_type').select2({
            dropdownParent: $('.container-document-type')
        });


        $('#location').select2({
            dropdownParent: $('.container-location')
        });

    });
</script>
<?= $this->endSection('my_script') ?>