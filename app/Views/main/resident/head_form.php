<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Resident Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>main/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Resident Profile</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Household Form</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <!-- FINAL FORM -->
            <div class="container-form mt-4">
                <div class="border border-primary p-1" id="print-content">
                    <div class="bg-primary">
                        <div class="row p-2">
                            <div class="col-3">
                                <div class="d-flex justify-content-start align-items-center">
                                    <img src="<?= base_url(); ?>public/assets/images/logo.png" alt="logo" style="width:150px;height:150px;">
                                </div>
                            </div>
                            <div class="col-6 text-center text-white">
                                <p class="lh-1">
                                    Republic of the Philippines<br>
                                    Province of Cagayan<br>
                                    Municipality of Sanchez Mira<br>
                                    <span class="brgy-name"><?= $brgy_name ?? ''; ?></span>
                                </p>
                                <h2 class="fw-bold">Barangay Information System</h2>
                                <small class="fw-bold">HOUSEHOLD ID NO</small>
                                <h5 class="p-household"><?= $household_id ?? ''; ?></h5>
                            </div>
                            <div class="col-3">
                                <div class="d-flex justify-content-end align-items-center">
                                    <img src="<?= $brgy_logo ?? ''; ?>" alt="qr-code" class="p-qr" style="width:150px;height:150px;">
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- HOUSEHOLD HEAD -->
                    <div class="bg-light py-2">
                        <h6 class="fw-bold mx-2">HOUSEHOLD HEAD</h6>
                    </div>
                    <div class="container-fluid bg-white">
                        <div class="d-flex justify-content-start align-items-start bg-white gap-2 py-2">
                            <div class="p-1">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <img src="<?= $img_path ?? ''; ?>" alt="pic" class="p-img" style="width:150px;height:150px">
                                    <small>Status</small>
                                    <small class="p-status fw-bold"></small>
                                    <div class="mt-2 d-flex justify-content-center align-items-center flex-column">
                                        <small>Household Head ID</small>
                                        <small class="p-head-id fw-bold"><?= $head_info['resident_id'] ?? ''; ?></small>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="container-fluid">
                                <!-- NAME -->
                                <div class="row border border-dark">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-lname fw-bold"><?= $head_info['lname'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-fname fw-bold"><?= $head_info['fname'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-mname fw-bold"><?= $head_info['mname'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-suffix fw-bold"><?= $head_info['suffix'] ?? ''; ?></small>
                                    </div>
                                </div>
                                <div class="row bg-light text-dark mb-2">
                                    <div class="col-3 border border-light text-center">
                                        <small>Last Name</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>First Name</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Middle Name</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Suffix</small>
                                    </div>
                                </div>
                                <!-- BDAY -->
                                <div class="row border border-dark">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-bday fw-bold"><?= $head_info['bday'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-bplace fw-bold"><?= $head_info['bplace'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-gender fw-bold"><?= $head_info['gender'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-cstatus fw-bold"><?= $head_info['cstatus'] ?? ''; ?></small>
                                    </div>
                                </div>
                                <div class="row bg-light text-dark mb-2">
                                    <div class="col-3 border border-light text-center">
                                        <small>Birthday (mm-dd-yyyy)</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Birthplace</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Gender</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Civil Status</small>
                                    </div>
                                </div>
                                <!-- EDUCATIONAL ATTAINMENT -->
                                <div class="row border border-dark">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-educ fw-bold"><?= $head_info['educ'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-course fw-bold"><?= $head_info['course'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-rel fw-bold"><?= $head_info['religion'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-occ fw-bold"><?= $head_info['occupation'] ?? ''; ?></small>
                                    </div>
                                </div>
                                <div class="row bg-light text-dark mb-2">
                                    <div class="col-3 border border-light text-center">
                                        <small>Educational Attainment</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Course</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Religion</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Occupation</small>
                                    </div>
                                </div>
                                <!-- PHILHEALTH -->
                                <div class="row border border-dark">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-phealth_no fw-bold"><?= $head_info['phealth_no'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-m_income fw-bold"><?= $head_info['m_income'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-cp fw-bold"><?= $head_info['cp'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-email fw-bold"><?= $head_info['email'] ?? ''; ?></small>
                                    </div>
                                </div>
                                <div class="row bg-light text-dark mb-2">
                                    <div class="col-3 border border-light text-center">
                                        <small>Philhealth No.</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Monthly Income</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Contact No.</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Email</small>
                                    </div>
                                </div>
                                <!-- NUTRTIONAL STATUS -->
                                <div class="row border border-dark">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-nstatus fw-bold"><?= $head_info['nstatus'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-btype fw-bold"><?= $head_info['btype'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-height fw-bold"><?= $head_info['height'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-weight fw-bold"><?= $head_info['weight'] ?? ''; ?></small>
                                    </div>
                                </div>
                                <div class="row bg-light text-dark mb-2">
                                    <div class="col-3 border border-light text-center">
                                        <small>Nutritional Status</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Blood Type</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Height (cm)</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Weight (kg)</small>
                                    </div>
                                </div>
                                <!-- BARANGAY -->
                                <div class="row border border-dark border-bottom">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-brgy fw-bold"><?= $head_info['brgy'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-purok fw-bold"><?= $head_info['purok'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-street fw-bold"><?= $head_info['street'] ?? ''; ?></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-house_no fw-bold"><?= $head_info['house_no'] ?? ''; ?></small>
                                    </div>
                                </div>
                                <div class="row bg-light text-dark mb-2">
                                    <div class="col-3 border border-light text-center">
                                        <small>Barangay</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Purok/Zone</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>Street Name</small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small>House No.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- HOUSEHOLD MEMBER -->
                    <div class="bg-light py-2">
                        <h6 class="fw-bold mx-2">HOUSEHOLD MEMBER</h6>
                    </div>
                    <div class="container-fluid bg-white mt-2">
                        <table class="table border border-dark">
                            <thead>
                                <tr>
                                    <th class="col-2 unbold">Resident ID</th>
                                    <th class="col-4 unbold">Name</th>
                                    <th class="col-1 text-center unbold">Age</th>
                                    <th class="col-1 text-center unbold">Gender</th>
                                    <th class="col-2 text-center unbold">Civil Status</th>
                                    <th class="col-2 text-center unbold">Relationship to Head</th>
                                </tr>
                            </thead>
                            <tbody class="p-member">
                            <?php echo implode("", $member); ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- OTHER HOUSEHOLD INFORMATION -->
                    <div class="bg-light py-2">
                        <h6 class="fw-bold mx-2">OTHER HOUSEHOLD INFORMATION</h6>
                    </div>
                    <div class="container-fluid bg-white">
                        <!-- water, power -->
                        <div class="container-fluid row p-0 mt-2">
                            <!-- water -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-10 unbold">Water Sources</th>
                                            <th class="col-2 unbold text-center">Ave. Consumption/mo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-water-other">
                                    <?php echo implode("", $water); ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- power -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-10 unbold">Power Sources</th>
                                            <th class="col-2 unbold text-center">Ave. Consumption/mo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-power-other">
                                    <?php echo implode("", $power); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- san, cook -->
                        <div class="container-fluid row p-0 mt-2">
                            <!-- san -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="unbold">Sanitation-Toilet Facility</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-san-other">
                                    <?php echo implode("", $san); ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- cook -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="unbold">Way of Cooking</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-cook-other">
                                    <?php echo implode("", $cook); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- app, comm -->
                        <div class="container-fluid row p-0 mt-2">
                            <!-- app -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-10 unbold">Appliances/Gadgets</th>
                                            <th class="col-2 unbold text-center">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-app-other">
                                    <?php echo implode("", $app); ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- comm -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="unbold">Communication Line</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-comm-other">
                                    <?php echo implode("", $comm); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- amenities, vhcl -->
                        <div class="container-fluid row p-0 mt-2">
                            <!-- amenities -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="unbold">Building Amenities</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-amenities-other">
                                    <?php echo implode("", $amenities); ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- vhcl -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-10 unbold">Type of Vehicle</th>
                                            <th class="col-2 unbold text-center">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-vhcl-other">
                                    <?php echo implode("", $vhcl); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- amach, alive -->
                        <div class="container-fluid row p-0 mt-2">
                            <!-- amach -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-10 unbold">Agricultural Machineries</th>
                                            <th class="col-2 unbold text-center">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-amach-other">
                                    <?php echo implode("", $amach); ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- alive -->
                            <div class="col-6">
                                <table class="table border border-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-10 unbold">Agricultural Livestocks</th>
                                            <th class="col-2 unbold text-center">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-p-alive-other">
                                    <?php echo implode("", $alive); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- BUILDING INFORMATION -->
                    <div class="bg-light py-2">
                        <h6 class="fw-bold mx-2">BUILDING INFORMATION</h6>
                    </div>
                    <div class="container-fluid bg-white py-2">
                        <!-- bldg info -->
                        <div class="row border border-dark">
                            <div class="col-3 border border-light text-center">
                                <span class="p-bldg-type fw-bold"><?= $bldginfo['bldg_type'] ?? ''; ?></span>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <span class="p-construction-yr fw-bold"><?= $bldginfo['construction_yr'] ?? ''; ?></span>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <span class="p-yr-occupied fw-bold"><?= $bldginfo['yr_occupied'] ?? ''; ?></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-bldg-permit fw-bold"><?= $bldginfo['bldg_permit_no'] ?? ''; ?></span>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <span class="p-lot-no fw-bold"><?= $bldginfo['lot_no'] ?? ''; ?></span>
                            </div>
                        </div>
                        <div class="row bg-light text-dark mb-2">
                            <div class="col-3 border border-light text-center">
                                <small>Building Type</small>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <small>Construction Year</small>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <small>Year Occupied</small>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <small>Building Permit No.</small>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <small>Erected on Lot</small>
                            </div>
                        </div>
                    </div>
                    <!-- GARBAGE INFORMATION -->
                    <div class="bg-light py-2">
                        <h6 class="fw-bold mx-2">AVERAGE OF WEEKLY GENERATED GARBAGES</h6>
                    </div>
                    <div class="container-fluid bg-white py-2">
                        <!-- garbage info -->
                        <div class="row border border-dark">
                            <div class="col-3 border border-light text-center">
                                <span class="p-hazardous fw-bold"><?= $garbages['hazardous'] ?? ''; ?></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-recyclable fw-bold"><?= $garbages['recyclable'] ?? ''; ?></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-residual fw-bold"><?= $garbages['residual'] ?? ''; ?></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-biodegradable fw-bold"><?= $garbages['biodegradable'] ?? ''; ?></span>
                            </div>
                        </div>
                        <div class="row bg-light text-dark mb-2">
                            <div class="col-3 border border-light text-center">
                                <small>Hazardous</small>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <small>Recyclable</small>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <small>Residual</small>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <small>Biodegradable</small>
                            </div>
                        </div>
                    </div>
                    <!-- HOUSEHOLD HEAD -->
                    <div class="col-4 mt-4">
                        <div class="mt-2 border-bottom border-dark">
                            <b class="c-user"><?= $head_info['fullname'] ?? ''; ?></b>
                        </div>
                        <p class="fst-italic">Household Head</p>
                        

                    </div>
                    <!-- PREPARED BY -->
                    <div class="col-4 mt-4">
                        <label class="mb-4">Prepared by:</label>
                        <div class="mt-2 border-bottom border-dark">
                            <b class="c-user"><?= $user_data['fullname'] ?? ''; ?></b>
                        </div>
                        <p class="fst-italic">System Administrator (MAIN)</p>
                        Date generated: <small class="c-dated"><?= $user_data['dated'] ?? ''; ?></small>

                    </div>
                </div>
 
                <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                    <a href="<?= base_url('resident/active_inactive3'); ?>" class="btn btn-light">Cancel</a>
                    <button type="button" class="btn btn-success btn-print-form">
                        <span>Print Form</span><i class="fi fi-rs-print ms-2"></i>
                    </button>
                </div>
            </div>
            <!-- FINAL FORM -->
        </div>
    </div>
    </div>
</main>

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {


        // PRINT OTHER INFORMATION (FORM)
        function print_household_info(household_id) {

            if (household_id !== "") {
                $.ajax({
                    url: "<?= site_url('/resident/printHouseholdInfo/') ?>" + household_id,
                    type: "GET",
                    dataSrc: "data",
                    success: function(res) {
                        let data = res.data;
                        let head_info = data.head_info;
                        let member = data.member;
                        let bldgInfo = data.bldginfo;
                        let garbages = data.garbages;
                        let user_data = data.user_data;
                        // PREPARED BY
                        $(".c-user").text(user_data.fullname);
                        $(".c-dated").text(user_data.dated);
                        // HOUSEHOLD HEAD
                        $(".p-household").text(head_info.household);
                        $(".p-img").attr("src", data.img_path);
                        $(".p-qr").attr("src", data.brgy_logo);
                        $(".p-head-id").text(head_info.resident_id);
                        $(".p-status").text(head_info.status);
                        $(".p-lname").text(head_info.lname);
                        $(".p-fname").text(head_info.fname);
                        $(".p-mname").text(head_info.mname);
                        $(".p-suffix").text(head_info.suffix);
                        $(".p-bday").text(head_info.bday);
                        $(".p-bplace").text(head_info.bplace);
                        $(".p-gender").text(head_info.gender);
                        $(".p-cstatus").text(head_info.cstatus);
                        $(".p-educ").text(head_info.educ);
                        $(".p-course").text(head_info.course);
                        $(".p-rel").text(head_info.religion);
                        $(".p-occ").text(head_info.occupation);
                        $(".p-phealth_no").text(head_info.phealth_no);
                        $(".p-m_income").text(head_info.m_income);
                        $(".p-cp").text(head_info.cp);
                        $(".p-email").text(head_info.email);
                        $(".p-nstatus").text(head_info.nstatus);
                        $(".p-btype").text(head_info.btype);
                        $(".p-height").text(head_info.height);
                        $(".p-weight").text(head_info.weight);
                        $(".p-brgy").text(head_info.brgy);
                        $(".p-purok").text(head_info.purok);
                        $(".p-street").text(head_info.street);
                        $(".p-house_no").text(head_info.house_no);
                        // HOUSEHOLD MEMBER
                        $(".p-member").html(member);
                        // OTHER INFO
                        $(".tbody-p-water-other").html(data.water);
                        $(".tbody-p-power-other").html(data.power);
                        $(".tbody-p-san-other").html(data.san);
                        $(".tbody-p-cook-other").html(data.cook);
                        $(".tbody-p-app-other").html(data.app);
                        $(".tbody-p-comm-other").html(data.comm);
                        $(".tbody-p-amenities-other").html(data.amenities);
                        $(".tbody-p-vhcl-other").html(data.vhcl);
                        $(".tbody-p-amach-other").html(data.amach);
                        $(".tbody-p-alive-other").html(data.alive);
                        // BUILDING INFO
                        $(".p-bldg-type").text(bldgInfo.bldg_type);
                        $(".p-construction-yr").text(bldgInfo.construction_yr);
                        $(".p-yr-occupied").text(bldgInfo.yr_occupied);
                        $(".p-bldg-permit").text(bldgInfo.bldg_permit_no);
                        $(".p-lot-no").text(bldgInfo.lot_no);
                        // GARBAGE INFO
                        $(".p-hazardous").text(garbages.hazardous);
                        $(".p-recyclable").text(garbages.recyclable);
                        $(".p-residual").text(garbages.residual);
                        $(".p-biodegradable").text(garbages.biodegradable);
                        // BARANGAY
                        $(".brgy-name").text(data.brgy_name);
                        show_container_form();
                    },
                    error: function(err) {
                        console.error(err);
                    },
                });
            }
        }

        // PRINT RESIDENT FORM
        $(document).on("click", ".btn-print-form", function() {
            printContent();
        });


    });
</script>
<?= $this->endSection('my_script') ?>