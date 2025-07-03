<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Certification / Clearances</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Certification / Clearances</a></li>
    </ul>

    <div class="container-fluid">
        <div class="info-data">
            <div class="card">
                <div class="head">
                    <!-- CONTENT -->
                    <div class="container-fluid mt-4">
                        <div class="container-fluid p-0 m-0" id="print-content">
                            <div class="container-fluid bg-primary">
                                <div class="row p-2">
                                    <div class="col-3">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <img src="<?= base_url(); ?>public/assets/images/logo.png" alt="logo" style="width:250px;height:250px;">
                                        </div>
                                    </div>
                                    <div class="col-6 text-center text-white d-flex justify-content-center align-items-center">
                                        <div>
                                            <div>
                                                <p class="lh-2 fs-4">
                                                    Republic of the Philippines<br>
                                                    Province of <?= $brgy_profile->province ?? ''; ?><br>
                                                    Municipality of <?= $brgy_profile->municipality ?? ''; ?><br>
                                                    <span class="brgy-name">OFFICE OF THE PUNONG BARANGAY</span>
                                                </p>

                                                <h4 class="my-2 fs-1">BARANGAY <?= $brgy_name ?? ''; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-end align-items-center">
                                            <img src="<?= $brgy_profile->logo ?? ''; ?>" alt="logo" class="p-qr" style="width:250px;height:250px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- BARANGAY CLEARANCE -->
                            <?php if ($certificate->document_type && $certificate->document_type == "BC"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">BARANGAY CLEARANCE</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <h3 class="fw-bold">TO WHOM IT MAY CONCERN:</h3>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This is to certify that 
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span>, of
                                        <?= $resident_data->age ?? ''; ?> age,
                                        <?= $resident_data->cstatus_id ?? ''; ?>,
                                        and a resident of <?= $brgy_name ?? ''; ?>,
                                        <?= $brgy_profile->municipality ?? ''; ?>,
                                        <?= $brgy_profile->province ?? ''; ?>
                                        is known to me personally, a person of good moral character, peaceful and law-abiding citizen in our barangay.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This clearance is issued upon the request of <span class="text-decoration-underline"><?= $resident_data->fullname ?? ''; ?></span>
                                        for all legal intent and purposes it may serve <?= $resident_data->gender ?? ''; ?>.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        Given this <span class="text-decoration-underline"><?= $date_issued['day'] ?? ''; ?></span>
                                        day of <span class="text-decoration-underline"><?= $date_issued['month'] ?? ''; ?>
                                            <?= $date_issued['year'] ?? ''; ?></span>
                                        at the Office of the Punong Barangay of <?= $brgy_name ?? ''; ?>,
                                        <?= $brgy_profile->municipality ?? ''; ?>,
                                        <?= $brgy_profile->province ?? ''; ?>.
                                    </p>
                                </div>
                                <div class="pt-4">
                                    <div class="pt-4 fs-3 fw-normal text-center">
                                        <div class="mb-4">Certified by:</div>
                                        <div class="d-flex justify-content-around align-items-center mt-4">
                                            <div></div>
                                            <div>
                                                <span class="text-decoration-underline"><?= $captain_data['name'] ?? ''; ?></span><br>
                                                <span class="fst-italic"><?= $captain_data['position'] ?? ''; ?></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="ms-4 mt-4 pt-4">
                                        <b>Community Tax No.</b><?= $ctc_details['ctc_no'] ?? ''; ?><br>
                                        <b>Date issued:</b><?= $ctc_details['ctc_date'] ?? ''; ?><br><br>
                                        <b>Clearance fee:</b><?= $payment_details['amount_paid'] ?? ''; ?><br>
                                        <b>Paid under O.R. No.:</b><?= $payment_details['or_no'] ?? ''; ?><br>
                                        <b>Dated.:</b><?= $payment_details['or_date'] ?? ''; ?><br>
                                        <b>Control No.: </b><?= $certificate->control_no ?? ''; ?><br>
                                        <b>Note: </b><i>Not valid without the dry seal of the barangay.</i>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <!-- CERTIFICATE OF INDIGENCY -->
                            <?php if ($certificate->document_type && $certificate->document_type == "CI"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">CERTIFICATE OF INDIGENCY</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT: </b>
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span>,
                                        of <?= $resident_data->age ?? ''; ?> age,
                                        <?= $resident_data->cstatus_id ?? ''; ?>,
                                        and a resident of <?= $brgy_name ?? ''; ?>,
                                        <?= $brgy_profile->municipality ?? ''; ?>,
                                        <?= $brgy_profile->province ?? ''; ?>
                                        is known to me personally, a person of good moral character, peaceful and law-abiding citizen in our barangay.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This certifies further that the above-named person is listed as one of the indigent family in our barangay.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This Certificate of Indigency is being issued upon the request of <span class="text-decoration-underline"><?= $resident_data->fullname ?? ''; ?></span>
                                        for all legal intent and purposes it may serve <?= $resident_data->gender ?? ''; ?>.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        Given this <span class="text-decoration-underline"><?= $date_issued['day'] ?? ''; ?></span>
                                        day of <span class="text-decoration-underline"><?= $date_issued['month'] ?? ''; ?>
                                            <?= $date_issued['year'] ?? ''; ?></span>
                                        at the Office of the Punong Barangay of <?= $brgy_name ?? ''; ?>,
                                        <?= $brgy_profile->municipality ?? ''; ?>,
                                        <?= $brgy_profile->province ?? ''; ?>.
                                    </p>
                                </div>
                                <div class="pt-4">
                                    <div class="pt-4 fs-3 fw-normal text-center">
                                        <div class="mb-4">Certified by:</div>
                                        <div class="d-flex justify-content-around align-items-center mt-4">
                                            <div></div>
                                            <div>
                                                <span class="text-decoration-underline"><?= $captain_data['name'] ?? ''; ?></span><br>
                                                <span class="fst-italic"><?= $captain_data['position'] ?? ''; ?></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="ms-4 mt-4 pt-4">
                                        <b>Control No.: </b><?= $certificate->control_no ?? ''; ?><br>
                                        <b>Note: </b><i>Not valid without the dry seal of the barangay.</i>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <!-- BUSINESS CLEARANCE -->
                            <?php if ($certificate->document_type && $certificate->document_type == "BsC"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">BUSINESS CLEARANCE</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT: </b>
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span>,
                                        <?= $resident_data->age ?? ''; ?> age,
                                        <?= $resident_data->cstatus_id ?? ''; ?>,
                                        and a resident of <?= $brgy_name ?? ''; ?>,
                                        <?= $brgy_profile->municipality ?? ''; ?>,
                                        <?= $brgy_profile->province ?? ''; ?>
                                        is known to me personally, a person of good moral character, peaceful and law-abiding citizen in our barangay.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This certifies further that the above-named person is listed as one of the indigent family in our barangay.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This Certificate of Indigency is being issued upon the request of <span class="text-decoration-underline"><?= $resident_data->fullname ?? ''; ?></span>
                                        for all legal intent and purposes it may serve <?= $resident_data->gender ?? ''; ?>.
                                    </p>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-3">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        Given this <span class="text-decoration-underline"><?= $date_issued['day'] ?? ''; ?></span>
                                        day of <span class="text-decoration-underline"><?= $date_issued['month'] ?? ''; ?>
                                            <?= $date_issued['year'] ?? ''; ?></span>
                                        at the Office of the Punong Barangay of <?= $brgy_name ?? ''; ?>,
                                        <?= $brgy_profile->municipality ?? ''; ?>,
                                        <?= $brgy_profile->province ?? ''; ?>.
                                    </p>
                                </div>
                                <div class="pt-4">
                                    <div class="pt-4 fs-3 fw-normal text-center">
                                        <div class="mb-4">Certified by:</div>
                                        <div class="d-flex justify-content-around align-items-center mt-4">
                                            <div></div>
                                            <div>
                                                <span class="text-decoration-underline"><?= $captain_data['name'] ?? ''; ?></span><br>
                                                <span class="fst-italic"><?= $captain_data['position'] ?? ''; ?></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="ms-4 mt-4 pt-4">
                                    <b>Community Tax No.</b><?= $ctc_details['ctc_no'] ?? ''; ?><br>
                                        <b>Date issued:</b><?= $ctc_details['ctc_date'] ?? ''; ?><br><br>
                                        <b>Amount Paid:</b><?= $payment_details['amount_paid'] ?? ''; ?><br>
                                        <b>O.R. No.:</b><?= $payment_details['or_no'] ?? ''; ?><br>
                                        <b>O.R. Dated.:</b><?= $payment_details['or_date'] ?? ''; ?><br>
                                        <b>Control No.: </b><?= $certificate->control_no ?? ''; ?><br>
                                        <b>Note: </b><i>Not valid without the dry seal of the barangay.</i>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                            <a href="<?= base_url(); ?>certification/" class="btn btn-light">
                                <i class="fi fi-rs-angle-left me-2"></i><span>Back</span>
                            </a>
                            <button type="button" class="btn btn-primary btn-print-form">
                                <span>Print</span><i class="fi fi-rs-print ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CONTENT -->

            </div>
        </div>
    </div>
    </div>
</main>

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {
        $(document).on("click", ".btn-print-form", function(e) {
            e.preventDefault();
            try {
                var doc = $("#print-document");
                doc.css("height", "100vh");
                setTimeout(function() {
                    printContent(doc);
                }, 100); // Adjust the delay as necessary


            } catch (err) {
                console.error(err);
            }
        })
    })
</script>
<?= $this->endSection('my_script') ?>