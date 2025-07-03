<!-- Modal -->
<div class="modal fade" id="compose-msg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Compose Message</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="messageForm" method="POST">
        <div class="modal-body">
          <label>Recipients: <strong>Table Data</strong></label>
          <div class="form-floating mt-2">
            <textarea class="form-control" placeholder="Enter your message here" id="message" style="height: 200px" required></textarea>
            <label for="message">Enter your message</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Send <i class='bx bx-send ms-2' ></i></button>
        </div>
      </form>
    </div>
  </div>
</div>