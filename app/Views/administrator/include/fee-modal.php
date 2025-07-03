
<div class="modal fade" id="feeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Document Fees</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url(); ?>administrator/update_document_fees" method="POST">
            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>Barangay Clearance</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="BC-fee" id="BC-fee" value="<?= $document_fee['bc_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>Business Clearance</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="BsC-fee" id="BsC-fee" value="<?= $document_fee['bsc_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>Certificate of Indigency</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="CI-fee" id="CI-fee" value="<?= $document_fee['ci_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>One & Same Person</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="OSP-fee" id="OSP-fee" value="<?= $document_fee['osp_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>Poor Health Condition</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="PHC-fee" id="PHC-fee" value="<?= $document_fee['phc_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>Person w/ Disability</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="PWD-fee" id="PWD-fee" value="<?= $document_fee['pwd_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>House/Shelter Burn-out</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="HB-fee" id="HB-fee" value="<?= $document_fee['hb_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="row border-bottom p-2">
                <div class="col-8">
                    <label>House/Shelter Damaged by Typhoon</label>
                </div>
                <div class="col-4">
                <input type="number" class="form-control text-end" name="HDT-fee" id="HDT-fee" value="<?= $document_fee['hdt_fee'] ?? "0"; ?>" placeholder="0" required>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-2 gap-2">
            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>