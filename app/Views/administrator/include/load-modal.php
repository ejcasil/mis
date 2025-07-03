<div class="modal fade modal-lg" id="load-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Select Existing Individual</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body table-responsive">
        <div class="alert alert-danger collapse"></div>
        <table class="table table-hover" id="table-load">
          <thead>
            <tr>
              <th class="col-11">Resident Name</th>
              <th class="col-1 text-center">Action</th>
            </tr>
          </thead>
          <tbody class="tbody-load">
            <?php if ($residents) : ?>
              <?php foreach ($residents as $row) : ?>
                <tr>
                  <td><?= $row->fullname; ?></td>
                  <td class='text-center'>
                    <button type='button' class='btn btn-sm btn-primary btn-select' data-id='<?= $row->id; ?>' data-bs-toggle='tooltip' data-bs-title='Select'>
                      <i class='fi fi-rs-check me-2'></i>Select
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>