<div class="modal fade" id="upload-document" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow-lg">
      <div class="modal-header border-0 d-flex justify-content-between">
        <h5 class="modal-title" id="staticBackdropLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex justify-content-center align-items-center flex-column">
        <!-- Dropzone Form -->
        <form action="<?= site_url('upload') ?>" class="dropzone bg-light border-2 border-primary rounded p-4" id="myDropzone" enctype="multipart/form-data">
            <input type="hidden" name="document_id" id="document_id">
            <div class="d-flex flex-column align-items-center">
                <i class="bx bx-image-alt mb-3 text-primary" style="font-size: 5rem;"></i>
                <span class="text-muted">Drag and drop your file here or click to select a file</span>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>