<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Municipal Information System</title>
    <!-- CSRF -->
    <!-- <meta name="csrf-token" content="<?= csrf_hash() ?>"> -->
    <!-- TITLE -->
    <meta name="description" content="Municipal Information System">
    <!-- VIEWPORT -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SYSTEM ICON -->
    <link rel="icon" type="image/png" href="<?= session()->get('user_brgy_logo'); ?>">
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- boxicons CSS -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- FLAT ICON -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <!-- FLATPICKR (DATETIMEPICKER) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- PRINT-THIS (DOC) -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/print-this.css'); ?>">
    <!-- MY STYLESHEET -->
    <link rel="stylesheet" href="<?= base_url('public/assets/css/layout.css'); ?>">
</head>

<body class="bg-light">
    <!-- SIDEBAR -->
   

    <!-- MAIN -->
    <section>
        <!-- NAVBAR -->
        <?= $this->include('resident/include/navbar'); ?>
        <!-- NAVBAR -->

        <?= $this->include('include/announcement'); ?>

        <!-- CONTENT AREA -->
        <?= $this->renderSection('content'); ?>
        <!-- CONTENT AREA -->

    </section>
    <!-- MAIN -->


    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- MY SCRIPT JS -->
    <script src="<?= base_url('public/assets/js/resident-layout.js'); ?>"></script>
    <!-- JQUERY CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- VALIDATE JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- DATATABLE JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <!-- DATATABLE BOOTSTRAP JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <!-- FLATPICKR JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- SWEETALERT2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- PRINT-THIS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>
    <!-- ALERT JS -->
    <script src="<?= base_url('public/assets/js/alert.js'); ?>"></script>
    <!-- PRINT-THIS FUNCTION JS -->
    <script src="<?= base_url('public/assets/js/print-this.js'); ?>"></script>
    <!-- RESTRICT INPUT JS -->
    <script src="<?= base_url('public/assets/js/restrict.js'); ?>"></script>
    <!-- PROFILE PIC LAYOUT JS -->
    <script src="<?= base_url('public/assets/js/profile-pic-layout.js'); ?>"></script>
    <!-- MY SCRIPT JS -->
    <?= $this->renderSection('my_script'); ?>

</body>

</html>