<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="<?= base_url(); ?>public/assets/images/logo.png">
    <link rel="stylesheet" href="<?= base_url(); ?>public/assets/css/login.css">
    <title>Municipal Information System</title>
</head>

<body>
    <?= $this->renderSection('content'); ?>
    
    <?= $this->include("include/forgot-password"); ?>
    <?= $this->include("include/toast"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="<?= base_url(); ?>public/assets/js/login.js"></script>
    <script src="<?= base_url(); ?>public/assets/js/restrict.js"></script>
</body>

</html>