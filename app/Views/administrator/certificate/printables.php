<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Certification / Clearances</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
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
                            <?php if ($certificate->document_type && $certificate->document_type === "BC"): ?>
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
                            <?php if ($certificate->document_type && $certificate->document_type === "CI"): ?>
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
                            <?php if ($certificate->document_type && $certificate->document_type === "BsC"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">BUSINESS CLEARANCE</h1>
                                <div class="container-fluid mt-4 px-4">
                                        
                                        <div class="lh-1 text-center mb-4">
                                            <h3 class="text-decoration-underline"><?= isset($certificate->business_name) ? $certificate->business_name : ""; ?></h3>
                                            <h6>Business Name</h4>
                                        </div>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4"> 
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>CLEARANCE </b>is hereby granted to
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span> for opening/operating <?= isset($resident_data->gender) && $resident_data->gender === "him" ? "his" : "her"; ?>
                                        business at Barangay <?= isset($brgy_name) ? ucfirst($brgy_name) : " " ?>, <?= $brgy_profile->municipality ?? ''; ?>, <?= $brgy_profile->province ?? ''; ?>.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>This is issued in compliance with the existing ordinance, rules and regulation governing the business and trade and shall deemed for one (1) year from the date of issuance unless revoked by the Local Government Unit.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
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
                                    <div class="pt-4 fs-4 fw-normal text-center">
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
                            <!-- PERSON W/ DISABILITY -->
                            <?php if ($certificate->document_type && $certificate->document_type === "PWD"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">C E R T I F I C A T I O N</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <h3 class="fw-bold">TO WHOM IT MAY CONCERN:</h3>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-4"> 
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT </b>
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span> of 
                                        <?= isset($resident_data->age) ? $resident_data->age : " "; ?> age,  
                                        <?= isset($resident_data->cstatus_id) ? $resident_data->cstatus_id : " "; ?>,
                                        Filipino and a resident of <?= isset($brgy_name) ? ucfirst($brgy_name) : " " ?>, <?= $brgy_profile->municipality ?? ''; ?>, <?= $brgy_profile->province ?? ''; ?>.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFIES FURTHER THAT </b><?= isset($resident_data->gender) && $resident_data->gender === "him" ? "he " : "she "; ?>is one of the <b>PERSONS WITH DISABILITY</b> in this barangay.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFICATION </b>is issued upon the request of <?= isset($resident_data->gender) && $resident_data->gender === "him" ? "Mr. " : "Ms. "; ?><?= isset($resident_data->lname) ? $resident_data->lname : " "; ?>
                                        for whatever legal purpose it may serve <?= isset($resident_data->gender) ? $resident_data->gender : " "; ?>.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
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
                                    <div class="pt-4 fs-4 fw-normal text-center">
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
                            <!-- ONE AND SAME PERSON -->
                             <?php if ($certificate->document_type && $certificate->document_type === "OSP"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">C E R T I F I C A T I O N</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <h3 class="fw-bold">TO WHOM IT MAY CONCERN:</h3>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-4"> 
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT </b>
                                        as per record of this office, the names
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span> and ______________________________
                                        pertain to <b>ONE and THE SAME PERSON </b> using the name involving transactions with this barangay office and other government offices.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFICATION </b>is being issued upon the request of the above-mentioned person for any legal purpose it may serve.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
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
                                    <div class="pt-4 fs-4 fw-normal text-center">
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
                            <!-- POOR HEALTH CONDITION -->
                            <?php if ($certificate->document_type && $certificate->document_type === "PHC"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">C E R T I F I C A T I O N</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <h3 class="fw-bold">TO WHOM IT MAY CONCERN:</h3>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-4"> 
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT </b>
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span> of 
                                        <?= isset($resident_data->age) ? $resident_data->age : " "; ?> age,  
                                        <?= isset($resident_data->cstatus_id) ? $resident_data->cstatus_id : " "; ?>,
                                        Filipino and a resident of <?= isset($brgy_name) ? ucfirst($brgy_name) : " " ?>, <?= $brgy_profile->municipality ?? ''; ?>, <?= $brgy_profile->province ?? ''; ?>.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFIES FURTHER </b>that <?= isset($resident_data->gender) && $resident_data->gender === "him" ? "he" : "she"; ?>
                                        is still alive but cannot report personally due to <?= isset($resident_data->gender) && $resident_data->gender === "him" ? "his" : "her"; ?> very poor health condition.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFICATION </b>is being issued upon the request of the above-mentioned person for any legal purpose it may serve.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
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
                                    <div class="pt-4 fs-4 fw-normal text-center">
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
                            <!-- House/Shelter Burn-out -->
                            <?php if ($certificate->document_type && $certificate->document_type === "HB"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">C E R T I F I C A T I O N</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <h3 class="fw-bold">TO WHOM IT MAY CONCERN:</h3>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-4"> 
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT </b>
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span> of 
                                        <?= isset($resident_data->age) ? $resident_data->age : " "; ?> age,  
                                        <?= isset($resident_data->cstatus_id) ? $resident_data->cstatus_id : " "; ?>,
                                        Filipino and a resident of <?= isset($brgy_name) ? ucfirst($brgy_name) : " " ?>, <?= $brgy_profile->municipality ?? ''; ?>, <?= $brgy_profile->province ?? ''; ?>.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFIES FURTHER </b>that <?= isset($resident_data->gender) && $resident_data->gender === "him" ? "his" : "her"; ?>
                                        house/shelter was burnt-out/damaged by fire last _________________.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFICATION </b>is being issued upon the request of the above-mentioned person for any legal purpose it may serve.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
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
                                    <div class="pt-4 fs-4 fw-normal text-center">
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
                             <!-- House/Shelter Damaged by Typhoon -->
                             <?php if ($certificate->document_type && $certificate->document_type === "HDT"): ?>
                                <h1 class="text-center text-dark fw-bold my-4 py-4">C E R T I F I C A T I O N</h1>
                                <div class="container-fluid mt-4 px-4">
                                    <h3 class="fw-bold">TO WHOM IT MAY CONCERN:</h3>
                                    <p class="text-justify text-dark fw-normal lh-lg fs-4"> 
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span><b>THIS IS TO CERTIFY THAT </b>
                                        <span class="text-decoration-underline"><?= $resident_data->fullname; ?></span> of 
                                        <?= isset($resident_data->age) ? $resident_data->age : " "; ?> age,  
                                        <?= isset($resident_data->cstatus_id) ? $resident_data->cstatus_id : " "; ?>,
                                        Filipino and a resident of <?= isset($brgy_name) ? ucfirst($brgy_name) : " " ?>, <?= $brgy_profile->municipality ?? ''; ?>, <?= $brgy_profile->province ?? ''; ?>.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFIES FURTHER </b>that their
                                        house/shelter was damaged by the recent typhoon ______________ occured ______________.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
                                        <span class="ms-4"></span><span class="ms-4"></span><span class="ms-4"></span>
                                        <b>THIS CERTIFICATION </b>is being issued upon the request of the above-mentioned person for any legal purpose it may serve.
                                    </p>

                                    <p class="text-justify text-dark fw-normal lh-lg fs-4">
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
                                    <div class="pt-4 fs-4 fw-normal text-center">
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