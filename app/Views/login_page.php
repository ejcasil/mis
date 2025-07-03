<?= $this->extend('home-layout'); ?>
<?= $this->section('content'); ?>
<div class="wrapper">
    <div class="text-center py-2">
        <img src="<?= base_url(); ?>public/assets/images/Municipal.png" class="img-fluid">
    </div>

    <h2>Login your account!</h2>
    <form action="<?= base_url(); ?>login/authenticate" method="POST">
        <?php if (session()->getFlashdata('invalid')): ?>
            <div class="alert alert-danger my-2" role="alert">
                <i class="fi fi-rs-triangle-warning me-4"></i><?= session()->getFlashdata('invalid'); ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success my-2" role="alert">
                <i class="fi fi-rs-check-circle me-4"></i><?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>
        <div class="input-box">
            <input type="username" name="username" id="username" placeholder="Enter your username" required>
        </div>
        <div class="input-box form-floating">
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <i class="fi fi-rs-eye collapse" id="togglePassword"></i>
        </div>

        <div class="input-box button">
            <input type="Submit" value="Login Now">
        </div>
        <div class="text d-flex">
            <h4><a href="<?= base_url(); ?>register/registration_form">Create Account</a></h4>
            <h4> <a href="#" class="forgot" data-bs-toggle="modal" data-bs-target="#forgot-password">Forgot Password</a></h4>
        </div>

    </form>
</div>
<?= $this->endSection('content'); ?>