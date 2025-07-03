<?php

namespace App\Controllers;

use App\Models\OfficialModel;
use App\Models\CategoryModel;
use CodeIgniter\Controller;
use App\Models\BrgyProfileModel;

class OfficialController extends BaseController
{

    public function index()
    {
        // Get list of position from category
        $positions = $this->getListDescriptionBasedOnCategory('position');

        $output['position'] = $positions;

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        return view("/administrator/official/index", $output);
    }

    // // GET LIST OF BARANGAY OFFICIAL FOR TABLE DISPLAY
    public function getOfficialData()
    {
        $OfficialModel = new OfficialModel();

        try {
            $officialData = $OfficialModel->where('brgy_id', $this->brgy_id)->findAll();
            $data = [];

            foreach ($officialData as $row) {
                //Get position description
                $position_id = $row->position_id;
                $position = $this->getCategoryDescription($position_id);

                $data[] = [
                    $row->id,
                    $row->fullname,
                    $position,
                    $row->term
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

    // // SAVE DATA
    public function saveOfficial()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'lname' => 'required',
            'fname' => 'required',
            'bday' => 'required',
            'email' => 'required|valid_email',
            'cp' => 'required',
            'term' => 'required',
            'position' => 'required'
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

        // Get the OfficialModel instance
        $OfficialModel = new OfficialModel();

        // Extract data from POST request
        $id = isset($postData['id']) && $postData['id'] !== '' ? $postData['id'] : null;

        // Format fullname
        $raw_fullname = [
            "lname" => isset($postData['lname']) ? $postData['lname'] : "",
            "fname" => isset($postData['fname']) ? $postData['fname'] : "",
            "mname" => isset($postData['mname']) ? $postData['mname'] : "",
            "suffix" => isset($postData['suffix']) ? $postData['suffix'] : ""
        ];

        $fullname = $this->formatFullname($raw_fullname); // format fullname
        $bday = $this->save_date($postData['bday']); // format bday

        // =========== Upload file =========== //
        /*
            if existing file is not empty and there was an uploaded file
            then check if the file still exists, if true, unlink it and upload the new file

        */

        $img_path = isset($postData['img_path']) ? $postData['img_path'] : "";
        
        $file = $this->request->getFile('image');

        if ($this->request->getFile('image') !== null && $file->isValid()) {
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
            $result = $this->uploadFile('image',$allowedType);

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
            'lname' => strtoupper($postData['lname']),
            'fname' => strtoupper($postData['fname']),
            'mname' => strtoupper($postData['mname']),
            'suffix' => strtoupper($postData['suffix']),
            'fullname' => $fullname,
            'bday' => $bday,
            'age' => $this->compute_age($bday),
            'email' => $postData['email'],
            'cp' => $postData['cp'],
            'img_path' => $img_path,
            'position_id' => $postData['position'],
            'term' => $postData['term'],
            'brgy_id' => $this->brgy_id
        ];

        // CHECK UNIQUENESS
        $passData = [
            'id' => $id,
            'fullname' => $fullname,
            'term' => $postData['term'],
        ];

        $isUnique = $this->isUnique($passData);
        if (!$isUnique) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => 'Barangay Official and corresponding term already taken.'
            ]);
        }

        // CHECK IF THE SELECTED TERM HAS ALREADY CAPTAIN
        $chkCaptain = [
            'position_id' => $data['position_id'],
            'id' => $id,
            'term' => $data['term']
        ];

        $alreadyHaveCaptain = $this->alreadyHaveCaptain($chkCaptain);

        if ($alreadyHaveCaptain) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => 'Selected term has already brgy captain.'
            ]);
        }


        try {
            // If an ID is provided, update the barangay code; otherwise, insert a new barangay code
            if ($id) {
                $OfficialModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated barangay official: '. $data['fullname'], session()->get("id"));
            } else {
                $OfficialModel->insert($data);
                 // Log activity 
                 $this->activityLogService->logActivity('Added new barangay official: '. $data['fullname'], session()->get("id"));
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // // GET DATA FOR UPDATE
    public function getOfficial($id)
    {
        $OfficialModel = new OfficialModel();
        $official = $OfficialModel->find($id);
        
        // Check if the official was found
        if ($official) {
            // Format the birthday if it exists
            $official = (array)$official; // Converts to array if necessary
            $official['bday'] = $this->display_date($official['bday']);
        
            return $this->response->setJSON($official);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Barangay official not found'
            ]);
        }
        
    }

    // // CHECK UNIQUE 
    private function isUnique($data)
    {
        $id = $data['id'];
        $fullname = $data['fullname'] ?? '';
        $term = $data['term'] ?? '';

        // load model
        $OfficialModel = new OfficialModel();

        $count_rows = 0;

        if ($id) {
            $count_rows = $OfficialModel->where('id !=', $id)
                ->groupStart() // Start grouping conditions
                ->where('fullname', $fullname)
                ->where('term', $term)
                ->groupEnd() // End grouping conditions
                ->countAllResults();
        } else {
            $count_rows = $OfficialModel->where('fullname', $fullname)->where('term', $term)->countAllResults();
        }

        $isUnique = $count_rows > 0 ? FALSE : TRUE;

        return $isUnique;
    }

    // CHECK IF THE SELECTED TERM HAS ALREADY BRGY CAPTAIN
    private function alreadyHaveCaptain($data) {
        $position_id = $data['position_id'] ?? '';
        $term = $data['term'] ?? '';
        $id = $data['id'] ?? '';

        // Get the official id of the current captain
        $BrgyProfileModel = new BrgyProfileModel();
        $brgy_id = $this->brgy_id; // Or use dynamic value for `brgy_id`
        $current_captain = $BrgyProfileModel->where('brgy_id', $brgy_id)->first();

        // Check if the captain exists
        if (!$current_captain) {
            // throw new \Exception("No captain found for Barangay ID: $brgy_id");
        }

        // Get the captain's id (Ensure this is the correct field name)
        $current_captain_id = $current_captain->official_id ?? '';

        // Get the position of the current captain
        $OfficialModel = new OfficialModel();
        $captain_data = $OfficialModel->find($current_captain_id);
        $current_captain_position_id = $captain_data->position_id ?? '';

        if ($current_captain_position_id === $position_id) {
            // The selected position is captain
            if ($id) {
                // If no id is provided, check all records excluding the current id
                $count_rows = $OfficialModel->where('id !=', $id)->where('term', $term)->where('position_id', $position_id)->first();

            } else {
                // If no id is provided, check all records
                $count_rows = $OfficialModel->where('term', $term)->where('position_id', $position_id)->first();
            }

            return $count_rows ? true : false; // if true, already have captain

        } else {
            // The selected position is not captain
            return false;
        }
    }

}
