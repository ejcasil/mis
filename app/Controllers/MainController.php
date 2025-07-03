<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\AlivestockModel;
use App\Models\AmachineriesModel;
use App\Models\AppliancesModel;
use App\Models\AttachmentsModel;
use App\Models\BldgAmenitiesModel;
use App\Models\BldgInfoModel;
use App\Models\CommModel;
use App\Models\ComorbiditiesModel;
use App\Models\CookingModel;
use App\Models\DialectModel;
use App\Models\DisabilityModel;
use App\Models\GarbageModel;
use App\Models\GprogramsModel;
use App\Models\HAppliancesModel;
use App\Models\HVehicleModel;
use App\Models\PowerModel;
use App\Models\SanitationModel;
use App\Models\SincomeModel;
use App\Models\TrainingModel;
use App\Models\VehicleModel;
use App\Models\WaterModel;
use App\Models\ResidentModel;
use App\Models\TmpResidentModel;
use App\Models\CategoryModel;
use App\Models\BrgyCodeModel;
use App\Models\ZpCodeModel;
use App\Models\BrgyProfileModel;
use App\Models\CertificateModel;
use App\Models\DocFeeModel;
use App\Models\EncodingScheduleModel;


class MainController extends BaseController
{
    public function dashboard()
    {
        // AUTO BDAY GREETINGS
        $this->bday_greetings();
        // UPDATE RESIDENT'S AGE FROM THE DATABASE
        $resident_age = $this->updateAge();
        // GET TOTAL NUMBER OF POPULATION
        $output['total_population'] = $this->total_population();
        // GET TOTAL NUMBER OF HOUSEHOLD HEADS
        $output['total_household_heads'] = $this->total_household_heads();
        // GET TOTAL NUMBER OF FAMILY HEADS
        $output['total_family_heads'] = $this->total_family_heads();
        // GET TOTAL NUMBER OF SENIOR CITIZENS
        $output['total_senior_citizens'] = $this->total_senior_citizens();
        // GET TOTAL NUMBER OF PWD
        $output['total_pwd'] = $this->total_pwd();
        // GET TOTAL NUMBER OF PERSON WITH COMORBIDITIES
        $output['total_with_comorbidities'] = $this->total_with_comorbidities();
        // GET EMPLOYMENT RATE
        $output['employment_rate'] = $this->total_employed();
        // GET UNEMPLOYMENT RATE
        $output['unemployment_rate'] = $this->total_unemployed();

        $output['online_request'] = $this->online_request();

        // DATA VISUALIZATION
        $output['populationByYear'] = $this->population_graph();
        // HOUSEHOLD HEAD
        $output['householdHeadByYear'] = $this->household_head_graph();
        // FAMILY HEAD
        $output['familyHeadByYear'] = $this->family_head_graph();
        // HOUSEHOLD HEAD AND FAMILY HEAD COMPARISON
        $output['householdFamilyHeadByYear'] = $this->household_family_head_graph();
        
        // GET DOCUMENT FEE
        $output['document_fee'] = $this->document_fee();

        // GET ENCODING SCHEDULE
        $output['encoding_schedule'] = $this->getEncodingSchedule();

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        return view("main/dashboard", $output);
        
    }

    // UPDATE ENCODING SCHEDULE
    public function update_encoding_schedule()
    {
        $post = $this->request->getPost();
        $start_date = isset($post['start_date']) ? $post['start_date'] : null;
        $end_date = isset($post['end_date']) ? $post['end_date'] : null;
    
        if (is_null($start_date) || is_null($end_date)) {
            session()->setFlashdata('fail', 'Invalid date range.');
            return redirect()->to('main/dashboard'); // Add a redirect to show the error
        }
    
        // Format the dates to 'Y-m-d' format
        $formattedStartDate = $this->save_date($start_date);
        $formattedEndDate = $this->save_date($end_date);
    
        // Check if today's date is within the range
        $today = $this->save_date(date('Y-m-d'));
    
        if (($formattedStartDate >= $today && $formattedEndDate > $formattedStartDate)) {
            // Prepare data to save
        $data = [
            'start_date' => $formattedStartDate,
            'end_date' => $formattedEndDate
        ];
    
        // Ensure you're updating the correct record
        $EncodingScheduleModel = new EncodingScheduleModel();
        $isOkay = $EncodingScheduleModel->set($data)->where('id > 0')->update(); 
    
        if ($isOkay) {
            session()->setFlashdata('success', 'Encoding schedule updated successfully.');
        } else {
            session()->setFlashdata('fail', 'Failed to update encoding schedule.');
        }
    
        return redirect()->to('main/dashboard');
            
        } else {
            session()->setFlashdata('fail', 'Invalid date range.');
            return redirect()->to('main/dashboard'); // Add a redirect to show the error
        }
    
        
    }
    
    // GET DOCUMENT FEES
    public function document_fee() {
        $brgy_id = $this->brgy_id;
        $DocFeeModel = new DocFeeModel();
        
        // Fetch document fees for the specific barangay
        $document_fees = $DocFeeModel->where('brgy_id', $brgy_id)->findAll();
    
        // Initialize fees
        $fees = [
            'BC' => "0",
            'BsC' => "0",
            'CI' => "0"
        ];
    
        // Populate fees based on existing records
        foreach ($document_fees as $row) {
            if (isset($fees[$row->document_type])) {
                $fees[$row->document_type] = $row->fee;
            }
        }
    
        // Prepare output
        $output = [
            'bc_fee' => $fees['BC'],
            'bsc_fee' => $fees['BsC'],
            'ci_fee' => $fees['CI']
        ];
    
        return $output;
    }
    

    // UPDATE AGE FROM THE DATABASE
    public function updateAge()
    {
        $residentModel = new ResidentModel();
        $batchSize = 100; // Update in batches of 100 residents
        $offset = 0;
        $updateSuccessful = true; // Assume update is successful by default

        do {
            $residentData = $residentModel->findAll($batchSize, $offset);

            if ($residentData) {
                $batchUpdateData = [];

                foreach ($residentData as $row) {
                    $id = $row->id ?? "";
                    $bday = $this->display_date($row->bday);
                    $bday = $this->save_date($bday);

                    // Debugging output
                    if (!$bday) {
                        error_log("Invalid birthday for resident ID $id.");
                        continue; // Skip this resident if birthday is invalid
                    }

                    $age = $this->compute_age($bday);

                    // Debugging output
                    if ($age === 0) {
                        error_log("Computed age for resident ID $id is 0. Birthday: $bday");
                    }

                    // Prepare data for batch update
                    $get = [
                        'id' => $id,
                        'age' => $age
                    ];
                    $batchUpdateData[] = $get;
                }

                // Bulk update ages
                try {
                    $residentModel->updateBatch($batchUpdateData, 'id');
                } catch (\Exception $e) {
                    // Handle error
                    // Log or throw the exception
                    error_log("Update failed: " . $e->getMessage());
                    $updateSuccessful = false;
                }
            }

            $offset += $batchSize;
        } while (count($residentData) == $batchSize);

        return $updateSuccessful;
    }

    // TOTAL NUMBER OF POPULATION 
    public function total_population()
    {
        $residentModel = new ResidentModel();
        $resident_count = $residentModel->where("status", "ACTIVE")->countAllResults();
        return $resident_count;
    }

    // TOTAL NUMBER OF HOUSEHOLD HEADS
    public function total_household_heads()
    {
        $residentModel = new ResidentModel();
        $resident_count = $residentModel->where("isHead", "TRUE")->where("status", "ACTIVE")->countAllResults();
        return $resident_count;
    }

    // TOTAL NUMBER OF FAMILY HEADS
    public function total_family_heads()
    {
        $residentModel = new ResidentModel();
        $total_heads = $residentModel->distinct()->select('fh_id')->where("status", "ACTIVE")->where("fh_id !=", "0")->countAllResults();

        return $total_heads;
    }

    // TOTAL NUMBER OF SENIOR CITIZENS
    public function total_senior_citizens()
    {
        $residentModel = new ResidentModel();
        $data = $residentModel
            ->where('age >=', 60)
            ->where('status', 'ACTIVE')
            ->countAllResults();

        return $data;

    }

    // TOTAL NUMBER OF PWD
    public function total_pwd()
    {
        $model = new DisabilityModel();
        $data = $model->distinct()->select('res_id')->countAllResults();

        return $data;
    }

    // TOTAL NUMBER OF PERSON WITH COMORBIDITIES
    public function total_with_comorbidities()
    {
        $model = new ComorbiditiesModel();
        $data = $model->distinct()->select('res_id')->countAllResults();

        return $data;
    }

    // GET TOTAL NUMBER OF UNEMPLOYED INDIVIDUAL
    public function total_unemployed()
    {
        $population = $this->total_population();

        $residentModel = new ResidentModel();
        $total = $residentModel
            ->where('occ_id =', "0")
            ->where('status', 'ACTIVE')
            ->countAllResults();

        $percentage = "0%";
        if (isset($total) && $total > 0) {
            $percentage = number_format((($total / $population) * 100), 2) . "%";
        }

        return $percentage;
    }

    // GET TOTAL NUMBER OF EMPLOYED INDIVIDUAL
    public function total_employed()
    {
        $population = $this->total_population();

        $residentModel = new ResidentModel();
        $total = $residentModel
            ->where('occ_id !=', "0")
            ->where('status', 'ACTIVE')
            ->countAllResults();

        $percentage = "0%";
        if (isset($total) && $total > 0) {
            $percentage = number_format((($total / $population) * 100), 2) . "%";
        }

        return $percentage;
    }

    // UPDATE DOCUMENT FEES
    public function update_document_fees() {
        $post = $this->request->getPost();
        $brgy_id = $this->brgy_id;
    
        // Validate fees
        // $bsc_fee = is_numeric($post['BsC-fee']) ? $post['BsC-fee'] : 0;
        $bc_fee = is_numeric($post['BC-fee']) ? $post['BC-fee'] : 0;
        $ci_fee = is_numeric($post['CI-fee']) ? $post['CI-fee'] : 0;
    
        // Prepare data
        $data = [
            ['document_type' => "BC", "fee" => $bc_fee, "brgy_id" => $brgy_id],
            // ['document_type' => "BsC", "fee" => $bsc_fee, "brgy_id" => $brgy_id],
            ['document_type' => "CI", "fee" => $ci_fee, "brgy_id" => $brgy_id],
        ];
    
        $DocFeeModel = new DocFeeModel();
    
        foreach ($data as $item) {
            // Check for existing record
            $existingFee = $DocFeeModel->where(['brgy_id' => $brgy_id, 'document_type' => $item['document_type']])->first();
    
            if ($existingFee) {
                // Update the fee using set()
                $DocFeeModel->set(['fee' => $item['fee']])
                            ->where('id', $existingFee->id)
                            ->update();

                $doc_name = $this->getDocName($item['document_type']);
                // Log activity 
                $this->activityLogService->logActivity('Updated documentary fee of ' . $doc_name . ' amounting to: ' . $item['fee'] . ' pesos', session()->get("id"));

            } else {
                // Insert the new fee
                if (!$DocFeeModel->insert($item)) {
                    log_message('error', 'Insert failed for document type: ' . $item['document_type']);
                }
            }
        }

        return redirect()->to('main/dashboard');
    }
    

    /*
       =================================
       DATA VISUALIZATION (GRAPHS)
       =================================
       */

    // POPULATION EVERY YEAR
    public function population_graph()
    {
        $residentModel = new ResidentModel();
        $data = $residentModel->where("status", "ACTIVE")->findAll();
        $populationByYear = [];

        if (!empty($data) && is_array($data)) {
            foreach ($data as $row) {
                // Get the year from the created_on date, ensuring it's in valid format
                $year = isset($row->created_on) ? date('Y', strtotime($row->created_on)) : null;

                if ($year) {
                    // Increment the population for the corresponding year
                    if (!isset($populationByYear[$year])) {
                        $populationByYear[$year] = 0;
                    }
                    $populationByYear[$year]++;
                }
            }
        }

        return $populationByYear;
    }


    // HOUSEHOLD HEADS
    public function household_head_graph()
    {
        $residentModel = new ResidentModel();
        $residents = $residentModel->where("isHead", "TRUE")->where("status", "ACTIVE")->findAll();
        $householdHeadByYear = []; // Array to store household head count by barangay
        if (!empty($residents)) {
            foreach ($residents as $row) {
                // Get the year from the created_on date, ensuring it's in valid format
                $year = isset($row->created_on) ? date('Y', strtotime($row->created_on)) : null;

                if ($year) {
                    // Increment the population for the corresponding year
                    if (!isset($householdHeadByYear[$year])) {
                        $householdHeadByYear[$year] = 0;
                    }
                    $householdHeadByYear[$year]++;
                }
            }
        }
        return $householdHeadByYear;
    }

    // FAMILY HEADS
    public function family_head_graph()
    {
        $residentModel = new ResidentModel();
        $residents = $residentModel
            ->distinct()
            ->select('fh_id, add_id, created_on')
            ->where("status", "ACTIVE")
            ->findAll();


        $familyHeadByYear = []; // Array to store family head count by barangay
        if (!empty($residents)) {
            foreach ($residents as $row) {
                // Get the year from the created_on date, ensuring it's in valid format
                $year = isset($row->created_on) ? date('Y', strtotime($row->created_on)) : null;

                if ($year) {
                    // Increment the population for the corresponding year
                    if (!isset($familyHeadByYear[$year])) {
                        $familyHeadByYear[$year] = 0;
                    }
                    $familyHeadByYear[$year]++;
                }
            }
        }
        return $familyHeadByYear;
    }

    // NUMBER OF HOUSEHOLD HEAD COMPARED TO NUMBER OF FAMILY HEAD
    public function household_family_head_graph()
    {
        // Get the household heads by year
        $householdHeads = $this->household_head_graph();

        // Get the family heads by year
        $familyHeads = $this->family_head_graph();

        // Initialize an array to store the comparison data
        $comparisonData = [];

        // Combine the household and family head data by year
        $years = array_merge(array_keys($householdHeads), array_keys($familyHeads));
        $years = array_unique($years);  

        foreach ($years as $year) {
            $householdCount = isset($householdHeads[$year]) ? $householdHeads[$year] : 0;
            $familyCount = isset($familyHeads[$year]) ? $familyHeads[$year] : 0;

            // Add the comparison data for the year
            $comparisonData[$year] = [
                'household_head_count' => $householdCount,
                'family_head_count' => $familyCount,
            ];
        }

        return $comparisonData;
    }


    /*
       =================================
       DOWNLOADABLES (REPORTS)
       =================================
       */

    // LIST OF RESIDENTS (POPULATION)
    // LIST OF HOUSEHOLD HEADS
    public function download_list_household_heads()
    {
        // Execute the query
        $model = new ResidentModel();
        $data = $model->where("isHead", "TRUE")->where("status", "ACTIVE")->findAll();

        $responseData = [];

        if ($data && is_array($data) && count($data) > 0) {
            foreach ($data as $row) {
                    $resident_id = $row->resident_id;
                    $fullname = $row->fullname;
                    $age = $row->age;
                    $cp = $row->cp;
                    $brgy_data = $this->getBrgyDescription($row->add_id ?? '');
                    $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
    
                    // Build data array
                    $responseData[] = [
                        'Resident ID' => $resident_id,
                        'Name' => $fullname,
                        'Age' => $age,
                        'Contact No.' => $cp,
                        'Barangay' => $brgy,
                    ];
            }
        } else {
            // If no records found, set response data accordingly
            // Build data array
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Contact No.' => "",
                'Barangay' => "",
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_household_heads.csv"')
            ->setBody($csvData);

        return $response;
    }
    // LIST OF FAMILY HEADS
    public function download_list_family_heads()
    {
        // Execute the query
        $model = new ResidentModel();
        $data = $model->select('fh_id, resident_id, fullname, age, cp, add_id')
            ->where('status', 'ACTIVE')
            ->where("fh_id !=", '0')
            ->groupBy('fh_id') // Group by fh_id to get distinct values
            ->findAll();

        $responseData = [];

        if ($data && is_array($data) && count($data) > 0) {
            foreach ($data as $row) {
                $resident_id = $row->resident_id;
                $fullname = $row->fullname;
                $age = $row->age;
                $cp = $row->cp;
                $brgy_data = $this->getBrgyDescription($row->add_id ?? '');
                $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';

                // Build data array
                $responseData[] = [
                    'Resident ID' => $resident_id,
                    'Name' => $fullname,
                    'Age' => $age,
                    'Contact No.' => $cp,
                    'Barangay' => $brgy,
                ];
            }
        } else {
            // If no records found, set response data accordingly
            // Build data array
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Contact No.' => "",
                'Barangay' => "",
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_family_heads.csv"')
            ->setBody($csvData);

        return $response;
    }
    // LIST OF ISSUED CERTIFICATES
    public function download_issued_certificates() {
         // Execute the query
         $model = new CertificateModel();
         $ResidentModel = new ResidentModel();

         $data = $model->where('status', 'ISSUED')
             ->findAll();
 
         $responseData = [];
 
         if ($data && is_array($data) && count($data) > 0) {
             foreach ($data as $row) {
                  // Ensure it returns corresponding 
                  $res_id = $row->res_id ?? '';
                  $resident_info = $ResidentModel->find($res_id);
                  if ($resident_info) {
                        $resident_data = $this->getResidentDataFromID($row->res_id ?? '');

                        $resident_id = $resident_data->resident_id ?? '';
                        $fullname = $resident_data->fullname ?? '';
                        $cp = $resident_data->cp ?? '';
       
                        $brgy_data = $this->getBrgyDescription($resident_data->add_id ?? '');
                        $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
       
                        $document = $this->getDocName($row->document_type ?? '');
        
                        // Build data array
                        $responseData[] = [
                            'Control No.' => $row->control_no ?? '',
                            'Resident ID' => $resident_id,
                            'Name' => $fullname,
                            'Purpose' => $row->purpose ?? '',
                            'Document Type' => $document ?? '',
                            'Contact No.' => $cp,
                            'Barangay' => $brgy,
                            'Issued on' => $row->created_on ?? ''
                        ];
                  }
             }
         } else {
             // If no records found, set response data accordingly
             // Build data array
             $responseData[] = [
                'Control No.' => '',
                     'Resident ID' => '',
                     'Name' => '',
                     'Purpose' => '',
                     'Document Type' => '',
                     'Contact No.' => '',
                     'Barangay' => '',
                     'Issued on' => ''
             ];
         }
 
         // Convert data array to CSV format
         $csvData = implode(',', array_keys($responseData[0])) . "\n";
         foreach ($responseData as $record) {
             // Ensure each value is properly escaped and enclosed in quotes
             $csvData .= '"' . implode('","', array_map(function ($value) {
                 return str_replace('"', '""', $value);
             }, $record)) . '"' . "\n";
         }
 
         // Set headers to force download
         $response = $this->response
             ->setHeader('Content-Type', 'application/csv')
             ->setHeader('Content-Disposition', 'attachment; filename="list_of_issued_certificates.csv"')
             ->setBody($csvData);
 
         return $response;
    }
    // LIST OF SENIOR CITIZENS
    public function download_list_senior_citizens()
    {
        // Execute the query
        $model = new ResidentModel();
        $data = $model->where("age >=", 60)->where("status", "ACTIVE")->findAll();

        $responseData = [];

        if ($data && is_array($data) && count($data) > 0) {
            foreach ($data as $row) {
                    $resident_id = isset($row->resident_id) ? $row->resident_id : "";
                    $fullname = isset($row->fullname) ? $row->fullname : "";
                    $age = isset($row->age) ? $row->age : "";
                    $bday = isset($row->bday) ? $this->display_date($row->bday) : "";
                    $cstatus = isset($row->cstatus_id) ? $this->getCategoryDescription($row->cstatus_id) : "";
                    $cp = isset($row->cp) ? $row->cp : "";
                    $brgy_data = $this->getBrgyDescription($row->add_id ?? '');
                    $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
    
                    // Build data array
                    $responseData[] = [
                        'Resident ID' => $resident_id,
                        'Name' => $fullname,
                        'Age' => $age,
                        'Birthday' => $bday,
                        'Civil Status' => $cstatus,
                        'Contact No.' => $cp,
                        'Barangay' => $brgy,
                    ];
            }
        } else {
            // If no records found, set response data accordingly
            // Build data array
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Birthday' => "",
                'Civil Status' => "",
                'Contact No.' => "",
                'Barangay' => "",
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_senior_citizens.csv"')
            ->setBody($csvData);

        return $response;
    }

    // LIST OF PERSONS WITH DISABILITIES
    public function download_list_with_disabilities()
    {
        $disabilityModel = new DisabilityModel();

        // Fetch all disabilities data
        $disabilityData = $disabilityModel->distinct()->select("res_id")->findAll();

        $responseData = [];

        // If disabilities data exists
        if (!empty($disabilityData)) {
            foreach ($disabilityData as $row) {
                $res_id = isset($row->res_id) ? $row->res_id : "";

                $residentModel = new ResidentModel();
                $resident = $residentModel->where("id", $res_id)->first();

                if ($resident) {
                        $resident_id = isset($resident->resident_id) ? $resident->resident_id : "";
                        $fullname = isset($resident->fullname) ? $resident->fullname : "";
                        $age = $resident->age ?? '';
                        $cp = isset($resident->cp) ? $resident->cp : "";
                        $brgy_data = $this->getBrgyDescription($resident->add_id ?? '');
                        $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
                        $disabilitiesString = $this->collect_categoryBasedResident($res_id, $disabilityModel);
                        // PREPARE DATA FOR CSV
                        // Build data array
                        $responseData[] = [
                            'Resident ID' => $resident_id,
                            'Name' => $fullname,
                            'Age' => $age,
                            'Contact No.' => $cp,
                            'Barangay' => $brgy,
                            'Disabilities' => $disabilitiesString
                        ];
                }
            }
        } else {
            // If no disabilities data found
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Contact No.' => "",
                'Barangay' => "",
                'Disabilities' => ""
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_of_resident_with_disabilities.csv"')
            ->setBody($csvData);

        return $response;
    }

    // LIST OF PERSONS WITH COMORBIDITIES
    public function download_list_with_comorbidities()
    {
        $comorModel = new ComorbiditiesModel();

        // Fetch all disabilities data
        $comorData = $comorModel->distinct()->select("res_id")->findAll();

        $responseData = [];

        // If disabilities data exists
        if (!empty($comorData)) {
            foreach ($comorData as $row) {
                $res_id = isset($row->res_id) ? $row->res_id : "";

                $residentModel = new ResidentModel();
                $resident = $residentModel->where("id", $res_id)->first();

                if ($resident) {
                    $resident_id = isset($resident->resident_id) ? $resident->resident_id : "";
                    $fullname = isset($resident->fullname) ? $resident->fullname : "";
                    $age = $resident->age ?? "";
                    $cp = isset($resident->cp) ? $resident->cp : "";
                    $brgy_data = $this->getBrgyDescription($resident->add_id ?? '');
                    $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
                    $comorbiditiesString = $this->collect_categoryBasedResident($res_id, $comorModel);
                    // PREPARE DATA FOR CSV
                    // Build data array
                    $responseData[] = [
                        'Resident ID' => $resident_id,
                        'Name' => $fullname,
                        'Age' => $age,
                        'Contact No.' => $cp,
                        'Barangay' => $brgy,
                        'Comorbidities' => $comorbiditiesString
                    ];
                }
            }
        } else {
            // If no disabilities data found
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Contact No.' => "",
                'Barangay' => "",
                'Comorbidities' => ""
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_of_resident_with_comorbidities.csv"')
            ->setBody($csvData);

        return $response;
    }
    // LIST OF PERSONS WITH TRAININGS AND SKILLS
    public function download_list_with_trainings()
    {
        $trainingModel = new TrainingModel();

        // Fetch all disabilities data
        $trainingData = $trainingModel->distinct()->select("res_id")->findAll();

        $responseData = [];

        // If disabilities data exists
        if (!empty($trainingData)) {
            foreach ($trainingData as $row) {
                $res_id = isset($row->res_id) ? $row->res_id : "";

                $residentModel = new ResidentModel();
                $resident = $residentModel->where("id", $res_id)->first();

                if ($resident) {
                    $resident_id = isset($resident->resident_id) ? $resident->resident_id : "";
                    $fullname = isset($resident->fullname) ? $resident->fullname : "";
                    $age = $resident->age ?? "";
                    $cp = isset($resident->cp) ? $resident->cp : "";
                    $brgy_data = $this->getBrgyDescription($resident->add_id ?? '');
                    $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
                    $listOf_trainings = $this->collect_categoryBasedResident($res_id, $trainingModel);
                    // PREPARE DATA FOR CSV
                    // Build data array
                    $responseData[] = [
                        'Resident ID' => $resident_id,
                        'Name' => $fullname,
                        'Age' => $age,
                        'Contact No.' => $cp,
                        'Barangay' => $brgy,
                        'Trainings/Skills' => $listOf_trainings
                    ];
                }
            }
        } else {
            // If no disabilities data found
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Contact No.' => "",
                'Barangay' => "",
                'Trainings/Skills' => ""
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_of_resident_with_trainings_skills.csv"')
            ->setBody($csvData);

        return $response;
    }
    // LIST OF PERSONS WHO HAVE AVAILED GOVERNMENT PROGRAMS/ASSISTANCE
    public function download_list_with_gprograms()
    {
        $gprogramModel = new GprogramsModel();

        // Fetch all disabilities data
        $gprogramsData = $gprogramModel->distinct()->select("res_id")->findAll();

        $responseData = [];

        // If disabilities data exists
        if (!empty($gprogramsData)) {
            foreach ($gprogramsData as $row) {
                $res_id = isset($row->res_id) ? $row->res_id : "";

                $residentModel = new ResidentModel();
                $resident = $residentModel->where("id", $res_id)->first();

                if ($resident) {
                        $resident_id = isset($resident->resident_id) ? $resident->resident_id : "";
                        $fullname = isset($resident->fullname) ? $resident->fullname : "";
                        $age = $resident->age ?? "";
                        $cp = isset($resident->cp) ? $resident->cp : "";
                        $brgy_data = $this->getBrgyDescription($resident->add_id ?? '');
                        $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';

                        $listOf_gprograms = $this->collect_gprogramsBasedResident($res_id, $gprogramModel);
                        // PREPARE DATA FOR CSV
                        // Build data array
                        $responseData[] = [
                            'Resident ID' => $resident_id,
                            'Name' => $fullname,
                            'Age' => $age,
                            'Contact No.' => $cp,
                            'Barangay' => $brgy,
                            'Government Programs/Assistance and Date Acquired' => $listOf_gprograms
                        ];
                }
            }
        } else {
            // If no disabilities data found
            $responseData[] = [
                'Resident ID' => "",
                'Name' => "NO DATA AVAILABLE",
                'Age' => "",
                'Contact No.' => "",
                'Barangay' => "",
                'Government Programs/Assistance and Date Acquired' => ""
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="list_of_resident_availed_gprograms.csv"')
            ->setBody($csvData);

        return $response;
    }
    // HOUSEHOLD PROFILE
    public function download_residents_information()
    {
        $residentModel = new ResidentModel();

        // Fetch all disabilities data
        $residentData = $residentModel->findAll();

        $responseData = [];

        // If disabilities data exists
        if (!empty($residentData)) {
            foreach ($residentData as $row) {
                $res_id = $row->resident_id ?? "";
                    $lname = $row->lname ?? "";
                    $fname = $row->fname ?? "";
                    $mname = $row->mname ?? "";
                    $suffix = $row->suffix ?? "";
                    $fullname = $row->fullname ?? "";
                    $bday = isset($row->bday) ? $this->display_date($row->bday) : "";
                    $age = $row->age ?? "";
                    $bplace = $row->bplace ?? "";
                    $gender = $row->gender ?? "";
                    $cstatus = isset($row->cstatus_id) ? $this->getCategoryDescription($row->cstatus_id) : "";
                    $educ = isset($row->educ_id) ? $this->getCategoryDescription($row->educ_id) : "";
                    $course = isset($row->course_id) ? $this->getCategoryDescription($row->course_id) : "";
                    $rel = isset($row->rel_id) ? $this->getCategoryDescription($row->rel_id) : "";
                    $phealth_no = isset($row->phealth_no) ? $row->phealth_no : "";
                    $occ = isset($row->occ_id) ? $this->getCategoryDescription($row->occ_id) : "";
                    $m_income = isset($row->m_income) ? number_format($row->m_income) : "";
                    $cp = $row->cp ?? "";
                    $email = $row->email ?? "";
                    $nstatus = $row->nstatus ?? "";
                    $relation_hh = isset($row->relation_hh) ? $this->getCategoryDescription($row->relation_hh) : "";
                    $relation_fh = isset($row->relation_fh) ? $this->getCategoryDescription($row->relation_fh) : "";
                    $family_head = isset($row->fh_id) ? $this->getFamilyHead($row->fh_id) : "";
                    $btype = $row->btype ?? "";
                    $height = $row->height ?? "";
                    $weight = $row->weight ?? "";
                    $house_no = $row->house_no ?? "";
                    $street = $row->street ?? "";
                    $purok_data = $this->getPurokDescription($row->add_id);
                    $purok = isset($purok_data->description) ? $purok_data->description : '';
                    $brgy_data = $this->getBrgyDescription($row->add_id);
                    $barangay = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
    
                    $isHead = "";
                    if (isset($row->isHead)) {
                        $isHead = ($row->isHead == "TRUE") ? "YES" : "NO";
                    }
    
                    $status = isset($row->status) ? $row->status : "";
                    $household = isset($row->household) ? $row->household : "";
                    $created_on = isset($row->created_on) ? $this->display_date($row->created_on) : "";
    
                    // PREPARE DATA FOR CSV
                    // Build data array
                    $responseData[] = [
                        'Resident ID' => $res_id,
                        'Last Name' => $lname,
                        'First Name' => $fname,
                        'Middle Name' => $mname,
                        'Suffix' => $suffix,
                        'Fullname' => $fullname,
                        'Birthday' => $bday,
                        'Age' => $age,
                        'Birthplace' => $bplace,
                        'Gender' => $gender,
                        'Civil Status' => $cstatus,
                        'Educational Attainment' => $educ,
                        'Course' => $course,
                        'Religion' => $rel,
                        'Philhealth No.' => $phealth_no,
                        'Occupation' => $occ,
                        'Monthly Income' => $m_income,
                        'Contact No.' => $cp,
                        'Email' => $email,
                        'Nutritional Status' => $nstatus,
                        'Relationship to Household Head' => $relation_hh,
                        'Relationship to Family Head' => $relation_fh,
                        'Family Head' => $family_head,
                        'Blood Type' => $btype,
                        'Height' => $height,
                        'Weight' => $weight,
                        'House No.' => $house_no,
                        'Street' => $street,
                        'Purok/Zone' => $purok,
                        'Barangay' => $barangay,
                        'Household Head?' => $isHead,
                        'Status' => $status,
                        'Household ID' => $household,
                        'Date Entered' => $created_on
                    ];
            }
        } else {
            // If no disabilities data found
            $responseData[] = [
                'Resident ID' => "NDA",
                'Last Name' => "NDA",
                'First Name' => "NDA",
                'Middle Name' => "NDA",
                'Suffix' => "NDA",
                'Fullname' => "NDA",
                'Birthday' => "NDA",
                'Age' => "NDA",
                'Birthplace' => "NDA",
                'Gender' => "NDA",
                'Civil Status' => "NDA",
                'Educational Attainment' => "NDA",
                'Course' => "NDA",
                'Religion' => "NDA",
                'Philhealth No.' => "NDA",
                'Occupation' => "NDA",
                'Monthly Income' => "NDA",
                'Contact No.' => "NDA",
                'Email' => "NDA",
                'Nutritional Status' => "NDA",
                'Relationship to Household Head' => "NDA",
                'Relationship to Family Head' => "NDA",
                'Family Head' => "NDA",
                'Blood Type' => "NDA",
                'Height' => "NDA",
                'Weight' => "NDA",
                'House No.' => "NDA",
                'Street' => "NDA",
                'Purok/Zone' => "NDA",
                'Barangay' => "NDA",
                'Household Head?' => "NDA",
                'Status' => "NDA",
                'Household ID' => "NDA",
                'Date Entered' => "NDA"
            ];
        }

        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="residents_information.csv"')
            ->setBody($csvData);

        return $response;
    }

    // COLLECT CATEGORY DESCRIPTION
    private function collect_categoryBasedResident($res_id, $model)
    {

        $data = $model->where("res_id", $res_id)->findAll();

        $result = array();

        if ($data) {
            foreach ($data as $row) {
                $result[] = $this->getCategoryDescription($row->category_id);
            }
        } else {
            $result[] = "NDA";
        }

        $output = count($result) > 0 ? implode(", ", $result) : "";

        return $output;
    }

    // COLLECT GOVERNMENT PROGRAMS
    private function collect_gprogramsBasedResident($res_id, $model)
    {
        $data = $model->where("res_id", $res_id)->findAll();

        $result = array();

        if ($data) {
            foreach ($data as $row) {
                $date_acquired = isset($row->date_acquired) ? $this->display_date($row->date_acquired) : "";
                $result[] =  $this->getCategoryDescription($row->category_id) . " - " . $date_acquired;
            }
        } else {
            $result[] = "NDA";
        }

        $output = count($result) > 0 ? implode("; ", $result) : "";

        return $output;
    }

    // GET THE NAME OF FAMILY HEAD 
    private function getFamilyHead($res_id)
    {
        $family_head = "";
        if (!empty($res_id)) {
            $residentModel = new ResidentModel();
            $data = $residentModel->where("id", $res_id)->first();
            if (isset($data) && isset($data->fullname)) {
                if ($data->id == $res_id) {
                    // the current resident is also the family head
                    $family_head = "FAMILY HEAD";
                } else {
                    // get the name of the family head
                    $family_head = $data->fullname;
                }
            }
        }
        return $family_head;
    }

    private function online_request()
    {
        $TmpResidentModel = new TmpResidentModel();
        $LoginModel = new LoginModel();
        $CertificateModel = new CertificateModel();
        $ResidentModel = new ResidentModel();


        // Count distinct households with status 'FOR APPROVAL'
        $household_count = $TmpResidentModel->select('household')
            ->distinct()
            ->where('status', 'FOR APPROVAL')
            ->countAllResults();


        // Count users with status 'PENDING'
        $user_count = $LoginModel->where('status', 'PENDING')
            ->countAllResults();


        // Count request certification/clearances
        $certification_count = $CertificateModel
            ->where('status', 'FOR PAYMENT')
            ->orWhere('status', 'FOR ISSUANCE')
            ->countAllResults();
        
        $request = [
            "household_request" => $this->total_count($household_count),
            "user_request" => $this->total_count($user_count),
            "certification_request" => $this->total_count($certification_count)
        ];

        return $request;
    }
}
