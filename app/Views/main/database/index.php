<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Database Management</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Database Management</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <div class="alert alert-danger collapse"></div>
                <div class="container-fluid bg-white rounded border p-2">
                    <label class="text-secondary unbold">Create Database Backup File</label>
                    <hr class="text-secondary">
                    <a href="<?= base_url(); ?>dbms/backup3" class="btn btn-primary"><i class="fi fi-rs-file-export me-2"></i>Click to Backup database</a>
                </div>

                <div class="container-fluid bg-white rounded border p-2 mt-4">
                    <label class="text-secondary unbold">Restore Database</label>
                    <hr class="text-secondary">
                    <div class="alert alert-info">
                        <strong>Note:</strong>
                        <span>Please be informed that once you clicked restore, the backup file selected will be loaded or imported to the system database.</span>
                    </div>
                    <form id="restoreForm" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <label>Select Backup file</label>
                                <input type="file" class="form-control" name="backup_file" required>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                <br>
                                <button type="submit" class="btn btn-primary"><i class="fi fi-rs-time-past me-2"></i>Restore Database</button>
                            </div>
                        </div>
                    </form>
                </div>
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
        // Handle form submit
        $('#restoreForm').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "<?= site_url('/dbms/restore3') ?>",
                type: "POST",
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                contentType: false, // Prevent jQuery from setting the Content-Type header
                success: function(response) {
                    if (response.success) {
                        $("#restoreForm")[0].reset();
                        showAlert('Database restoration successful');
                    } else {
                        var errorMessages = '<p>' + response.errors + '</p>';
                        $('.alert').html(errorMessages);
                        $('.alert').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                    $('.alert').html('<p>An unexpected error occurred. Please try again later.</p>');
                    $('.alert').show();
                }
            });
        });
    });
</script>
<?= $this->endSection('my_script') ?>