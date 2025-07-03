<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<div class="modal fade" id="import-file" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg">
      <div class="modal-header border-0 d-flex justify-content-between">
        <h5 class="modal-title" id="staticBackdropLabel">Import GeoJSON File</h5>
        <a href="<?= base_url('maps'); ?>" class="btn-close" aria-label="Close"></a>
      </div>
      <div class="modal-body d-flex justify-content-center align-items-center flex-column">
        <div class="container p-2 border-dashed">
          <form id="geoForm" method="post" enctype="multipart/form-data">
            <div class="d-flex justify-content-center align-items-center"
              id="dragArea"
              style="border: 2px dashed #ddd; padding: 20px; width: 100%; text-align: center; cursor: pointer;">
              <img src="<?= base_url('public/assets/images/file.png') ?>" alt="upload" class="img-fluid w-50 h-50" id="imgUpload">
              <p>Drag and drop files here or click to select files</p>
            </div>
            <input type="file" class="collapse" id="fileUpload" name="fileUpload[]" multiple />

            <div class="m-1">
              <label id="uploadCount"></label>
            </div>

            <div class="d-flex justify-content-between align-items-center gap-1 mt-2">
              <a href="<?= base_url('maps'); ?>" class="btn btn-light w-100">Cancel</a>
              <button type="submit" class="btn btn-primary w-100">Upload</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('my_script') ?>
<script>

  $(document).ready(function() {

    const $dragArea = $('#dragArea');
    const $fileInput = $('#fileUpload');

    // Trigger the file input when the drag area is clicked
    $dragArea.on('click', function() {
      $fileInput.click();
    });

    // Change border color when files are dragged over the area
    $dragArea.on('dragover', function(e) {
      e.preventDefault();
      $dragArea.css('border-color', '#5cb85c'); // Green border when dragging over
    });

    // Reset border color when drag leaves the area
    $dragArea.on('dragleave', function() {
      $dragArea.css('border-color', '#ddd'); // Reset to default border color
    });

    // Handle file drop
    $dragArea.on('drop', function(e) {
      e.preventDefault();
      $dragArea.css('border-color', '#ddd'); // Reset the border color after drop

      const files = e.originalEvent.dataTransfer.files; // Get the dropped files
      $fileInput[0].files = files; // Assign the dropped files to the input

      // Trigger change event to update the file count
      $fileInput.trigger('change');
    });

    // Update file count on file selection or drop
    $fileInput.on('change', function() {
      const fileCount = $fileInput[0].files.length;
      $('#uploadCount').text('Number of files selected: ' + fileCount);
    });

    $("#geoForm").submit(function(event) {
      event.preventDefault(); // Prevent the form from submitting the default way

      var formData = new FormData(this); // Collect the form data, including the files

      $.ajax({
        url: "<?= base_url('maps/import/geoJSON') ?>", // URL for the POST request
        method: "POST", // POST method
        data: formData, // Send the form data (including files)
        processData: false, // Don't process the data
        contentType: false, // Don't set content type
        success: function(res) {
          // Check if status is true
          if (res.status) {
            // Access and display the message from the response
            $("#uploadCount").html('');
            $("#geoForm")[0].reset();
            showAlert(res.message); // Show success message

          } else {
            // If status is false, show an error message
            showAlert('Error: ' + res.message);
          }
        },
        error: function(err) {
          // Handle any errors that occur during the request
          console.error("Error:", err);
          showAlert("An error occurred during the upload.");
        }
      });
    });


    $("#import-file").modal("show");
  })
</script>
<?= $this->endSection('my_script') ?>