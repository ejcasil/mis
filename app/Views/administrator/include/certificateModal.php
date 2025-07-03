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
                        <label for="resident">Resident Name</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="resident" id="resident" required>
                            <option value="" selected>Select resident</option>
                            <?php if ($resident): ?>
                                <?php foreach ($resident as $row): ?>
                                    <option value="<?= $row->id ?>"><?= $row->fullname ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                        </div>
                        <div class="col">
                        <div class="container-document-type form-group mb-2">
                        <label for="document_type">Document Type</label><span class="text-danger ms-2">*</span>
                        <select class="form-select" name="document_type" id="document_type" required>
                            <option value="" selected>Select option</option>
                            <option value="BC">Barangay Clearance</option>
                            <option value="BsC">Business Clearance</option>
                            <option value="CI">Certificate of Indigency</option>

                            <option value="OSP">One & Same Person</option>
                            <option value="PHC">Poor Health Condition</option>
                            <option value="PWD">Person w/ Disability</option>
                            <option value="HB">House/Shelter Burn-out</option>
                            <option value="HDT">House/Shelter Damaged by Typhoon</option>
                        </select>
                    </div>
                        </div>
                    </div>

                    <div class="mb-2 collapse" id="bsc-name-container">
                        <!-- BUSINESS CLEARANCE -->
                        <label>Business Name Registered</label>
                        <input type="text" class="form-control" placeholder="Business Name Registered" name="business_name" id="business_name">
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