<?php

namespace App\Controllers;

use App\Models\BrgyProfileModel;
use App\Models\CertificateModel;
use App\Models\UploadCertificationModel;
use App\Models\DocFeeModel;
use App\Models\ResidentModel;
use CodeIgniter\Controller;

class CertificateController extends BaseController
{

    public function index()
    {
        $list_of_residents = [];

        // GET THE LIST OF RESIDENTS
        $ResidentModel = new ResidentModel();
        $resident_data = $ResidentModel->where('status', 'ACTIVE')->findAll();
        if ($resident_data) {
            foreach ($resident_data as $resident) {
                // Ensure to return corresponding brgy data
                $add_id = $resident->add_id ?? '';
                $purok_data = $this->getPurokDescription($add_id);

                if ($purok_data && $this->brgy_id == $purok_data->brgy_id) {
                    $list_of_residents[] = $resident;
                }
            }
        }

        $output['resident'] = $list_of_residents;

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        return view("/administrator/certificate/index", $output);
    }

    // GET LIST OF data FOR TABLE DISPLAY
    public function getCertificateData()
    {
        $CertificateModel = new CertificateModel();
        $UploadCertificationModel = new UploadCertificationModel();

        try {
            if (session()->get('role') === "ADMIN") {
                $certificateData = $CertificateModel->where('brgy_id', $this->brgy_id)->orderBy('id', 'DESC')->findAll();
            } else {
                $certificateData = $CertificateModel->where('res_id', session()->get('res_id'))->orderBy('id', 'DESC')->findAll();
            }

            $data = [];

            foreach ($certificateData as $row) {
                //Get resident name
                $res_id = $row->res_id ?? '';
                $resident_data = $this->getResidentDataFromID($res_id);
                $resident_name = $resident_data->fullname ?? '';
                // Get document type
                $document_code = $row->document_type ?? '';
                $document_type = $this->getDocName($document_code);

                // created on
                $created_on = $this->display_date($row->created_on ?? '');

                // Get uploaded file

                $uploaded_file = $UploadCertificationModel->where("certificate_id", $row->id)->first();

                $file_name = isset($uploaded_file->file_name) ? $uploaded_file->file_name : '';

                $data[] = [
                    $row->id,
                    $resident_name,
                    $document_type,
                    $row->application_status ?? '',
                    $row->status ?? '',
                    $row->control_no,
                    $created_on ?? '',
                    $file_name
                ];
            }

            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // GET DOCUMENT FEE
    public function getDocumentFee($document_type)
    {
        $document_type = $document_type ?? "";
        $brgy_id = $this->brgy_id;

        $DocFeeModel = new DocFeeModel();
        $data = $DocFeeModel->where("brgy_id", $brgy_id)->where("document_type", $document_type)->first();

        if ($data) {
            $output = [
                "success" => true,
                "fee" => $data->fee ?? "0"
            ];
            return $this->response->setJSON($output);
        } else {
            $output = [
                "success" => true,
                "fee" => "0"
            ];
            return $this->response->setJSON($output);
        }
    }

    // SAVE DATA
    public function saveCertificate()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'resident' => 'required',
            'purpose' => 'required',
            'document_type' => 'required',
            'ctc_no' => 'required|is_numeric',
            'ctc_date' => 'required'
        ];

        // Retrieve POST data
        $postData = $this->request->getPost();

        // Validate input data
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Get the instance model
        $CertificateModel = new CertificateModel();

        // Extract data from POST request
        $id = isset($postData['id']) && $postData['id'] !== '' ? $postData['id'] : null;

        // BRGY ID
        $brgy_id = $this->brgy_id ?? "";

        // DOCUMENT TYPE OR CODE (BC, Bsc, CI)
        $docType = $postData['document_type'] ?? '';

        // CHECK IF THE USER IS RESIDENT AND DOCUMENT TYPE IS BsC
        // Get the selected business location, from $postData['business_location'];
        if (session()->get('role') === "RESIDENT" && $docType === "BsC") {
            $brgy_id = $postData['location'] ?? "";
        }

        // DOCUMENT NAME
        $doc_name = $this->getDocName($docType);

        // Check if the submitted document_type is BsC or Business Clearance
        if ($docType === "BsC" && (!isset($postData['business_name']) || empty($postData['business_name']))) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => "Business Name is required"
            ]);
        }

        $res_id = $postData['resident'] ?? '';

        // APPLICATION STATUS = "WALK-IN"
        if (session()->get('role') === "ADMIN") {
            $application_status = "WALK-IN";
        } else {
            $application_status = "ONLINE";
        }

        // STATUS = "FOR PAYMENT" IF THE DOCUMENT_FEE != 0 ELSE STATUS = "FOR ISSUANCE"
        $document_fee = floatval($postData['document_fee']);

        $status = $document_fee > 0 ? "FOR PAYMENT" : "FOR ISSUANCE";

        $data = [
            'res_id' =>  $res_id,
            'business_name' => isset($postData['business_name']) ? strtoupper($postData['business_name']) : '',
            'purpose' => isset($postData['purpose']) ? strtoupper($postData['purpose']) : '',
            'document_type' => $docType,
            'ctc_no' => $postData['ctc_no'] ?? '',
            'ctc_date' => $this->save_date($postData['ctc_date'] ?? ''),
            'control_no' => $this->getControlNo($docType),
            'document_fee' => $document_fee,
            'application_status' => $application_status,
            'status' => $status,
            'brgy_id' => $brgy_id
        ];

        // GET RESIDENT NAME FOR ACTIVITY LOG
        $resident_data = $this->getResidentDataFromID($res_id);
        $resident = $resident_data->fullname ?? '';

        $today = date("Y-m-d");

        try {

            if ($id) {
                // CHECK IF THE DOCTYPE IS BUSINESS
                if ($docType === "BsC") {
                    // Before inserting data, we have to ensure that there is no duplicated application, same doctype and resident
                    $isDuplicated = $CertificateModel->where("res_id", $res_id)
                        ->where("document_type", $docType)
                        ->where("DATE(created_on)", $today)
                        ->where("business_name", $data['business_name'])
                        ->where("id != ", $id)
                        ->countAllResults();
                } else {
                    // Before inserting data, we have to ensure that there is no duplicated application, same doctype and resident
                    $isDuplicated = $CertificateModel->where("res_id", $res_id)
                        ->where("document_type", $docType)
                        ->where("DATE(created_on)", $today)
                        ->where("brgy_id", $brgy_id)
                        ->where("id != ", $id)
                        ->countAllResults();
                }


                if ($isDuplicated > 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => "Resident already applied for that document today"
                    ]);
                }
                // Proceed to update data
                $CertificateModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated ' . $doc_name . " of " . $resident, session()->get("id"));
            } else {
                // CHECK IF THE DOCTYPE IS BUSINESS
                if ($docType === "BsC") {
                    // Before inserting data, we have to ensure that there is no duplicated application, same doctype and resident
                    $isDuplicated = $CertificateModel->where("res_id", $res_id)
                        ->where("document_type", $docType)
                        ->where("DATE(created_on)", $today)
                        ->where("business_name", $data['business_name'])
                        ->countAllResults();
                } else {
                    // Before inserting data, we have to ensure that there is no duplicated application, same doctype and resident
                    $isDuplicated = $CertificateModel->where("res_id", $res_id)
                        ->where("document_type", $docType)
                        ->where("DATE(created_on)", $today)
                        ->where("brgy_id", $brgy_id)
                        ->countAllResults();
                }

                if ($isDuplicated > 0) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => "Resident already applied for that document today"
                    ]);
                }


                // Proceed to insert data
                $CertificateModel->insert($data);
                // Log activity 
                $this->activityLogService->logActivity('Added ' . $doc_name . " of " . $resident, session()->get("id"));
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // GET DATA FOR UPDATE
    public function getCertificate($id)
    {
        $CertificateModel = new CertificateModel();
        $certificate = $CertificateModel->find($id);

        // Check if found
        if ($certificate && $certificate->brgy_id === $this->brgy_id) {
            $certificate->ctc_date = $this->display_date($certificate->ctc_date);
            return $this->response->setJSON($certificate);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Document not found'
            ]);
        }
    }

    // ISSUE DOCUMENT > status = "FOR ISSUANCE"
    public function issue($id)
    {
        $CertificateModel = new CertificateModel();
        $certificate = $CertificateModel->find($id);

        // VERIFY IF THE USER IS EDITING HIS/HER BRGY JURISDICTION
        if ($certificate && $certificate->brgy_id !== $this->brgy_id) {
            return redirect()->to('certification');
        }

        // GET BARANGAY PROFILE
        $BrgyProfileModel = new BrgyProfileModel();
        $this_brgy = $BrgyProfileModel->where("brgy_id", $this->brgy_id)->first();
        if ($this_brgy) {
            // GET BARANGAY LOGO
            $logo = $this_brgy->logo ?? '';
            $this_brgy->logo = base_url('writable/uploads/' . $logo);
            // GET BARANGAY NAME
            $brgy_name = $this->getBrgyName($this_brgy->brgy_id);

            $output['brgy_name'] = $brgy_name;
            $output['brgy_profile'] = $this_brgy;

            // BARANGAY CAPTAIN DATA
            $brgy_captain_id = $this_brgy->official_id ?? '';
            $official_data = $this->getOfficialDataFromID($brgy_captain_id);
            $position_id = $official_data->position_id ?? '';
            $position = $this->getCategoryDescription($position_id);
            $brgy_captain = $official_data->fullname ?? '';

            $captain_data = [
                "name" => $brgy_captain,
                "position" => $position
            ];

            $output['captain_data'] = $captain_data;
        }

        // GET RESIDENT DATA
        if ($certificate) {
            // UPDATE CONTROL NO., REMOVE TMP
            $control_no = $certificate->control_no ?? '';
            $new_control_no = $this->removeTMP($control_no);
            $data = [
                "control_no" => $new_control_no
            ];
            $certificate_id = $certificate->id ?? '';
            $update_data = $new_control_no ? $CertificateModel->set($data)->where("id", $certificate_id)->update() : "";

            $resident_data = $this->getResidentDataFromID($certificate->res_id);

            // GET AGE
            $resident_data->age = $resident_data->age >= 18 ? "legal" : "under";
            // GET CSTATUS 
            $resident_data->cstatus_id = strtolower($this->getCategoryDescription($resident_data->cstatus_id));
            // GET GENDER
            $resident_data->gender = $resident_data->gender == "MALE" ? "him" : "her";

            $output['resident_data'] = $resident_data;
        }

        // GET DATE ISSUED OF THE CERTIFICATE
        $created_on = $certificate->created_on ?? date("m-d-Y");

        $date_issued = [
            "day" => $this->ordinalNumber(date("d", strtotime($certificate->created_on))),
            "month" => strtoupper(date("F", strtotime($certificate->created_on))),
            "year" => date("Y", strtotime($certificate->created_on))
        ];

        $output['date_issued'] = $date_issued;

        // GET PAYMENT DETAILS AND CTC DETAILS
        // if ($certificate->document_type === "BC" || $certificate->document_type === "BsC") {
            // PAYMENT DETAILS
            $payment_details = [
                'amount_paid' => isset($certificate->amount_paid) ? 'P' . number_format($certificate->amount_paid, 2) : '',
                'or_no' => $certificate->or_no ?? '',
                'or_date' => isset($certificate->or_date) ? $this->display_date($certificate->or_date) : ''
            ];
            $output['payment_details'] = $payment_details;

            // CTC DETAILS
            $ctc_details = [
                'ctc_no' => $certificate->ctc_no ?? '',
                'ctc_date' => isset($certificate->ctc_date) ? $this->display_date($certificate->ctc_date) : ''
            ];
            $output['ctc_details'] = $ctc_details;
        // }

        if ($certificate->status === "FOR ISSUANCE") {
            // UPDATE STATUS TO ISSUED
            $update_data = [
                'status' => "ISSUED",
                'control_no' => $this->removeTMP($certificate->control_no)
            ];
            $CertificateModel->set($update_data)->where("id", $certificate_id)->update();
            // CERTIFICATE DATA
            $certificate = $CertificateModel->find($id);
            $output['certificate'] = $certificate;
            // Log activity 
            $this->activityLogService->logActivity('Issued clearance/certificate of ' . $resident_data->fullname ?? '', session()->get("id"));

            // REDIRECT
            return view("administrator/certificate/printables", $output);
        } else {
            return redirect()->to('certification/');
        }
    }

    // UPLOAD FILE
    public function upload_file()
    {
        // Get POST data
        $post = $this->request->getPost();
        $document_id = $post['document_id'] ?? '';  // Get the document ID
        $file = $this->request->getFile('file');  // Get the uploaded file

        // =========== Upload file =========== //
        /*
            if existing file is not empty and there was an uploaded file
            then check if the file still exists, if true, unlink it and upload the new file

        */

        $img_path = "";

        if ($this->request->getFile('file') !== null && $file->isValid()) {
            // Check if file exists in upload folder, if true, then unlink the file
            if (!empty($img_path)) {
                $exists = $this->checkFile($img_path);

                if ($exists) {
                    // Unlink the file
                    $deleteStatus = $this->deleteFile($img_path);
                }
            }

            // Call the uploadFile method with the appropriate input name
            $allowedType = ['png', 'jpg', 'jpeg'];
            $result = $this->uploadFile('file', $allowedType);

            // Check the result and respond accordingly
            if ($result['status']) {
                // Successfully uploaded
                $img_path = $result['file_name']; // Get the unique file name
            } else {
                // Handle errors
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $result['error']
                ]);
            }
        }

        // =========== End of Upload file =========== //

        $data = [
            'certificate_id' => $document_id,
            'file_name' => $img_path
        ];

        $UploadCertificationModel = new UploadCertificationModel();

        // Check if there is an uploaded file on that certificate id
        $alreadyUploaded = $UploadCertificationModel->where('certificate_id', $document_id)->first();

        if ($alreadyUploaded) {
            // Update data;
            $isOkay = $UploadCertificationModel->set($data)->where('certificate_id', $document_id)->update();
        } else {
            // Insert data;
            $isOkay = $UploadCertificationModel->insert($data);
        }

        $response = isset($isOkay) ? ['success' => true] : ['success' => false];
        return $this->response->setJSON($response);
    }

    // DOWNLOAD FILE (RESIDENT ACCOUNT)
    public function download($file_name)
    {
        try {
            // Sanitize the file name to avoid directory traversal or malicious input
            $file_name = basename($file_name); // Ensures no directories are included in the file name

            // Define the path to the writable folder where your files are stored
            $file_path = WRITEPATH . 'uploads/' . $file_name; // Assuming you store files in the 'uploads' directory within writable

            // Check if the file exists
            if (file_exists($file_path)) {
                // Log activity
                $this->activityLogService->logActivity('Downloaded scanned document', session()->get("id"));

                // Set appropriate headers for the download
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
                header('Content-Length: ' . filesize($file_path));

                // Output the file content
                readfile($file_path);
                exit;
            } else {
                // Handle the case when the file doesn't exist
                throw new \Exception("Error: The requested file was not found.");
            }
        } catch (\Exception $e) {
            // Log the error
            error_log("Download Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // return redirect()->to('/error')->with('message', 'The file could not be found or downloaded.');
        }
    }

    // PRINT DOCUMENT > status = "ISSUED"
    public function print($id)
    {
        $CertificateModel = new CertificateModel();
        $certificate = $CertificateModel->find($id);

        // VERIFY IF THE USER IS EDITING HIS/HER BRGY JURISDICTION
        if ($certificate && $certificate->brgy_id !== $this->brgy_id) {
            return redirect()->to('certification');
        }


        // GET BARANGAY PROFILE
        $BrgyProfileModel = new BrgyProfileModel();
        $this_brgy = $BrgyProfileModel->where("brgy_id", $this->brgy_id)->first();
        if ($this_brgy) {
            // GET BARANGAY LOGO
            $logo = $this_brgy->logo ?? '';
            $this_brgy->logo = base_url('writable/uploads/' . $logo);
            // GET BARANGAY NAME
            $brgy_name = $this->getBrgyName($this_brgy->brgy_id);

            $output['brgy_name'] = $brgy_name;
            $output['brgy_profile'] = $this_brgy;

            // BARANGAY CAPTAIN DATA
            $brgy_captain_id = $this_brgy->official_id ?? '';
            $official_data = $this->getOfficialDataFromID($brgy_captain_id);
            $position_id = $official_data->position_id ?? '';
            $position = $this->getCategoryDescription($position_id);
            $brgy_captain = $official_data->fullname ?? '';

            $captain_data = [
                "name" => $brgy_captain,
                "position" => $position
            ];

            $output['captain_data'] = $captain_data;
        }

        // GET RESIDENT DATA
        if ($certificate) {
            // UPDATE CONTROL NO., REMOVE TMP
            $control_no = $certificate->control_no ?? '';
            $new_control_no = $this->removeTMP($control_no);
            $data = [
                "control_no" => $new_control_no
            ];
            $certificate_id = $certificate->id ?? '';
            $update_data = $new_control_no ? $CertificateModel->set($data)->where("id", $certificate_id)->update() : "";

            $resident_data = $this->getResidentDataFromID($certificate->res_id);

            // GET AGE
            $resident_data->age = $resident_data->age >= 18 ? "legal" : "under";
            // GET CSTATUS 
            $resident_data->cstatus_id = strtolower($this->getCategoryDescription($resident_data->cstatus_id));
            // GET GENDER
            $resident_data->gender = $resident_data->gender == "MALE" ? "him" : "her";

            $output['resident_data'] = $resident_data;
        }

        // GET DATE ISSUED OF THE CERTIFICATE
        $created_on = $certificate->created_on ?? date("m-d-Y");

        $date_issued = [
            "day" => $this->ordinalNumber(date("d", strtotime($certificate->created_on))),
            "month" => strtoupper(date("F", strtotime($certificate->created_on))),
            "year" => date("Y", strtotime($certificate->created_on))
        ];

        $output['date_issued'] = $date_issued;

        // GET PAYMENT DETAILS AND CTC DETAILS
        // if ($certificate->document_type == "BC" || $certificate->document_type == "BsC") {
            // PAYMENT DETAILS
            $payment_details = [
                'amount_paid' => isset($certificate->amount_paid) ? 'P' . number_format($certificate->amount_paid, 2) : '',
                'or_no' => $certificate->or_no ?? '',
                'or_date' => isset($certificate->or_date) ? $this->display_date($certificate->or_date) : ''
            ];
            $output['payment_details'] = $payment_details;

            // CTC DETAILS
            $ctc_details = [
                'ctc_no' => $certificate->ctc_no ?? '',
                'ctc_date' => isset($certificate->ctc_date) ? $this->display_date($certificate->ctc_date) : ''
            ];
            $output['ctc_details'] = $ctc_details;
        // }

        if ($certificate->status === "ISSUED") {
            $output['certificate'] = $certificate;

            // REDIRECT
            return view("administrator/certificate/printables", $output);
        } else {
            return redirect()->to('certification/');
        }
    }

    // GET CONTROL NUMBER
    public function getControlNo($docType = null)
    {
        if ($docType === null) {
            return false;
        }

        $CertificateModel = new CertificateModel();

        /**
         * FORMAT: $doctype + "-" + year + incremented value
         * ex: TMP-BC-2024-0000000001
         * REMOVE TMP WHEN ALREADY ISSUED
         */
        $currentYear = date('Y');
        $control_no = "TMP-" . $docType . "-" . $currentYear . "-";
        $ctr = 1;
        $concatenated_zeros = "";


        $count_rows = $CertificateModel
            ->where("document_type", $docType)
            ->where("YEAR(created_on)", $currentYear)
            ->countAllResults();

        if ($count_rows === 0) {
            $ctr = 1;
        } else {
            $ctr = $count_rows + 1;
        }

        if ($ctr > 0 && $ctr < 10) {
            $concatenated_zeros = "000000000" . $ctr;
        } else if ($ctr >= 10 && $ctr <= 99) {
            $concatenated_zeros = "00000000" . $ctr;
        } else if ($ctr >= 100 && $ctr <= 999) {
            $concatenated_zeros = "0000000" . $ctr;
        } else if ($ctr >= 1000 && $ctr <= 9999) {
            $concatenated_zeros = "000000" . $ctr;
        } else if ($ctr >= 10000 && $ctr <= 99999) {
            $concatenated_zeros = "00000" . $ctr;
        } else if ($ctr >= 100000 && $ctr <= 999999) {
            $concatenated_zeros = "0000" . $ctr;
        } else if ($ctr >= 1000000 && $ctr <= 9999999) {
            $concatenated_zeros = "000" . $ctr;
        } else if ($ctr >= 10000000 && $ctr <= 99999999) {
            $concatenated_zeros = "00" . $ctr;
        } else if ($ctr >= 100000000 && $ctr <= 999999999) {
            $concatenated_zeros = "0" . $ctr;
        } else if ($ctr >= 1000000000 && $ctr <= 9999999999) {
            $concatenated_zeros = $ctr;
        } else {
            $concatenated_zeros = "XXX";
        }

        $control_no .= $concatenated_zeros;

        return $control_no;
    }

    // REMOVE TMP FROM CONTROL_NO
    public function removeTMP($control_no = null)
    {
        if ($control_no === null) {
            return false;
        }

        $new_control_no = str_replace('TMP-', '', $control_no);

        return $new_control_no;
    }

    // GET ORDINAL NUMBERS
    public function ordinalNumber($number = null)
    {
        if ($number === null) {
            return false;
        }

        $ordinal = $number;

        if ($number == "1" || $number == "21" || $number == "31") {
            $ordinal .= "st";
        } else if ($number == "2" || $number == "22") {
            $ordinal .= "nd";
        } else if ($number == "3" || $number == "23") {
            $ordinal .= "rd";
        } else {
            $ordinal .= "th";
        }

        return $ordinal;
    }

    /**
     * PAYMENT
     */

    // GET PAYMENT DETAILS
    public function getDetails($id)
    {
        $CertificateModel = new CertificateModel();
        $certificate_data = $CertificateModel->find($id);

        if ($certificate_data) {
            $resident_data = $this->getResidentDataFromID($certificate_data->res_id);
            if ($resident_data) {
                $resident_name = $resident_data->fullname ?? '';
                $doc_name = $this->getDocName($certificate_data->document_type ?? '');
                $doc_fee = $certificate_data->document_fee ? number_format($certificate_data->document_fee, 2) : '';

                $output = [
                    'success' => true,
                    'id' => $certificate_data->id ?? '',
                    'resident_name' => $resident_name,
                    'document_type' => $doc_name,
                    'document_fee' => $doc_fee
                ];

                return $this->response->setJSON($output);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Resident not found'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Certificate not found'
            ]);
        }
    }

    // SAVE PAYMENT (WALK-IN)
    public function savePayment()
    {
        $post = $this->request->getPost();

        $id = $post['certification-id'] ?? '';
        $amount_paid = $post['amount-paid'] ?? '';
        $or_no = $post['or-no'] ?? '';
        $or_date = isset($post['or-no-date']) ? $this->save_date($post['or-no-date']) : '';

        // document_fee should be equal to amount_paid
        // unique OR no.
        $payment_status = "PAID";
        $payment_method = "CASH";
        $status = "FOR ISSUANCE";

        if ($id) {
            /**
             * PROCEED CHECKING DETAILS
             */
            $CertificateModel = new CertificateModel();
            $certificate_data = $CertificateModel->find($id);
            if ($certificate_data) {
                // DOUBLE CHECKING DETAILS IF STATUS = "FOR PAYMENT" AND BRGY_ID = $this->brgy_id
                if ($certificate_data->status == "FOR PAYMENT" && $certificate_data->brgy_id = $this->brgy_id) {
                    // DOCUMENT FEE SHOULD BE EQUAL TO AMOUNT PAID
                    $document_fee = isset($certificate_data->document_fee) ? number_format($certificate_data->document_fee, 2) : '';
                    $amount_paid = !empty($amount_paid) ? number_format($amount_paid, 2) : '';
                    if ($document_fee != $amount_paid) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Document fee should be equal to amount paid'
                        ]);
                    } else {
                        // CHECK UNIQUE OR_NO
                        $is_unique = $CertificateModel->where("or_no", $or_no)->countAllResults();
                        if ($is_unique > 0) {
                            // OR HAS ALREADY TAKEN
                            return $this->response->setJSON([
                                'success' => false,
                                'message' => 'O.R. Number already taken'
                            ]);
                        } else {
                            // SAVE PAYMENT
                            $data = [
                                'amount_paid' => $amount_paid,
                                'payment_status' => $payment_status,
                                'payment_method' => $payment_method,
                                'or_no' => $or_no,
                                'or_date' => $or_date,
                                'status' => $status
                            ];

                            $update_data = $CertificateModel->set($data)->where("id", $id)->update();
                            if ($update_data) {
                                return $this->response->setJSON([
                                    'success' => true,
                                    'message' => 'Payment successful'
                                ]);
                            } else {
                                return $this->response->setJSON([
                                    'success' => false,
                                    'message' => 'Error encountered while updating record'
                                ]);
                            }
                        }
                    }
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Certificate not found'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Certificate not found'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Certificate not found'
            ]);
        }
    }
}
