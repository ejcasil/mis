
<div class="modal fade" id="payModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Payment Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger collapse"></div>
        <form id="savePayment" method="POST">
          <input type="hidden" id="certification-id" name="certification-id">
            <div class="mb-2">
              <label>Resident</label>
              <input type="text" class="form-control border-0 bg-transparent fw-bold" name="resident_detail" id="resident_detail" disabled>
            </div>
            <div class="d-flex justify-content-between">
            <div>
              <label>Document Type</label>
              <input type="text" class="form-control border-0 bg-transparent" name="document_type_detail" id="document_type_detail" disabled>
            </div>
            <div>
              <label>Document Fee</label>
              <input type="text" class="form-control border-0 bg-transparent" name="document_fee_detail" id="document_fee_detail" disabled>
            </div>
            </div>
            <hr class="text-secondary">
            <div class="mb-2">
              <label>Amount Paid</label>
              <input type="text" class="form-control" name="amount-paid" id="amount-paid" placeholder="Enter amount" required>
            </div>
            <div class="d-flex justify-content-between">
            <div>
              <label>O.R. Number</label>
              <input type="text" class="form-control" name="or-no" id="or-no" placeholder="Enter amount" required>
            </div>
            <div>
              <label>O.R. Dated</label>
              <input type="text" class="form-control datetimepicker" name="or-no-date" id="or-no-date" placeholder="Enter O.R. date" required>
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