<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Resident Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Resident Profile</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">For Approval</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">

                <!-- CONTENT -->
                <!-- Resident Table -->
                <table id="residentTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Household ID</th>
                            <th>Household Head</th>
                            <th>Civil Status</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
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

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {

        // Initialize the DataTable
        var table = $('#residentTable').DataTable({
            "ajax": {
                "url": "<?= site_url('/resident/getListOfForApproval') ?>",
                "type": "GET",
                "dataSrc": "data", // Ensure this matches the server response structure
                "error": function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            },
            "columns": [
                {
                    "data": 1
                }, // Household ID
                {
                    "data": 2
                }, // Household Head
                {
                    "data": 3
                }, // Civil Status
                {
                    "data": 4
                }, // Age
                {
                    "data": 5
                }, // Status
                {
                    "data": null,
                    "render": function(data, type, row) {
                        // Construct the URLs using a template literal for better readability
                        var id = row[1]; // HOUSEHOLD ID
                        var baseUrl = '<?= base_url() ?>'; // Assuming PHP is used for base URL

                        // Construct the button group HTML
                        var btn = `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}resident/view_approval/${id}' role='button'>
                                            <span class='icon-text'>View</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `;

                        return btn;
                    }
                }
            ]
        });

    });
</script>
<?= $this->endSection('my_script') ?>