<?php

namespace App\Controllers;

use App\Models\BrgyCodeModel;
use CodeIgniter\Controller;

class BrgyCodeController extends BaseController
{

    public function index()
    {
        // CHECK ENCODING SCHEDULE
         $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') == "ADMIN") {
            return view("administrator/brgy_code/index", $output);
        } else if (session()->get('role') == "MAIN") {
            return view("main/brgy_code/index", $output);
        }
    }

    // // GET LIST OF BARANGAY CODE FOR TABLE DISPLAY
    public function getBarangayData()
    {
        $BrgyCodeModel = new BrgyCodeModel();

        try {
            $barangayData = $BrgyCodeModel->findAll();
            $data = [];

            foreach ($barangayData as $row) {
                $data[] = [
                    $row->id,
                    $row->brgy_name,
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
    public function saveBrgyCode()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'barangay' => 'required',
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

        // Get the BrgyCodeModel instance
        $BrgyCodeModel = new BrgyCodeModel();

        // Extract data from POST request
        $id = isset($postData['id']) && $postData['id'] !== '' ? $postData['id'] : null;
        $data = [
            'brgy_name' => strtoupper($postData['barangay']),
            'code' => strtoupper($postData['code']),
            'status' => strtoupper($postData['status'])
        ];

        // CHECK UNIQUENESS
        $passData = [
            'id' => $id,
            'brgy_name' => $data['brgy_name'],
            'code' => $data['code'],
        ];

        $isUnique = $this->isUnique_barangayCode($passData);
        if (!$isUnique) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['message' => 'Barangay Or Code already taken.']
            ]);
        }

        try {
            // If an ID is provided, update the barangay code; otherwise, insert a new barangay code
            if ($id) {
                $BrgyCodeModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated barangay: '. $data['brgy_name']. ', code: '. $data['code'] . ', status: '. $data['status'], session()->get("id"));
            } else {
                $BrgyCodeModel->insert($data);
                 // Log activity 
                 $this->activityLogService->logActivity('Added new barangay: '. $data['brgy_name']. ', code: '. $data['code'] . ', status: '. $data['status'], session()->get("id"));
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
    public function getBarangay($id)
    {
        $BrgyCodeModel = new BrgyCodeModel();
        $barangay = $BrgyCodeModel->find($id);

        if ($barangay) {
            return $this->response->setJSON($barangay);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'barangay not found'
            ]);
        }
    }

    // // CHECK UNIQUE isUnique_barangayCode
    public function isUnique_barangayCode($data)
    {
        $id = $data['id'];
        $brgy_name = $data['brgy_name'] ?? '';
        $code = $data['code'] ?? '';

        // load model
        $BrgyCodeModel = new BrgyCodeModel();

        $count_rows = 0;

        if ($id) {
            $count_rows = $BrgyCodeModel->where('id !=', $id)
                ->groupStart() // Start grouping conditions
                ->where('brgy_name', $brgy_name)
                ->orWhere('code', $code)
                ->groupEnd() // End grouping conditions
                ->countAllResults();

        } else {
            $count_rows = $BrgyCodeModel->where('brgy_name', $brgy_name)->orWhere('code', $code)->countAllResults();
        }

        $isUnique = $count_rows > 0 ? FALSE : TRUE;

        return $isUnique;
    }
}
