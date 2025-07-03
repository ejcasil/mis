<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Resident Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Resident Profile</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Active</a></li>
    </ul>
    <!-- ALERT MESSAGE -->
     <!-- SUCCESS MSG -->
     <script src="<?= base_url(); ?>public/assets/js/alert.js"></script>
    <?php if (session()->has('success')) : ?>
        <script>
            // Show the toast when the page is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Code to execute when the document is ready
                showAlert('<?= session()->getFlashdata("success"); ?>');
            });
        </script>
    <?php endif; ?>
    <!-- ALERT MESSAGE -->


    <div class="info-data">
        <div class="card">
            <div class="head">

                <!-- CONTENT -->
                <a href="<?= base_url(); ?>resident/add" class="btn btn-primary ms-auto mb-3"><i class='bx bx-plus me-2'></i>Add Resident</a>
                <hr class='text-secondary'>
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
                "url": "<?= site_url('/resident/getResidentData') ?>",
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
                        var id = row[0]; // Ensure 'id' is the correct key
                        var household = row[1];
                        var baseUrl = '<?= base_url() ?>'; 

                        // Construct the button group HTML
                        var btn = `
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fi fi-rs-burger-menu'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}resident/edit_household/${id}' role='button'>
                                            <span class='icon-text'>Update details</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class='dropdown-item' href='${baseUrl}resident/view_form/${household}' role='button'>
                                            <span class='icon-text'>View form</span>
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
