<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Activity Log</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Activity Log</a></li>
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
                                    <label>Username</label>
                                    <select class="form-select" name="username" id="username">
                                        <option value="" selected>Select Username</option>
                                        <?php if ($users) : ?>
                                            <?php foreach ($users as $user) : ?>
                                                <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                    <label>From</label>
                                    <input type="text" class="form-control datetimepicker" id="date-from" name="date-from" placeholder="mm-dd-yyyy">
                                </div>
                                <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                    <label>To</label>
                                    <input type="text" class="form-control datetimepicker" id="date-to" name="date-to" placeholder="mm-dd-yyyy">
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                    <label>&nbsp;</label>
                                    <div class="d-flex justify-content-start gap-1">
                                        <button type="submit" class="btn btn-primary filter-activity"><i class='bx bx-filter-alt me-2'></i>Filter Record</button>
                                        <button type="button" class="btn btn-light download-activity"><i class='bx bx-export me-2'></i>Export Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <hr class="text-secondary">
                    <table id="table-activity" class="table table-striped table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th class="col-1">Username</th>
                                <th class="col-2">Name</th>
                                <th class="col-6">Activity</th>
                                <th class="col-1">Role</th>
                                <th class="col-2">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- CONTENT -->
            </div>



        </div>
    </div>
    </div>
</main>
<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {
        /* date time picker */
        function enable_datepicker() {
            flatpickr(".datetimepicker", {
                dateFormat: "m-d-Y", // Set date format
                enableTime: false, // Enable time selection
            });
        }
        enable_datepicker();

        // Initialize the DataTable
        var table = $('#table-activity').DataTable({
            "ajax": {
                "url": "<?= site_url('/activity/getActivities3') ?>",
                "type": "GET",
                "dataSrc": "data", // Ensure this matches the server response structure
                "error": function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            },
            "columns": [{
                    "data": 0 // Username
                },
                {
                    "data": 1 // Name
                },
                {
                    "data": 2 // Activity
                },
                {
                    "data": 3 // Role
                },
                {
                    "data": 4 // Date & Time
                }

            ]
        });
        // Initialize select2
        $('.form-select').select2();
        // Filter Record Form
        $('#filterRecordForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            $.ajax({
                url: "<?= site_url('/activity/filter3') ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    table.clear().rows.add(response.data).draw();
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            });
        });
        $(document).on("click", ".download-activity", function() {
            let data = {
                username: $("#username").val(),
                from: $("#date-from").val(),
                to: $("#date-to").val(),
            };

            $.ajax({
                url: "<?= site_url('/activity/download3') ?>",
                method: "POST",
                data: data,
                xhrFields: {
                    responseType: "blob", // Set the response type to Blob
                },
                success: function(res) {
                    // Create a blob object from the response
                    var blob = new Blob([res], {
                        type: "text/csv"
                    });

                    // Create a temporary link and trigger the download
                    var link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "activity_data.csv"; // Specify the file name
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function(err) {
                    console.error(err);
                },
            });
        });
    })
</script>
<?= $this->endSection('my_script') ?>