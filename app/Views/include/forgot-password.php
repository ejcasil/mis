<div class="modal fade" id="forgot-password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="p-2">
            <div class="title text-center">
                <h2>Forgot Password?</h2>
                <p>Remember your password ? <a href="#" data-bs-dismiss="modal" data-bs-target="#forgot-password">Let me Sign in.</a></p>
            </div>
        <form action="<?= base_url(); ?>recovery/forgot_password" method="POST">
          <div class="mb-2">
            <label>Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" autocomplete="off" required>
          </div> 
          <input type="submit" class="btn btn-primary d-block w-100 mb-2" value="Request Code">
        </form>
        </div>
      </div>
    </div>
  </div>
</div>