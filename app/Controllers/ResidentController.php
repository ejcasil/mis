<?php

namespace App\Controllers;


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
use App\Models\LoginModel;
use Exception;

class ResidentController extends BaseController
{

    public function active_inactive()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') === "ADMIN") {
            return view('administrator/resident/active_inactive', $output);
        } else if (session()->get('role') === "MAIN") {
            return view('main/resident/active_inactive', $output);
        }
    }

    // Get List of Household Heads for Table Display
    public function getResidentData()
    {

        $residentModel = new ResidentModel();

        try {
            $collect_resident = [];

            // Fetch and process resident data
            $resident_data = $residentModel->where('isHead', 'TRUE')->findAll();
            foreach ($resident_data as $row) {
                // Get add_id from tblresident
                $add_id = ($row->add_id) ? $row->add_id : '';

                if (!empty($add_id)) {
                    $brgy_data = $this->getBrgyDescription($add_id);
                    if ($brgy_data != false) {
                        if (session()->get('role') === "ADMIN" || session()->get('role') === "RESIDENT") {
                            if ($brgy_data->id == $this->brgy_id) {
                                $collect_resident[] = $this->processResidentRow($row);
                            }
                        } else if (session()->get('role') === "MAIN") {
                                $collect_resident[] = $this->processResidentRow($row);
                        }
                    }
                }
            }

            // Return the collected data as JSON
            return $this->response->setJSON(['data' => $collect_resident]);
        } catch (\Exception $e) {
            // Return error details in JSON response
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Add Resident Button CLicked
    public function add()
    {
        // Get cstatus
        $output['cstatus'] = $this->getListDescriptionBasedOnCategory('cstatus');
        // Get educational attainment
        $output['educ'] = $this->getListDescriptionBasedOnCategory('educ');
        // Get course
        $output['course'] = $this->getListDescriptionBasedOnCategory('course');
        // Get religion
        $output['rel'] = $this->getListDescriptionBasedOnCategory('rel');
        // Get occupation
        $output['occ'] = $this->getListDescriptionBasedOnCategory('occ');
        // Get relation (relation to household head/ family head)
        $output['relation'] = $this->getListDescriptionBasedOnCategory("relation");
        // Get building type
        $output['bldgtype'] = $this->getListDescriptionBasedOnCategory("bldgtype");
        // Get current brgy from barangay profile
        $BrgyCodeModel = new BrgyCodeModel();
        if (session()->get('role') === "ADMIN") {
            $brgy_data = $BrgyCodeModel->find($this->brgy_id);
        } else if (session()->get('role') === "MAIN" || session()->get('role') === "RESIDENT") {
            $brgy_data = $BrgyCodeModel->findAll();
        }
        
        $output['barangay'] = $brgy_data;

        // Gather list of purok based from brgy_id
        $listPurok = $this->getListOfPurok($this->brgy_id);

        if ($listPurok != false) {
            $output['purok'] = $listPurok;
        }

        // Gather list of document types
        $output['doctype'] = $this->getListDescriptionBasedOnCategory('doctype');

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();


        if (session()->get('role') === "ADMIN") {
            return view('administrator/resident/add', $output);
        } else if (session()->get('role') === "MAIN") { 
            return view('main/resident/add', $output);
        } else {
            return view('resident/household/add', $output);
        }
    }

    // Upload document - Supporting documents 
    public function uploadDocument()
    {
        $post = $this->request->getPost();

        // =========== Upload file =========== //

        $file = $this->request->getFile('file-doctype');

        if ($this->request->getFile('file-doctype') !== null && $file->isValid()) {

            // Call the uploadFile method with the appropriate input name
            $allowedType = ['png', 'jpg', 'jpeg', 'pdf'];
            $result = $this->uploadFile('file-doctype', $allowedType);

            // Check the result and respond accordingly
            if ($result['status']) {
                // Successfully uploaded
                $file_path = $result['file_name']; // Get the unique file name
                // Save file_name to table
                $isSaved = $this->saveFileToTable($file_path);
                if ($isSaved) {
                    return $this->response->setJSON([
                        'success' => true,
                        'file_path' => $file_path
                    ]);
                } else {
                    // Handle errors
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => "Error: Saving uploaded file"
                    ]);
                }
            } else {
                // Handle errors
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $result['error']
                ]);
            }
        } else {
            // Handle errors
            return $this->response->setJSON([
                'success' => false,
                'errors' => "Can't upload file."
            ]);
        }

        // =========== End of Upload file =========== //
    }

    // Set Household ID 
    public function getHouseholdID()
    {
        $barangay = $_POST['barangay'];
        $purok = $_POST['purok'];
        $house_no = $_POST['house_no'];

        $data = [
            "add_id" => $purok,
            "house_no" => $house_no
        ];

        $response = $this->setHouseholdID($data);
        return $this->response->setJSON(["data" => $response]);
        // Now, you can process the data or store it in the database

    }

    // SAVE CATEGORY - LIST
    public function createList()
    {
        $post = $this->request->getPost();

        $error_list = array();
        $status = "failed";

        // Check if 'category' and 'description' keys exist in the $post array
        if (isset($post['category']) && isset($post['description'])) {
            $category = $post['category'];
            $description = $post['description'];

            // Regular expression pattern
            $pattern = "/^[a-zA-Z0-9 ,.'\s]+$/";

            // accept only alphanumeric
            if (!preg_match($pattern, $description)) {
                $error_list[] = "String contains non-alphanumeric characters";
            } elseif ($this->descriptionExists($category, $description)) {
                $error_list[] = "Description already exists";
            } else {
                // INSERT DATA
                $categoryModel = new \App\Models\CategoryModel();
                $data = array(
                    'category' => $category,
                    'description' => strtoupper($description),
                    'status' => 'ACTIVE'
                );
                $insert = $categoryModel->insert($data);

                if ($insert) {
                    $status = "success";
                } else {
                    $error_list[] = "Failed to insert data";
                }
            }
        } else {
            $error_list[] = "Category or description is null";
        }

        // Output the response
        return $this->response->setJSON([
            'status' => $status,
            'errors' => $error_list
        ]);
    }

    // LOAD CATEGORY - LIST
    public function loadCategory()
    {
        $category = $this->request->getPost('category');

        $categoryModel = new CategoryModel();
        $data = $categoryModel->where("category", $category)->where("status", "ACTIVE")->findAll();

        $output = array();
        if (is_array($data) && count($data) > 0) {
            $ctr = 1;
            foreach ($data as $row) {
                $category_id = $row->id;
                $desc = $row->description;

                $output[] = "<tr>
                                    <td class='d-flex'>
                                        <input type='checkbox' id='chk$ctr' class='form-check me-2 chkRow' data-id='$category_id'>
                                        <label for='chk$ctr'>$desc</label>
                                    </td>
                                </tr>";
                $ctr++;
            }
        }

        return $this->response->setJSON($output);
    }

    // VIEW FILE
    public function viewFile($filename)
    {
        // Sanitize the filename (remove path info to prevent directory traversal)
        $filename = basename($filename);

        // Set the path to the file
        $path = WRITEPATH . 'uploads/' . $filename;

        // Check if the file exists
        if (!file_exists($path)) {
            // File doesn't exist, throw a 404 error
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Determine the MIME type
        $mimeType = mime_content_type($path);

        // Serve the file
        return $this->response->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(readfile($path));  // Use readfile for better memory management with large files
    }

    // SAVE HOUSEHOLD HEAD TO TMPRESIDENTMODEL, UPDATE STATUS = 'FOR APPROVAL'
    public function save()
    {
        $post = $this->request->getPost();

        // Retrieve form data from the request
        $formData = $post['formData'];
        // Parse the serialized form data into an associative array
        parse_str($formData, $form);

        // GENERATE RANDOM 10 DIGIT NUMBER
        // $random_number = mt_rand(100000, 999999);
        $random_number = $this->generate_code();
        // RESIDENT ID
        $res_id = isset($form['id']) && !empty($form['id']) ? $form['id'] : $random_number;

        // Get img_path form saved profile picture
        $img_path = ($form['img-path']) ?? '';

        // Retrieve other data from the request
        $training = json_decode($post['training'], true);
        $gprograms = json_decode($post['gprograms'], true);
        $dialect = json_decode($post['dialect'], true);
        $sincome = json_decode($post['sincome'], true);
        $app = json_decode($post['app'], true);
        $disability = json_decode($post['disability'], true);
        $comor = json_decode($post['comor'], true);
        $vhcl = json_decode($post['vhcl'], true);
        $docs = json_decode($post['docs'], true);

        // Initialize models
        $TmpResidentModel = new TmpResidentModel();
        $ResidentModel = new ResidentModel();

        // Gather ERROR LIST
        $error_list = [];

        // =========== Upload file =========== //

        $file = $this->request->getFile("pic");
        $file_path = "";

        // Check if there is a file to upload
        if ($this->request->getFile("pic") !== null && $file->isValid()) {

            // Call the uploadFile method with the appropriate input name
            $allowedType = ['png', 'jpg', 'jpeg'];
            $result = $this->uploadFile('pic', $allowedType);

            // Check the result and respond accordingly
            if ($result['status']) {
                // Successfully uploaded
                $file_path = $result['file_name']; // Get the unique file name
                $img_path = $file_path;
            } else {
                // Handle errors
                $error_list[] = $result['error'];
                // return $this->response->setJSON([
                //     'success' => false,
                //     'errors' => $result['error']
                // ]);
            }
        }

        // =========== End of Upload file =========== //

        // Format fullname
        $fullname = $this->formatFullname([
            'lname' => ($form['lname']) ?? '',
            'fname' => ($form['fname']) ?? '',
            'mname' => ($form['mname']) ?? '',
            'suffix' => ($form['suffix']) ?? '',
        ]);

        // Format bday
        $bday = $form['bday'] ? $this->save_date($form['bday']) : '';

        // Compute age
        $age = $this->compute_age($bday);

        // Get household id
        $household_id = $this->setHouseholdID([
            'add_id' => ($form['purok']) ?? '',
            'house_no' => ($form['house_no']) ?? ''
        ]);

        // Get resident id
        $resident_id = $this->setResidentID([
            'household_id' => $household_id,
            'res_id' => $res_id
        ]);

        // SET isHead = 'TRUE'
        $isHead = "TRUE";

        // SET status = 'FOR APPROVAL'
        $status = "FOR APPROVAL";

        // DELETE FROM tblTemp WHERE household_id = $household
        // Delete data first
        $TmpResidentModel->where('household', $household_id)->delete();


        /**
         * CHECK FULLNAME DUPLICATION
         * CHECK EMAIL UNIQUENESS
         * EXCLUDING THE RES_ID which is the UNIQUE ID of the tblresident
         */

        $check_fullname = $ResidentModel->where("fullname", $fullname)->where("id !=", $res_id)->findAll();
        $check_fullname_tmp = $TmpResidentModel->where("fullname", $fullname)->where("id !=", $res_id)->findAll();

        if ($check_fullname || $check_fullname_tmp) {
            // Fullname already exists
            $error_list[] = "Resident already exists";
        }

        // Check email uniqueness
        $email = ($form['email']) ?? '';
        $check_email = $ResidentModel->where("email", $email)->where("id !=", $res_id)->findAll();
        $check_email_tmp = $TmpResidentModel->where("email", $email)->where("id !=", $res_id)->findAll();

        if (!empty($check_email) || !empty($check_email_tmp)) {
            // Email already exists
            $error_list[] = "Email already exists";
        }

        /**
         * FOR RESIDENT ACCOUNT ONLY
         * CHECK IF THERE IS AN EXISTING HOUSEHOLD FROM THE SELECTED HOUSEHOLD
         * IF TRUE, THEN NOTIFY USER THAT THE HOUSEHOLD PROFILE ALREADY EXISTS
         */
        if (session()->get('role') == "RESIDENT") {
            $isHouseholdExists = $ResidentModel->where('household', $household_id)->first();

            if ($isHouseholdExists) {
                if (!session()->has('household_id') || session()->get('household_id') != $isHouseholdExists->household) {
                    $error_list[] = "Household profile already exists";
                }
            }
            
        }

        if (empty($error_list)) {
            // PROCEED TO SAVING DATA TO TBLTMP AND OTHER DATA
            // FORM DATA
            $data = array(
                'id' => $res_id,
                'lname' => (strtoupper($form['lname'])) ?? '',
                'fname' => (strtoupper($form['fname'])) ?? '',
                'mname' => (strtoupper($form['mname'])) ?? '',
                'suffix' => (strtoupper($form['suffix'])) ?? '',
                'fullname' => $fullname,
                'bday' => $bday,
                'age' => $age,
                'bplace' => ($form['bplace']) ?? '',
                'gender' => ($form['gender']) ?? '',
                'cstatus_id' => ($form['cstatus']) ?? '',
                'educ_id' => ($form['education']) ?? '',
                'course_id' => ($form['course']) ?? '',
                'rel_id' => ($form['religion']) ?? '',
                'phealth_no' => ($form['philhealth']) ?? '',
                'occ_id' => ($form['occupation']) ?? '',
                'm_income' => ($form['monthly_income']) ?? '',
                'cp' => ($form['cp']) ?? '',
                'email' => $email,
                'nstatus' => ($form['nstatus']) ?? '',
                'relation_hh' => '',
                'relation_fh' => '',
                'fh_id' => '',
                'btype' => ($form['btype']) ?? '',
                'height' => ($form['height']) ?? '',
                'weight' => ($form['weight']) ?? '',
                'img_path' => $img_path,
                'house_no' => ($form['house_no']) ?? '',
                'street' => ($form['street']) ?? '',
                'add_id' => ($form['purok']) ?? '',
                'isHead' => $isHead,
                'status' => $status,
                'household' => $household_id,
                'resident_id' => $resident_id
            );

            // SAVE HOUSEHOLD HEAD ===
            $TmpResidentModel->insert($data);

            /**
             * SAVE DATA COLLECTED FROM TABLES
             * UPDATE status = 'TMP'
             */
            $tbl_status = "TMP";

            // TRAINING AND SKILLS
            if (is_array($training) && count($training) > 0) {
                // loop inside the table
                //Todo:
                $trainingModel = new TrainingModel();
                // Delete data first
                $trainingModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($training as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $trainingModel->insert($data);
                    }
                }
            }

            // GOVERNMENT PROGRAMS/ASSISTANCE AVAILED
            if (is_array($gprograms) && count($gprograms) > 0) {
                // loop inside the table
                //Todo:
                $gprogramsModel = new GprogramsModel();
                // Delete data first
                $gprogramsModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($gprograms as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $date_acquired = $row['dateAcquired'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'date_acquired' => $this->save_date($date_acquired),
                            'status' => $tbl_status
                        );
                        $gprogramsModel->insert($data);
                    }
                }
            }

            // DIALECT
            if (is_array($dialect) && count($dialect) > 0) {
                // loop inside the table
                //Todo:
                $dialectModel = new DialectModel();
                // Delete data first
                $dialectModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($dialect as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $dialectModel->insert($data);
                    }
                }
            }

            // SOURCE OF INCOME
            if (is_array($sincome) && count($sincome) > 0) {
                // loop inside the table
                //Todo:
                $sincomeModel = new SincomeModel();
                // Delete data first
                $sincomeModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($sincome as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $sincomeModel->insert($data);
                    }
                }
            }

            // APPLIANCES
            if (is_array($app) && count($app) > 0) {
                // loop inside the table
                //Todo:
                $iappModel = new AppliancesModel();
                // Delete data first
                $iappModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($app as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $qty = $row['qty'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'qty' => $qty,
                            'status' => $tbl_status
                        );
                        $iappModel->insert($data);
                    }
                }
            }

            // DISABILITY
            if (is_array($disability) && count($disability) > 0) {
                // loop inside the table
                //Todo:
                $idisableModel = new DisabilityModel();
                // Delete data first
                $idisableModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($disability as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $idisableModel->insert($data);
                    }
                }
            }

            // COMORBIDITIES
            if (is_array($comor) && count($comor) > 0) {
                // loop inside the table
                //Todo:
                $icomorModel = new ComorbiditiesModel();
                // Delete data first
                $icomorModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($comor as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $icomorModel->insert($data);
                    }
                }
            }

            // VEHICLE
            if (is_array($vhcl) && count($vhcl) > 0) {
                // loop inside the table
                //Todo:
                $ivhclModel = new VehicleModel();
                // Delete data first
                $ivhclModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($vhcl as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $qty = $row['qty'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'qty' => $qty,
                            'status' => $tbl_status
                        );
                        $ivhclModel->insert($data);
                    }
                }
            }

            // SUPPORTING DOCUMENTS UPLOADED
            if (is_array($docs) && count($docs) > 0) {
                // loop inside the table
                //Todo:
                $AttachmentsModel = new AttachmentsModel();
                // Delete data first
                $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($docs as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $file_path = $row['file_path'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'filename' => $file_path,
                            'status' => $tbl_status
                        );
                        $AttachmentsModel->insert($data);
                    }
                }
            }

            // INSERT EXISTING MEMBERS TO TBLTEMP
            $isOkay = $this->insert_householdMBR_2_TEMP($household_id);
            // Activity log
            $this->activityLogService->logActivity("Saved household head with the household ID: $household_id", session()->get("id"));

            // return 
            return $this->response->setJSON(
                [
                    'success' => true,
                    'household' => $household_id
                ]
            );
        } else {
            // Error encountered
            return $this->response->setJSON([
                'success' => false,
                'errors' => $error_list
            ]);
        }
    }

    // SAVE HOUSEHOLD MEMBER TO TMPRESIDENTMODEL, UPDATE STATUS = 'FOR APPROVAL'
    // CHECK IF THE RESIDENT ID EXISTS IN TMPRESIDENTMODEL, IF TRUE, THEN REPLACE IT WITH THE NEW ONE
    // CHECK IF THE RESIDENT ID EXISTS IN TABLES, IF TRUE, THEN REPLACE IT WITH THE NEW
    // SAVE HOUSEHOLD HEAD TO TMPRESIDENTMODEL, UPDATE STATUS = 'FOR APPROVAL'
    public function saveMBR()
    {
        $post = $this->request->getPost();

        // Retrieve form data from the request
        $formData = $post['formData'];
        // Parse the serialized form data into an associative array
        parse_str($formData, $form);

        // GENERATE RANDOM 10 DIGIT NUMBER
        // $random_number = mt_rand(100000, 999999);
        $random_number = $this->generate_code();
        // RESIDENT ID
        $res_id = isset($form['id']) && !empty($form['id']) ? $form['id'] : $random_number;

        // Get img_path form saved profile picture
        $img_path = isset($form['img-path']) ? $form['img-path'] : '';

        // Retrieve other data from the request
        $training = json_decode($post['training'], true);
        $gprograms = json_decode($post['gprograms'], true);
        $dialect = json_decode($post['dialect'], true);
        $sincome = json_decode($post['sincome'], true);
        $app = json_decode($post['app'], true);
        $disability = json_decode($post['disability'], true);
        $comor = json_decode($post['comor'], true);
        $vhcl = json_decode($post['vhcl'], true);
        $docs = json_decode($post['docs'], true);

        // Initialize models
        $TmpResidentModel = new TmpResidentModel();
        $ResidentModel = new ResidentModel();

        // Gather ERROR LIST
        $error_list = [];

        // =========== Upload file =========== //

        $file = $this->request->getFile("pic");
        $file_path = "";

        // Check if there is a file to upload
        if ($this->request->getFile("pic") !== null && $file->isValid()) {

            // Call the uploadFile method with the appropriate input name
            $allowedType = ['png', 'jpg', 'jpeg'];
            $result = $this->uploadFile('pic', $allowedType);

            // Check the result and respond accordingly
            if ($result['status']) {
                // Successfully uploaded
                $file_path = $result['file_name']; // Get the unique file name
                $img_path = $file_path;
            } else {
                // Handle errors
                $error_list[] = $result['error'];
                // return $this->response->setJSON([
                //     'success' => false,
                //     'errors' => $result['error']
                // ]);
            }
        }

        // =========== End of Upload file =========== //

        // Format fullname
        $fullname = $this->formatFullname([
            'lname' => ($form['lname']) ?? '',
            'fname' => ($form['fname']) ?? '',
            'mname' => ($form['mname']) ?? '',
            'suffix' => ($form['suffix']) ?? '',
        ]);

        // Format bday
        $bday = $form['bday'] ? $this->save_date($form['bday']) : '';

        // Compute age
        $age = $this->compute_age($bday);

        // Get household id
        $household_id = ($form['household']) ?? '';

        // Get resident id
        $resident_id = $this->setResidentID([
            'household_id' => $household_id,
            'res_id' => $res_id
        ]);

        // Get purok_id OR add_id AND street AND house_no BASED ON household_id
        $add_id = "";
        $street = "";
        $house_no = "";
        $tmp_data = $TmpResidentModel->where('household', $household_id)->where('isHead', "TRUE")->first();
        if ($tmp_data) {
            $add_id = ($tmp_data->add_id) ?? '';
            $street = ($tmp_data->street) ?? '';
            $house_no = ($tmp_data->house_no) ?? '';
        }

        // SET isHead = 'FALSE'
        $isHead = "FALSE";

        // Get the value of family_head
        $family_head = $form['family_head'] ?? '';

        // SET fh_id based on chkbox = isFamilyHead else Get the value of the select element which is family_head
        $fh_id = isset($form['isFamilyHead']) ? $res_id : $family_head;


        // SET status = 'FOR APPROVAL'
        $status = "FOR APPROVAL";

        /**
         * CHECK FULLNAME DUPLICATION
         * CHECK EMAIL UNIQUENESS
         * EXCLUDING THE RES_ID which is the UNIQUE ID of the tblresident
         */

        $check_fullname = $ResidentModel->where("fullname", $fullname)->where("id !=", $res_id)->findAll();
        $check_fullname_tmp = $TmpResidentModel->where("fullname", $fullname)->where("id !=", $res_id)->findAll();

        if (!empty($check_fullname) || !empty($check_fullname_tmp)) {
            // Fullname already exists
            $error_list[] = "Resident already exists";
        }

        // Check email uniqueness
        $email = ($form['email']) ?? '';
        $check_email = $ResidentModel->where("email", $email)->where("id !=", $res_id)->findAll();
        $check_email_tmp = $TmpResidentModel->where("email", $email)->where("id !=", $res_id)->findAll();

        if (!empty($check_email) || !empty($check_email_tmp)) {
            // Email already exists
            $error_list[] = "Email already exists";
        }

        if (empty($error_list)) {
            // PROCEED TO SAVING DATA TO TBLTMP AND OTHER DATA
            // FORM DATA
            $data = array(
                'id' => $res_id,
                'lname' => (strtoupper($form['lname'])) ?? '',
                'fname' => (strtoupper($form['fname'])) ?? '',
                'mname' => (strtoupper($form['mname'])) ?? '',
                'suffix' => (strtoupper($form['suffix'])) ?? '',
                'fullname' => $fullname,
                'bday' => $bday,
                'age' => $age,
                'bplace' => ($form['bplace']) ?? '',
                'gender' => ($form['gender']) ?? '',
                'cstatus_id' => ($form['cstatus']) ?? '',
                'educ_id' => ($form['education']) ?? '',
                'course_id' => ($form['course']) ?? '',
                'rel_id' => ($form['religion']) ?? '',
                'phealth_no' => ($form['philhealth']) ?? '',
                'occ_id' => ($form['occupation']) ?? '',
                'm_income' => ($form['monthly_income']) ?? '',
                'cp' => ($form['cp']) ?? '',
                'email' => $email,
                'nstatus' => ($form['nstatus']) ?? '',
                'relation_hh' => ($form['relation_hh']) ?? '',
                'relation_fh' => ($form['relation_fh']) ?? '',
                'fh_id' => $fh_id,
                'btype' => ($form['btype']) ?? '',
                'height' => ($form['height']) ?? '',
                'weight' => ($form['weight']) ?? '',
                'img_path' => $img_path,
                'house_no' => $house_no,
                'street' => $street,
                'add_id' => $add_id,
                'isHead' => $isHead,
                'status' => $status,
                'household' => $household_id,
                'resident_id' => $resident_id
            );

            // DELETE FROM tblTemp WHERE household_id = $household
            // Delete data first
            $TmpResidentModel->where('id', $res_id)->delete();
            // SAVE HOUSEHOLD MEMBER ===
            $TmpResidentModel->insert($data);

            /**
             * SAVE DATA COLLECTED FROM TABLES
             * UPDATE status = 'TMP'
             */
            $tbl_status = "TMP";

            // TRAINING AND SKILLS
            if (is_array($training) && count($training) > 0) {
                // loop inside the table
                //Todo:
                $trainingModel = new TrainingModel();
                // Delete data first
                $trainingModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($training as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $trainingModel->insert($data);
                    }
                }
            }

            // GOVERNMENT PROGRAMS/ASSISTANCE AVAILED
            if (is_array($gprograms) && count($gprograms) > 0) {
                // loop inside the table
                //Todo:
                $gprogramsModel = new GprogramsModel();
                // Delete data first
                $gprogramsModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($gprograms as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $date_acquired = $row['dateAcquired'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'date_acquired' => $this->save_date($date_acquired),
                            'status' => $tbl_status
                        );
                        $gprogramsModel->insert($data);
                    }
                }
            }

            // DIALECT
            if (is_array($dialect) && count($dialect) > 0) {
                // loop inside the table
                //Todo:
                $dialectModel = new DialectModel();
                // Delete data first
                $dialectModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($dialect as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $dialectModel->insert($data);
                    }
                }
            }

            // SOURCE OF INCOME
            if (is_array($sincome) && count($sincome) > 0) {
                // loop inside the table
                //Todo:
                $sincomeModel = new SincomeModel();
                // Delete data first
                $sincomeModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($sincome as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $sincomeModel->insert($data);
                    }
                }
            }

            // APPLIANCES
            if (is_array($app) && count($app) > 0) {
                // loop inside the table
                //Todo:
                $iappModel = new AppliancesModel();
                // Delete data first
                $iappModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($app as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $qty = $row['qty'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'qty' => $qty,
                            'status' => $tbl_status
                        );
                        $iappModel->insert($data);
                    }
                }
            }

            // DISABILITY
            if (is_array($disability) && count($disability) > 0) {
                // loop inside the table
                //Todo:
                $idisableModel = new DisabilityModel();
                // Delete data first
                $idisableModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($disability as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $idisableModel->insert($data);
                    }
                }
            }

            // COMORBIDITIES
            if (is_array($comor) && count($comor) > 0) {
                // loop inside the table
                //Todo:
                $icomorModel = new ComorbiditiesModel();
                // Delete data first
                $icomorModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($comor as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'status' => $tbl_status
                        );
                        $icomorModel->insert($data);
                    }
                }
            }

            // VEHICLE
            if (is_array($vhcl) && count($vhcl) > 0) {
                // loop inside the table
                //Todo:
                $ivhclModel = new VehicleModel();
                // Delete data first
                $ivhclModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($vhcl as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $qty = $row['qty'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'qty' => $qty,
                            'status' => $tbl_status
                        );
                        $ivhclModel->insert($data);
                    }
                }
            }

            // SUPPORTING DOCUMENTS UPLOADED
            if (is_array($docs) && count($docs) > 0) {
                // loop inside the table
                //Todo:
                $AttachmentsModel = new AttachmentsModel();
                // Delete data first
                $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // Insert new data
                foreach ($docs as $row) {
                    if (array_key_exists('id', $row)) {
                        $category_id = $row['id'];
                        $file_path = $row['file_path'];
                        $data = array(
                            'res_id' => $res_id,
                            'category_id' => $category_id,
                            'filename' => $file_path,
                            'status' => $tbl_status
                        );
                        $AttachmentsModel->insert($data);
                    }
                }
            }

            // // INSERT EXISTING MEMBERS TO TBLTEMP
            // $isOkay = $this->insert_householdMBR_2_TEMP($household_id);
            // Log activity
            $this->activityLogService->logActivity("Saved household member with the household ID: $household_id", session()->get("id"));

            // return 
            return $this->response->setJSON(
                [
                    'success' => true,
                    'household' => $household_id
                ]
            );
        } else {
            // Error encountered
            return $this->response->setJSON([
                'success' => false,
                'errors' => $error_list
            ]);
        }
    }

    // GET LIST OF HOUSEHOLD MEMBERS FOR TABLE MEMBERS (FORM)
    public function load_household_member($household)
    {
        $household_id = $household ?? '';
        $isHead = "FALSE";

        // Initialize ResidentModel and TmpResidentModel instances
        $ResidentModel = new ResidentModel();
        $TmpResidentModel = new TmpResidentModel();

        // Gather list of household members from ResidentModel
        $resident_data = $ResidentModel->where("household", $household_id)
            ->where("isHead", $isHead)
            ->findAll();

        // Gather list of household members from TmpResidentModel
        $tmpresident_data = $TmpResidentModel->where("household", $household_id)
            ->where("isHead", $isHead)
            ->findAll();

        // Create a hash map for tmpresident_data for faster lookup by ID
        $tmpresident_map = [];
        foreach ($tmpresident_data as $tmp) {
            $tmpresident_map[$tmp->id] = $tmp; // Use 'id' as the key
        }

        // Arrays to store matched and unmatched results
        $matched_data = [];
        $unmatched_data = [];

        // Iterate through resident_data
        foreach ($resident_data as $resident) {
            $res_id = $resident->id;

            // If the resident id exists in tmpresident_map, use the data from tmpresident
            if (isset($tmpresident_map[$res_id])) {
                $tmp = $tmpresident_map[$res_id];  // Get the matched tmp resident data

                // Overwrite resident data with tmpresident data
                $matched_data[] = [
                    'id' => $tmp->id,
                    'resident_id' => $tmp->resident_id,
                    'name' => $tmp->fullname,
                    'age' => $tmp->age,
                    'cstatus' => $this->getCategoryDescription($tmp->cstatus_id),
                    'relation_hh' => $this->getCategoryDescription($tmp->relation_hh),
                ];

                // Remove the matched tmpresident from the map to avoid duplicating it in unmatched_data
                unset($tmpresident_map[$res_id]);
            } else {
                // No match, so save the unmatched resident data
                $unmatched_data[] = [
                    'id' => $res_id,
                    'resident_id' => $resident->resident_id,
                    'name' => $resident->fullname,
                    'age' => $resident->age,
                    'cstatus' => $this->getCategoryDescription($resident->cstatus_id),
                    'relation_hh' => $this->getCategoryDescription($resident->relation_hh),
                ];
            }
        }

        // Any remaining data in tmpresident_map is unmatched, so save those
        foreach ($tmpresident_map as $tmp) {
            $unmatched_data[] = [
                'id' => $tmp->id,
                'resident_id' => $tmp->resident_id,
                'name' => $tmp->fullname,
                'age' => $tmp->age,
                'cstatus' => $this->getCategoryDescription($tmp->cstatus_id),
                'relation_hh' => $this->getCategoryDescription($tmp->relation_hh),
            ];
        }

        // Combine matched and unmatched data
        $household_members = array_merge($matched_data, $unmatched_data);

        // Return the output as a JSON response
        return $this->response->setJSON(['data' => $household_members]);
    }

    // GET LIST OF HOUSEHOLD MEMBERS FOR SELECT ELEMENT FAMILY_HEAD
    public function getFamilyHeads($household)
    {
        $household_id = $household ?? '';

        // Initialize ResidentModel and TmpResidentModel instances
        $TmpResidentModel = new TmpResidentModel();

        $tmp_data = $TmpResidentModel->where('household', $household_id)->findAll();

        $household_members = [];

        if ($tmp_data) {
            foreach ($tmp_data as $row) {
                $household_members[] = [
                    'id' => $row->id,
                    'name' => $row->fullname
                ];
            }
        }

        // Return the output as a JSON response
        return $this->response->setJSON(['data' => $household_members]);
    }

    // FETCH INDIVIDUAL DATA FOR UPDATE
    public function fetchIndividual($id)
    {
        // Initialize models
        $TmpResidentModel = new TmpResidentModel();
        $TrainingModel = new TrainingModel();
        $GprogramsModel = new GprogramsModel();
        $DialectModel = new DialectModel();
        $SincomeModel = new SincomeModel();
        $AppliancesModel = new AppliancesModel();
        $DisabilityModel = new DisabilityModel();
        $ComorbiditiesModel = new ComorbiditiesModel();
        $VehicleModel = new VehicleModel();
        $AttachmentsModel = new AttachmentsModel();

        $tbl_status = "TMP";
        $status = "FOR APPROVAL";

        // Retrieve the data-id value from the request
        $res_id = $id;

        // Fetch data based on the id submitted
        $data = $TmpResidentModel->where('id', $res_id)->where('status', $status)->first();

        // UPDATE BDAY FORMAT
        $data->bday = !empty($data->bday) ? $this->display_date($data->bday) : '';

        // CHECK IF FH_ID = $res_id then assign 'checked' if true;
        $fh_id = isset($data->fh_id) ? $data->fh_id : '';
        $checked = false;
        if ($res_id == $fh_id) {
            $checked = true;
        }

        // Fetch brgy and purok 
        $purok_id = $data->add_id ?? '';
        $brgy_data = $this->getPurokDescription($purok_id);
        $brgy_id = isset($brgy_data->brgy_id) ? $brgy_data->brgy_id : '';


        // Fetch training
        $data_training = $TrainingModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_training = array();
        if (is_array($data_training) && count($data_training) > 0) {
            foreach ($data_training as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description
                $html_training[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_training[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }

        // Fetch gprograms (category_id, date_acquired)
        $data_gprograms = $GprogramsModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_gprograms = array();
        if (is_array($data_gprograms) && count($data_gprograms) > 0) {
            foreach ($data_gprograms as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $date_acquired = $this->display_date($row->date_acquired);
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_gprograms[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td>
                                                <input type='text' class='form-control datetimepicker' placeholder='mm-dd-yyyy' value='$date_acquired'>
                                            </td>
                                    </tr>";
            }
        } else {
            $html_gprograms[] = "<tr>
                                    <td class='text-center' colspan='2'>No record found</td>
                                </tr>";
        }
        // Fetch Dialect spoken
        $data_dialect = $DialectModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_dialect = array();
        if (is_array($data_dialect) && count($data_dialect) > 0) {
            foreach ($data_dialect as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_dialect[] = "<tr>
                                         <td>
                                             <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                 <i class='fi fi-rs-trash'></i>
                                             </button>
                                             <span>$cDesc</span>
                                         </td>
                                     </tr>";
            }
        } else {
            $html_dialect[] = "<tr>
                                     <td class='text-center'>No record found</td>
                                 </tr>";
        }

        // Fetch All applicable sources of income
        $data_sincome = $SincomeModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_sincome = array();
        if (is_array($data_sincome) && count($data_sincome) > 0) {
            foreach ($data_sincome as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_sincome[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_sincome[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // Fetch appliances (category_id, qty)
        $data_app = $AppliancesModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_app = array();
        if (is_array($data_app) && count($data_app) > 0) {
            foreach ($data_app as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_app[] = "<tr>
                                         <td>
                                             <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                 <i class='fi fi-rs-trash'></i>
                                             </button>
                                             <span>$cDesc</span>
                                         </td>
                                         <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                     </tr>";
            }
        } else {
            $html_app[] = "<tr>
                                     <td class='text-center' colspan='2'>No record found</td>
                                 </tr>";
        }
        // Fetch disability
        $data_disability = $DisabilityModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_disability = array();
        if (is_array($data_disability) && count($data_disability) > 0) {
            foreach ($data_disability as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_disability[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_disability[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // Fetch comorbidities
        $data_comorbidities = $ComorbiditiesModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_comor = array();
        if (is_array($data_comorbidities) && count($data_comorbidities) > 0) {
            foreach ($data_comorbidities as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_comor[] = "<tr>
                                         <td>
                                             <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                 <i class='fi fi-rs-trash'></i>
                                             </button>
                                             <span>$cDesc</span>
                                         </td>
                                     </tr>";
            }
        } else {
            $html_comor[] = "<tr>
                                     <td class='text-center'>No record found</td>
                                 </tr>";
        }

        // Fetch vehicle (category_id, qty)
        $data_vhcl = $VehicleModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_vhcl = array();
        if (is_array($data_vhcl) && count($data_vhcl) > 0) {
            foreach ($data_vhcl as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_vhcl[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                    </tr>";
            }
        } else {
            $html_vhcl[] = "<tr>
                                    <td class='text-center' colspan='2'>No record found</td>
                                </tr>";
        }

        // Fetch attachments
        $data_docs = $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_docs = array();
        if (is_array($data_docs) && count($data_docs) > 0) {
            foreach ($data_docs as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $filename = $row->filename;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                if (session()->get('role') === "ADMIN") {
                    $actions = "<a href='" . site_url("/resident/viewFile/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>
                    <button type='button' class='btn btn-sm btn-info btnRemove-doctype' data-id='$category_id'>Remove</button>";
                } else if (session()->get('role') === "MAIN") {
                    $actions = "<a href='" . site_url("/resident/viewFile3/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>
                    <button type='button' class='btn btn-sm btn-info btnRemove-doctype' data-id='$category_id'>Remove</button>";
                } else {
                    $actions = "<a href='" . site_url("/resident/viewFile2/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>
                    <button type='button' class='btn btn-sm btn-info btnRemove-doctype' data-id='$category_id'>Remove</button>";
                }

                $html_docs[] = "<tr>
                <td>$cDesc</td>
                <td><label>$filename</label></td>
                <td>$actions</td>
            </tr>";
            }
        } else {
            $html_docs[] = "<tr>
                                    <td class='text-center' colspan='3'>No record found</td>
                                </tr>";
        }

        $output = array(
            'data' => $data,
            'trainings' => $html_training,
            'gprograms' => $html_gprograms,
            'dialect' => $html_dialect,
            'sincome' => $html_sincome,
            'app' => $html_app,
            'disability' => $html_disability,
            'comor' => $html_comor,
            'vhcl' => $html_vhcl,
            'docs' => $html_docs,
            'brgy_id' => $brgy_id,
            'purok_id' => $purok_id,
            'checked' => $checked
        );

        return $this->response->setJSON(['data' => $output]);
    }


    // INSERT EXISTING MEMBERS TO TBLTEMP WHEN SAVING HOUSEHOLD HEAD
    public function insert_householdMBR_2_TEMP($household)
    {
        $household_id = ($household) ?? '';
        $isHead = "FALSE";
        $status = "FOR APPROVAL"; // FOR RESIDENT RECORD
        $tbl_status = "TMP"; // FOR TABLE RECORDS (APPLIANCES, VEHICLES, ETC.)
        $active = "ACTIVE";

        // Initialize ResidentModel and TmpResidentModel instances
        $ResidentModel = new ResidentModel();
        $TmpResidentModel = new TmpResidentModel();
        $TrainingModel = new TrainingModel();
        $GprogramsModel = new GprogramsModel();
        $DialectModel = new DialectModel();
        $SincomeModel = new SincomeModel();
        $AppliancesModel = new AppliancesModel();
        $DisabilityModel = new DisabilityModel();
        $ComorbiditiesModel = new ComorbiditiesModel();
        $VehicleModel = new VehicleModel();
        $AttachmentsModel = new AttachmentsModel();

        $resident_data = $ResidentModel->where('household', $household_id)->where('isHead', $isHead)->findAll();

        if ($resident_data) {
            foreach ($resident_data as $resident) {
                $res_id = $resident->id;
                // INSERT EXISTING resident TO tbltemp FOR APPROVAL
                $data = [
                    'id' => $resident->id,
                    'lname' => $resident->lname,
                    'fname' => $resident->fname,
                    'mname' => $resident->mname,
                    'suffix' => $resident->suffix,
                    'fullname' => $resident->fullname,
                    'bday' => $resident->bday,
                    'age' => $resident->age,
                    'bplace' => $resident->bplace,
                    'gender' => $resident->gender,
                    'cstatus_id' => $resident->cstatus_id,
                    'educ_id' => $resident->educ_id,
                    'course_id' => $resident->course_id,
                    'rel_id' => $resident->rel_id,
                    'phealth_no' => $resident->phealth_no,
                    'occ_id' => $resident->occ_id,
                    'm_income' => $resident->m_income,
                    'cp' => $resident->cp,
                    'email' => $resident->email,
                    'nstatus' => $resident->nstatus,
                    'relation_hh' => $resident->relation_hh,
                    'relation_fh' => $resident->relation_fh,
                    'fh_id' => $resident->fh_id,
                    'btype' => $resident->btype,
                    'height' => $resident->height,
                    'weight' => $resident->weight,
                    'img_path' => $resident->img_path,
                    'house_no' => $resident->house_no,
                    'street' => $resident->street,
                    'add_id' => $resident->add_id,
                    'isHead' => $resident->isHead,
                    'status' => $status,
                    'household' => $resident->household,
                    'resident_id' => $resident->resident_id,
                    'created_on' => $resident->created_on
                ];
                // INSERT QUERY
                $TmpResidentModel->insert($data);
                /**
                 * GET OTHER DATA FROM TABLES (APPLIANCES, VEHICLES, ETC.)
                 */
                // TRAINING
                // - DELETE TMP
                $TrainingModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $training_data = $TrainingModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($training_data) {
                    foreach ($training_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'status' => $tbl_status
                        ];
                        $TrainingModel->insert($tbl_data);
                    }
                }

                // GOV'T PROGRAMS
                // - DELETE TMP
                $GprogramsModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $gprograms_data = $GprogramsModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($gprograms_data) {
                    foreach ($gprograms_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'date_acquired' => $row->date_acquired,
                            'status' => $tbl_status
                        ];
                        $GprogramsModel->insert($tbl_data);
                    }
                }

                // DIALECT
                // - DELETE TMP
                $DialectModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $dialect_data = $DialectModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($dialect_data) {
                    foreach ($dialect_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'status' => $tbl_status
                        ];
                        $DialectModel->insert($tbl_data);
                    }
                }

                // SOURCE OF INCOME
                // - DELETE TMP
                $SincomeModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $sincome_data = $SincomeModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($sincome_data) {
                    foreach ($sincome_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'status' => $tbl_status
                        ];
                        $SincomeModel->insert($tbl_data);
                    }
                }

                // APPLIANCES
                // - DELETE TMP
                $AppliancesModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $app_data = $AppliancesModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($app_data) {
                    foreach ($app_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'qty' => $row->qty,
                            'status' => $tbl_status
                        ];
                        $AppliancesModel->insert($tbl_data);
                    }
                }

                // DISABILITY
                // - DELETE TMP
                $DisabilityModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $disability_data = $DisabilityModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($disability_data) {
                    foreach ($disability_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'status' => $tbl_status
                        ];
                        $DisabilityModel->insert($tbl_data);
                    }
                }

                // COMORBIDITIES
                // - DELETE TMP
                $ComorbiditiesModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $comor_data = $ComorbiditiesModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($comor_data) {
                    foreach ($comor_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'status' => $tbl_status
                        ];
                        $ComorbiditiesModel->insert($tbl_data);
                    }
                }

                // VEHICLE
                // - DELETE TMP
                $VehicleModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $vhcl_data = $VehicleModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($vhcl_data) {
                    foreach ($vhcl_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'qty' => $row->qty,
                            'status' => $tbl_status
                        ];
                        $VehicleModel->insert($tbl_data);
                    }
                }

                // ATTACHMENTS
                // - DELETE TMP
                $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->delete();
                // check if resident has other info stored in tables
                $doc_data = $AttachmentsModel->where('res_id', $res_id)->where('status', $active)->findAll();
                if ($doc_data) {
                    foreach ($doc_data as $row) {
                        $tbl_data = [
                            'res_id' => $res_id,
                            'category_id' => $row->category_id,
                            'filename' => $row->filename,
                            'status' => $tbl_status
                        ];
                        $AttachmentsModel->insert($tbl_data);
                    }
                }
            }
        }

        return true;
    }

    // GET OTHER INFORMATION (HOUSEHOLD FORM)
    public function getOtherInfo($household_id)
    {

        // Initialize models
        $WaterModel = new WaterModel();
        $PowerModel = new PowerModel();
        $SanitationModel = new SanitationModel();
        $CookingModel = new CookingModel();
        $HAppliancesModel = new HAppliancesModel();
        $CommModel = new CommModel();
        $BldgAmenitiesModel = new BldgAmenitiesModel();
        $HVehicleModel = new HVehicleModel();
        $AmachineriesModel = new AmachineriesModel();
        $AlivestockModel = new AlivestockModel();
        $BldgInfoModel = new BldgInfoModel();
        $GarbageModel = new GarbageModel();

        $tbl_status = "TMP"; // TEMPORARY
        $active = "ACTIVE"; // ACTIVE

        // RESET ALL Tables that has status = "TMP"
        $WaterModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $PowerModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $SanitationModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $CookingModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $HAppliancesModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $CommModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $BldgAmenitiesModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $HVehicleModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $AmachineriesModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $AlivestockModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $BldgInfoModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $GarbageModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();


        // ========= Water Sources ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $WaterModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'ave_per_mo' => ($row->ave_per_mo) ?? '',
                    'status' => $tbl_status
                ];
                $WaterModel->insert($data);
            }
        }
        $data_water = $WaterModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_water = array();
        if (is_array($data_water) && count($data_water) > 0) {
            foreach ($data_water as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $categoryModel = new \App\Models\CategoryModel();
                $data_category = $categoryModel->where('id', $category_id)->first();
                $cID = $data_category->id; // Category id
                $cDesc = $data_category->description; // Description

                $html_water[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$ave_per_mo'></td>
                                    </tr>";
            }
        } else {
            $html_water[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Power Sources ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $PowerModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'ave_per_mo' => ($row->ave_per_mo) ?? '',
                    'status' => $tbl_status
                ];
                $PowerModel->insert($data);
            }
        }
        $data_power = $PowerModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_power = array();
        if (is_array($data_power) && count($data_power) > 0) {
            foreach ($data_power as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_power[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$ave_per_mo'></td>
                                    </tr>";
            }
        } else {
            $html_power[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Sanitation ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $SanitationModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'status' => $tbl_status
                ];
                $SanitationModel->insert($data);
            }
        }
        $data_san = $SanitationModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_san = array();
        if (is_array($data_san) && count($data_san) > 0) {
            foreach ($data_san as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_san[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_san[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Cooking ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $CookingModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'status' => $tbl_status
                ];
                $CookingModel->insert($data);
            }
        }
        $data_cook = $CookingModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_cook = array();
        if (is_array($data_cook) && count($data_cook) > 0) {
            foreach ($data_cook as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_cook[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_cook[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Appliances ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $HAppliancesModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'qty' => ($row->qty) ?? '',
                    'status' => $tbl_status
                ];
                $HAppliancesModel->insert($data);
            }
        }
        $data_app = $HAppliancesModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_app = array();
        if (is_array($data_app) && count($data_app) > 0) {
            foreach ($data_app as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_app[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                    </tr>";
            }
        } else {
            $html_app[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Communication ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $CommModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'status' => $tbl_status
                ];
                $CommModel->insert($data);
            }
        }
        $data_comm = $CommModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_comm = array();
        if (is_array($data_comm) && count($data_comm) > 0) {
            foreach ($data_comm as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_comm[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_comm[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Amenities ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $BldgAmenitiesModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'status' => $tbl_status
                ];
                $BldgAmenitiesModel->insert($data);
            }
        }
        $data_amenities = $BldgAmenitiesModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_amenities = array();
        if (is_array($data_amenities) && count($data_amenities) > 0) {
            foreach ($data_amenities as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_amenities[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_amenities[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Vehicle ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $HVehicleModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'qty' => ($row->qty) ?? '',
                    'status' => $tbl_status
                ];
                $HVehicleModel->insert($data);
            }
        }
        $data_vhcl = $HVehicleModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_vhcl = array();
        if (is_array($data_vhcl) && count($data_vhcl) > 0) {
            foreach ($data_vhcl as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_vhcl[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                    </tr>";
            }
        } else {
            $html_vhcl[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agi-machineries ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $AmachineriesModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'qty' => ($row->qty) ?? '',
                    'status' => $tbl_status
                ];
                $AmachineriesModel->insert($data);
            }
        }
        $data_amach = $AmachineriesModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_amach = array();
        if (is_array($data_amach) && count($data_amach) > 0) {
            foreach ($data_amach as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_amach[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                    </tr>";
            }
        } else {
            $html_amach[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agri-livestock ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $AlivestockModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'category_id' => ($row->category_id) ?? '',
                    'qty' => ($row->qty) ?? '',
                    'status' => $tbl_status
                ];
                $AlivestockModel->insert($data);
            }
        }
        $data_alive = $AlivestockModel->where("hh_id", $household_id)->where("status", $tbl_status)->findAll();
        // Convert to html data
        $html_alive = array();
        if (is_array($data_alive) && count($data_alive) > 0) {
            foreach ($data_alive as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($cID); // Description

                $html_alive[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                    </tr>";
            }
        } else {
            $html_alive[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Info ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $BldgInfoModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'bldg_type_id' => ($row->bldg_type_id) ?? '',
                    'construction_yr' => ($row->construction_yr) ?? '',
                    'yr_occupied' => ($row->yr_occupied) ?? '',
                    'bldg_permit_no' => ($row->bldg_permit_no) ?? '',
                    'lot_no' => ($row->lot_no) ?? '',
                    'status' => $tbl_status
                ];
                $BldgInfoModel->insert($data);
            }
        }
        $result_bldgInfo = $BldgInfoModel->where("hh_id", $household_id)->where("status", $tbl_status)->first();

        $data_bldgInfo = array();

        if ($result_bldgInfo) {
            // Existing data of building info
            $data_bldgInfo = [
                'hh_id' => $result_bldgInfo->hh_id,
                'bldg_type_id' => $result_bldgInfo->bldg_type_id,
                'construction_yr' => $this->display_date($result_bldgInfo->construction_yr),
                'yr_occupied' => $this->display_date($result_bldgInfo->yr_occupied),
                'bldg_permit_no' => $result_bldgInfo->bldg_permit_no,
                'lot_no' => $result_bldgInfo->lot_no
            ];
        } else {
            // No data found
            $data_bldgInfo = [
                'hh_id' => '',
                'bldg_type_id' => '',
                'construction_yr' => '',
                'yr_occupied' => '',
                'bldg_permit_no' => '',
                'lot_no' => ''
            ];
        }

        // ========= Average generated garbages ========= //
        // INSERT TO TABLES that has STATUS = "ACTIVE"
        $insert_data = $GarbageModel->where("hh_id", $household_id)->where("status", $active)->findAll();
        if ($insert_data) {
            foreach ($insert_data as $row) {
                $data = [
                    'hh_id' => $household_id,
                    'hazardous' => ($row->hazardous) ?? '',
                    'recyclable' => ($row->recyclable) ?? '',
                    'residual' => ($row->residual) ?? '',
                    'biodegradable' => ($row->biodegradable) ?? '',
                    'status' => $tbl_status
                ];
                $GarbageModel->insert($data);
            }
        }
        $result_garbages = $GarbageModel->where("hh_id", $household_id)->where("status", $tbl_status)->first();

        $data_garbages = array();

        if ($result_garbages) {
            // Existing data
            $data_garbages = [
                'hh_id' => $result_garbages->hh_id,
                'hazardous' => $result_garbages->hazardous,
                'recyclable' => $result_garbages->recyclable,
                'residual' => $result_garbages->residual,
                'biodegradable' => $result_garbages->biodegradable
            ];
        } else {
            // No data found
            $data_garbages = [
                'hh_id' => '',
                'hazardous' => '',
                'recyclable' => '',
                'residual' => '',
                'biodegradable' => ''
            ];
        }


        $output = array(
            'water' => $html_water,
            'power' => $html_power,
            'san' => $html_san,
            'cook' => $html_cook,
            'app' => $html_app,
            'comm' => $html_comm,
            'amenities' => $html_amenities,
            'vhcl' => $html_vhcl,
            'amach' => $html_amach,
            'alive' => $html_alive,
            'bldginfo' => $data_bldgInfo,
            'garbages' => $data_garbages
        );

        return $this->response->setJSON(['data' => $output]);
    }

    // SAVE OTHER INFORMATION (HOUSEHOLD FORM)
    public function saveOtherInfo()
    {
        $post = $this->request->getPost();

        $household_id = isset($post['household_id']) ? $post['household_id'] : "";
        $water = isset($post['water']) ? json_decode($post['water'], true) : "";
        $power = isset($post['power']) ? json_decode($post['power'], true) : "";
        $san = isset($post['san']) ? json_decode($post['san'], true) : "";
        $cook = isset($post['cook']) ? json_decode($post['cook'], true) : "";
        $app = isset($post['app']) ? json_decode($post['app'], true) : "";
        $comm = isset($post['comm']) ? json_decode($post['comm'], true) : "";
        $amenities = isset($post['amenities']) ? json_decode($post['amenities'], true) : "";
        $vhcl = isset($post['vhcl']) ? json_decode($post['vhcl'], true) : "";
        $amach = isset($post['amach']) ? json_decode($post['amach'], true) : "";
        $alive = isset($post['alive']) ? json_decode($post['alive'], true) : "";

        // Retrieve form data from the request
        $formData = $post['formData'];
        // Parse the serialized form data into an associative array
        parse_str($formData, $form);
        // Get building information
        $bldg_type_id = isset($form['bldg-type']) ? $form['bldg-type'] : "";
        $construction_yr = isset($form['construction-yr']) ? $form['construction-yr'] : "";
        $yr_occupied = isset($form['yr-occupied']) ? $form['yr-occupied'] : "";
        $bldg_permit_no = isset($form['bldg-permit-no']) ? $form['bldg-permit-no'] : "";
        $lot_no = isset($form['lot-no']) ? $form['lot-no'] : "";
        // Get garbage information
        $hazardous = isset($form['hazardous']) ? $form['hazardous'] : "";
        $recyclable = isset($form['recyclable']) ? $form['recyclable'] : "";
        $residual = isset($form['residual']) ? $form['residual'] : "";
        $biodegradable = isset($form['biodegradable']) ? $form['biodegradable'] : "";

        // Initialize models
        $WaterModel = new WaterModel();
        $PowerModel = new PowerModel();
        $SanitationModel = new SanitationModel();
        $CookingModel = new CookingModel();
        $HAppliancesModel = new HAppliancesModel();
        $CommModel = new CommModel();
        $BldgAmenitiesModel = new BldgAmenitiesModel();
        $HVehicleModel = new HVehicleModel();
        $AmachineriesModel = new AmachineriesModel();
        $AlivestockModel = new AlivestockModel();
        $BldgInfoModel = new BldgInfoModel();
        $GarbageModel = new GarbageModel();

        $tbl_status = "TMP"; // TEMPORARY
        $active = "ACTIVE"; // ACTIVE

        // RESET ALL Tables that has status = "TMP"
        $WaterModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $PowerModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $SanitationModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $CookingModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $HAppliancesModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $CommModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $BldgAmenitiesModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $HVehicleModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $AmachineriesModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $AlivestockModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $BldgInfoModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();
        $GarbageModel->where("hh_id", $household_id)->where("status", $tbl_status)->delete();

        // SAVE BUILDING INFO
        $values_bldgInfo = [
            'hh_id' => $household_id,
            'bldg_type_id' => $bldg_type_id,
            'construction_yr' => $this->save_date($construction_yr),
            'yr_occupied' => $this->save_date($yr_occupied),
            'bldg_permit_no' => $bldg_permit_no,
            'lot_no' => $lot_no,
            'status' => $tbl_status
        ];
        // INSERT DATA
        $insertBldgInfo = $BldgInfoModel->insert($values_bldgInfo);

        // SAVE GARBAGE INFO
        $values_garbageInfo = [
            'hh_id' => $household_id,
            'hazardous' => $hazardous,
            'recyclable' => $recyclable,
            'residual' => $residual,
            'biodegradable' => $biodegradable,
            'status' => $tbl_status
        ];
        // INSERT DATA
        $insertGarbageInfo = $GarbageModel->insert($values_garbageInfo);

        // water sources
        if (!empty($water) && is_array($water) && count($water) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($water as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    $ave_per_mo = $row['ave_per_mo'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'ave_per_mo' => $ave_per_mo,
                        'status' => $tbl_status
                    ];
                    $insert = $WaterModel->insert($values);
                }
            }
        }
        // power sources
        if (!empty($power) && is_array($power) && count($power) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($power as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    $ave_per_mo = $row['ave_per_mo'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'ave_per_mo' => $ave_per_mo,
                        'status' => $tbl_status
                    ];
                    $insert = $PowerModel->insert($values);
                }
            }
        }
        // sanitation - toilet facilities
        if (!empty($san) && is_array($san) && count($san) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($san as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'status' => $tbl_status
                    ];
                    $insert = $SanitationModel->insert($values);
                }
            }
        }
        // type of cooking
        if (!empty($cook) && is_array($cook) && count($cook) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($cook as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'status' => $tbl_status
                    ];
                    $insert = $CookingModel->insert($values);
                }
            }
        }
        // appliances/gadgets
        if (!empty($app) && is_array($app) && count($app) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($app as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    $qty = $row['qty'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'qty' => $qty,
                        'status' => $tbl_status
                    ];
                    $insert = $HAppliancesModel->insert($values);
                }
            }
        }
        // type of communication
        if (!empty($comm) && is_array($comm) && count($comm) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($comm as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'status' => $tbl_status
                    ];
                    $insert = $CommModel->insert($values);
                }
            }
        }
        // building amenities
        if (!empty($amenities) && is_array($amenities) && count($amenities) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($amenities as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'status' => $tbl_status
                    ];
                    $insert = $BldgAmenitiesModel->insert($values);
                }
            }
        }
        // vehicles owned
        if (!empty($vhcl) && is_array($vhcl) && count($vhcl) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($vhcl as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    $qty = $row['qty'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'qty' => $qty,
                        'status' => $tbl_status
                    ];
                    $insert = $HVehicleModel->insert($values);
                }
            }
        }
        // agricultural machineries
        if (!empty($amach) && is_array($amach) && count($amach) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($amach as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    $qty = $row['qty'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'qty' => $qty,
                        'status' => $tbl_status
                    ];
                    $insert = $AmachineriesModel->insert($values);
                }
            }
        }

        // agricultural livestock
        if (!empty($alive) && is_array($alive) && count($alive) > 0) {
            // ADD NEW COLLECTED DATA
            foreach ($alive as $row) {
                if (array_key_exists('id', $row)) {
                    $id = $row['id'];
                    $qty = $row['qty'];
                    // SAVE
                    $values = [
                        'hh_id' => $household_id,
                        'category_id' => $id,
                        'qty' => $qty,
                        'status' => $tbl_status
                    ];
                    $insert = $AlivestockModel->insert($values);
                }
            }
        }

        return $this->response->setJSON(['household_id' => $household_id]);
    }

    // GET ALL INFORMATION BASED ON THE HOUSEHOLD ID
    public function printHouseholdInfo($household_id)
    {

        $status = "FOR APPROVAL";
        $tbl_status = "TMP";

        // Initialize models
        $TmpResidentModel = new TmpResidentModel();
        $WaterModel = new WaterModel();
        $PowerModel = new PowerModel();
        $SanitationModel = new SanitationModel();
        $CookingModel = new CookingModel();
        $HAppliancesModel = new HAppliancesModel();
        $CommModel = new CommModel();
        $BldgAmenitiesModel = new BldgAmenitiesModel();
        $HVehicleModel = new HVehicleModel();
        $AmachineriesModel = new AmachineriesModel();
        $AlivestockModel = new AlivestockModel();
        $BldgInfoModel = new BldgInfoModel();
        $GarbageModel = new GarbageModel();

        // ======================== //
        // HOUSEHOLD HEAD, HOUSEHOLD MEMBER, AND OTHER INFO OF HOUSEHOLD HEAD
        // ======================== //

        // Get household head and member information
        $head_info = array();
        $household_member = array();
        // $qr_household_member = array();
        $img_path = "";
        $brgy = "";
        // GET BARANGAY LOGO
        // Check logo if exists
        $BrgyProfileModel = new BrgyProfileModel();
        $profile_data = $BrgyProfileModel->where("brgy_id", $this->brgy_id)->first();
        $logo_path = "";
        if ($profile_data) {
            $logo = isset($profile_data->logo) ? $profile_data->logo : '';
            $isExists = $this->checkFile($logo);
            $logo_path = ($isExists) ? base_url('writable/uploads/' . $logo) : base_url('public/assets/images/logo.png');
        } else {
            $logo_path = base_url('public/assets/images/logo.png');
        }

        // Check household that has a household id of submitted post request
        $resident_data = $TmpResidentModel->where("household", $household_id)->where("status", $status)->findAll();

        if (is_array($resident_data) && count($resident_data) > 0) {
            foreach ($resident_data as $resident) {
                $lname = $resident->lname;
                $fname = $resident->fname;
                $mname = $resident->mname;
                $suffix = $resident->suffix;
                $fullname = $resident->fullname;
                $bday = $this->display_date($resident->bday);
                $age = $resident->age;
                $bplace = $resident->bplace;
                $gender = $resident->gender;
                $cstatus_id = $resident->cstatus_id;
                $educ_id = $resident->educ_id;
                $course_id = $resident->course_id;
                $rel_id = $resident->rel_id;
                $phealth_no = $resident->phealth_no;
                $occ_id = $resident->occ_id;
                $m_income = $resident->m_income;
                $cp = $resident->cp;
                $email = $resident->email;
                $nstatus = $resident->nstatus;
                $relation_hh = $resident->relation_hh;
                $relation_fh = $resident->relation_fh;
                $fh_id = $resident->fh_id;
                $btype = $resident->btype;
                $height = $resident->height;
                $weight = $resident->weight;
                //$img_path = $resident->img_path;
                $house_no = $resident->house_no;
                $street = $resident->street;
                $add_id = $resident->add_id;
                $isHead = $resident->isHead;
                $household = $resident->household;
                $resident_id = $resident->resident_id;
                // get description from the category table based on the connected id of the resident id
                $cstatus = $this->getCategoryDescription($cstatus_id);
                $educ = $this->getCategoryDescription($educ_id);
                $course = $this->getCategoryDescription($course_id);
                $religion = $this->getCategoryDescription($rel_id);
                $occupation = $this->getCategoryDescription($occ_id);
                // relationship to household head
                $relation = $this->getCategoryDescription($relation_hh);


                if ($isHead == "TRUE") {
                    // HOUSEHOLD HEAD

                    // GET IMAGE
                    $img = ($resident->img_path) ?? '';
                    $isExists = $this->checkFile($img);
                    $img_path = ($isExists) ? base_url('writable/uploads/' . $img) : base_url('public/assets/images/logo.png');
                    // Get purok/zone name
                    $purok_data = $this->getPurokDescription($add_id);
                    $purok_desc = ($purok_data) ? $purok_data->description : '';
                    // Get barangay name
                    $brgy_data = $this->getBrgyDescription($add_id);
                    $brgy_desc = ($brgy_data) ? $brgy_data->brgy_name : '';
                    $brgy = $brgy_desc;
                    // PASS DATA TO ARRAY
                    $head_info = [
                        'household' => $household,
                        'status' => $status,
                        'resident_id' => $resident_id,
                        'lname' => $lname,
                        'fname' => $fname,
                        'mname' => $mname,
                        'suffix' => $suffix,
                        'fullname' => $fullname,
                        'bday' => $bday,
                        'bplace' => $bplace,
                        'gender' => $gender,
                        'cstatus' => $cstatus,
                        'educ' => $educ,
                        'course' => $course,
                        'religion' => $religion,
                        'occupation' => $occupation,
                        'phealth_no' => $phealth_no,
                        'm_income' => $this->convert_to_accounting($m_income),
                        'cp' => $cp,
                        'email' => $email,
                        'nstatus' => $nstatus,
                        'btype' => $btype,
                        'height' => $height,
                        'weight' => $weight,
                        'brgy' => $brgy_desc,
                        'purok' => $purok_desc,
                        'street' => $street,
                        'house_no' => $house_no,
                        'resident_id' => $resident_id
                    ];

                } else {
                    // HOUSEHOLD MEMBER
                    $household_member[] = "<tr>
                                                <td>$resident_id</td>
                                                <td>$fullname</td>
                                                <td class='text-center'>$age</td>
                                                <td class='text-center'>$gender</td>
                                                <td class='text-center'>$cstatus</td>
                                                <td class='text-center'>$relation</td>
                                            </tr>";
                    // // QR code
                    // $qr_household_member[] = "Resident ID: $resident_id; Member Name: $fullname";
                }
            }
        }


        // ======================== //
        // OTHER INFORMATION
        // ======================== //

        // ========= Water Sources ========= //
        $data_water = $WaterModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_water = array();
        if (is_array($data_water) && count($data_water) > 0) {
            foreach ($data_water as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_water[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$ave_per_mo</td>
                                    </tr>";
            }
        } else {
            $html_water[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Power Sources ========= //
        $data_power = $PowerModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_power = array();
        if (is_array($data_power) && count($data_power) > 0) {
            foreach ($data_power as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_power[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$ave_per_mo</td>
                                    </tr>";
            }
        } else {
            $html_power[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Sanitation ========= //
        $data_san = $SanitationModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_san = array();
        if (is_array($data_san) && count($data_san) > 0) {
            foreach ($data_san as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_san[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_san[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Cooking ========= //
        $data_cook = $CookingModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_cook = array();
        if (is_array($data_cook) && count($data_cook) > 0) {
            foreach ($data_cook as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_cook[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_cook[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Appliances ========= //
        $data_app = $HAppliancesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_app = array();
        if (is_array($data_app) && count($data_app) > 0) {
            foreach ($data_app as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_app[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_app[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Communication ========= //
        $data_comm = $CommModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_comm = array();
        if (is_array($data_comm) && count($data_comm) > 0) {
            foreach ($data_comm as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_comm[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_comm[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Amenities ========= //
        $data_amenities = $BldgAmenitiesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_amenities = array();
        if (is_array($data_amenities) && count($data_amenities) > 0) {
            foreach ($data_amenities as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_amenities[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_amenities[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Vehicle ========= //
        $data_vhcl = $HVehicleModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_vhcl = array();
        if (is_array($data_vhcl) && count($data_vhcl) > 0) {
            foreach ($data_vhcl as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_vhcl[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_vhcl[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agi-machineries ========= //
        $data_amach = $AmachineriesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_amach = array();
        if (is_array($data_amach) && count($data_amach) > 0) {
            foreach ($data_amach as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_amach[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_amach[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agri-livestock ========= //
        $data_alive = $AlivestockModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_alive = array();
        if (is_array($data_alive) && count($data_alive) > 0) {
            foreach ($data_alive as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_alive[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_alive[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Info ========= //
        $result_bldgInfo = $BldgInfoModel->where('hh_id', $household_id)->where('status', $tbl_status)->first();

        $data_bldgInfo = array();

        if ($result_bldgInfo !== null) {
            // Get Building Type description from category table
            $bldg_type_id = $result_bldgInfo->bldg_type_id;

            $data_bldgInfo = [
                'hh_id' => $result_bldgInfo->hh_id,
                'bldg_type' => $this->getCategoryDescription($bldg_type_id),
                'construction_yr' => $this->display_date($result_bldgInfo->construction_yr),
                'yr_occupied' => $this->display_date($result_bldgInfo->yr_occupied),
                'bldg_permit_no' => $result_bldgInfo->bldg_permit_no,
                'lot_no' => $result_bldgInfo->lot_no
            ];
        } else {
            // No data found
            $data_bldgInfo = [
                'hh_id' => '',
                'bldg_type' => '',
                'construction_yr' => '',
                'yr_occupied' => '',
                'bldg_permit_no' => '',
                'lot_no' => ''
            ];
        }

        // ========= Average generated garbages ========= //
        $result_garbages = $GarbageModel->where('hh_id', $household_id)->where('status', $tbl_status)->first();

        $data_garbages = array();

        if ($result_garbages) {
            // Existing data
            $data_garbages = [
                'hh_id' => $result_garbages->hh_id,
                'hazardous' => $result_garbages->hazardous,
                'recyclable' => $result_garbages->recyclable,
                'residual' => $result_garbages->residual,
                'biodegradable' => $result_garbages->biodegradable
            ];
        } else {
            // No data found
            $data_garbages = [
                'hh_id' => '',
                'hazardous' => '',
                'recyclable' => '',
                'residual' => '',
                'biodegradable' => ''
            ];
        }

        // GENERATE QR CODE CONTENT
        // $qr_content = "Household ID: $household_id \n\n";
        // $qr_content .= "Household Head ID: " . $head_info['resident_id'] . "\n";
        // $qr_content .= "Household Head: " . $head_info['fullname'] . "\n\n";
        // $qr_content .= "Household Member: \n";
        // $qr_content .= implode("\n\n", $qr_household_member) . "\n";
        // $qr_content .= "\nDate generated: " . date("m-d-Y");


        // $qr_img = $this->generateQrCodeImage($qr_content);

        // Get user who encoded it
        $user_fullname = session()->get('fullname');
        $date_generated = date("m-d-Y");

        $current_user = array(
            'fullname' => $user_fullname,
            'dated' => $date_generated
        );

        // CHECK IF THERE ARE HOUSEHOLD MEMBERS
        if (is_array($household_member) && count($household_member) === 0) {
            $household_member[] = "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
        }

        $output = array(
            'head_info' => $head_info,
            'member' => $household_member,
            'water' => $html_water,
            'power' => $html_power,
            'san' => $html_san,
            'cook' => $html_cook,
            'app' => $html_app,
            'comm' => $html_comm,
            'amenities' => $html_amenities,
            'vhcl' => $html_vhcl,
            'amach' => $html_amach,
            'alive' => $html_alive,
            'bldginfo' => $data_bldgInfo,
            'garbages' => $data_garbages,
            'img_path' => $img_path,
            'brgy_logo' => $logo_path,
            'user_data' => $current_user,
            'brgy_name' => $brgy,
            'household_id' => $household_id,
        );

        return $this->response->setJSON(['data' => $output]);
    }

    // SAVE HOUSEHOLD PROFILE
    public function doneSubmission()
    {
        session()->setFlashdata('success', 'Successfully saved data, Please wait for the approval of your household profile');
        if (session()->get('role') === "ADMIN") {
            return redirect()->to('resident/for_approval');
        } else if (session()->get('role') === "MAIN") { 
            return redirect()->to('resident/for_approval3');
        } else {
            return redirect()->to('resident/dashboard');
        }
    }

    // FETCH DATA TO UPDATE HOUSEHOLD
    public function edit_household($id)
    {
        // QUERY DATABASE BASED ON THE RESIDENT ID WHICH IS THE $id
        // Initialize models
        $ResidentModel = new ResidentModel();
        $TmpResidentModel = new TmpResidentModel();
        $TrainingModel = new TrainingModel();
        $GprogramsModel = new GprogramsModel();
        $DialectModel = new DialectModel();
        $SincomeModel = new SincomeModel();
        $AppliancesModel = new AppliancesModel();
        $DisabilityModel = new DisabilityModel();
        $ComorbiditiesModel = new ComorbiditiesModel();
        $VehicleModel = new VehicleModel();
        $AttachmentsModel = new AttachmentsModel();

        $tbl_status = "ACTIVE";
        $status = "ACTIVE";

        // Retrieve the data-id value from the request
        $res_id = $id;

        // Fetch data based on the id submitted
        $data = $ResidentModel->where('id', $res_id)->where('status', $status)->first();

        /**
         * Check if there is data
         */
        if (!$data) {
            if (session()->get('role') === "RESIDENT") {
                return redirect()->to('resident/dashboard');
            } else if (session()->get('role') === "ADMIN") {
                return redirect()->to('administrator/dashboard');
            } else if (session()->get('role') === "MAIN") {
                return redirect()->to('main/dashboard');
            }
        }

        $getPurok = $data->add_id ? $this->getPurokDescription($data->add_id) : '';

        /**
         * Verify that the user is updating his/her household profile
         */
        
        if (session()->get('role') === "RESIDENT") { // RESIDENT ACCOUNT
            // CHECK IF THE USER'S HOUSEHOLD IS EQUAL TO THE FETCHING DATA HOUSEHOLD
            if ($data->household && session()->get('household_id') !== $data->household) {
                return redirect()->to('resident/dashboard');
            }
        } else if (session()->get('role') === "ADMIN") {
            // CHECK IF THE USER IS UPDATING HIS/HER BRGY JURISDICTION
            if ($getPurok->brgy_id && session()->get('brgy_id') !== $getPurok->brgy_id) {
                return redirect()->to('administrator/dashboard');
            }
        }

        // Get cstatus
        $output['cstatus'] = $this->getListDescriptionBasedOnCategory('cstatus');
        // Get educational attainment
        $output['educ'] = $this->getListDescriptionBasedOnCategory('educ');
        // Get course
        $output['course'] = $this->getListDescriptionBasedOnCategory('course');
        // Get religion
        $output['rel'] = $this->getListDescriptionBasedOnCategory('rel');
        // Get occupation
        $output['occ'] = $this->getListDescriptionBasedOnCategory('occ');
        // Get relation (relation to household head/ family head)
        $output['relation'] = $this->getListDescriptionBasedOnCategory("relation");
        // Get building type
        $output['bldgtype'] = $this->getListDescriptionBasedOnCategory("bldgtype");
        // Get current brgy from barangay profile
        $BrgyCodeModel = new BrgyCodeModel();

        if (session()->get('role') === "ADMIN" || session()->get('role') === "RESIDENT") {
            $brgy_data = $BrgyCodeModel->find($this->brgy_id);
        } else if (session()->get('role') === "MAIN") {
            $brgy_data = $BrgyCodeModel->findAll();
        }

        $output['barangay'] = $brgy_data;

        // Gather list of purok based from brgy_id
        $add_id = $data->add_id ?? '';
        $brgy_info = $this->getBrgyDescription($add_id);
        $listPurok = $this->getListOfPurok($brgy_info->id);
        $output['purok'] = $listPurok;

        // Gather list of document types
        $output['doctype'] = $this->getListDescriptionBasedOnCategory('doctype');

        // UPDATE BDAY FORMAT
        $data->bday = !empty($data->bday) ? $this->display_date($data->bday) : '';

        // Fetch brgy and purok 
        $purok_id = isset($data->add_id) ? $data->add_id : '';
        $brgy_data = $this->getPurokDescription($purok_id);
        $brgy_id = isset($brgy_data->brgy_id) ? $brgy_data->brgy_id : '';

        // check image
        $img_path = $data->img_path ?? '';
        $isExists = $this->checkFile($data->img_path);
        $img = base_url('public/assets/images/logo.png');
        if ($isExists) {
            $img = base_url('writable/uploads/' . $img_path);
        }
        // Fetch training
        $data_training = $TrainingModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_training = array();
        if (is_array($data_training) && count($data_training) > 0) {
            foreach ($data_training as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description
                $html_training[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_training[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }

        // Fetch gprograms (category_id, date_acquired)
        $data_gprograms = $GprogramsModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_gprograms = array();
        if (is_array($data_gprograms) && count($data_gprograms) > 0) {
            foreach ($data_gprograms as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $date_acquired = $this->display_date($row->date_acquired);
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_gprograms[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td>
                                                <input type='text' class='form-control datetimepicker' placeholder='mm-dd-yyyy' value='$date_acquired'>
                                            </td>
                                    </tr>";
            }
        } else {
            $html_gprograms[] = "<tr>
                                    <td class='text-center' colspan='2'>No record found</td>
                                </tr>";
        }
        // Fetch Dialect spoken
        $data_dialect = $DialectModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_dialect = array();
        if (is_array($data_dialect) && count($data_dialect) > 0) {
            foreach ($data_dialect as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_dialect[] = "<tr>
                                         <td>
                                             <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                 <i class='fi fi-rs-trash'></i>
                                             </button>
                                             <span>$cDesc</span>
                                         </td>
                                     </tr>";
            }
        } else {
            $html_dialect[] = "<tr>
                                     <td class='text-center'>No record found</td>
                                 </tr>";
        }

        // Fetch All applicable sources of income
        $data_sincome = $SincomeModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_sincome = array();
        if (is_array($data_sincome) && count($data_sincome) > 0) {
            foreach ($data_sincome as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_sincome[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_sincome[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // Fetch appliances (category_id, qty)
        $data_app = $AppliancesModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_app = array();
        if (is_array($data_app) && count($data_app) > 0) {
            foreach ($data_app as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_app[] = "<tr>
                                         <td>
                                             <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                 <i class='fi fi-rs-trash'></i>
                                             </button>
                                             <span>$cDesc</span>
                                         </td>
                                         <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                     </tr>";
            }
        } else {
            $html_app[] = "<tr>
                                     <td class='text-center' colspan='2'>No record found</td>
                                 </tr>";
        }
        // Fetch disability
        $data_disability = $DisabilityModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_disability = array();
        if (is_array($data_disability) && count($data_disability) > 0) {
            foreach ($data_disability as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_disability[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_disability[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // Fetch comorbidities
        $data_comorbidities = $ComorbiditiesModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_comor = array();
        if (is_array($data_comorbidities) && count($data_comorbidities) > 0) {
            foreach ($data_comorbidities as $row) {
                // Data collected from database
                $category_id = $row->category_id;

                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_comor[] = "<tr>
                                         <td>
                                             <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                 <i class='fi fi-rs-trash'></i>
                                             </button>
                                             <span>$cDesc</span>
                                         </td>
                                     </tr>";
            }
        } else {
            $html_comor[] = "<tr>
                                     <td class='text-center'>No record found</td>
                                 </tr>";
        }

        // Fetch vehicle (category_id, qty)
        $data_vhcl = $VehicleModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_vhcl = array();
        if (is_array($data_vhcl) && count($data_vhcl) > 0) {
            foreach ($data_vhcl as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                $html_vhcl[] = "<tr>
                                        <td>
                                            <button type='button' class='btn btn-light me-2 btnRemove-list' data-id='$cID'>
                                                <i class='fi fi-rs-trash'></i>
                                            </button>
                                            <span>$cDesc</span>
                                        </td>
                                        <td><input type='number' class='form-control' placeholder='0' value='$qty'></td>
                                    </tr>";
            }
        } else {
            $html_vhcl[] = "<tr>
                                    <td class='text-center' colspan='2'>No record found</td>
                                </tr>";
        }

        // Fetch attachments
        $data_docs = $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_docs = array();
        if (is_array($data_docs) && count($data_docs) > 0) {
            foreach ($data_docs as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $filename = $row->filename;
                // Gather category info
                $cID = $category_id; // Category id
                $cDesc = $this->getCategoryDescription($category_id); // Description

                if (session()->get('role') === "ADMIN") {
                    $actions = "<a href='" . site_url("/resident/viewFile/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>
                    <button type='button' class='btn btn-sm btn-info btnRemove-doctype' data-id='$category_id'>Remove</button>";
                } else if (session()->get('role') === "MAIN") { 
                    $actions = "<a href='" . site_url("/resident/viewFile3/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>
                    <button type='button' class='btn btn-sm btn-info btnRemove-doctype' data-id='$category_id'>Remove</button>";
                } else {
                    $actions = "<a href='" . site_url("/resident/viewFile2/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>
                    <button type='button' class='btn btn-sm btn-info btnRemove-doctype' data-id='$category_id'>Remove</button>";
                }

                $html_docs[] = "<tr>
                <td>$cDesc</td>
                <td><label>$filename</label></td>
                <td>$actions</td>
            </tr>";
            }
        } else {
            $html_docs[] = "<tr>
                                    <td class='text-center' colspan='3'>No record found</td>
                                </tr>";
        }

        $output['data'] = $data;
        $output['img'] = $img;
        $output['brgy_id'] = $brgy_id;
        $output['purok_id'] = $purok_id;

        $output['html_trainings'] = $html_training;
        $output['html_gprograms'] = $html_gprograms;
        $output['html_dialect'] = $html_dialect;
        $output['html_sincome'] = $html_sincome;
        $output['html_app'] = $html_app;
        $output['html_disability'] = $html_disability;
        $output['html_comor'] = $html_comor;
        $output['html_vhcl'] = $html_vhcl;
        $output['html_docs'] = $html_docs;

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') === 'ADMIN') {
            return view('administrator/resident/update', $output);
        } else if (session()->get('role') === "MAIN") { 
            return view('main/resident/update', $output);
        } else {
            return view('resident/household/update', $output);
        }
        
    }

    // FETCH PUROK LIST BASED ON BRGY_ID
    public function fetchPurokList($brgy_id) {
        $purok_list = $this->getListOfPurok($brgy_id);
        return $this->response->setJSON($purok_list);
    }

    // Helper function to process a resident row
    private function processResidentRow($row)
    {
        return [
            $row->id,
            $row->household,
            $row->fullname,
            isset($row->cstatus_id) ? $this->getCategoryDescription($row->cstatus_id) : '',
            $row->age,
            $row->status
        ];
    }

    /**
     * ---------------------------------------------------------------
     * FOR APPROVAL SECTION
     * ---------------------------------------------------------------
     */

    public function for_approval()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') === "ADMIN") {
            return view('administrator/resident/for_approval', $output);
        } else {
            return view('main/resident/for_approval', $output);
        }
    }

    // GET LIST OF FOR APPROVAL (TABLE DISPLAY)
    public function getListOfForApproval()
    {
        $TmpResidentModel = new TmpResidentModel();

        try {
            $collect_resident = [];

            // Fetch and process resident data
            $resident_data = $TmpResidentModel->where('isHead', 'TRUE')->where('status', 'FOR APPROVAL')->findAll();
            foreach ($resident_data as $row) {
                // Get add_id from tblresident
                $add_id = ($row->add_id) ? $row->add_id : '';

                if (!empty($add_id)) {
                    $brgy_data = $this->getBrgyDescription($add_id);
                    if ($brgy_data != false) {
                        if (session()->get('role') === "ADMIN" && $brgy_data->id == $this->brgy_id) {
                                $collect_resident[] = $this->processResidentRow($row);
                        } else if (session()->get('role') === "MAIN") {
                            $collect_resident[] = $this->processResidentRow($row);
                        }
                    }
                }
            }

            // Return the collected data as JSON
            return $this->response->setJSON(['data' => $collect_resident]);
        } catch (\Exception $e) {
            // Return error details in JSON response
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // VIEW HOUSEHOLD INFORMATION
    public function view_approval($household_id)
    {
        $status = "FOR APPROVAL";
        $tbl_status = "TMP";

        // Initialize models
        $TmpResidentModel = new TmpResidentModel();
        $WaterModel = new WaterModel();
        $PowerModel = new PowerModel();
        $SanitationModel = new SanitationModel();
        $CookingModel = new CookingModel();
        $HAppliancesModel = new HAppliancesModel();
        $CommModel = new CommModel();
        $BldgAmenitiesModel = new BldgAmenitiesModel();
        $HVehicleModel = new HVehicleModel();
        $AmachineriesModel = new AmachineriesModel();
        $AlivestockModel = new AlivestockModel();
        $BldgInfoModel = new BldgInfoModel();
        $GarbageModel = new GarbageModel();
        $AttachmentsModel = new AttachmentsModel();

        // ======================== //
        // HOUSEHOLD HEAD, HOUSEHOLD MEMBER, AND OTHER INFO OF HOUSEHOLD HEAD
        // ======================== //

        // Get household head and member information
        $head_info = array();
        $household_member = array();
        $qr_household_member = array();
        // initialization for gathering document submitted
        $docs = array();
        $html_docs = array();

        $img_path = "";
        $brgy = "";

        // GET BARANGAY LOGO
        // Check logo if exists
        $BrgyProfileModel = new BrgyProfileModel();
        $profile_data = $BrgyProfileModel->where("brgy_id", $this->brgy_id)->first();
        $logo_path = "";
        if ($profile_data) {
            $logo = isset($profile_data->logo) ? $profile_data->logo : '';
            $isExists = $this->checkFile($logo);
            $logo_path = ($isExists) ? base_url('writable/uploads/' . $logo) : base_url('public/assets/images/logo.png');
        } else {
            $logo_path = base_url('public/assets/images/logo.png');
        }

        // Check household that has a household id of submitted post request
        $resident_data = $TmpResidentModel->where("household", $household_id)->where("status", $status)->findAll();

        if (is_array($resident_data) && count($resident_data) > 0) {
            foreach ($resident_data as $resident) {
                $res_id = $resident->id;
                $lname = $resident->lname;
                $fname = $resident->fname;
                $mname = $resident->mname;
                $suffix = $resident->suffix;
                $fullname = $resident->fullname;
                $bday = $this->display_date($resident->bday);
                $age = $resident->age;
                $bplace = $resident->bplace;
                $gender = $resident->gender;
                $cstatus_id = $resident->cstatus_id;
                $educ_id = $resident->educ_id;
                $course_id = $resident->course_id;
                $rel_id = $resident->rel_id;
                $phealth_no = $resident->phealth_no;
                $occ_id = $resident->occ_id;
                $m_income = $resident->m_income;
                $cp = $resident->cp;
                $email = $resident->email;
                $nstatus = $resident->nstatus;
                $relation_hh = $resident->relation_hh;
                $relation_fh = $resident->relation_fh;
                $fh_id = $resident->fh_id;
                $btype = $resident->btype;
                $height = $resident->height;
                $weight = $resident->weight;
                //$img_path = $resident->img_path;
                $house_no = $resident->house_no;
                $street = $resident->street;
                $add_id = $resident->add_id;
                $isHead = $resident->isHead;
                $household = $resident->household;
                $resident_id = $resident->resident_id;
                // get description from the category table based on the connected id of the resident id
                $cstatus = $this->getCategoryDescription($cstatus_id);
                $educ = $this->getCategoryDescription($educ_id);
                $course = $this->getCategoryDescription($course_id);
                $religion = $this->getCategoryDescription($rel_id);
                $occupation = $this->getCategoryDescription($occ_id);
                // relationship to household head
                $relation = $this->getCategoryDescription($relation_hh);


                if ($isHead == "TRUE") {
                    // HOUSEHOLD HEAD

                    // GET IMAGE
                    $img = ($resident->img_path) ?? '';
                    $isExists = $this->checkFile($img);
                    $img_path = ($isExists) ? base_url('writable/uploads/' . $img) : base_url('public/assets/images/logo.png');
                    // Get purok/zone name
                    $purok_data = $this->getPurokDescription($add_id);
                    $purok_desc = ($purok_data) ? $purok_data->description : '';
                    // Get barangay name
                    $brgy_data = $this->getBrgyDescription($add_id);
                    $brgy_desc = ($brgy_data) ? $brgy_data->brgy_name : '';
                    $brgy = $brgy_desc;
                    // PASS DATA TO ARRAY
                    $head_info = [
                        'household' => $household,
                        'status' => $status,
                        'resident_id' => $resident_id,
                        'lname' => $lname,
                        'fname' => $fname,
                        'mname' => $mname,
                        'suffix' => $suffix,
                        'fullname' => $fullname,
                        'bday' => $bday,
                        'bplace' => $bplace,
                        'gender' => $gender,
                        'cstatus' => $cstatus,
                        'educ' => $educ,
                        'course' => $course,
                        'religion' => $religion,
                        'occupation' => $occupation,
                        'phealth_no' => $phealth_no,
                        'm_income' => $this->convert_to_accounting($m_income),
                        'cp' => $cp,
                        'email' => $email,
                        'nstatus' => $nstatus,
                        'btype' => $btype,
                        'height' => $height,
                        'weight' => $weight,
                        'brgy' => $brgy_desc,
                        'purok' => $purok_desc,
                        'street' => $street,
                        'house_no' => $house_no,
                        'resident_id' => $resident_id
                    ];
                } else {
                    // HOUSEHOLD MEMBER
                    $household_member[] = "<tr>
                                                <td>$resident_id</td>
                                                <td>$fullname</td>
                                                <td class='text-center'>$age</td>
                                                <td class='text-center'>$gender</td>
                                                <td class='text-center'>$cstatus</td>
                                                <td class='text-center'>$relation</td>
                                            </tr>";
                    // // QR code
                    // $qr_household_member[] = "Resident ID: $resident_id; Member Name: $fullname";
                }

                // GET SUBMITTED DOCUMENT
                $data_docs = $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
                // Convert to html data
                if (is_array($data_docs) && count($data_docs) > 0) {
                    foreach ($data_docs as $row) {
                        // Data collected from database
                        $category_id = $row->category_id;
                        $filename = $row->filename;
                        // Gather category info
                        $cID = $category_id; // Category id
                        $cDesc = $this->getCategoryDescription($category_id); // Description

                        if (session()->get('role') === "ADMIN") {
                            $view_file = "<a href='" . site_url("/resident/viewFile/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>";
                        } else if (session()->get('role') === "MAIN") {
                            $view_file = "<a href='" . site_url("/resident/viewFile3/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>";
                        }
                        $html_docs[] = "<tr>
                        <td>$fullname</td>
                        <td>$cDesc</td>
                        <td><label>$filename</label></td>
                        <td>
                            $view_file
                        </td>
                    </tr>";
                    }
                }
            }
        }


        // ======================== //
        // OTHER INFORMATION
        // ======================== //

        // ========= Water Sources ========= //
        $data_water = $WaterModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_water = array();
        if (is_array($data_water) && count($data_water) > 0) {
            foreach ($data_water as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_water[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$ave_per_mo</td>
                                    </tr>";
            }
        } else {
            $html_water[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Power Sources ========= //
        $data_power = $PowerModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_power = array();
        if (is_array($data_power) && count($data_power) > 0) {
            foreach ($data_power as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_power[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$ave_per_mo</td>
                                    </tr>";
            }
        } else {
            $html_power[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Sanitation ========= //
        $data_san = $SanitationModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_san = array();
        if (is_array($data_san) && count($data_san) > 0) {
            foreach ($data_san as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_san[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_san[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Cooking ========= //
        $data_cook = $CookingModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_cook = array();
        if (is_array($data_cook) && count($data_cook) > 0) {
            foreach ($data_cook as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_cook[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_cook[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Appliances ========= //
        $data_app = $HAppliancesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_app = array();
        if (is_array($data_app) && count($data_app) > 0) {
            foreach ($data_app as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_app[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_app[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Communication ========= //
        $data_comm = $CommModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_comm = array();
        if (is_array($data_comm) && count($data_comm) > 0) {
            foreach ($data_comm as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_comm[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_comm[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Amenities ========= //
        $data_amenities = $BldgAmenitiesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_amenities = array();
        if (is_array($data_amenities) && count($data_amenities) > 0) {
            foreach ($data_amenities as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_amenities[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_amenities[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Vehicle ========= //
        $data_vhcl = $HVehicleModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_vhcl = array();
        if (is_array($data_vhcl) && count($data_vhcl) > 0) {
            foreach ($data_vhcl as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_vhcl[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_vhcl[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agi-machineries ========= //
        $data_amach = $AmachineriesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_amach = array();
        if (is_array($data_amach) && count($data_amach) > 0) {
            foreach ($data_amach as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_amach[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_amach[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agri-livestock ========= //
        $data_alive = $AlivestockModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_alive = array();
        if (is_array($data_alive) && count($data_alive) > 0) {
            foreach ($data_alive as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_alive[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_alive[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Info ========= //
        $result_bldgInfo = $BldgInfoModel->where('hh_id', $household_id)->where('status', $tbl_status)->first();

        $data_bldgInfo = array();

        if ($result_bldgInfo !== null) {
            // Get Building Type description from category table
            $bldg_type_id = $result_bldgInfo->bldg_type_id;

            $data_bldgInfo = [
                'hh_id' => $result_bldgInfo->hh_id,
                'bldg_type' => $this->getCategoryDescription($bldg_type_id),
                'construction_yr' => $this->display_date($result_bldgInfo->construction_yr),
                'yr_occupied' => $this->display_date($result_bldgInfo->yr_occupied),
                'bldg_permit_no' => $result_bldgInfo->bldg_permit_no,
                'lot_no' => $result_bldgInfo->lot_no
            ];
        } else {
            // No data found
            $data_bldgInfo = [
                'hh_id' => '',
                'bldg_type' => '',
                'construction_yr' => '',
                'yr_occupied' => '',
                'bldg_permit_no' => '',
                'lot_no' => ''
            ];
        }

        // ========= Average generated garbages ========= //
        $result_garbages = $GarbageModel->where('hh_id', $household_id)->where('status', $tbl_status)->first();

        $data_garbages = array();

        if ($result_garbages) {
            // Existing data
            $data_garbages = [
                'hh_id' => $result_garbages->hh_id,
                'hazardous' => $result_garbages->hazardous,
                'recyclable' => $result_garbages->recyclable,
                'residual' => $result_garbages->residual,
                'biodegradable' => $result_garbages->biodegradable
            ];
        } else {
            // No data found
            $data_garbages = [
                'hh_id' => '',
                'hazardous' => '',
                'recyclable' => '',
                'residual' => '',
                'biodegradable' => ''
            ];
        }

        // GENERATE QR CODE CONTENT
        // $qr_content = "Household ID: $household_id \n\n";
        // $qr_content .= "Household Head ID: " . $head_info['resident_id'] . "\n";
        // $qr_content .= "Household Head: " . $head_info['fullname'] . "\n\n";
        // $qr_content .= "Household Member: \n";
        // $qr_content .= implode("\n\n", $qr_household_member) . "\n";
        // $qr_content .= "\nDate generated: " . date("m-d-Y");


        // $qr_img = $this->generateQrCodeImage($qr_content);

        // Get user who encoded it
        $user_fullname = session()->get('fullname');
        $date_generated = date("m-d-Y");

        $current_user = array(
            'fullname' => $user_fullname,
            'dated' => $date_generated
        );

        // CHECK IF THERE ARE HOUSEHOLD MEMBERS
        if (is_array($household_member) && count($household_member) === 0) {
            $household_member[] = "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
        }

        // CHECK IF THERE ARE DOCUMENTS SUBMITTED
        if (is_array($html_docs) && count($html_docs) === 0) {
            $html_docs[] = "<tr><td colspan='4' class='text-center'>No record found</td></tr>";
        }


        $output = array(
            'head_info' => $head_info,
            'member' => $household_member,
            'water' => $html_water,
            'power' => $html_power,
            'san' => $html_san,
            'cook' => $html_cook,
            'app' => $html_app,
            'comm' => $html_comm,
            'amenities' => $html_amenities,
            'vhcl' => $html_vhcl,
            'amach' => $html_amach,
            'alive' => $html_alive,
            'bldginfo' => $data_bldgInfo,
            'garbages' => $data_garbages,
            'img_path' => $img_path,
            'brgy_logo' => $logo_path,
            'user_data' => $current_user,
            'brgy_name' => $brgy,
            'household_id' => $household_id,
            'docs' => $html_docs
        );

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();
        
        if (session()->get('role') === "ADMIN") {
            return view("administrator/resident/form_review", $output);
        } else if (session()->get('role') === "MAIN") {
            return view("main/resident/form_review", $output);
        }
    }

    // VIEW HOUSEHOLD INFORMATION (PRINTABLE = HOUSEHOLD FORM)
    // COPIED FROM VIEW_APPROVAL METHOD
    public function view_form($household_id)
    {
        $status = "ACTIVE";
        $tbl_status = "ACTIVE";

        // Initialize models
        $ResidentModel = new ResidentModel();
        $WaterModel = new WaterModel();
        $PowerModel = new PowerModel();
        $SanitationModel = new SanitationModel();
        $CookingModel = new CookingModel();
        $HAppliancesModel = new HAppliancesModel();
        $CommModel = new CommModel();
        $BldgAmenitiesModel = new BldgAmenitiesModel();
        $HVehicleModel = new HVehicleModel();
        $AmachineriesModel = new AmachineriesModel();
        $AlivestockModel = new AlivestockModel();
        $BldgInfoModel = new BldgInfoModel();
        $GarbageModel = new GarbageModel();
        $AttachmentsModel = new AttachmentsModel();

        // ======================== //
        // HOUSEHOLD HEAD, HOUSEHOLD MEMBER, AND OTHER INFO OF HOUSEHOLD HEAD
        // ======================== //

        // Get household head and member information
        $head_info = array();
        $household_member = array();
        $qr_household_member = array();
        // initialization for gathering document submitted
        $docs = array();
        $html_docs = array();

        $img_path = "";
        $brgy = "";

        // GET BARANGAY LOGO
        // Check logo if exists
        $BrgyProfileModel = new BrgyProfileModel();
        $profile_data = $BrgyProfileModel->where("brgy_id", $this->brgy_id)->first();
        $logo_path = "";
        if ($profile_data) {
            $logo = isset($profile_data->logo) ? $profile_data->logo : '';
            $isExists = $this->checkFile($logo);
            $logo_path = ($isExists) ? base_url('writable/uploads/' . $logo) : base_url('public/assets/images/logo.png');
        } else {
            $logo_path = base_url('public/assets/images/logo.png');
        }

        // Check household that has a household id of submitted post request
        $resident_data = $ResidentModel->where("household", $household_id)->where("status", $status)->findAll();

        if (is_array($resident_data) && count($resident_data) > 0) {
            foreach ($resident_data as $resident) {
                $res_id = $resident->id;
                $lname = $resident->lname;
                $fname = $resident->fname;
                $mname = $resident->mname;
                $suffix = $resident->suffix;
                $fullname = $resident->fullname;
                $bday = $this->display_date($resident->bday);
                $age = $resident->age;
                $bplace = $resident->bplace;
                $gender = $resident->gender;
                $cstatus_id = $resident->cstatus_id;
                $educ_id = $resident->educ_id;
                $course_id = $resident->course_id;
                $rel_id = $resident->rel_id;
                $phealth_no = $resident->phealth_no;
                $occ_id = $resident->occ_id;
                $m_income = $resident->m_income;
                $cp = $resident->cp;
                $email = $resident->email;
                $nstatus = $resident->nstatus;
                $relation_hh = $resident->relation_hh;
                $relation_fh = $resident->relation_fh;
                $fh_id = $resident->fh_id;
                $btype = $resident->btype;
                $height = $resident->height;
                $weight = $resident->weight;
                //$img_path = $resident->img_path;
                $house_no = $resident->house_no;
                $street = $resident->street;
                $add_id = $resident->add_id;
                $isHead = $resident->isHead;
                $household = $resident->household;
                $resident_id = $resident->resident_id;
                // get description from the category table based on the connected id of the resident id
                $cstatus = $this->getCategoryDescription($cstatus_id);
                $educ = $this->getCategoryDescription($educ_id);
                $course = $this->getCategoryDescription($course_id);
                $religion = $this->getCategoryDescription($rel_id);
                $occupation = $this->getCategoryDescription($occ_id);
                // relationship to household head
                $relation = $this->getCategoryDescription($relation_hh);


                if ($isHead == "TRUE") {
                    // HOUSEHOLD HEAD

                    // GET IMAGE
                    $img = ($resident->img_path) ?? '';
                    $isExists = $this->checkFile($img);
                    $img_path = ($isExists) ? base_url('writable/uploads/' . $img) : base_url('public/assets/images/logo.png');
                    // Get purok/zone name
                    $purok_data = $this->getPurokDescription($add_id);
                    $purok_desc = ($purok_data) ? $purok_data->description : '';
                    // Get barangay name
                    $brgy_data = $this->getBrgyDescription($add_id);
                    $brgy_desc = ($brgy_data) ? $brgy_data->brgy_name : '';
                    $brgy = $brgy_desc;
                    // PASS DATA TO ARRAY
                    $head_info = [
                        'household' => $household,
                        'status' => $status,
                        'resident_id' => $resident_id,
                        'lname' => $lname,
                        'fname' => $fname,
                        'mname' => $mname,
                        'suffix' => $suffix,
                        'fullname' => $fullname,
                        'bday' => $bday,
                        'bplace' => $bplace,
                        'gender' => $gender,
                        'cstatus' => $cstatus,
                        'educ' => $educ,
                        'course' => $course,
                        'religion' => $religion,
                        'occupation' => $occupation,
                        'phealth_no' => $phealth_no,
                        'm_income' => $this->convert_to_accounting($m_income),
                        'cp' => $cp,
                        'email' => $email,
                        'nstatus' => $nstatus,
                        'btype' => $btype,
                        'height' => $height,
                        'weight' => $weight,
                        'brgy' => $brgy_desc,
                        'purok' => $purok_desc,
                        'street' => $street,
                        'house_no' => $house_no,
                        'resident_id' => $resident_id
                    ];
                } else {
                    // HOUSEHOLD MEMBER
                    $household_member[] = "<tr>
                                                <td>$resident_id</td>
                                                <td>$fullname</td>
                                                <td class='text-center'>$age</td>
                                                <td class='text-center'>$gender</td>
                                                <td class='text-center'>$cstatus</td>
                                                <td class='text-center'>$relation</td>
                                            </tr>";
                    // // QR code
                    // $qr_household_member[] = "Resident ID: $resident_id; Member Name: $fullname";
                }

                // // GET SUBMITTED DOCUMENT
                // $data_docs = $AttachmentsModel->where('res_id', $res_id)->where('status', $tbl_status)->findAll();
                // // Convert to html data
                // if (is_array($data_docs) && count($data_docs) > 0) {
                //     foreach ($data_docs as $row) {
                //         // Data collected from database
                //         $category_id = $row->category_id;
                //         $filename = $row->filename;
                //         // Gather category info
                //         $cID = $category_id; // Category id
                //         $cDesc = $this->getCategoryDescription($category_id); // Description

                //         if (session()->get('role') === "ADMIN") {
                //             $view_file = "<a href='" . site_url("/resident/viewFile/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>";
                //         } else if (session()->get('role') === "MAIN") {
                //             $view_file = "<a href='" . site_url("/resident/viewFile3/$filename") . "' target='_blank' class='btn btn-sm btn-info btnView-doctype'>View</a>";
                //         }
                //         $html_docs[] = "<tr>
                //         <td>$fullname</td>
                //         <td>$cDesc</td>
                //         <td><label>$filename</label></td>
                //         <td>
                //             $view_file
                //         </td>
                //     </tr>";
                //     }
                // }
            }
        }


        // ======================== //
        // OTHER INFORMATION
        // ======================== //

        // ========= Water Sources ========= //
        $data_water = $WaterModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_water = array();
        if (is_array($data_water) && count($data_water) > 0) {
            foreach ($data_water as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_water[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$ave_per_mo</td>
                                    </tr>";
            }
        } else {
            $html_water[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Power Sources ========= //
        $data_power = $PowerModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_power = array();
        if (is_array($data_power) && count($data_power) > 0) {
            foreach ($data_power as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $ave_per_mo = $row->ave_per_mo;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_power[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$ave_per_mo</td>
                                    </tr>";
            }
        } else {
            $html_power[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Sanitation ========= //
        $data_san = $SanitationModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_san = array();
        if (is_array($data_san) && count($data_san) > 0) {
            foreach ($data_san as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_san[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_san[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Cooking ========= //
        $data_cook = $CookingModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_cook = array();
        if (is_array($data_cook) && count($data_cook) > 0) {
            foreach ($data_cook as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_cook[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_cook[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Appliances ========= //
        $data_app = $HAppliancesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_app = array();
        if (is_array($data_app) && count($data_app) > 0) {
            foreach ($data_app as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_app[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_app[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Communication ========= //
        $data_comm = $CommModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_comm = array();
        if (is_array($data_comm) && count($data_comm) > 0) {
            foreach ($data_comm as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_comm[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_comm[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Amenities ========= //
        $data_amenities = $BldgAmenitiesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_amenities = array();
        if (is_array($data_amenities) && count($data_amenities) > 0) {
            foreach ($data_amenities as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                //$ave_per_mo = $row->ave_per_mo; 
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_amenities[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                    </tr>";
            }
        } else {
            $html_amenities[] = "<tr>
                                    <td class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Vehicle ========= //
        $data_vhcl = $HVehicleModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_vhcl = array();
        if (is_array($data_vhcl) && count($data_vhcl) > 0) {
            foreach ($data_vhcl as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_vhcl[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_vhcl[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agi-machineries ========= //
        $data_amach = $AmachineriesModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_amach = array();
        if (is_array($data_amach) && count($data_amach) > 0) {
            foreach ($data_amach as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_amach[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_amach[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Agri-livestock ========= //
        $data_alive = $AlivestockModel->where('hh_id', $household_id)->where('status', $tbl_status)->findAll();
        // Convert to html data
        $html_alive = array();
        if (is_array($data_alive) && count($data_alive) > 0) {
            foreach ($data_alive as $row) {
                // Data collected from database
                $category_id = $row->category_id;
                $qty = $row->qty;
                // Gather category info
                $cDesc = $this->getCategoryDescription($category_id);

                $html_alive[] = "<tr>
                                        <td>
                                            <span>$cDesc</span>
                                        </td>
                                        <td class='text-center'>$qty</td>
                                    </tr>";
            }
        } else {
            $html_alive[] = "<tr>
                                    <td colspan='2' class='text-center'>No record found</td>
                                </tr>";
        }
        // ========= Building Info ========= //
        $result_bldgInfo = $BldgInfoModel->where('hh_id', $household_id)->where('status', $tbl_status)->first();

        $data_bldgInfo = array();

        if ($result_bldgInfo !== null) {
            // Get Building Type description from category table
            $bldg_type_id = $result_bldgInfo->bldg_type_id;

            $data_bldgInfo = [
                'hh_id' => $result_bldgInfo->hh_id,
                'bldg_type' => $this->getCategoryDescription($bldg_type_id),
                'construction_yr' => $this->display_date($result_bldgInfo->construction_yr),
                'yr_occupied' => $this->display_date($result_bldgInfo->yr_occupied),
                'bldg_permit_no' => $result_bldgInfo->bldg_permit_no,
                'lot_no' => $result_bldgInfo->lot_no
            ];
        } else {
            // No data found
            $data_bldgInfo = [
                'hh_id' => '',
                'bldg_type' => '',
                'construction_yr' => '',
                'yr_occupied' => '',
                'bldg_permit_no' => '',
                'lot_no' => ''
            ];
        }

        // ========= Average generated garbages ========= //
        $result_garbages = $GarbageModel->where('hh_id', $household_id)->where('status', $tbl_status)->first();

        $data_garbages = array();

        if ($result_garbages) {
            // Existing data
            $data_garbages = [
                'hh_id' => $result_garbages->hh_id,
                'hazardous' => $result_garbages->hazardous,
                'recyclable' => $result_garbages->recyclable,
                'residual' => $result_garbages->residual,
                'biodegradable' => $result_garbages->biodegradable
            ];
        } else {
            // No data found
            $data_garbages = [
                'hh_id' => '',
                'hazardous' => '',
                'recyclable' => '',
                'residual' => '',
                'biodegradable' => ''
            ];
        }

        // GENERATE QR CODE CONTENT
        // $qr_content = "Household ID: $household_id \n\n";
        // $qr_content .= "Household Head ID: " . $head_info['resident_id'] . "\n";
        // $qr_content .= "Household Head: " . $head_info['fullname'] . "\n\n";
        // $qr_content .= "Household Member: \n";
        // $qr_content .= implode("\n\n", $qr_household_member) . "\n";
        // $qr_content .= "\nDate generated: " . date("m-d-Y");


        // $qr_img = $this->generateQrCodeImage($qr_content);

        // Get user who encoded it
        $user_fullname = session()->get('fullname');
        $date_generated = date("m-d-Y");

        $current_user = array(
            'fullname' => $user_fullname,
            'dated' => $date_generated
        );

        // CHECK IF THERE ARE HOUSEHOLD MEMBERS
        if (is_array($household_member) && count($household_member) === 0) {
            $household_member[] = "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
        }

        // CHECK IF THERE ARE DOCUMENTS SUBMITTED
        if (is_array($html_docs) && count($html_docs) === 0) {
            $html_docs[] = "<tr><td colspan='4' class='text-center'>No record found</td></tr>";
        }


        $output = array(
            'head_info' => $head_info,
            'member' => $household_member,
            'water' => $html_water,
            'power' => $html_power,
            'san' => $html_san,
            'cook' => $html_cook,
            'app' => $html_app,
            'comm' => $html_comm,
            'amenities' => $html_amenities,
            'vhcl' => $html_vhcl,
            'amach' => $html_amach,
            'alive' => $html_alive,
            'bldginfo' => $data_bldgInfo,
            'garbages' => $data_garbages,
            'img_path' => $img_path,
            'brgy_logo' => $logo_path,
            'user_data' => $current_user,
            'brgy_name' => $brgy,
            'household_id' => $household_id,
            'docs' => $html_docs
        );

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();
        
        if (session()->get('role') === "ADMIN") {
            return view("administrator/resident/head_form", $output);
        } else if (session()->get('role') === "MAIN") {
            return view("main/resident/head_form", $output);
        }
    }

    // APPROVE 
    public function approve()
    {
        $post = $this->request->getPost();
        $household_id = $post['household_id'] ?? '';

        $status = "FOR APPROVAL";
        $tbl_status = "TMP";
        $active = "ACTIVE";

        $email_collect = []; // Collect email for email notification;

        $fh_id_toUpdate = []; // Initialize the array

        // Start the transaction
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Initialize models
            $ResidentModel = new ResidentModel();
            $TmpResidentModel = new TmpResidentModel();

            // SELECT DATA FROM TBLTEMP
            $household_data = $TmpResidentModel->where('household', $household_id)->where('status', $status)->findAll();

            if ($household_data) {
                foreach ($household_data as $resident) {
                    $tmp_id = $resident->id ?? '';
                    $id_exists = $ResidentModel->find($tmp_id);

                    $data = [
                        'lname' => $resident->lname,
                        'fname' => $resident->fname,
                        'mname' => $resident->mname,
                        'suffix' => $resident->suffix,
                        'fullname' => $resident->fullname,
                        'bday' => $resident->bday,
                        'age' => $resident->age,
                        'bplace' => $resident->bplace,
                        'gender' => $resident->gender,
                        'cstatus_id' => $resident->cstatus_id,
                        'educ_id' => $resident->educ_id,
                        'course_id' => $resident->course_id,
                        'rel_id' => $resident->rel_id,
                        'phealth_no' => $resident->phealth_no,
                        'occ_id' => $resident->occ_id,
                        'm_income' => $resident->m_income,
                        'cp' => $resident->cp,
                        'email' => $resident->email,
                        'nstatus' => $resident->nstatus,
                        'relation_hh' => $resident->relation_hh,
                        'relation_fh' => $resident->relation_fh,
                        'fh_id' => $resident->fh_id,
                        'btype' => $resident->btype,
                        'height' => $resident->height,
                        'weight' => $resident->weight,
                        'img_path' => $resident->img_path,
                        'house_no' => $resident->house_no,
                        'street' => $resident->street,
                        'add_id' => $resident->add_id,
                        'isHead' => $resident->isHead,
                        'status' => $active,
                        'household' => $resident->household,
                        'resident_id' => $resident->resident_id
                    ];

                    if ($id_exists) {
                        // Update only if there's data to update
                        $updateResult = $ResidentModel->set($data)->where('id', $tmp_id)->update();
                        if ($updateResult === false) {
                            log_message('error', 'Failed to update ResidentModel for tmp_id: ' . $tmp_id);
                        }

                        $this->updateRelatedTables($tmp_id, $active);

                        // UPDATE USER ACCOUNT DETAILS
                        $update_user = [
                            'fullname' => $data['fullname'],
                            'add_id' => $data['add_id'],
                            'gender' => $data['gender'],
                            'bday' => $data['bday'],
                            'age' => $data['age'],
                            'cstatus_id' => $data['cstatus_id'],
                            'email' => $data['email'],
                            'cp' => $data['cp'],
                            'insertID' => $tmp_id
                        ];
                        $this->updateUserAccount($update_user);

                    } else {
                        $ResidentModel->insert($data);
                        $insertID = $ResidentModel->getInsertID();

                        $data_for_residentID = [
                            'household_id' => $household_id,
                            'res_id' => $insertID
                        ];
                        $new_residentID = $this->setResidentID($data_for_residentID);
                        $ResidentModel->set(['resident_id' => $new_residentID])->where('id', $insertID)->update();

                        // GATHER DATA FOR UPDATING OF FAMILY HEAD ID
                        $fh_id_toUpdate[] = [
                            'old_fh_id' => $tmp_id,
                            'new_fh_id' => $insertID 
                        ];

                        $this->updateRelatedTables($tmp_id, $active, $insertID);

                        // UPDATE USER ACCOUNT DETAILS
                        $update_user = [
                            'fullname' => $data['fullname'],
                            'add_id' => $data['add_id'],
                            'gender' => $data['gender'],
                            'bday' => $data['bday'],
                            'age' => $data['age'],
                            'cstatus_id' => $data['cstatus_id'],
                            'email' => $data['email'],
                            'cp' => $data['cp'],
                            'insertID' => $data['insertID']
                        ];
                        $this->updateUserAccount($update_user);

                    }

                    // Collect data for email notification that the resident information was successfully approved by the system admin.
                    $email_collect [] = [
                        'email' => $data['email'],
                        'fname' => $data['fname'],
                        'resident_id' => $data['resident_id']
                    ];
                }
                $this->updateHouseholdData($household_id, $active);
                // DELETE RECORDS FROM TEMP WHERE HOUSEHOLD = $household_id
                $TmpResidentModel->where('household', $household_id)->delete();
                // UPDATE FH_ID
                $this->updateFH_ID($fh_id_toUpdate);
                
            }

            // Commit the transaction if no issues
            $db->transCommit();

            // Send email notification
            if ($email_collect) {
                $subject = "RESIDENT'S INFORMATION WAS BEING APPROVED";
                foreach ($email_collect AS $row) {
                    $email = $row['email'];
                    $fname = $row['fname'];
                    $resident_id = $row['resident_id'];
                    $message = "Dear <b>$fname</b>, <br><br>
                    This is to inform you that your household information was successfully approved by the Barangay System Administrator. 
                    <br><br>Your resident id is $resident_id. <br><br> Thank you so much and God bless.<br><br><br>
                    Yours truly,<br><br><b>System Administrator</b><br>Barangay Information System<br><br>
                    <a href='https://webdev-system.com/login'>You may visit the link here</a>";
                    $this->send_email($email, $message, $subject);
                }
            }

            // Log activity
            $this->activityLogService->logActivity("Approved household ID: $household_id", session()->get("id"));

            session()->setFlashdata('success', 'Household ID:'. $household_id .' was successfully approved');
            
            if (session()->get('role') === "ADMIN") {
                return redirect()->to('resident/active_inactive');
            } else if (session()->get('role') === "MAIN") {
                return redirect()->to('resident/active_inactive3');
            }
        } catch (\Exception $e) {
            // Rollback if there is an error
            $db->transRollback();
            log_message('error', $e->getMessage());
            throw $e;
        }
    }

    private function updateFH_ID($array) {
        $ResidentModel = new ResidentModel();
        if ($array && is_array($array)) {
            foreach($array AS $data) {
                $old_id = $data['old_fh_id'];
                $new_id = $data['new_fh_id'];

                $set = ['fh_id' => $new_id];

                if ($ResidentModel->where('fh_id', $old_id)->first()) {
                    $update = $ResidentModel->set($set)->where('fh_id', $old_id)->update();
                }
            }
        }
    }
    

    private function updateHouseholdData($household_id, $active)
    {
        // Manually check and update each model without using loops

        // Check and update WaterModel
        $WaterModel = new WaterModel();
        if ($WaterModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($WaterModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in WaterModel for household_id: ' . $household_id);
        }

        // Check and update PowerModel
        $PowerModel = new PowerModel();
        if ($PowerModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($PowerModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in PowerModel for household_id: ' . $household_id);
        }

        // Check and update SanitationModel
        $SanitationModel = new SanitationModel();
        if ($SanitationModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($SanitationModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in SanitationModel for household_id: ' . $household_id);
        }

        // Check and update CookingModel
        $CookingModel = new CookingModel();
        if ($CookingModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($CookingModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in CookingModel for household_id: ' . $household_id);
        }

        // Check and update HAppliancesModel
        $HAppliancesModel = new HAppliancesModel();
        if ($HAppliancesModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($HAppliancesModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in HAppliancesModel for household_id: ' . $household_id);
        }

        // Check and update CommModel
        $CommModel = new CommModel();
        if ($CommModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($CommModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in CommModel for household_id: ' . $household_id);
        }

        // Check and update BldgAmenitiesModel
        $BldgAmenitiesModel = new BldgAmenitiesModel();
        if ($BldgAmenitiesModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($BldgAmenitiesModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in BldgAmenitiesModel for household_id: ' . $household_id);
        }

        // Check and update HVehicleModel
        $HVehicleModel = new HVehicleModel();
        if ($HVehicleModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($HVehicleModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in HVehicleModel for household_id: ' . $household_id);
        }

        // Check and update AmachineriesModel
        $AmachineriesModel = new AmachineriesModel();
        if ($AmachineriesModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($AmachineriesModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in AmachineriesModel for household_id: ' . $household_id);
        }

        // Check and update AlivestockModel
        $AlivestockModel = new AlivestockModel();
        if ($AlivestockModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($AlivestockModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in AlivestockModel for household_id: ' . $household_id);
        }

        // Check and update BldgInfoModel
        $BldgInfoModel = new BldgInfoModel();
        if ($BldgInfoModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($BldgInfoModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in BldgInfoModel for household_id: ' . $household_id);
        }

        // Check and update GarbageModel
        $GarbageModel = new GarbageModel();
        if ($GarbageModel->where('hh_id', $household_id)->first()) {
            $this->updateModelStatus($GarbageModel, 'hh_id', $household_id, $active);
        } else {
            log_message('error', 'No matching record found in GarbageModel for household_id: ' . $household_id);
        }
    }


    public function updateRelatedTables($tmp_id, $active, $insertID = null)
    {
        // Check and update TrainingModel
        $TrainingModel = new TrainingModel();
        if ($TrainingModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($TrainingModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in TrainingModel for tmp_id: ' . $tmp_id);
        }

        // Check and update GprogramsModel
        $GprogramsModel = new GprogramsModel();
        if ($GprogramsModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($GprogramsModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in GprogramsModel for tmp_id: ' . $tmp_id);
        }

        // Check and update DialectModel
        $DialectModel = new DialectModel();
        if ($DialectModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($DialectModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in DialectModel for tmp_id: ' . $tmp_id);
        }

        // Check and update SincomeModel
        $SincomeModel = new SincomeModel();
        if ($SincomeModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($SincomeModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in SincomeModel for tmp_id: ' . $tmp_id);
        }

        // Check and update AppliancesModel
        $AppliancesModel = new AppliancesModel();
        if ($AppliancesModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($AppliancesModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in AppliancesModel for tmp_id: ' . $tmp_id);
        }

        // Check and update DisabilityModel
        $DisabilityModel = new DisabilityModel();
        if ($DisabilityModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($DisabilityModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in DisabilityModel for tmp_id: ' . $tmp_id);
        }

        // Check and update ComorbiditiesModel
        $ComorbiditiesModel = new ComorbiditiesModel();
        if ($ComorbiditiesModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($ComorbiditiesModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in ComorbiditiesModel for tmp_id: ' . $tmp_id);
        }

        // Check and update VehicleModel
        $VehicleModel = new VehicleModel();
        if ($VehicleModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($VehicleModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in VehicleModel for tmp_id: ' . $tmp_id);
        }

        // Check and update AttachmentsModel
        $AttachmentsModel = new AttachmentsModel();
        if ($AttachmentsModel->where('res_id', $tmp_id)->first()) {
            $this->updateModelStatus($AttachmentsModel, 'res_id', $tmp_id, $active);
        } else {
            log_message('error', 'No matching record found in AttachmentsModel for tmp_id: ' . $tmp_id);
        }

        // If insertID is provided, update the new resident ID in all models
        if ($insertID) {
            // Check and update TrainingModel
            if ($TrainingModel->where('res_id', $tmp_id)->first()) {
                $TrainingModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in TrainingModel for tmp_id: ' . $tmp_id);
            }

            // Check and update GprogramsModel
            if ($GprogramsModel->where('res_id', $tmp_id)->first()) {
                $GprogramsModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in GprogramsModel for tmp_id: ' . $tmp_id);
            }

            // Check and update DialectModel
            if ($DialectModel->where('res_id', $tmp_id)->first()) {
                $DialectModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in DialectModel for tmp_id: ' . $tmp_id);
            }

            // Check and update SincomeModel
            if ($SincomeModel->where('res_id', $tmp_id)->first()) {
                $SincomeModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in SincomeModel for tmp_id: ' . $tmp_id);
            }

            // Check and update AppliancesModel
            if ($AppliancesModel->where('res_id', $tmp_id)->first()) {
                $AppliancesModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in AppliancesModel for tmp_id: ' . $tmp_id);
            }

            // Check and update DisabilityModel
            if ($DisabilityModel->where('res_id', $tmp_id)->first()) {
                $DisabilityModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in DisabilityModel for tmp_id: ' . $tmp_id);
            }

            // Check and update ComorbiditiesModel
            if ($ComorbiditiesModel->where('res_id', $tmp_id)->first()) {
                $ComorbiditiesModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in ComorbiditiesModel for tmp_id: ' . $tmp_id);
            }

            // Check and update VehicleModel
            if ($VehicleModel->where('res_id', $tmp_id)->first()) {
                $VehicleModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in VehicleModel for tmp_id: ' . $tmp_id);
            }

            // Check and update VehicleModel
            if ($AttachmentsModel->where('res_id', $tmp_id)->first()) {
                $AttachmentsModel->set('res_id', $insertID)->where('res_id', $tmp_id)->update();
            } else {
                log_message('error', 'No matching record found in AttachmentsModel for tmp_id: ' . $tmp_id);
            }
        }
    }

    private function updateModelStatus($model, $column, $value, $status = 'ACTIVE')
    {
        // Update status only if records exist
        $model->where($column, $value)->where('status', 'ACTIVE')->delete();
        $model->set(['status' => $status])->where($column, $value)->update();
    }

    private function updateUserAccount($update_user)
    {

        $LoginModel = new LoginModel();
        $user_data = $LoginModel->where("fullname", trim($update_user['fullname']))->first();
        $user_id = $user_data->id ?? '';
        // Get brgy_id
        $add_id = $update_user['add_id'] ?? '';
        $purok_data = $this->getPurokDescription($add_id);
        $brgy_id = $purok_data->brgy_id ?? '';

        $data_user_to_update = [
            'gender' => $update_user['gender'],
            'bday' => $update_user['bday'],
            'age' => $update_user['age'],
            'cstatus_id' => $update_user['cstatus_id'],
            'email' => $update_user['email'],
            'cp' => $update_user['cp'],
            'brgy_id' => $brgy_id,
            'res_id' => $update_user['insertID']
        ];

        // Update user details
        $LoginModel->set($data_user_to_update)->where('id', $user_id)->update();
    }
}
