<?= $this->extend('administrator/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">Resident Profile</h1>
    <ul class="breadcrumbs">
        <li><a href="<?= base_url(); ?>administrator/dashboard">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Resident Profile</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">Update Resident Profile</a></li>
    </ul>

    <div class="info-data">
        <div class="card">
            <div class="head">
                <div class="d-flex justify-content-between align-items-center">
                    <div class='tab-head text-primary'><i class='bx bx-check me-2 chk-head collapse'></i>Household Head</div>
                    <div class='tab-member text-secondary'><i class='bx bx-check me-2 chk-member collapse'></i>Household Members</div>
                    <div class='tab-other text-secondary'><i class='bx bx-check me-2 chk-other collapse'></i>Other Household Information</div>
                    <div class='tab-form text-secondary'><i class='bx bx-check me-2 chk-form collapse'></i>Household Form</div>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 25%"></div>
                </div>
            </div>

            <!-- CONTENT -->
            <!-- HOUSEHOLD HEAD FORM -->
            <div class="container-head mt-4">
                <form id="residentForm" method="POST" enctype="multipart/form-data">
                    <div class="alert-area"></div>
                    <div class="container-fluid bg-light p-2 rounded my-2 d-flex gap-2">

                        <div class="profile-container border p-2">
                        <img src='<?= $img; ?>' id="img-head" name="img-head" class='profile-img'>
                        <i class='bx bx-upload profile-icon' id="btn-upload-profile" ></i>
                        <input type="file" class="form-control collapse" id="file-head" name="file-head" accept=".png, .jpg, .jpeg, .gif">

                        </div>

                        
                        <div>
                            <input type="hidden" name="id" id="id-head" value="<?= $data->id ?? ''; ?>">
                            <h1 class="text-dark fw-bold fullname"><?= $data->fullname ?? ''; ?></h1>
                            <small><b>Household ID: </b><span class='household-id'><?= $data->household ?? ''; ?></span></small>
                            <input type="hidden" name="img-path" value="<?= $data->img_path ?? ''; ?>">
                        </div>


                    </div>
                    <!-- lname,fname,mname,suffix -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="lname">Last Name</label>
                            <input type="text" value="<?= $data->lname ?? ''; ?>" class="form-control" name="lname" id="lname" placeholder="Enter your last name" required>
                            <div class="invalid-feedback">Please provide your last name.</div>
                        </div>
                        <div class="col">
                            <label for="fname">First Name</label>
                            <input type="text" value="<?= $data->fname ?? ''; ?>" class="form-control" name="fname" id="fname" placeholder="Enter your first name" required>
                            <div class="invalid-feedback">Please provide your first name.</div>
                        </div>
                        <div class="col">
                            <label for="mname">Middle Name</label>
                            <input type="text" value="<?= $data->mname ?? ''; ?>" class="form-control" name="mname" id="mname" placeholder="Enter your middle name">
                        </div>
                        <div class="col">
                            <label for="suffix">Suffix</label>
                            <input type="text" value="<?= $data->suffix ?? ''; ?>" class="form-control" name="suffix" id="suffix" placeholder="Enter your suffix">
                        </div>
                    </div>
                    <!-- bday,bplace,gender,cstatus -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="bday">Birthday</label>
                            <input type="text" value="<?= $data->bday ?? ''; ?>" class="form-control datetimepicker" name="bday" id="bday" placeholder="mm-dd-yyyy" required>
                            <div class="invalid-feedback">Please provide your birthday.</div>
                        </div>
                        <div class="col">
                            <label for="bplace">Birthplace</label>
                            <input type="text" value="<?= $data->bplace ?? ''; ?>" class="form-control" name="bplace" id="bplace" placeholder="Enter your birthplace">
                        </div>
                        <div class="col">
                            <label for="gender">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" <?= $data->gender == "" ? "selected" : ''; ?>>Select option</option>
                                <option value="MALE" <?= $data->gender == "MALE" ? "selected" : ''; ?>>Male</option>
                                <option value="FEMALE" <?= $data->gender == "FEMALE" ? "selected" : ''; ?>>Female</option>
                            </select>
                            <div class="invalid-feedback">Please provide your gender.</div>
                        </div>
                        <div class="col">
                            <label for="cstatus">Civil Status</label>
                            <select class="form-select" name="cstatus" id="cstatus" required>
                                <option value="" <?= $data->cstatus_id == "" ? "selected" : ''; ?>>Select option</option>
                                <?php foreach ($cstatus as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?= $data->cstatus_id == $row->id ? "selected" : ""; ?>><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your civil status.</div>
                        </div>
                    </div>
                    <!-- educ,course,rel,occ -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="education">Educational Attainment</label>
                            <select class="form-select" id="education" name="education">
                                <option value="" <?= $data->educ_id == "" ? "selected" : ""; ?>>Select option</option>
                                <?php foreach ($educ as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?= $data->educ_id == $row->id ? "selected" : ""; ?>><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="course">Course</label>
                            <select class="form-select" id="course" name="course">
                                <option value="" <?= $data->course_id == "" ? "selected" : ""; ?>>Select option</option>
                                <?php foreach ($course as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?= $data->course_id == $row->id ? "selected" : ""; ?>><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="religion">Religion</label>
                            <select class="form-select" id="religion" name="religion">
                                <option value="" selected>Select option</option>
                                <?php foreach ($rel as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?= $data->rel_id == $row->id ? "selected" : ""; ?>><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="occupation">Occupation</label>
                            <select class="form-select" id="occupation" name="occupation" required>
                                <option value="" selected>Select option</option>
                                <?php foreach ($occ as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?= $data->occ_id == $row->id ? "selected" : ""; ?>><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your occupation.</div>
                        </div>
                    </div>
                    <!-- philhealth_no,m_income,cp,email -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="philhealth">Philhealth No.</label>
                            <input type="text" value="<?= $data->phealth_no ?? ''; ?>" class="form-control" id="philhealth" name="philhealth" placeholder="Enter your philhealth no.">
                        </div>
                        <div class="col">
                            <label for="monthly_income">Monthly Income</label>
                            <input type="number" value="<?= $data->m_income ?? ''; ?>" class="form-control" id="monthly_income" name="monthly_income" placeholder="Enter your monthly income">
                        </div>
                        <div class="col">
                            <label for="cp">Contact No.</label>
                            <input type="text" value="<?= $data->cp ?? ''; ?>" class="form-control" id="cp" name="cp" placeholder="Enter your contact no." required>
                            <div class="invalid-feedback">Please provide your contact no.</div>
                        </div>
                        <div class="col">
                            <label for="email">Email</label>
                            <input type="email" value="<?= $data->email ?? ''; ?>" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                            <div class="invalid-feedback">Please provide your email.</div>
                        </div>
                    </div>
                    <!-- nstatus,btype,height,weight -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="nstatus">Nutritional Status</label>
                            <select class="form-select select2" id="nstatus" name="nstatus">
                                <option value="" <?= $data->nstatus == "" ? 'selected' : ''; ?>>Select Nutritional Status</option>
                                <option value="N" <?= $data->nstatus == "N" ? 'selected' : ''; ?>>Normal</option>
                                <option value="UW" <?= $data->nstatus == "UW" ? 'selected' : ''; ?>>Underweight</option>
                                <option value="SeUW" <?= $data->nstatus == "SeUW" ? 'selected' : ''; ?>>Severely Underweight</option>
                                <option value="St" <?= $data->nstatus == "St" ? 'selected' : ''; ?>>Stunted</option>
                                <option value="SeSt" <?= $data->nstatus == "SeSt" ? 'selected' : ''; ?>>Severely Stunted</option>
                                <option value="MW" <?= $data->nstatus == "MW" ? 'selected' : ''; ?>>Moderately Wasted</option>
                                <option value="SeW" <?= $data->nstatus == "SeW" ? 'selected' : ''; ?>>Severely Wasted</option>
                                <option value="OW" <?= $data->nstatus == "OW" ? 'selected' : ''; ?>>Overweight</option>
                                <option value="O" <?= $data->nstatus == "O" ? 'selected' : ''; ?>>Obese</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="btype">Blood type</label>
                            <select class="form-select select2" name="btype" id="btype">
                                <option value="" <?= $data->btype == "" ? 'selected' : ''; ?>>Select Blood Type</option>
                                <option value="A+" <?= $data->btype == "A+" ? 'selected' : ''; ?>>A+</option>
                                <option value="A-" <?= $data->btype == "A-" ? 'selected' : ''; ?>>A-</option>
                                <option value="B+" <?= $data->btype == "B+" ? 'selected' : ''; ?>>B+</option>
                                <option value="B-" <?= $data->btype == "B-" ? 'selected' : ''; ?>>B-</option>
                                <option value="AB+" <?= $data->btype == "AB+" ? 'selected' : ''; ?>>AB+</option>
                                <option value="AB-" <?= $data->btype == "AB-" ? 'selected' : ''; ?>>AB-</option>
                                <option value="O+" <?= $data->btype == "O+" ? 'selected' : ''; ?>>O+</option>
                                <option value="O-" <?= $data->btype == "O-" ? 'selected' : ''; ?>>O-</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="height">Height (cm)</label>
                            <input type="number" value="<?= $data->height ?? ''; ?>" class="form-control" id="height" name="height" placeholder="Enter your height">
                        </div>
                        <div class="col">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" value="<?= $data->weight ?? ''; ?>" class="form-control" id="weight" name="weight" placeholder="Enter your weight">
                        </div>
                    </div>
                    <!-- Brgy,purok-zone,street,house_no -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="barangay">Barangay</label>
                            <select class="form-select" name="barangay" id="barangay" required>
                                <?php if ($barangay) : ?>
                                    <option value="<?= $barangay->id ?>" <?= $brgy_id == $barangay->id ? "selected" : ""; ?>><?= $barangay->brgy_name ?></option>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your barangay.</div>
                        </div>
                        <div class="col">
                            <label class="purok">Purok/Zone</label>
                            <select class="form-select" name="purok" id="purok" required>
                                <option value="" selected>Select option</option>
                                <?php if ($purok) : ?>
                                    <?php foreach ($purok as $row) : ?>
                                        <option value='<?= $row->id; ?>' <?= $purok_id == $row->id ? "selected" : ""; ?>><?= $row->description; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your purok/zone.</div>
                        </div>
                        <div class="col">
                            <label for="street">Street</label>
                            <input type="text" value="<?= $data->street ?? ''; ?>" class="form-control" id="street" name="street" placeholder="Enter your street name">
                        </div>
                        <div class="col">
                            <label for="house_no">House No.</label>
                            <input type="text" value="<?= $data->house_no ?? ''; ?>" class="form-control" id="house_no" name="house_no" placeholder="Enter your house no." required>
                        </div>
                        <div class="invalid-feedback">Please provide your house no.</div>
                    </div>
                    <!-- TABLES -->
                    <div class="row mt-2">
                        <!-- TRAININGS -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-training">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Trainings and Skills</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-trainings-head">
                                        <?php echo implode("", $html_trainings); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- GPROGRAMS -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-gprograms">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Government Programs/Assistance Availed</span>
                                            </th>
                                            <th class="text-secondary">Date Acquired</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-programs-head">
                                        <?php echo implode("", $html_gprograms); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- DIALECT -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-dialect">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Dialect spoken</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-dialect-head">
                                        <?php echo implode("", $html_dialect); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- SINCOME -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-sincome">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">All Applicable Sources of Income</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-sincome-head">
                                        <?php echo implode("", $html_sincome); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- APP -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th class="col-10">
                                                <button type="button" class="btn btn-light me-2 btn-add-app">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Appliances/Gadgets</span>
                                            </th>
                                            <th class="col-2 text-center text-secondary">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-app-head">
                                        <?php echo implode("", $html_app); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- DISABILITY -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-disability">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Type of Disability</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-disability-head">
                                        <?php echo implode("", $html_disability); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- COMORBIDITIY -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-comor">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Type of Comorbidities</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-comor-head">
                                        <?php echo implode("", $html_comor); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- VEHICLE -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th class="col-10">
                                                <button type="button" class="btn btn-light me-2 btn-add-vhcl">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Vehicle owned</span>
                                            </th>
                                            <th class="col-2 text-center text-secondary">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-vhcl-head">
                                        <?php echo implode("", $html_vhcl); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="lead">Supporting documents</h3>
                        <hr class="text-secondary">
                        <div class="alert alert-danger alert-doctype collapse" role="alert"></div><!-- ALERT MESSAGE -->
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="d-flex gap-2">
                                    <label for="doctype">Select document type</label>
                                    <select class="form-select" id="doctype" name="doctype">
                                        <option value="" selected>Select option</option>
                                        <?php if ($doctype): ?>
                                            <?php foreach ($doctype as $row): ?>
                                                <option value="<?= $row->id; ?>"><?= htmlspecialchars($row->description); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <input type="file" class="form-control" name="file-doctype" id="file-doctype" accept=".jpg, .jpeg, .png, .pdf">
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <button type="button" class="btn btn-primary submit-doctype" name="submit-doctype" id="submit-doctype">Upload</button>
                            </div>
                        </div>
                        <div class="table-response">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>File Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-doctype-head">
                                    <?php echo implode("", $html_docs); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                        <a href="<?= base_url(); ?>resident/active_inactive" class="btn btn-light">
                            <i class="fi fi-rs-angle-left me-2"></i><span>Back</span>
                        </a>
                        <button type="submit" class="btn btn-primary" name="submitFormHead" id="submitFormHead">
                            <span>Next</span><i class="fi fi-rs-angle-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- HOUSEHOLD HEAD FORM -->

            <!-- HOUSEHOLD MEMBER TABLE -->
            <div class="container-member collapse mt-4">
                <div class="d-flex justify-content-end my-2">
                    <button type="button" class="btn btn-primary show-mbr-form"><i class='bx bx-plus me-2'></i>Add Member</button>
                </div>
                <hr class="text-secondary">
                <table class="table table-hover table-bordered table-striped mt-4" id="table-member" style="width:100%">
                    <thead>
                        <tr>
                            <th>Resident ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Civil Status</th>
                            <th>Relation to Household Head</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                    <button class="btn btn-light show-head">
                        <i class="fi fi-rs-angle-left me-2"></i><span>Back</span>
                    </button>
                    <button class="btn btn-primary show-info">
                        <i class="fi fi-rs-angle-right me-2"></i><span>Next</span>
                    </button>
                </div>
            </div>
            <!-- HOUSEHOLD MEMBER TABLE -->

            <!-- HOUSEHOLD MEMBER FORM -->
            <div class="container-member-form collapse">
                <form id="memberForm" method="POST" enctype="multipart/form-data">
                    <div class="alert-area"></div>
                    <div class="container-fluid bg-light p-2 rounded my-2 d-flex gap-2">

                        <div class="profile-container border p-2">
                            <img src='<?= base_url(); ?>public/assets/images/logo.png' id="img-mbr" name="img-mbr" class='profile-img2'>
                            <i class='bx bx-upload profile-icon' id="btn-upload-profile2" ></i>
                            <input type="file" class="form-control collapse" id="file-mbr" name="file-mbr" accept=".png, .jpg, .jpeg, .gif">
                        </div>

                        
                        <div>
                            <input type="hidden" name="id" id="id-mbr">
                            <h1 class="text-dark fw-bold fullname-mbr"></h1>
                            <small><b>Household ID: </b><span class='household-id'></span></small>
                            
                            <input type="hidden" name="img-path" id="img-path-mbr">
                            <input type="hidden" name="household" id="household-mbr" value="<?= $data->household ?? ''; ?>">
                        </div>


                    </div>
                    <!-- lname,fname,mname,suffix -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname-mbr" placeholder="Enter your last name" required>
                            <div class="invalid-feedback">Please provide your last name.</div>
                        </div>
                        <div class="col">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname-mbr" placeholder="Enter your first name" required>
                            <div class="invalid-feedback">Please provide your first name.</div>
                        </div>
                        <div class="col">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname-mbr" placeholder="Enter your middle name">
                        </div>
                        <div class="col">
                            <label for="suffix">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix-mbr" placeholder="Enter your suffix">
                        </div>
                    </div>
                    <!-- bday,bplace,gender,cstatus -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="bday">Birthday</label>
                            <input type="text" class="form-control datetimepicker" name="bday" id="bday-mbr" placeholder="mm-dd-yyyy" required>
                            <div class="invalid-feedback">Please provide your birthday.</div>
                        </div>
                        <div class="col">
                            <label for="bplace">Birthplace</label>
                            <input type="text" class="form-control" name="bplace" id="bplace-mbr" placeholder="Enter your birthplace">
                        </div>
                        <div class="col">
                            <label for="gender">Gender</label>
                            <select class="form-select" id="gender-mbr" name="gender" required>
                                <option value="" selected>Select option</option>
                                <option value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                            </select>
                            <div class="invalid-feedback">Please provide your gender.</div>
                        </div>
                        <div class="col">
                            <label for="cstatus">Civil Status</label>
                            <select class="form-select" name="cstatus" id="cstatus-mbr" required>
                                <option value="" selected>Select option</option>
                                <?php foreach ($cstatus as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your civil status.</div>
                        </div>
                    </div>
                    <!-- educ,course,rel,occ -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="education">Educational Attainment</label>
                            <select class="form-select" id="education-mbr" name="education">
                                <option value="" selected>Select option</option>
                                <?php foreach ($educ as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="course">Course</label>
                            <select class="form-select" id="course-mbr" name="course">
                                <option value="" selected>Select option</option>
                                <?php foreach ($course as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="religion">Religion</label>
                            <select class="form-select" id="religion-mbr" name="religion">
                                <option value="" selected>Select option</option>
                                <?php foreach ($rel as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="occupation">Occupation</label>
                            <select class="form-select" id="occupation-mbr" name="occupation" required>
                                <option value="" selected>Select option</option>
                                <?php foreach ($occ as $row) : ?>
                                    <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your occupation.</div>
                        </div>
                    </div>
                    <!-- philhealth_no,m_income,cp,email -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="philhealth">Philhealth No.</label>
                            <input type="text" class="form-control" id="philhealth-mbr" name="philhealth" placeholder="Enter your philhealth no.">
                        </div>
                        <div class="col">
                            <label for="monthly_income">Monthly Income</label>
                            <input type="number" class="form-control" id="monthly_income-mbr" name="monthly_income" placeholder="Enter your monthly income">
                        </div>
                        <div class="col">
                            <label for="cp">Contact No.</label>
                            <input type="text" class="form-control" id="cp-mbr" name="cp" placeholder="Enter your contact no." required>
                            <div class="invalid-feedback">Please provide your contact no.</div>
                        </div>
                        <div class="col">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email-mbr" name="email" placeholder="Enter your email address" required>
                            <div class="invalid-feedback">Please provide your email.</div>
                        </div>
                    </div>
                    <!-- nstatus,btype,height,weight -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="nstatus">Nutritional Status</label>
                            <select class="form-select select2" id="nstatus-mbr" name="nstatus">
                                <option value="" selected>Select option</option>
                                <option value="N">Normal</option>
                                <option value="UW">Underweight</option>
                                <option value="SeUW">Severely Underweight</option>
                                <option value="St">Stunted</option>
                                <option value="SeSt">Severely Stunted</option>
                                <option value="MW">Moderately Wasted</option>
                                <option value="SeW">Severely Wasted</option>
                                <option value="OW">Overweight</option>
                                <option value="O">Obese</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="btype">Blood type</label>
                            <select class="form-select select2" name="btype" id="btype-mbr">
                                <option value="" selected>Select option</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="height">Height (cm)</label>
                            <input type="number" class="form-control" id="height-mbr" name="height" placeholder="Enter your height">
                        </div>
                        <div class="col">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" class="form-control" id="weight-mbr" name="weight" placeholder="Enter your weight">
                        </div>
                    </div>
                    <!-- relation_hh, relation_fh, fh -->
                    <div class="row mb-2">
                        <div class="col">
                            <label for="relation_hh">Relation to Household Head</label>
                            <select class="form-select select2" id="relation_hh" name="relation_hh" required>
                                <option value="" selected>Select option</option>
                                <?php if ($relation): ?>
                                    <?php foreach ($relation as $row): ?>
                                        <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your relationship.</div>
                        </div>
                        <div class="col">
                            <label for="relation_hh">Relation to Family Head</label>
                            <select class="form-select select2" id="relation_fh" name="relation_fh" required>
                                <option value="" selected>Select option</option>
                                <?php if ($relation): ?>
                                    <?php foreach ($relation as $row): ?>
                                        <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Please provide your relationship.</div>
                        </div>
                        <div class="col">
                            <label for="height">Family Head</label>
                            <select class="form-select select2" id="family_head" name="family_head">
                            </select>
                            <div class="d-flex">
                                <input type="checkbox" class="form-check" name="isFamilyHead" id="isFamilyHead"><label class="text-secondary fw-bold ms-2" for="isFamilyHead"><small>Is Family Head?</small></label>
                            </div>
                        </div>
                        <div class="col">

                        </div>
                    </div>
                    <!-- TABLES -->
                    <div class="row mt-2">
                        <!-- TRAININGS -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-training-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Trainings and Skills</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-trainings-mbr">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- GPROGRAMS -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-gprograms-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Government Programs/Assistance Availed</span>
                                            </th>
                                            <th class="text-secondary">Date Acquired</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-programs-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- DIALECT -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-dialect-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Dialect spoken</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-dialect-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- SINCOME -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-sincome-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">All Applicable Sources of Income</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-sincome-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- APP -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th class="col-10">
                                                <button type="button" class="btn btn-light me-2 btn-add-app-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Appliances/Gadgets</span>
                                            </th>
                                            <th class="col-2 text-center text-secondary">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-app-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- DISABILITY -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-disability-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Type of Disability</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-disability-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- COMORBIDITIY -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-light me-2 btn-add-comor-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Type of Comorbidities</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-comor-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- VEHICLE -->
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered rounded-75">
                                    <thead>
                                        <tr>
                                            <th class="col-10">
                                                <button type="button" class="btn btn-light me-2 btn-add-vhcl-mbr">
                                                    <i class="fi fi-rs-plus"></i>
                                                </button>
                                                <span class="text-secondary">Vehicle owned</span>
                                            </th>
                                            <th class="col-2 text-center text-secondary">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-vhcl-mbr"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <h3 class="lead">Supporting documents</h3>
                        <hr class="text-secondary">
                        <div class="alert alert-danger alert-doctype collapse" role="alert"></div><!-- ALERT MESSAGE -->
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="d-flex gap-2">
                                    <label for="doctype">Select document type</label>
                                    <select class="form-select" id="doctype-mbr" name="doctype">
                                        <option value="" selected>Select option</option>
                                        <?php if ($doctype): ?>
                                            <?php foreach ($doctype as $row): ?>
                                                <option value="<?= $row->id; ?>"><?= htmlspecialchars($row->description); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <input type="file" class="form-control" name="file-doctype" id="file-doctype-mbr" accept=".jpg, .jpeg, .png, .pdf">
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <button type="button" class="btn btn-primary submit-doctype" name="submit-doctype" id="submit-doctype-mbr">Upload</button>
                            </div>
                        </div>
                        <div class="table-response">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>File Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody-doctype-mbr">
                                    <tr>
                                        <td class="text-center" colspan="3">No record found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                        <button class="btn btn-light show-tbl-mbr">
                            <i class="fi fi-rs-angle-left me-2"></i><span>Back</span>
                        </button>
                        <button type="submit" class="btn btn-primary" name="submitFormMBR" id="submitFormMBR">
                            <span>Next</span><i class="fi fi-rs-angle-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- HOUSEHOLD MEMBER FORM -->

            <!-- OTHER HOUSEHOLD INFO FORM -->
            <div class="container-other collapse mt-4">
                <form id="otherForm">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <!-- TABLES -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    Household Information
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <!-- WATER SOURCES -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-water">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Water Sources</span>
                                                            </th>
                                                            <th class="col-2 text-center text-primary">Ave.Consumption/mo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-water">
                                                        <tr>
                                                            <td class="text-center" colspan="2">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- POWER SOURCES -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-power">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Power Sources</span>
                                                            </th>
                                                            <th class="col-2 text-center text-primary">Ave.Consumption/mo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-power">
                                                        <tr>
                                                            <td colspan="2" class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <!-- SANITATION -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <button type="button" class="btn btn-light me-2 btn-add-san">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Sanitation-Toilet Facilities</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-san">
                                                        <tr>
                                                            <td class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- COOKING -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-cook">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Way of Cooking</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-cook">
                                                        <tr>
                                                            <td class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <!-- APPLIANCES -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-app-other">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Appliances/Gadgets</span>
                                                            </th>
                                                            <th class="col-2 text-primary text-center">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-app">
                                                        <tr>
                                                            <td colspan="2" class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- COMMUNICATION -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <button type="button" class="btn btn-light me-2 btn-add-comm">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Communication Line</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-comm">
                                                        <tr>
                                                            <td class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <!-- BUILDING AMENITIES -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <button type="button" class="btn btn-light me-2 btn-add-amenities">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Building Amenities</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-amenities">
                                                        <tr>
                                                            <td class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- VEHICLE -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-vhcl-other">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Vehicle owned</span>
                                                            </th>
                                                            <th class="col-2 text-center text-primary">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-vhcl">
                                                        <tr>
                                                            <td colspan="2" class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <!-- AGRICULTURAL MACHINERIES -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-amach">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Agricultural Machineries</span>
                                                            </th>
                                                            <th class="col-2 text-primary text-center">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-amach">
                                                        <tr>
                                                            <td colspan="2" class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- VEHICLE -->
                                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered rounded-75">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-10">
                                                                <button type="button" class="btn btn-light me-2 btn-add-alive">
                                                                    <i class="fi fi-rs-plus"></i>
                                                                </button>
                                                                <span class="text-primary">Agricultural Livestocks</span>
                                                            </th>
                                                            <th class="col-2 text-center text-primary">Qty</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody-alive">
                                                        <tr>
                                                            <td colspan="2" class="text-center">No record found</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUILDING INFORMATION -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    Building Information
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Building Type</label>
                                                <select class="form-select" name="bldg-type" id="bldg-type">
                                                    <option value="" selected>Select Building Type</option>
                                                    <?php foreach ($bldgtype as $row) : ?>
                                                        <option value="<?= $row->id; ?>"><?= $row->description; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Construction Yr</label>
                                                <input type="text" name="construction-yr" id="construction-yr" class="form-control datetimepicker" placeholder="mm-dd-yyyy">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Yr Occupied</label>
                                                <input type="text" name="yr-occupied" id="yr-occupied" class="form-control datetimepicker" placeholder="mm-dd-yyyy">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Building Permit No.</label>
                                                <input type="text" name="bldg-permit-no" id="bldg-permit-no" class="form-control" placeholder="Enter building permit no">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Erected on Lot No.</label>
                                                <input type="text" name="lot-no" id="lot-no" class="form-control" placeholder="Enter Lot No.">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- GARBAGE INFORMATION -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingThree">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                    Average of weekly generated garbages
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Hazardous</label>
                                                <select class="form-select" name="hazardous" id="hazardous">
                                                    <option value="" selected>Select ...</option>
                                                    <option value="0-5 kg">0-5 kg</option>
                                                    <option value="5-10 kg">5-10 kg</option>
                                                    <option value="10-15kg">10-15kg</option>
                                                    <option value="15kg up">15kg up</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Recyclable</label>
                                                <select class="form-select" name="recyclable" id="recyclable">
                                                    <option value="" selected>Select ...</option>
                                                    <option value="0-5 kg">0-5 kg</option>
                                                    <option value="5-10 kg">5-10 kg</option>
                                                    <option value="10-15kg">10-15kg</option>
                                                    <option value="15kg up">15kg up</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Residual</label>
                                                <select class="form-select" name="residual" id="residual">
                                                    <option value="" selected>Select ...</option>
                                                    <option value="5-10 kg">5-10 kg</option>
                                                    <option value="10-15 kg">10-15 kg</option>
                                                    <option value="15-20 kg">15-20 kg</option>
                                                    <option value="20kg up">20kg up</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                            <div class="d-flex justify-content-start align-items-start flex-column">
                                                <label>Biodegradable</label>
                                                <select class="form-select" name="biodegradable" id="biodegradable">
                                                    <option value="" selected>Select ...</option>
                                                    <option value="10-20 kg">10-20 kg</option>
                                                    <option value="20-40 kg">20-40 kg</option>
                                                    <option value="40-60 kg">40-60 kg</option>
                                                    <option value="60kg up">60kg up</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                        <button type="button" class="btn btn-light show-tbl-mbr">
                            <i class="fi fi-rs-angle-left me-2"></i><span>Back</span>
                        </button>
                        <button type="button" class="btn btn-primary" id="submitOther">
                            <span>Next</span><i class="fi fi-rs-angle-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- OTHER HOUSEHOLD INFO FORM -->

            <!-- FINAL FORM -->
            <div class="container-form collapse mt-4">
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
                                    <span class="brgy-name"></span>
                                </p>
                                <h2 class="fw-bold">Barangay Information System</h2>
                                <small class="fw-bold">HOUSEHOLD ID NO</small>
                                <h5 class="p-household"></h5>
                            </div>
                            <div class="col-3">
                                <div class="d-flex justify-content-end align-items-center">
                                    <img src="<?= base_url(); ?>public/assets/images/logo.png" alt="qr-code" class="p-qr" style="width:150px;height:150px;">
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
                                    <img src="<?= base_url(); ?>public/assets/images/logo.png" alt="pic" class="p-img" style="width:150px;height:150px">
                                    <small>Status</small>
                                    <small class="p-status fw-bold"></small>
                                    <div class="mt-2 d-flex justify-content-center align-items-center flex-column">
                                        <small>Household Head ID</small>
                                        <small class="p-head-id fw-bold"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <!-- NAME -->
                                <div class="row border border-dark">
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-lname fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-fname fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-mname fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-suffix fw-bold"></small>
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
                                        <small class="p-bday fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-bplace fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-gender fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-cstatus fw-bold"></small>
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
                                        <small class="p-educ fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-course fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-rel fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-occ fw-bold"></small>
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
                                        <small class="p-phealth_no fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-m_income fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-cp fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-email fw-bold"></small>
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
                                        <small class="p-nstatus fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-btype fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-height fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-weight fw-bold"></small>
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
                                        <small class="p-brgy fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-purok fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-street fw-bold"></small>
                                    </div>
                                    <div class="col-3 border border-light text-center">
                                        <small class="p-house_no fw-bold"></small>
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
                            <tbody class="p-member"></tbody>
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
                                    <tbody class="tbody-p-water-other"></tbody>
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
                                    <tbody class="tbody-p-power-other"></tbody>
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
                                    <tbody class="tbody-p-san-other"></tbody>
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
                                    <tbody class="tbody-p-cook-other"></tbody>
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
                                    <tbody class="tbody-p-app-other"></tbody>
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
                                    <tbody class="tbody-p-comm-other"></tbody>
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
                                    <tbody class="tbody-p-amenities-other"></tbody>
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
                                    <tbody class="tbody-p-vhcl-other"></tbody>
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
                                    <tbody class="tbody-p-amach-other"></tbody>
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
                                    <tbody class="tbody-p-alive-other"></tbody>
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
                                <span class="p-bldg-type fw-bold"></span>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <span class="p-construction-yr fw-bold"></span>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <span class="p-yr-occupied fw-bold"></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-bldg-permit fw-bold"></span>
                            </div>
                            <div class="col-2 border border-light text-center">
                                <span class="p-lot-no fw-bold"></span>
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
                                <span class="p-hazardous fw-bold"></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-recyclable fw-bold"></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-residual fw-bold"></span>
                            </div>
                            <div class="col-3 border border-light text-center">
                                <span class="p-biodegradable fw-bold"></span>
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
                    <!-- PREPARED BY -->
                    <div class="col-4 mt-4">
                        <label class="mb-4">Prepared by:</label>
                        <div class="mt-2 border-bottom border-dark">
                            <b class="c-user"></b>
                        </div>
                        <p class="fst-italic"><?= (session()->has("role") && session()->get("role") == "ADMIN") ? "System Administrator" : "System Encoder"; ?></p>
                        Date generated: <small class="c-dated"></small>

                    </div>
                </div>
                <div class="my-bg-blue-subtle p-2 d-flex justify-content-end align-items-center gap-2">
                    <button type="button" class="btn btn-light show-other">
                        <i class="fi fi-rs-angle-left me-2"></i><span>Back</span>
                    </button>
                    <button type="button" class="btn btn-light btn-print-form">
                        <span>Print</span><i class="fi fi-rs-print ms-2"></i>
                    </button>
                    <form action="<?= base_url(); ?>resident/doneSubmission" method="post">
                        <button type="submit" class="btn btn-primary btn-done">
                            <span>Done</span><i class="fi fi-rs-check ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
            <!-- FINAL FORM -->
        </div>
    </div>
    </div>
</main>

<?= $this->include('administrator/include/category-modal'); ?>

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {

        // ========== datetimepicker ========== //
        function enable_datepicker() {
            flatpickr(".datetimepicker", {
                dateFormat: "m-d-Y", // Set date format
                enableTime: false, // Enable time selection
            });
        }
        enable_datepicker();

        /**
         * functions to show containers
         */
        // Helper function to show a specific container and update the UI accordingly
        // Helper function to show the specific container and update the UI
        function showContainer(containerToShow, progressWidth, activeTab) {
            // Hide all containers
            $(".container-head, .container-member, .container-other, .container-form, .container-member-form").hide();

            // Show the container we need
            $(containerToShow).show();

            // Reset all tab styles to inactive (text-secondary)
            $(".tab-head, .tab-member, .tab-other, .tab-form").removeClass("text-primary").addClass("text-secondary");

            // Add the "text-primary" class to the active tab
            $(activeTab).removeClass("text-secondary").addClass("text-primary");

            // Update the progress bar width
            $(".progress-bar").css("width", progressWidth);
        }

        // Specific container functions that call the helper
        function show_container_head() {
            showContainer(".container-head", "25%", ".tab-head");
        }

        function show_container_member() {
            showContainer(".container-member", "50%", ".tab-member");
        }

        function show_container_other() {
            showContainer(".container-other", "75%", ".tab-other");
        }

        function show_container_form() {
            showContainer(".container-form", "100%", ".tab-form");
        }

        function show_member_form() {
            // Hide all containers
            $(".container-head, .container-member, .container-other, .container-form").hide();
            // Show form
            $(".container-member-form").show();
        }

        // Clear tables for member form
        function clear_tables() {
            let tables = [
                ".tbody-trainings-mbr",
                ".tbody-programs-mbr",
                ".tbody-dialect-mbr",
                ".tbody-sincome-mbr",
                ".tbody-app-mbr",
                ".tbody-disability-mbr",
                ".tbody-comor-mbr",
                ".tbody-vhcl-mbr",
                ".tbody-doctype-mbr"
            ];

            tables.forEach((tbl) => {
                var table = $(tbl).closest("table");
                var thCount = table.find("th").length;
                $(tbl).html(
                    `<tr><td colspan='${thCount}' class='text-center'>No record found</td></tr>`
                );
            });
            // Clear name
            $(".fullname-mbr").html('');

            // Reset the form fields
            $("#memberForm")[0].reset();

            // Reset the form validation
            $("#memberForm").validate().resetForm();
            $("#memberForm .error").removeClass("error"); // Remove validation error class
            $("#memberForm .valid").removeClass("valid"); // Remove valid class (if needed)

            // Reset Select2 dropdowns
            $("#memberForm select").each(function() {
                $(this).val(null).trigger('change'); // Reset Select2 value and trigger change
            });

        }

        // Show alert
        function showAlert(content) {
            var myAlert = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ${content}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`;

            $(".alert-area").html(myAlert);
            $(document).scrollTop(0); // Scroll to top to show the error alert
        }
        // Show member form
        $(document).on("click", ".show-mbr-form", function(e) {
            e.preventDefault();
            // Reset the form validation
            clearValidationClasses('#memberForm');
            show_member_form();
        })
        // Show table member
        $(document).on("click", ".show-tbl-mbr", function(e) {
            e.preventDefault();
            show_container_member();
        })
        // SHow household head form
        $(document).on("click", ".show-head", function(e) {
            e.preventDefault();
            show_container_head();
        })
        // SHow other form
        $(document).on("click", ".show-other", function(e) {
            e.preventDefault();
            show_container_other();
        })
        // SHow printable form
        $(document).on("click", ".show-form", function(e) {
            e.preventDefault();
            show_container_form();
        })

        // FUNCTION FETCH HOUSEHOLD MEMBER
        function fetch_family_heads(household_id) {
            if (household_id) {
                $.ajax({
                    url: "<?= site_url('/resident/getFamilyHeads') ?>/" + household_id,
                    type: "GET",
                    dataSrc: "data",
                    success: function(res) {
                        if (res.data) {
                            let data = res.data;
                            let options = "";
                            data.forEach(function(row) {
                                options += `<option value='${row.id}'>${row.name}</option>`;
                            });
                            // Populate dropdown
                            $('#family_head').html(options);
                        }
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error:', error);
                        console.error('XHR:', xhr);
                    }
                })
            }
        }

        // SHOW OTHER INFORMATION
        function load_other_info(household_id) {
            if (household_id) {
                $.ajax({
                    url: "<?= site_url('/resident/getOtherInfo') ?>/" + household_id,
                    type: "GET",
                    dataSrc: "data",
                    success: function(res) {
                        var data = res.data;
                        $(".tbody-water").html(data.water);
                        $(".tbody-power").html(data.power);
                        $(".tbody-san").html(data.san);
                        $(".tbody-cook").html(data.cook);
                        $(".tbody-app").html(data.app);
                        $(".tbody-comm").html(data.comm);
                        $(".tbody-amenities").html(data.amenities);
                        $(".tbody-vhcl").html(data.vhcl);
                        $(".tbody-amach").html(data.amach);
                        $(".tbody-alive").html(data.alive);
                        //Bldg information
                        $("#bldg-type")
                            .val(data.bldginfo.bldg_type_id)
                            .trigger("change.select2");
                        $("#construction-yr").val(
                            data.bldginfo.construction_yr !== "" ?
                            data.bldginfo.construction_yr :
                            ""
                        );
                        $("#yr-occupied").val(
                            data.bldginfo.yr_occupied !== "" ?
                            data.bldginfo.yr_occupied :
                            ""
                        );
                        $("#bldg-permit-no").val(data.bldginfo.bldg_permit_no);
                        $("#lot-no").val(data.bldginfo.lot_no);
                        // Garbage info
                        $("#hazardous")
                            .val(data.garbages.hazardous)
                            .trigger("change.select2");
                        $("#recyclable")
                            .val(data.garbages.recyclable)
                            .trigger("change.select2");
                        $("#residual").val(data.garbages.residual).trigger("change.select2");
                        $("#biodegradable")
                            .val(data.garbages.biodegradable)
                            .trigger("change.select2");
                    },
                    error: function(err) {
                        console.error(err);
                    },
                });
            }
        }

        // Initialize the DataTable
        var i = "0000-00-00"; // Initial household ID
        var tblMember = $('#table-member').DataTable({
            "ajax": {
                "url": "<?= site_url('/resident/load_household_member') ?>/" + i,
                "type": "GET",
                "dataSrc": "data", // The response should have a 'data' field containing the records
                "error": function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            },
            "columns": [{
                    "data": "resident_id"
                },
                {
                    "data": "name"
                },
                {
                    "data": "age"
                },
                {
                    "data": "cstatus"
                },
                {
                    "data": "relation_hh"
                },
                {
                    "data": null, // For actions (buttons)
                    "render": function(data, type, row) {
                        var id = row['id'];
                        var baseUrl = '<?= base_url() ?>';

                        var btn = `
                    <div class='btn-group' role='group'>
                        <button type='button' class='btn btn-sm btn-primary dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'>
                            <i class='fi fi-rs-burger-menu'></i>
                        </button>
                        <ul class='dropdown-menu'>
                            <li>
                                <button class='dropdown-item btnUpdate-mbr' data-id='${id}' role='button'>
                                    <span class='icon-text'>Update</span>
                                </button>
                            </li>
                            
                        </ul>
                    </div>
                `;
                        return btn;
                    }
                }
            ]
        });

        // ========== select2 ========== //
        $('.form-select').select2();

        // ========== Update fullname ========== //
        function formatFullname(data) {
            let fname = data.fname;
            let mname = data.mname;
            let lname = data.lname;
            let suffix = data.suffix;
            let fullname = "";

            if (lname != "") {
                fullname = lname;
            }
            if (suffix != "") {
                fullname += " " + suffix;
            }
            fullname += ", ";

            if (fname != "") {
                fullname += fname + " ";
            }

            if (mname != "") {
                fullname += mname;
            }

            if (lname == "" && fname == "") {
                fullname = "";
            }

            return fullname;

        }

        // Refactored to reduce redundancy
        function updateFullname(selectorSuffix, fullnameSelector) {
            $(document).on("input", `#fname${selectorSuffix ? '-' + selectorSuffix : ''}, #mname${selectorSuffix ? '-' + selectorSuffix : ''}, #lname${selectorSuffix ? '-' + selectorSuffix : ''}, #suffix${selectorSuffix ? '-' + selectorSuffix : ''}`, (e) => {
                e.preventDefault();
                let data = {
                    lname: $(`#lname${selectorSuffix ? '-' + selectorSuffix : ''}`).val().trim().toUpperCase(),
                    fname: $(`#fname${selectorSuffix ? '-' + selectorSuffix : ''}`).val().trim().toUpperCase(),
                    mname: $(`#mname${selectorSuffix ? '-' + selectorSuffix : ''}`).val().trim().toUpperCase(),
                    suffix: $(`#suffix${selectorSuffix ? '-' + selectorSuffix : ''}`).val().trim().toUpperCase()
                };
                $(fullnameSelector).html(formatFullname(data));
            });
        }

        // Update fullname for display
        updateFullname("", ".fullname");

        // Update fullname for household member
        updateFullname("mbr", ".fullname-mbr");


        // ========== // LOAD SELECTED IMAGE TO <IMG> ========== //
        function load_image(head_member, input) {
            var reader = new FileReader();
            var img = (head_member === "head") ? $("#img-head") : $("#img-mbr");

            reader.onload = function() {
                var dataURL = reader.result;
                img.attr("src", dataURL);
            };

            // Read the selected file as Data URL
            reader.readAsDataURL(input.files[0]);


        }

        $("#file-head").change(function(event) {
            var input = event.target;
            load_image("head", input);
        });

        $("#file-mbr").change(function(event) {
            var input = event.target;
            load_image("member", input);
        });

        // ========== UPDATE HOUSEHOLD ID ========== //
        $(document).on("change", "#barangay", function(e) {
            e.preventDefault();
            updateHouseholdID();
        })
        $(document).on("change", "#purok", function(e) {
            e.preventDefault();
            updateHouseholdID();
        })
        $(document).on("change", "#house_no", function(e) {
            e.preventDefault();
            updateHouseholdID();
        })

        function updateHouseholdID() {
            let barangay = $("#barangay").val().trim();
            let purok = $("#purok").val().trim();
            let house_no = $("#house_no").val().trim();

            if (barangay != "" && purok != "" && house_no != "") {
                let data = {
                    barangay: barangay,
                    purok: purok,
                    house_no: house_no
                };

                $.ajax({
                    url: "<?= site_url('/resident/getHouseholdID') ?>",
                    type: "POST",
                    data: data, // Send the data as an object
                    success: function(response) {
                        // Handle the success response here
                        if (response.data != false) {
                            $(".household-id").html(response.data);
                            $("#household-mbr").val(response.data);
                        }
                    },
                    error: function(xhr, error, thrown) {
                        console.error("Error: ", error);
                        console.error("XHR:", xhr);
                    }
                });
            }

        }

        // ============== FORM TABLES ============== //

        var table; // Define table variable
        var modal_title = "";

        function loadCategory(category) {
            $("#chkList").prop("checked", false);
            $(".category-title").text(modal_title);
            $.ajax({
                url: "<?= site_url('/resident/loadCategory') ?>",
                method: "POST",
                data: {
                    category: category
                },
                success: function(res) {
                    var data = res;

                    // Destroy existing DataTable instance if it exists
                    if (table && table instanceof $.fn.dataTable.Api) {
                        table.destroy();
                    }

                    $(".tbody-list").html(data);
                    $("#category-list").val(category);

                    // Initialize DataTable after loading data
                    table = $("#table-list").DataTable({
                        pageLength: 10, // Set the number of rows per page
                        searching: true, // Enable searching
                        columnDefs: [{
                            targets: [0], // name column
                            type: "text",
                            searchable: true,
                        }, ],
                    });

                    select_list_category();
                },
                error: function(err) {
                    console.error(err);
                },
            });
        }

        // ======== ADD TRAINING ======== //
        $(document).on("click", ".btn-add-training", function() {
            modal_title = "Training/Skills";
            loadCategory("training");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-training-mbr", function() {
            modal_title = "Training/Skills";
            loadCategory("training");
            head_or_member = "member";
        });

        // ======== ADD gprograms  ======== //
        $(document).on("click", ".btn-add-gprograms", function() {
            modal_title = "Government Programs/Assistance";
            loadCategory("gprograms");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-gprograms-mbr", function() {
            modal_title = "Government Programs/Assistance";
            loadCategory("gprograms");
            head_or_member = "member";
        });

        // ======== ADD dialect  ======== //
        $(document).on("click", ".btn-add-dialect", function() {
            modal_title = "Dialect";
            loadCategory("dialect");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-dialect-mbr", function() {
            modal_title = "Dialect";
            loadCategory("dialect");
            head_or_member = "member";
        });

        // ======== ADD sincome  ======== //
        $(document).on("click", ".btn-add-sincome", function() {
            modal_title = "Sources of Income";
            loadCategory("sincome");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-sincome-mbr", function() {
            modal_title = "Sources of Income";
            loadCategory("sincome");
            head_or_member = "member";
        });

        // ======== ADD appliances  ======== //
        $(document).on("click", ".btn-add-app", function() {
            modal_title = "Appliances/Gadgets";
            loadCategory("app");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-app-mbr", function() {
            modal_title = "Appliances/Gadgets";
            loadCategory("app");
            head_or_member = "member";
        });

        $(document).on("click", ".btn-add-app-other", function() {
            modal_title = "Appliances/Gadgets";
            loadCategory("app");
            head_or_member = "other";
        });

        // ======== ADD disability  ======== //
        $(document).on("click", ".btn-add-disability", function() {
            modal_title = "Disability";
            loadCategory("disability");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-disability-mbr", function() {
            modal_title = "Disability";
            loadCategory("disability");
            head_or_member = "member";
        });

        // ======== ADD comorbidities  ======== //
        $(document).on("click", ".btn-add-comor", function() {
            modal_title = "Comorbidity";
            loadCategory("comor");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-comor-mbr", function() {
            modal_title = "Comorbidity";
            loadCategory("comor");
            head_or_member = "member";
        });

        // ======== ADD vehicle  ======== //
        $(document).on("click", ".btn-add-vhcl", function() {
            modal_title = "Vehicle";
            loadCategory("vhcl");
            head_or_member = "head";
        });

        $(document).on("click", ".btn-add-vhcl-mbr", function() {
            modal_title = "Vehicle";
            loadCategory("vhcl");
            head_or_member = "member";
        });

        $(document).on("click", ".btn-add-vhcl-other", function() {
            modal_title = "Vehicle";
            loadCategory("vhcl");
            head_or_member = "other";
        });

        // ======== ADD water  ======== //
        $(document).on("click", ".btn-add-water", function() {
            modal_title = "Water Sources";
            loadCategory("water");
            head_or_member = "other";
        });

        // ======== ADD power  ======== //
        $(document).on("click", ".btn-add-power", function() {
            modal_title = "Power Sources";
            loadCategory("power");
            head_or_member = "other";
        });

        // ======== ADD sanitation  ======== //
        $(document).on("click", ".btn-add-san", function() {
            modal_title = "Sanitation-Toilet Facilities";
            loadCategory("san");
            head_or_member = "other";
        });

        // ======== ADD cooking  ======== //
        $(document).on("click", ".btn-add-cook", function() {
            modal_title = "Type of Cooking";
            loadCategory("cook");
            head_or_member = "other";
        });

        // ======== ADD communication  ======== //
        $(document).on("click", ".btn-add-comm", function() {
            modal_title = "Type of Communication";
            loadCategory("comm");
            head_or_member = "other";
        });

        // ======== ADD bldg amenities  ======== //
        $(document).on("click", ".btn-add-amenities", function() {
            modal_title = "Building Amenities";
            loadCategory("amenities");
            head_or_member = "other";
        });

        // ======== ADD agricultural machineries  ======== //
        $(document).on("click", ".btn-add-amach", function() {
            modal_title = "Agricultural Machineries";
            loadCategory("amach");
            head_or_member = "other";
        });

        // ======== ADD agricultural livestocks  ======== //
        $(document).on("click", ".btn-add-alive", function() {
            modal_title = "Agricultural Livestock";
            loadCategory("alive");
            head_or_member = "other";
        });

        // Select all from the list
        $(document).on("change", ".chkList", function() {
            // Get the check state of the header checkbox
            var isChecked = $(this).prop("checked");

            // Apply the check state to all checkboxes in the table rows
            $(".chkRow").prop("checked", isChecked);
        });
        // ======== ADD SELECTED TO THE CORRESPONDING TABLE  ======== //
        $(document).on("click", ".select-list", function() {
            var checked = false;
            var selected = []; // Array to store selected rows HTML
            // CHECK CORRESPONDING CATEGORY
            var category = $("#category-list").val();
            var tbl;
            switch (category) {
                case "training":
                    tbl =
                        head_or_member === "head" ?
                        $(".tbody-trainings-head") :
                        $(".tbody-trainings-mbr");
                    break;
                case "gprograms":
                    tbl =
                        head_or_member === "head" ?
                        $(".tbody-programs-head") :
                        $(".tbody-programs-mbr");
                    break;
                case "dialect":
                    tbl =
                        head_or_member === "head" ?
                        $(".tbody-dialect-head") :
                        $(".tbody-dialect-mbr");
                    break;
                case "sincome":
                    tbl =
                        head_or_member === "head" ?
                        $(".tbody-sincome-head") :
                        $(".tbody-sincome-mbr");
                    break;
                case "app":
                    if (head_or_member === "head") {
                        tbl = $(".tbody-app-head");
                    } else if (head_or_member === "member") {
                        tbl = $(".tbody-app-mbr");
                    } else {
                        tbl = $(".tbody-app");
                    }
                    break;
                case "disability":
                    tbl =
                        head_or_member === "head" ?
                        $(".tbody-disability-head") :
                        $(".tbody-disability-mbr");
                    break;
                case "comor":
                    tbl =
                        head_or_member === "head" ?
                        $(".tbody-comor-head") :
                        $(".tbody-comor-mbr");
                    break;
                case "vhcl":
                    if (head_or_member === "head") {
                        tbl = $(".tbody-vhcl-head");
                    } else if (head_or_member === "member") {
                        tbl = $(".tbody-vhcl-mbr");
                    } else {
                        tbl = $(".tbody-vhcl");
                    }
                    break;
                case "water":
                    tbl = $(".tbody-water");
                    break;
                case "power":
                    tbl = $(".tbody-power");
                    break;
                case "san":
                    tbl = $(".tbody-san");
                    break;
                case "cook":
                    tbl = $(".tbody-cook");
                    break;
                case "comm":
                    tbl = $(".tbody-comm");
                    break;
                case "amenities":
                    tbl = $(".tbody-amenities");
                    break;
                case "amach":
                    tbl = $(".tbody-amach");
                    break;
                case "alive":
                    tbl = $(".tbody-alive");
                    break;
                default:
                    tbl = "";
                    break;
            }

            var ctr_selectedExist = 0;
            $(".tbody-list tr").each(function() {
                var chkbox = $(this).find(".chkRow");
                var id = chkbox.attr("data-id"); // Simplified accessing data-id
                var desc = $(this).find("label").text();
                if (chkbox.prop("checked")) {
                    checked = true;
                    // CHECK TO THE tbl if it already has an input['checkbox'] with the same data-id, if found then do not include in selected array
                    if (tbl.find(`[data-id='${id}']`).length === 0) {
                        if (category === "gprograms") {
                            selected.push(`
                                            <tr>
                                                <td>
                                                    <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='${id}'>
                                                        <i class='fi fi-rs-trash'></i>
                                                    </button>
                                                    <span>${desc}</span>
                                                </td>
                                                <td><input type='text' class='form-control datetimepicker' placeholder='mm-dd-yyyy'></td>
                                            </tr>
                                        `);
                        } else if (
                            category === "app" ||
                            category === "vhcl" ||
                            category === "water" ||
                            category === "power" ||
                            category === "amach" ||
                            category === "alive"
                        ) {
                            selected.push(`
                <tr>
                    <td>
                        <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='${id}'>
                            <i class='fi fi-rs-trash'></i>
                        </button>
                        <span>${desc}</span>
                    </td>
                    <td><input type='number' class='form-control' placeholder='0'></td>
                </tr>
            `);
                        } else {
                            selected.push(`
                <tr>
                    <td>
                        <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='${id}'>
                            <i class='fi fi-rs-trash'></i>
                        </button>
                        <span>${desc}</span>
                    </td>
                </tr>
            `);
                        }
                    } else {
                        ctr_selectedExist++;
                    }
                }
            });

            if (ctr_selectedExist > 0) {
                $(".alert-danger").show();
                $(".alert-danger").html("Selected item/s already on the list");
                $(".alert-danger").fadeOut(8000);
            }

            if (checked && tbl !== "" && selected.length > 0) {
                if (tbl.find("button").length === 0) {
                    tbl.find("tr").remove();
                    tbl.html(selected.join("")); // Replace existing rows with selected ones
                } else {
                    tbl.append(selected.join("")); // Append selected rows if there are any
                }
                $("#category-modal").modal("hide");
            }
            //reinitialize datetimepicker
            enable_datepicker();
        });
        // ======== REMOVE FROM THE LIST  ======== //
        $(document).on("click", ".btnRemove-list", function() {
            // Remove the closest tr (row) from the table
            var rowToRemove = $(this).closest("tr");
            var table = rowToRemove.closest("table");
            var tbody = table.find("tbody");

            rowToRemove.remove();

            // Count the number of th elements in the table
            var thCount = table.find("th").length;

            // Count the number of tr elements in the tbody
            var trCount = tbody.find("tr").length;

            // If there are no remaining rows in the tbody
            if (trCount === 0) {
                // Create a new row indicating that no records are found
                tbody.html(
                    `<tr><td class='text-center' colspan='${thCount}'>No record found</td></tr>`
                );
            }
        });
        // ======== SHOW MODAL - ADD LIST  ======== //
        $(document).on("click", ".add-list", function() {
            var category = $("#category-list").val(); // Get the value of the selected option
            $("#category-list-create").val(category);
            //show modal
            add_list_category();
        });
        // ======== CANCEL BUTTON  ======== //
        $(document).on("click", ".cancel-list", function() {
            select_list_category();
        });
        // ======== SAVE LIST ======== //
        $(document).on("click", ".save-list", function() {
            var category = $("#category-list-create").val().trim();
            var description = $(".desc-create").val().trim();

            if (category !== "" && description !== "") {
                $.ajax({
                    url: "<?= site_url('/resident/createList') ?>",
                    method: "POST",
                    data: {
                        category: category,
                        description: description,
                    },
                    success: function(res) {
                        var parsed = res;
                        if (parsed.status === "success") {
                            // Update .category-title here if needed
                            loadCategory(category); // Pass the modal title
                            select_list_category();
                        } else {
                            show_alert(parsed.errors.join("\n"));
                        }
                    },
                    error: function(err) {
                        console.error(err);
                    },
                });
            } else {
                show_alert("Please fill up the required field");
            }
        });

        function show_alert(err) {
            $(".alert-danger").show();
            $(".alert-danger").html(
                `<i class="fi fi-rs-triangle-warning me-2"></i> <small>${err}</small>`
            );
            $(".alert-danger").fadeOut(8000);
        }

        function clear_category_create() {
            $("#category-list-create").val("");
            $(".desc-create").val("");
        }

        function select_list_category() {
            clear_category_create();
            $("#category-modal").modal("show");
            $("#add-category-modal").modal("hide");
        }

        function add_list_category() {
            $("#category-modal").modal("hide");
            $("#add-category-modal").modal("show");
        }

        // ===========================================//
        // ======== SUPPORTING DOCUMENTS ======== //
        // Remove row from table
        $(document).on("click", ".btnRemove-doctype", function(e) {
            e.preventDefault();
            // Remove the closest tr (row) from the table
            var rowToRemove = $(this).closest("tr");
            var table = rowToRemove.closest("table");
            var tbody = table.find("tbody");

            rowToRemove.remove();

            // Count the number of th elements in the table
            var thCount = table.find("th").length;

            // Count the number of tr elements in the tbody
            var trCount = tbody.find("tr").length;

            // If there are no remaining rows in the tbody
            if (trCount === 0) {
                // Create a new row indicating that no records are found
                tbody.html(
                    `<tr><td class='text-center' colspan='${thCount}'>No record found</td></tr>`
                );
            }
        })

        /**
         * Upload function for supporting documents
         */
        function upload_supporting_docs(head_member) {
            var select = head_member === "member" ? $("#doctype-mbr") : $("#doctype");
            var file = head_member === "member" ? $("#file-doctype-mbr") : $("#file-doctype");
            var tbl = head_member === "member" ? $(".tbody-doctype-mbr") : $(".tbody-doctype-head");
            var doctype_id = select.val();
            var documentDesc = select.find("option:selected").text();
            var fileInput = file[0];

            if (!doctype_id || fileInput.files.length === 0) {
                $(".alert-danger").show().text("Please select document type and/or choose file").fadeOut(8000);
                return;
            }

            // Check if document type already exists in the table
            var exists = tbl.find(".btnRemove-doctype[data-id='" + doctype_id + "']").length > 0;
            if (exists) {
                $(".alert-danger").show().text("Selected document type already on the list").fadeOut(8000);
                return;
            }

            var formData = new FormData();
            formData.append('file-doctype', fileInput.files[0]);

            $.ajax({
                url: "<?= site_url('/resident/uploadDocument') ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        var file_path = response.file_path;
                        file.val(''); // Clear file input
                        $('.alert').hide();

                        // Add the new row to the table
                        var newRow = `
                                        <tr>
                                            <td>${documentDesc}</td>
                                            <td><label>${file_path}</label></td>
                                            <td>
                                                <a href="<?= site_url('/resident/viewFile/'); ?>${file_path}" target="_blank" class="btn btn-sm btn-info btnView-doctype">View</a>
                                                <button type="button" class="btn btn-sm btn-info btnRemove-doctype" data-id="${doctype_id}">Remove</button>
                                            </td>
                                        </tr>
                                    `;

                        if (tbl.find("button").length === 0) {
                            tbl.find("tr").remove();
                            // tbl.html(selected.join("")); // Replace existing rows with selected ones
                            tbl.append(newRow);
                        } else {
                            tbl.append(newRow);
                        }

                    } else {
                        var errorMessages = Array.isArray(response.errors) ? response.errors.join('<p>') : response.errors;
                        $('.alert-doctype').html(errorMessages).show();
                    }
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                    $(".alert-danger").show().text("An error occurred while uploading.").fadeOut(8000);
                }
            });
        }

        // Upload file - head
        $('#submit-doctype').click(function(e) {
            e.preventDefault();
            upload_supporting_docs("head");
        });
        // Upload file - member
        $('#submit-doctype-mbr').click(function(e) {
            e.preventDefault();
            upload_supporting_docs("member");
        });
        /**
         * HOUSEHOLD HEAD - (SAVE)
         */

        function clearValidationClasses(formSelector) {
            var form = $(formSelector);
            form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
            form.removeClass('was-validated');
        }

        function checkValidDate(rawDate) {
            if (rawDate === "") {
                return false;
            }
            // Manually validate the date format
            var bdayValue = rawDate;
            var datePattern = /^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])-\d{4}$/;

            if (datePattern.test(bdayValue)) {
                // If the date format is valid, proceed
                return true;
            } else {
                // If the date format is invalid
                return false;
            }
        }


        // Initialize Bootstrap validation
        $("#residentForm").validate();

        $(document).on("click", "#submitFormHead", function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Check if the form is valid
            if ($("#residentForm")[0].checkValidity()) {
                // Proceed with form submission
                // Get training and skills
                let training = [];
                $(".tbody-trainings-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    training.push({
                        id: id,
                    });
                });
                // Get Government programs
                let gprograms = [];
                $(".tbody-programs-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    var dateAcquired = $(this).find("input").val();
                    gprograms.push({
                        id: id,
                        dateAcquired: dateAcquired,
                    });
                });
                // Get dialect spoken
                let dialect = [];
                $(".tbody-dialect-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    dialect.push({
                        id: id,
                    });
                });
                // Get source of income
                let sincome = [];
                $(".tbody-sincome-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    sincome.push({
                        id: id,
                    });
                });
                // Get Appliances/Gadgets
                let app = [];
                $(".tbody-app-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    var qty = $(this).find("input").val();
                    app.push({
                        id: id,
                        qty: qty,
                    });
                });
                // Get disabilities
                let disability = [];
                $(".tbody-disability-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    disability.push({
                        id: id,
                    });
                });
                // Get comorbidities
                let comor = [];
                $(".tbody-comor-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    comor.push({
                        id: id,
                    });
                });
                // Get vehicles
                let vhcl = [];
                $(".tbody-vhcl-head tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    var qty = $(this).find("input").val();
                    vhcl.push({
                        id: id,
                        qty: qty,
                    });
                });
                // Get supporting documents
                let docs = [];
                $(".tbody-doctype-head tr").each(function() {
                    var doctype_id = $(this).find("button").attr("data-id");
                    var file_path = $(this).find("label").text();
                    docs.push({
                        id: doctype_id,
                        file_path: file_path,
                    });
                });

                // CHECK IF THERE IS SUBMITTED SUPPORTING DOCUMENTS
                // CHECK VALID DATE
                if (!checkValidDate($("#bday").val())) {
                    // Show alert, please attach supporting documents for review
                    showAlert('Please check bday format');
                } else if (docs.length > 0 && docs[0].id) {
                    // Create a new FormData object
                    var formData = new FormData();
                    // Picture
                    var pic = $("#file-head")[0].files[0] ? $("#file-head")[0].files[0] : "";

                    // Append the file input to FormData object
                    formData.append("pic", pic);

                    // Append other form data to FormData object
                    formData.append("formData", $("#residentForm").serialize());
                    formData.append("training", JSON.stringify(training));
                    formData.append("gprograms", JSON.stringify(gprograms));
                    formData.append("dialect", JSON.stringify(dialect));
                    formData.append("sincome", JSON.stringify(sincome));
                    formData.append("app", JSON.stringify(app));
                    formData.append("disability", JSON.stringify(disability));
                    formData.append("comor", JSON.stringify(comor));
                    formData.append("vhcl", JSON.stringify(vhcl));
                    formData.append("docs", JSON.stringify(docs));

                    // Make AJAX request using FormData
                    $.ajax({
                        method: "POST",
                        url: "<?= site_url('/resident/save'); ?>",
                        data: formData,
                        processData: false, // Prevent jQuery from processing the data
                        contentType: false, // Prevent jQuery from setting contentType
                        success: function(res) {
                            if (res.success) {
                                // Handle success case here
                                // Load household members
                                var householdId = res.household;
                                $(".household-id").html(householdId);
                                // Reset form fields and validation
                                clearValidationClasses('#residentForm');
                                if (householdId) {
                                    // Reload the DataTable with the new household ID
                                    tblMember.ajax.url("<?= site_url('/resident/load_household_member') ?>/" + householdId).load();
                                    fetch_family_heads(householdId);
                                } else {
                                    console.error("No household ID returned in the response.");
                                }
                                show_container_member();
                            } else {
                                // Error: handle the errors and display them
                                var errorList = res.errors;

                                // If errorList is not an array, convert it to an array or handle the error appropriately
                                if (!Array.isArray(errorList)) {
                                    errorList = [errorList]; // If it's a single object or string, make it an array
                                }

                                var displayError = "<ul>";

                                // Loop through each error
                                errorList.forEach(function(error) {
                                    if (typeof error === "string") {
                                        displayError += "<li>" + '-' + error + "</li>";
                                    } else if (typeof error === "object" && error !== null) {
                                        $.each(error, function(field, errorMsg) {
                                            displayError += "<li><strong>" + field + ":</strong> " + errorMsg + "</li>";
                                        });
                                    }
                                });

                                displayError += "</ul>";

                                // Display error
                                showAlert(displayError);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        },
                    });
                } else {
                    // Show alert, please attach supporting documents for review
                    showAlert('Please attach supporting documents for review');
                }


            } else {
                // If the form is invalid, prevent submission
                // and display Bootstrap's default validation messages
                $("#residentForm").addClass("was-validated");
                $(document).scrollTop(0);
            }
        });

        /**
         * HOUSEHOLD MEMBERS - (SAVE)
         */
        function fetchIndividual(res_id) {
            $.ajax({
                url: "<?= site_url('/resident/fetchIndividual/') ?>" + res_id,
                type: "GET",
                dataSrc: "data",
                success: function(res) {
                    let response = res.data;
                    let resident_data = response.data;
                    let img = resident_data.img_path ? "<?= base_url('writable/uploads/') ?>" + resident_data.img_path : "<?= base_url('public/assets/images/logo.png'); ?>";
                    let checked = response.checked; // if the current profile is family_head
                    // UPDATE checkbox
                    $("#isFamilyHead").prop("checked", checked);
                    // UPDATE <img> SRC
                    $("#img-mbr").attr("src", img);
                    // UPDATE RESIDENT INFO
                    $("#id-mbr").val(resident_data.id);
                    $("#lname-mbr").val(resident_data.lname);
                    $("#fname-mbr").val(resident_data.fname);
                    $("#mname-mbr").val(resident_data.mname);
                    $("#suffix-mbr").val(resident_data.suffix);
                    $(".fullname-mbr").text(response.fullname);
                    $("#bday-mbr").val(resident_data.bday);
                    $("#bplace-mbr").val(resident_data.bplace);
                    $("#gender-mbr").val(resident_data.gender).trigger('change.select2');
                    $("#cstatus-mbr").val(resident_data.cstatus_id).trigger('change.select2');
                    $("#education-mbr").val(resident_data.educ_id).trigger('change.select2');
                    $("#course-mbr").val(resident_data.course_id).trigger('change.select2');
                    $("#religion-mbr").val(resident_data.rel_id).trigger('change.select2');
                    $("#occupation-mbr").val(resident_data.occ_id).trigger('change.select2');
                    $("#philhealth-mbr").val(resident_data.phealth_no);
                    $("#monthly_income-mbr").val(resident_data.m_income);
                    $("#cp-mbr").val(resident_data.cp);
                    $("#email-mbr").val(resident_data.email);
                    $("#nstatus-mbr").val(resident_data.nstatus).trigger('change.select2');
                    $("#btype-mbr").val(resident_data.btype).trigger('change.select2');
                    $("#height-mbr").val(resident_data.height);
                    $("#weight-mbr").val(resident_data.weight);
                    $("#relation_hh").val(resident_data.relation_hh).trigger('change.select2');
                    $("#relation_fh").val(resident_data.relation_fh).trigger('change.select2');
                    $("#family_head").val(resident_data.fh_id).trigger('change.select2');
                    $("#household-mbr").val(resident_data.household);
                    $("#img-path-mbr").val(resident_data.img_path);
                    // UPDATE TABLE CONTENTS
                    $(".tbody-trainings-mbr").html(response.trainings);
                    $(".tbody-programs-mbr").html(response.gprograms);
                    $(".tbody-dialect-mbr").html(response.dialect);
                    $(".tbody-sincome-mbr").html(response.sincome);
                    $(".tbody-app-mbr").html(response.app);
                    $(".tbody-disability-mbr").html(response.disability);
                    $(".tbody-comor-mbr").html(response.comor);
                    $(".tbody-vhcl-mbr").html(response.vhcl);
                    $(".tbody-doctype-mbr").html(response.docs);
                    // SHOW FORM
                    show_member_form();
                },
                error: function(xhr, error, thrown) {
                    console.error('Error:', error);
                    console.error('XHR:', xhr);
                }
            })
        }

        // Disable select element when it is checked (isFamilyHead)
        $(document).on("change", "#isFamilyHead", function(e) {
            e.preventDefault();
            if (this.checked === true) {
                $("#family_head").attr("disabled", true);
                $("#family_head").val('').trigger('change');
            } else {
                $("#family_head").attr("disabled", false);
            }
        })

        // Initialize Bootstrap validation
        $("#memberForm").validate();
        // SUBMIT FORM
        $(document).on("click", "#submitFormMBR", function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Check if the form is valid
            if ($("#memberForm")[0].checkValidity()) {
                // Proceed with form submission
                // Get training and skills
                let training = [];
                $(".tbody-trainings-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    training.push({
                        id: id,
                    });
                });
                // Get Government programs
                let gprograms = [];
                $(".tbody-programs-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    var dateAcquired = $(this).find("input").val();
                    gprograms.push({
                        id: id,
                        dateAcquired: dateAcquired,
                    });
                });
                // Get dialect spoken
                let dialect = [];
                $(".tbody-dialect-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    dialect.push({
                        id: id,
                    });
                });
                // Get source of income
                let sincome = [];
                $(".tbody-sincome-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    sincome.push({
                        id: id,
                    });
                });
                // Get Appliances/Gadgets
                let app = [];
                $(".tbody-app-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    var qty = $(this).find("input").val();
                    app.push({
                        id: id,
                        qty: qty,
                    });
                });
                // Get disabilities
                let disability = [];
                $(".tbody-disability-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    disability.push({
                        id: id,
                    });
                });
                // Get comorbidities
                let comor = [];
                $(".tbody-comor-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    comor.push({
                        id: id,
                    });
                });
                // Get vehicles
                let vhcl = [];
                $(".tbody-vhcl-mbr tr").each(function() {
                    var id = $(this).find("button").attr("data-id");
                    var qty = $(this).find("input").val();
                    vhcl.push({
                        id: id,
                        qty: qty,
                    });
                });
                // Get supporting documents
                let docs = [];
                $(".tbody-doctype-mbr tr").each(function() {
                    var doctype_id = $(this).find("button").attr("data-id");
                    var file_path = $(this).find("label").text();
                    docs.push({
                        id: doctype_id,
                        file_path: file_path,
                    });
                });

                console.log(gprograms);

                // CHECK IF THERE IS SUBMITTED SUPPORTING DOCUMENTS
                // CHECK VALID DATE
                if (!checkValidDate($("#bday-mbr").val())) {
                    // Show alert, please attach supporting documents for review
                    showAlert('Please check bday format');
                } else if (docs.length > 0 && docs[0].id) {
                    // Create a new FormData object
                    var formData = new FormData();
                    // Picture
                    var pic = $("#file-mbr")[0].files[0] ? $("#file-mbr")[0].files[0] : "";

                    // Append the file input to FormData object
                    formData.append("pic", pic);

                    // Append other form data to FormData object
                    formData.append("formData", $("#memberForm").serialize());
                    formData.append("training", JSON.stringify(training));
                    formData.append("gprograms", JSON.stringify(gprograms));
                    formData.append("dialect", JSON.stringify(dialect));
                    formData.append("sincome", JSON.stringify(sincome));
                    formData.append("app", JSON.stringify(app));
                    formData.append("disability", JSON.stringify(disability));
                    formData.append("comor", JSON.stringify(comor));
                    formData.append("vhcl", JSON.stringify(vhcl));
                    formData.append("docs", JSON.stringify(docs));

                    // Make AJAX request using FormData
                    $.ajax({
                        method: "POST",
                        url: "<?= site_url('/resident/saveMBR'); ?>",
                        data: formData,
                        processData: false, // Prevent jQuery from processing the data
                        contentType: false, // Prevent jQuery from setting contentType
                        success: function(res) {
                            if (res.success) {
                                clearValidationClasses('#memberForm');
                                // Handle success case here
                                // Load household members
                                var householdId = res.household;
                                // Clear additional data
                                clear_tables(); // Clear tables, vehicle, trainings, etc.
                                $("#household-mbr").val(res.household); // Set household ID
                                $(".household-id").html(householdId);
                                if (householdId) {
                                    // Reload the DataTable with the new household ID
                                    tblMember.ajax.url("<?= site_url('/resident/load_household_member') ?>/" + householdId).load();
                                    fetch_family_heads(householdId);
                                } else {
                                    console.error("No household ID returned in the response.");
                                }
                                show_container_member();
                            } else {
                                // Error: handle the errors and display them
                                var errorList = res.errors;

                                // If errorList is not an array, convert it to an array or handle the error appropriately
                                if (!Array.isArray(errorList)) {
                                    errorList = [errorList]; // If it's a single object or string, make it an array
                                }

                                var displayError = "<ul>";

                                // Loop through each error
                                errorList.forEach(function(error) {
                                    if (typeof error === "string") {
                                        displayError += "<li>" + '-' + error + "</li>";
                                    } else if (typeof error === "object" && error !== null) {
                                        $.each(error, function(field, errorMsg) {
                                            displayError += "<li><strong>" + field + ":</strong> " + errorMsg + "</li>";
                                        });
                                    }
                                });

                                displayError += "</ul>";
                                // Display error
                                showAlert(displayError);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        },
                    });
                } else {
                    // Show alert, please attach supporting documents for review
                    showAlert('Please attach supporting documents for review');
                }
            } else {
                // If the form is invalid, prevent submission
                // and display Bootstrap's default validation messages
                $("#memberForm").addClass("was-validated");
                $(document).scrollTop(0);
            }
        });
        // FETCH MEMBER TO UPDATE
        $(document).on("click", ".btnUpdate-mbr", function(e) {
            e.preventDefault();
            let res_id = $(this).attr("data-id");
            fetchIndividual(res_id);

        })

        /**
         * OTHER HOUSEHOLD INFORMATION - (SAVE)
         */

        // GET OTHER INFORMATION (FORM)
        function getOtherInfo(household) {
            if (household && household != "") {
                $.ajax({
                    url: "<?= site_url('/resident/getOtherInfo/') ?>" + household,
                    type: "GET",
                    dataSrc: "data",
                    success: function(res) {
                        let data = res.data;
                        $(".tbody-water").html(data.water);
                        $(".tbody-power").html(data.power);
                        $(".tbody-san").html(data.san);
                        $(".tbody-cook").html(data.cook);
                        $(".tbody-app").html(data.app);
                        $(".tbody-comm").html(data.comm);
                        $(".tbody-amenities").html(data.amenities);
                        $(".tbody-vhcl").html(data.vhcl);
                        $(".tbody-amach").html(data.amach);
                        $(".tbody-alive").html(data.alive);
                        //Bldg information
                        $("#bldg-type")
                            .val(data.bldginfo.bldg_type_id)
                            .trigger("change.select2");
                        $("#construction-yr").val(
                            data.bldginfo.construction_yr !== "" ?
                            data.bldginfo.construction_yr :
                            ""
                        );
                        $("#yr-occupied").val(
                            data.bldginfo.yr_occupied !== "" ?
                            data.bldginfo.yr_occupied :
                            ""
                        );
                        $("#bldg-permit-no").val(data.bldginfo.bldg_permit_no);
                        $("#lot-no").val(data.bldginfo.lot_no);
                        // Garbage info
                        $("#hazardous")
                            .val(data.garbages.hazardous)
                            .trigger("change.select2");
                        $("#recyclable")
                            .val(data.garbages.recyclable)
                            .trigger("change.select2");
                        $("#residual").val(data.garbages.residual).trigger("change.select2");
                        $("#biodegradable")
                            .val(data.garbages.biodegradable)
                            .trigger("change.select2");
                        // SHOW OTHER FORM
                        show_container_other();
                    },
                    error: function(xhr, error, thrown) {
                        console.error('Error:', error);
                        console.error('XHR:', xhr);
                    }
                })
            }
        }

        $(document).on("click", ".show-info", function(e) {
            let household_id = $("#household-mbr").val();
            getOtherInfo(household_id);
        })

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
                        console.log(head_info);
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
        // ========== SAVE OTHER INFORMATION ========== //
        $(document).on("click", "#submitOther", function() {
            // household id
            let household_id = $("#household-mbr").val();
            // Get water sources
            let water = [];
            $(".tbody-water tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                var ave_per_mo = $(this).find("input").val();
                water.push({
                    id: id,
                    ave_per_mo: ave_per_mo,
                });
            });
            // Get power sources
            let power = [];
            $(".tbody-power tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                var ave_per_mo = $(this).find("input").val();
                power.push({
                    id: id,
                    ave_per_mo: ave_per_mo,
                });
            });
            // Get sanitation
            let san = [];
            $(".tbody-san tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                //var ave_per_mo = $(this).find("input").val();
                san.push({
                    id: id,
                });
            });
            // Get cooking type
            let cook = [];
            $(".tbody-cook tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                cook.push({
                    id: id,
                });
            });
            // Get appliances
            let app = [];
            $(".tbody-app tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                var qty = $(this).find("input").val();
                app.push({
                    id: id,
                    qty: qty,
                });
            });
            // Get communication
            let comm = [];
            $(".tbody-comm tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                //var qty = $(this).find("input").val();
                comm.push({
                    id: id,
                });
            });
            // Get building amenities
            let amenities = [];
            $(".tbody-amenities tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                //var qty = $(this).find("input").val();
                amenities.push({
                    id: id,
                });
            });
            // Get vehicle
            let vhcl = [];
            $(".tbody-vhcl tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                var qty = $(this).find("input").val();
                vhcl.push({
                    id: id,
                    qty: qty,
                });
            });
            // Get agricultural machineries
            let amach = [];
            $(".tbody-amach tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                var qty = $(this).find("input").val();
                amach.push({
                    id: id,
                    qty: qty,
                });
            });

            // Get agricultural livestock
            let alive = [];
            $(".tbody-alive tr").each(function() {
                var id = $(this).find("button").attr("data-id");
                var qty = $(this).find("input").val();
                alive.push({
                    id: id,
                    qty: qty,
                });
            });

            // Make AJAX request using FormData
            $.ajax({
                method: "POST",
                url: "<?= site_url('/resident/saveOtherInfo') ?>",
                data: {
                    household_id: household_id, //$("#household-id").val(),
                    formData: $("#otherForm").serialize(),
                    water: JSON.stringify(water),
                    power: JSON.stringify(power),
                    san: JSON.stringify(san),
                    cook: JSON.stringify(cook),
                    app: JSON.stringify(app),
                    comm: JSON.stringify(comm),
                    amenities: JSON.stringify(amenities),
                    vhcl: JSON.stringify(vhcl),
                    amach: JSON.stringify(amach),
                    alive: JSON.stringify(alive),
                },
                success: function(res) {
                    print_household_info(res.household_id);

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                },
            });
        });

        // PRINT RESIDENT FORM
        $(document).on("click", ".btn-print-form", function() {
            printContent();
        });


    });
</script>
<?= $this->endSection('my_script') ?>