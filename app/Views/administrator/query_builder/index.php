<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Query Builder</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Query Builder</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <!-- CONTENT -->
                <div class="table-responsive">
                    <div class="my-2">
                        <form id="filterRecordForm">
                            <div class="row mb-2">
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Household ID</label>
                                    <input type="text" class="form-control" id="household_id" name="household_id" placeholder="Household ID">
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>Resident ID</label>
                                    <input type="text" class="form-control" id="resident_id" name="resident_id" placeholder="Resident ID">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <label>&nbsp;</label>
                                    <div class="d-flex justify-content-start gap-1">
                                        <button type="submit" class="btn btn-primary" id="btnFilter"><i class='bx bx-filter-alt me-2'></i>Filter Record</button>
                                        <button type="button" class="btn btn-light" id="btnMultiQuery"><i class='bx bx-filter me-2'></i>Show Multi-query</button>
                                        <button type="button" class="btn btn-light" id="btnExport"><i class='bx bx-export me-2'></i>Export Data</button>
                                        <button type="button" class="btn btn-light" id="btnCompose"><i class='bx bx-message-dots me-2'></i>Compose Message</button>
                                    </div>
                                </div>
                            </div>
                            <div class="container-multi collapse">
                                <!-- lname,fname,mname,suffix -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control" name="lname" id="lname" placeholder="Enter your last name">
                                    </div>
                                    <div class="col">
                                        <label for="fname">First Name</label>
                                        <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter your first name">
                                    </div>
                                    <div class="col">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" class="form-control" name="mname" id="mname" placeholder="Enter your middle name">
                                    </div>
                                    <div class="col">
                                        <label for="suffix">Suffix</label>
                                        <input type="text" class="form-control" name="suffix" id="suffix" placeholder="Enter your suffix">
                                    </div>
                                </div>
                                <!-- bday,bplace,gender,cstatus -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label>Age bracket</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="number" name="ageFrom" id="ageFrom" class="form-control" placeholder="0">To<input type="number" name="ageTo" id="ageTo" class="form-control" placeholder="10">
                                        </div>

                                    </div>
                                    <div class="col">
                                        <label for="bplace">Birthplace</label>
                                        <input type="text" class="form-control" name="bplace" id="bplace" placeholder="Enter your birthplace">
                                    </div>
                                    <div class="col">
                                        <label for="gender">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="" selected>Select option</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="cstatus">Civil Status</label>
                                        <select class="form-select" name="cstatus" id="cstatus">
                                            <option value="" selected>Select option</option>
                                            <?php if (isset($cstatus)) : ?>
                                                <?php foreach ($cstatus as $row) : ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- educ,course,rel,occ -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="education">Educational Attainment</label>
                                        <select class="form-select" id="education" name="education">
                                            <option value="" selected>Select option</option>
                                            <?php if (isset($educ)) : ?>
                                                <?php foreach ($educ as $row) : ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="course">Course</label>
                                        <select class="form-select" id="course" name="course">
                                            <option value="" selected>Select option</option>
                                            <?php if (isset($course)) : ?>
                                                <?php foreach ($course as $row) : ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="religion">Religion</label>
                                        <select class="form-select" id="religion" name="religion">
                                            <option value="" selected>Select option</option>
                                            <?php if (isset($rel)) : ?>
                                                <?php foreach ($rel as $row) : ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="occupation">Occupation</label>
                                        <select class="form-select" id="occupation" name="occupation">
                                            <option value="" selected>Select option</option>
                                            <?php if (isset($occ)) : ?>
                                                <?php foreach ($occ as $row) : ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- philhealth_no,m_income,cp,email -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="philhealth">Philhealth No.</label>
                                        <input type="text" class="form-control" id="philhealth" name="philhealth" placeholder="Enter your philhealth no.">
                                    </div>
                                    <div class="col">
                                        <label for="monthly_income">Monthly Income bracket</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input type="number" class="form-control" name="m_income_from" id="m_income_from" placeholder="0">To<input type="number" class="form-control" name="m_income_to" id="m_income_to" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label for="cp">Contact No.</label>
                                        <input type="text" class="form-control" id="cp" name="cp" placeholder="Enter your contact no.">
                                    </div>
                                    <div class="col">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
                                    </div>
                                </div>
                                <!-- nstatus,btype,height,weight -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="nstatus">Nutritional Status</label>
                                        <select class="form-select select2" id="nstatus" name="nstatus">
                                            <option value="" selected>Select option</option>
                                            <option value="N">Normal</option>
                                            <option value="UW">Underweight</option>
                                            <option value="SeUW">Severely Underweight</option>
                                            <option value="St">Stunted</option>
                                            <option value="SeSt">Severely Stunted</option>
                                            <option value="MW">Moderately Wasted</option>
                                            <option value="SeW">Severely Wasted</option>
                                            <option value="OW">Overweight</option>
                                            <option value="O">Obese</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="btype">Blood type</label>
                                        <select class="form-select select2" name="btype" id="btype">
                                            <option value="" selected>Select option</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="height">Height (cm)</label>
                                        <input type="number" class="form-control" id="height" name="height" placeholder="Enter your height">
                                    </div>
                                    <div class="col">
                                        <label for="weight">Weight (kg)</label>
                                        <input type="number" class="form-control" id="weight" name="weight" placeholder="Enter your weight">
                                    </div>
                                </div>
                                <!-- Brgy,purok-zone,street,house_no -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label for="barangay">Barangay</label>
                                        <select class="form-select" name="barangay" id="barangay">
                                            <?php if (isset($barangay)) : ?>
                                                <option value="<?= $barangay->id ?>" selected><?= $barangay->brgy_name ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="purok">Purok/Zone</label>
                                        <select class="form-select" name="purok" id="purok">
                                            <option value="" selected>Select option</option>
                                            <?php if (isset($purok)) : ?>
                                                <?php foreach ($purok as $row) : ?>
                                                    <option value='<?= $row->id; ?>'><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="street">Street</label>
                                        <input type="text" class="form-control" id="street" name="street" placeholder="Enter your street name">
                                    </div>
                                    <div class="col">
                                        <label for="house_no">House No.</label>
                                        <input type="text" class="form-control" id="house_no" name="house_no" placeholder="Enter your house no.">
                                    </div>
                                </div>
                                <!-- TABLES -->
                                <div class="row mb-2">
                                    <div class="col">
                                        <label>Training/Skills</label>
                                        <select id="training" name="training[]" multiple size="100">
                                            <?php if (isset($training)): ?>
                                                <?php foreach ($training as $row): ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Gov't Programs/Assistance Availed</label>
                                        <select id="gprograms" name="gprograms[]" multiple size="100">
                                            <?php if (isset($gprograms)): ?>
                                                <?php foreach ($gprograms as $row): ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Disability</label>
                                        <select id="disability" name="disability[]" multiple size="100">
                                            <?php if (isset($disability)): ?>
                                                <?php foreach ($disability as $row): ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label>Comorbidity</label>
                                        <select id="comorbidity" name="comorbidity[]" multiple size="100">
                                            <?php if (isset($comor)): ?>
                                                <?php foreach ($comor as $row): ?>
                                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <hr class="text-secondary">
                    <table id="table_query" class="table table-striped table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th class="col-2">Resident ID</th>
                                <th class="col-2">Household ID</th>
                                <th class="col-2">Name</th>
                                <th class="col-2">Gender</th>
                                <th class="col-2">Age</th>
                                <th class="col-2">Contact No.</th>
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

<?= $this->include('administrator/include/compose-msg'); ?>
<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>

<script>
    $(document).ready(function() {
        let filteredData;

        // EXPORT DATA
        $(document).on("click", "#btnExport", function() {
            if (!filteredData || filteredData.length === 0) {
                alert("No data to export");
                return;
            }

            $.ajax({
                url: "<?= site_url('/query_builder/export_data') ?>", // Send data to the server
                type: "POST",
                data: {
                    filteredData: JSON.stringify(filteredData) // Stringify the array
                },
                success: function(response) {
                    // Create an invisible link to trigger the file download
                    var link = document.createElement('a');
                    link.style.display = 'none';

                    // Create a Blob from the CSV data
                    var blob = new Blob([response], {
                        type: 'application/csv'
                    });
                    link.href = URL.createObjectURL(blob);
                    link.download = 'export_data_query_builder.csv'; // Set the filename for the CSV file

                    // Append the link to the document body
                    document.body.appendChild(link);

                    // Trigger the click event to download the CSV file
                    link.click();

                    // Clean up the DOM by removing the link
                    document.body.removeChild(link);
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });

        // SHOW COMPOSE MESSAGE MODAL
        $(document).on("click", "#btnCompose", function() {
            if (!filteredData || filteredData.length === 0) {
                alert("No recipients found");
                return;
            }

            $("#compose-msg").modal("show");
        })

        // SEND MESSAGE
        $("#messageForm").submit(function(e) {
            e.preventDefault(); // Prevent the default form submission behavior

            var $submitButton = $(this).find('button[type="submit"]');
            var originalButtonText = $submitButton.html();
            $submitButton.prop('disabled', true);
            $submitButton.html('<i class="fa fa-spinner fa-spin"></i> Sending...');

            $.ajax({
                url: "<?= site_url('/query_builder/sendMessage') ?>", // Send data to the server
                type: "POST",
                data: {
                    message: $("#message").val().trim(), // Serialize the form data
                    filteredData: JSON.stringify(filteredData) // Send the filtered data as a JSON string
                },
                success: function(response) {
                    // Reset the button after success
                    console.log(response.details);
                    $submitButton.prop('disabled', false);
                    $submitButton.html(originalButtonText);
                    //console.log(response); // Log the response from the server      
                    $submitButton.prop('disabled', true); // Disable the button after success
                    $submitButton.html(`<i class='bx bx-check-circle' ></i> Sent`);

                    // After 3 seconds, revert the button text to the original text and enable the button again
                    setTimeout(function() {
                        $submitButton.prop('disabled', false); // Enable the button again
                        $submitButton.html(originalButtonText); // Restore the original button text
                    }, 3000); 
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                    $submitButton.prop('disabled', false);
                    $submitButton.html(originalButtonText);
                }
            });
        });

        // // ========== datetimepicker ========== //
        // function enable_datepicker() {
        //     flatpickr(".datetimepicker", {
        //         dateFormat: "m-d-Y", // Set date format
        //         enableTime: false, // Enable time selection
        //     });
        // }
        // enable_datepicker();

        // function showSuccess(message) {
        //     $(".alert-success").html(`<i class='bx bxs-check-circle me-2'></i>${message}.`);
        //     $(".alert-success").show();
        //     $(".alert-success").fadeOut(8000);
        // }

        // function showDanger(message) {
        //     $(".alert-danger").html(`<i class='bx bxs-error me-2' ></i>${message}.`);
        //     $(".alert-danger").show();
        //     $(".alert-danger").fadeOut(8000);
        // }

        // Initialize the DataTable
        var myTable = $('#table_query').DataTable({
            "ajax": {
                "url": "<?= site_url('/query_builder/getDefaultData') ?>",
                "type": "GET",
                "dataSrc": "data", // Ensure this matches the server response structure
                "error": function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            },
            "columns": [{
                    "data": 0
                }, // resident id
                {
                    "data": 1
                }, // household id
                {
                    "data": 2
                }, // resident name
                {
                    "data": 3
                }, // gender
                {
                    "data": 4
                }, // age
                {
                    "data": 5
                } // cp

            ],
            // Capture data after every draw (or refresh)
            "drawCallback": function(settings) {
                // Check if the table is initialized correctly
                if ($('#table_query').DataTable()) {
                    // Get the table instance
                    var table = $('#table_query').DataTable();

                    // Ensure the table is fully initialized before accessing the rows
                    if (table) {
                        // Get the filtered data (rows based on current search/filter)
                        filteredData = table.rows().data().toArray();

                        // You can now use the filteredData array for any further operations
                        // console.log(filteredData);
                    } else {
                        console.error("Table not initialized properly in drawCallback.");
                    }
                } else {
                    console.error("DataTable not initialized yet.");
                }
            }
        });

        console.log("Document ready, initializing Chosen...");
        $("#training").chosen({
            width: "100%"
        });
        $("#gprograms").chosen({
            width: "100%"
        });
        $("#disability").chosen({
            width: "100%"
        });
        $("#comorbidity").chosen({
            width: "100%"
        });

        $("#filterRecordForm").submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            var $submitButton = $(this).find('button[type="submit"]');
            var originalButtonText = $submitButton.html();
            $submitButton.prop('disabled', true);
            $submitButton.html('<i class="fa fa-spinner fa-spin"></i> Filtering...');


            $.ajax({
                url: "<?= site_url('/query_builder/filter') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    // Save filtered data to my variable = "$filteredData";
                    filteredData = response.data;
                    // console.log($filteredData);
                    // Reset the button after success
                    $submitButton.prop('disabled', false);
                    $submitButton.html(originalButtonText);
                    // Check if the response is valid and contains the necessary data
                    if (response.data) {
                        // Update the DataTable with the filtered data
                        myTable.clear().rows.add(response.data).draw(); // Clear the existing table data, add the new data, and redraw the table
                    } else {
                        // If there's no data, you can optionally show a message to the user
                        console.error("No data received or the response is invalid.");
                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                    $submitButton.prop('disabled', false);
                    $submitButton.html(originalButtonText);
                    // showDanger("Something went wrong. Please try again later.");
                }
            });
        })


        // $("#messageForm").submit(function(e) {
        //     e.preventDefault();

        //     var formData = $(this).serialize();

        //     var $submitButton = $(this).find('button[type="submit"]');
        //     var originalButtonText = $submitButton.html();
        //     $submitButton.prop('disabled', true);
        //     $submitButton.html('<i class="fa fa-spinner fa-spin"></i> Loading...');


        //     $.ajax({
        //         url: "<?= site_url('/message/send') ?>",
        //         type: "POST",
        //         data: formData,
        //         success: function(response) {
        //             if (response.success) {
        //                 console.log(response);
        //                 // Reset the button after success
        //                 $submitButton.prop('disabled', false);
        //                 $submitButton.html(originalButtonText);

        //                 //clear the form or show a success message
        //                 $("#messageForm")[0].reset();
        //                 $("#recipients").trigger('chosen:updated');
        //                 showSuccess("Message sent successfully!");
        //             } else {
        //                 showDanger(response.error);
        //             }

        //         },
        //         error: function(xhr, error, thrown) {
        //             console.error('Error:', error);
        //             console.error('XHR:', xhr);
        //             $submitButton.prop('disabled', false);
        //             $submitButton.html(originalButtonText);
        //             showDanger("Something went wrong. Please try again later.");
        //         }
        //     });
        // });

        // Show multi-query
        $(document).on("click", "#btnMultiQuery", () => {
            $(".container-multi").slideToggle("down");
        })
    });
</script>
<?= $this->endSection('my_script') ?>