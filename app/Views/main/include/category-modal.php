<div class="modal fade modal-lg" id="category-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Select <span class='category-title'></span></h5>
        <button type="button" class="btn btn-sm btn-light text-primary border-primary add-list"><i class="fi fi-rs-plus me-2"></i>Create New</button>
      </div>
      <div class="modal-body table-responsive">
        <input type='hidden' id='category-list'>
        <div class="alert alert-danger collapse"></div><!-- ALERT -->
        <table class="table table-hover" id="table-list">
          <thead>
            <tr>
              <th>
                <div class="d-flex">
                  <input type='checkbox' id="chkList" class='form-check chkList me-2' data-bs-toggle='tooltip' data-bs-title='Select All'>
                  <label for="chkList">Description</label>
                </div>

              </th>
            </tr>
          </thead>
          <tbody class="tbody-list">
            <tr>
              <td class='text-center'>
                No record found
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type='button' class='btn btn-primary select-list'>Select</button>
        <button type='button' class='btn btn-light' data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-category-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="staticBackdropLabel">Create New <span class='category-title'></span></h6>
      </div>
      <div class="modal-body table-responsive">
        <?= csrf_field(); ?>
        <input type='hidden' id='category-list-create'>
        <div class="alert alert-danger collapse"></div><!-- ALERT -->
          <label>Description</label><span class="text-danger ms-2">*</span>
          <input type="text" class="form-control desc-create" placeholder="Enter description">
      </div>
      <div class="modal-footer">
        <button type='button' class='btn btn-primary save-list'>Save</button>
        <button type='button' class='btn btn-light cancel-list'>Cancel</button>
      </div>
    </div>
  </div>
</div>