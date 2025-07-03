<?php

namespace App\Controllers;

use App\Models\ZpCodeModel;
use App\Models\BrgyCodeModel;
use CodeIgniter\Controller;

class PurokZoneCodeController extends BaseController
{

    public function index()
    {
        // Get list of barangays
        $BrgyCodeModel = new BrgyCodeModel();

        // CHECK ENCODING SCHEDULE
         $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') == "ADMIN") {
            $output['barangay'] = $BrgyCodeModel->where('id', $this->brgy_id)->where('status', 'ACTIVE')->findAll();
            return view("administrator/zp_code/index", $output);
        } else if (session()->get('role') == "MAIN") {
            $output['barangay'] = $BrgyCodeModel->where('status', 'ACTIVE')->findAll();
            return view("main/zp_code/index", $output);
        }
        
    }

    // // GET LIST OF BARANGAY CODE FOR TABLE DISPLAY
    public function getPurokZoneData()
    {
        $ZpCodeModel = new ZpCodeModel();
        $BrgyCodeModel = new BrgyCodeModel();

        try {
            if (session()->get('role') == "ADMIN") {
                $purokData = $ZpCodeModel->where('brgy_id', $this->brgy_id)->findAll();
            } else if (session()->get('role') == "MAIN") {
                $purokData = $ZpCodeModel->findAll();
            }
            $data = [];

            foreach ($purokData as $row) {
                // Get barangay description based on brgy_id
                $barangay = "";
                $brgy_data = $BrgyCodeModel->where('id', $row->brgy_id)->first();
                if ($brgy_data && isset($brgy_data->brgy_name)) {
                     $barangay = $brgy_data->brgy_name;
                }
                $data[] = [
                    $row->id,
                    $barangay,
                    $row->description,
                    $row->code,
                    $row->status
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
    public function savePurokZoneCode()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'barangay' => 'required',
            'purok_zone' => 'required',
            'code' => 'required',
            'status' => 'required'
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

        // Get the ZpCodeModel instance
        $ZpCodeModel = new ZpCodeModel();

        // Extract data from POST request
        $id = isset($postData['id']) && $postData['id'] !== '' ? $postData['id'] : null;
        $data = [
            'brgy_id' => $postData['barangay'],
            'description' => strtoupper($postData['purok_zone']),
            'code' => strtoupper($postData['code']),
            'status' => strtoupper($postData['status'])
        ];

        // CHECK UNIQUENESS
        $passData = [
            'id' => $id,
            'brgy_id' => $data['brgy_id'],
            'description' => $data['description'],
            'code' => $data['code']
        ];

        $isUnique = $this->isUnique_purokCode($passData);

        if (!$isUnique) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['message' => 'Purok/Zone Name Or Code already taken.']
            ]);
        }

        try {
            // If an ID is provided, update the barangay code; otherwise, insert a new barangay code
            if ($id) {
                $ZpCodeModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated barangay id: ' . $data['brgy_id'] . ', purok/zone name: ' . $data['description'] . ', code: ' . $data['code'] . ', status: ' . $data['status'], session()->get("id"));
            } else {
                $ZpCodeModel->insert($data);
                // Log activity 
                $this->activityLogService->logActivity('Added new barangay id: ' . $data['brgy_id'] . ', purok/zone name: ' . $data['description'] . ', code: ' . $data['code'] . ', status: ' . $data['status'], session()->get("id"));
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
    public function getPurokZoneCode($id)
    {
        $ZpCodeModel = new ZpCodeModel();
        $data = $ZpCodeModel->find($id);

        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'purok/zone not found'
            ]);
        }
    }

    // // CHECK UNIQUE isUnique_purokCode
    public function isUnique_purokCode($data)
    {
        // Log the incoming data for debugging
        log_message('debug', print_r($data, true));
    
        // Safely retrieve data from the input array
        $id = $data['id'] ?? null;
        $brgy_id = $data['brgy_id'] ?? null;
        $description = $data['description'] ?? '';
        $code = $data['code'] ?? '';
    
        // Load the model
        $ZpCodeModel = new ZpCodeModel();
    
        // Start building the query
        $builder = $ZpCodeModel->builder();
    
        // Start with the base condition for brgy_id
        $builder->where('brgy_id', $brgy_id);
    
        // Exclude the specified ID if it exists
        if ($id) {
            $builder->where('id !=', $id);
        }
    
        // Add conditions for description and code
        $builder->groupStart()
            ->where('description', $description)
            ->orWhere('code', $code)
            ->groupEnd();
    
        // Count the results
        $count_rows = $builder->countAllResults();
    
        return $count_rows === 0; // Return true if unique (count is zero)
    }
    
}
