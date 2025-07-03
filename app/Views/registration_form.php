<?= $this->extend('home-layout'); ?>
<?= $this->section('content'); ?>
<div class="wrapper">
    <div class="text-center py-2">
        <img src="<?= base_url(); ?>public/assets/images/Municipal.png" class="img-fluid">
    </div>

    <h2>Sign Up!</h2>
    <form action="<?= base_url(); ?>register/register_account" method="POST">
        <?php if (session()->getFlashdata('invalid')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fi fi-rs-triangle-warning me-2"></i><?= session()->getFlashdata('invalid') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fi fi-rs-check-circle me-2"></i><small><?= session()->getFlashdata('success') ?></small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="input-box">
            <input type="text" name="username" id="username" placeholder="Enter your username" value="<?= old('username') ?>" required>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="input-box">
                    <input type="text" name="lname" id="lname" placeholder="Last name" value="<?= old('lname') ?>" required>
                </div>
            </div>
            <div class="col-6">

                <div class="input-box">
                    <input type="text" name="fname" id="fname" placeholder="First name" value="<?= old('fname') ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="input-box">
                    <input type="text" name="mname" id="mname" placeholder="Middle name" value="<?= old('mname') ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="input-box">
                    <input type="text" name="suffix" id="suffix" placeholder="Suffix name" value="<?= old('suffix') ?>">
                </div>
            </div>
        </div>

        <div class="input-box">
            <input type="email" name="email" id="email" placeholder="Enter your email" value="<?= old('email') ?>" required>
        </div>

        <div class="input-box button">
            <input type="Submit" value="Register Account">
        </div>
        <div class="text-center">
            <small><a href="<?= base_url(); ?>login">Already have an account?</a></small>
        </div>
    </form>
</div>
<?= $this->endSection('content'); ?>