<?= $this->extend('main/layout') ?>
<?= $this->section('content') ?>
<main>
    <h1 class="title">System Admin Dashboard</h1>
    <ul class="breadcrumbs">
        <li><a href="#">Home</a></li>
        <li class="divider">/</li>
        <li><a href="#" class="active">System Admin Dashboard</a></li>
    </ul>

    <div class="info-data">
        <div class="my-card">
            <div class="head">
                <div>
                    <h2><?= isset($total_population) ? number_format($total_population) : "0"; ?></h2>
                    <div class="d-flex">
                        <p><i class='bx bx-world me-2 text-white'></i>Population</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-card">
            <div class="head">
                <div>
                    <h2><?= isset($total_household_heads) ? number_format($total_household_heads) : "0"; ?></h2>
                    <p><i class='bx bx-building-house me-2 text-white'></i>Household Heads</p>
                </div>
            </div>
        </div>
        <div class="my-card">
            <div class="head">
                <div>
                    <h2><?= isset($total_family_heads) ? number_format($total_family_heads) : "0"; ?></h2>
                    <p><i class='bx bx-male me-2 text-white'></i>Family Heads</p>
                </div>
            </div>
        </div>
        <div class="my-card">
            <div class="head">
                <div>
                    <h2><?= isset($total_senior_citizens) ? number_format($total_senior_citizens) : "0"; ?></h2>
                    <p><i class='bx bx-handicap me-2 text-white'></i>Senior Citizens</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <section class="graph mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 my-2">

                    <div class="p-2 bg-white border rounded mb-2">
                        <div class="border-secondary text-secondary fw-bold border-bottom">
                            <h6 class="fw-bold mx-2"><i class="fi fi-rs-chart-pie-alt me-2"></i>Population</h6>
                        </div>
                        <div class="container-fluid">
                            <canvas style="height:400px;" id="populationChartCanvas"></canvas>
                        </div>
                    </div>

                    <div class="p-2 bg-white border rounded mb-2">
                        <div class="border-secondary text-secondary border-bottom">
                            <h6 class="fw-bold mx-2"><i class="fi fi-rs-chart-pie-alt me-2"></i>Household Head</h6>
                        </div>
                        <div class="container-fluid">
                            <canvas style="height:400px;" id="householdHeadChartCanvas"></canvas>
                        </div>
                    </div>

                    <div class="p-2 bg-white border rounded mb-2">
                        <div class="border-secondary text-secondary border-bottom">
                            <h6 class="fw-bold mx-2"><i class="fi fi-rs-chart-pie-alt me-2"></i>Family Head</h6>
                        </div>
                        <div class="container-fluid">
                            <canvas style="height:400px;" id="familyHeadChartCanvas"></canvas>
                        </div>
                    </div>

                    <div class="p-2 bg-white border rounded mb-2">
                        <div class="border-secondary text-secondary border-bottom">
                            <h6 class="fw-bold mx-2"><i class="fi fi-rs-chart-pie-alt me-2"></i>Household Head and Family Head Comparison</h6>
                        </div>
                        <div class="container-fluid">
                            <canvas style="height:400px;" id="householdFamilyHeadChartCanvas"></canvas>
                        </div>
                    </div>



                </div>
                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 my-2">
                    <div class="blog-card">
                        <div class="meta">
                            <div class="photo" style="background-image: url(https://storage.googleapis.com/chydlx/codepen/blog-cards/image-1.jpg)"></div>
                            <ul class="details">
                                <!-- <li class="author"><a href="#">John Doe</a></li>
                                <li class="date">Aug. 24, 2015</li>
                                <li class="tags">
                                    <ul>
                                        <li><a href="#">Learn</a></li>
                                        <li><a href="#">Code</a></li>
                                        <li><a href="#">HTML</a></li>
                                        <li><a href="#">CSS</a></li>
                                    </ul>
                                </li> -->
                            </ul>
                        </div>
                        <div class="description">
                            <h1>Encoding Schedule</h1>
                            <h2>Set household record for updating</h2>
                            <form action="<?= base_url('main/setSchedule'); ?>" method="POST">
                                <p>Start Date</p>
                                <input type="text" class="form-control datetimepicker" placeholder="Start Date" name="start_date" id="start_date" value="<?= isset($encoding_schedule) && $encoding_schedule['start_date'] ? $encoding_schedule['start_date'] : ""; ?>">
                                <p>End Date</p>
                                <input type="text" class="form-control datetimepicker" placeholder="End Date" name="end_date" id="end_date" value="<?= isset($encoding_schedule) && $encoding_schedule['end_date'] ? $encoding_schedule['end_date'] : ""; ?>">
                                <input type="submit" class="btn btn-primary w-100 mt-2" value="Set Date">
                            </form>

                        </div>
                    </div>

                    <!-- cards -->
                    <div class="p-2 bg-white border rounded mb-2 collapse">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Person w/ disability</td>
                                    <td class="text-primary fw-bold"><?= isset($total_pwd) ? number_format($total_pwd) : "0"; ?></td>
                                </tr>
                                <tr>
                                    <td>Person w/ comorbidity</td>
                                    <td class="text-primary fw-bold"><?= isset($total_with_comorbidities) ? number_format($total_with_comorbidities) : "0"; ?></td>
                                </tr>
                                <tr class="collapse">
                                    <td>Employment Rate</td>
                                    <td class="text-primary fw-bold"><?= isset($employment_rate) ? $employment_rate : "0%"; ?></td>
                                </tr>
                                <tr class="collapse">
                                    <td>Unemployment Rate</td>
                                    <td class="text-primary fw-bold"><?= isset($unemployment_rate) ? $unemployment_rate : "0%"; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white rounded border mb-2 p-2">
                        <h6 class="fw-bold text-secondary border-bottom p-2">Online Request</h6>
                        <table class="table table-border table-hover">
                            <tbody>
                                <tr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <td>Household Approval<span class="badge text-bg-primary ms-2"><?= isset($online_request['household_request']) && $online_request['household_request'] !== "0" ? $online_request['household_request'] : ''; ?></span></td>
                                        <td><a href="<?= base_url(); ?>resident/for_approval3" class="btn btn-primary btn-sm"><i class='bx bx-list-ul' data-bs-toggle="tooltip" data-bs-title="View details"></i></a></td>
                                    </div>
                                </tr>
                                <tr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <td>User<span class="badge text-bg-primary ms-2"><?= isset($online_request['user_request']) && $online_request['user_request'] !== "0" ? $online_request['user_request'] : ''; ?></span></td>
                                        <td><a href="<?= base_url(); ?>user_management3/" class="btn btn-primary btn-sm"><i class='bx bx-list-ul' data-bs-toggle="tooltip" data-bs-title="View details"></i></a></td>
                                    </div>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- cards -->
                    <div class="bg-white rounded border p-2">
                        <h6 class="fw-bold text-secondary border-bottom p-2">Generate/Download Reports</h6>
                        <a href="<?= base_url(); ?>main/download_residents_information" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of Residents (Population)</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_household_heads" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of Household Heads</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_family_heads" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of Family Heads</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_senior_citizens" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of Senior Citizens</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_with_disabilities" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of residents with disabilities</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_with_comorbidities" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of residents with comorbidities</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_with_trainings" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of residents with trainings and skills</span>
                        </a>
                        <a href="<?= base_url(); ?>main/download_list_with_gprograms" class="nav-link text-primary">
                            <i class="fi fi-rs-angle-double-small-right me-2"></i>
                            <span class="download-item">List of residents who have availed Government Programs/Assistance</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?= $this->include('main/include/fee-modal'); ?>

<?= $this->endSection('content') ?>

<?= $this->section('my_script') ?>
<script>
    $(document).ready(function() {
        // ALERT ON SETTING ENCODING SCHEDULE
        let fail = "<?= session()->getFlashdata('fail'); ?>";
        let ok = fail ? showAlert(fail,"error") : "";

        let success = "<?= session()->getFlashdata('success'); ?>";
        let ok2 = success ? showAlert(success,"success") : "";
        

        // ========== datetimepicker ========== //
        function enable_datepicker() {
            flatpickr(".datetimepicker", {
                dateFormat: "m-d-Y", // Set date format
                enableTime: false, // Enable time selection
            });
        }
        enable_datepicker();
    })

    // Function to initialize tooltips
    function initializeTooltips() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    initializeTooltips();
    // Chart configuration options
    const chartOptions = {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    // Function to create a standard chart
    function createChart(ctx, data, label) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data), // Years
                datasets: [{
                    label: label,
                    data: Object.values(data), // Values of population/household/family heads
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });
    }

    // Function to create a comparison chart for Household Heads vs Family Heads
    function createChart2(ctx, data, labels) {
        // Convert the object to an array of values so we can use map
        const chartData = Object.values(data);

        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // Years
                datasets: [{
                        label: 'Household Heads',
                        data: chartData.map(item => item.household_head_count), // Extract household_head_count
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Family Heads',
                        data: chartData.map(item => item.family_head_count), // Extract family_head_count
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: chartOptions
        });
    }


    // Population chart
    var populationCtx = document.getElementById('populationChartCanvas').getContext('2d');
    var populationChart = createChart(populationCtx, <?= json_encode($populationByYear) ?>, 'Population');

    // Household heads chart
    var householdHeadCtx = document.getElementById('householdHeadChartCanvas').getContext('2d');
    var householdHeadChart = createChart(householdHeadCtx, <?= json_encode($householdHeadByYear) ?>, 'Household Heads');

    // Family heads chart
    var familyHeadCtx = document.getElementById('familyHeadChartCanvas').getContext('2d');
    var familyHeadChart = createChart(familyHeadCtx, <?= json_encode($familyHeadByYear) ?>, 'Family Heads');

    // Creating the comparison chart
    var householdFamilyHeadCtx = document.getElementById('householdFamilyHeadChartCanvas').getContext('2d');
    var householdFamilyHeadChart = createChart2(
        householdFamilyHeadCtx,
        <?= json_encode($householdFamilyHeadByYear) ?>,
        Object.keys(<?= json_encode($householdFamilyHeadByYear) ?>) // This will be the years
    );

    // Collect notifications
    // Collect notifications
    var user_request = <?= json_encode($online_request['user_request']) ?>;
    var msg1 = user_request && user_request !== "0" ? showToast(`You have ${user_request} user request`, "success") : '';

    var household_request = <?= json_encode($online_request['household_request']) ?>;
    var msg3 = household_request && household_request !== "0" ? showToast(`You have ${household_request} household approval`, "success") : '';
</script>
<?= $this->endSection('my_script') ?>