<?php

namespace App\Controllers;

use App\Models\BannerModel;
use CodeIgniter\Controller;

class BannerController extends BaseController
{

    public function index()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();
        return view("/administrator/banner/index", $output);
    }

    // GET LIST OF BANNERS FOR TABLE DISPLAY
    public function getBannerData()
    {
        $BannerModel = new BannerModel();

        try {
            $bannerData = $BannerModel->where('brgy_id', $this->brgy_id)->findAll();
            $data = [];

            foreach ($bannerData as $row) {
                // Check if img exists
                $img_path = $row->img_path ?? '';
                $isExists = $this->checkFile($img_path);
                $img = base_url('public/assets/images/logo.png');
                if ($isExists) {
                    $img = base_url('writable/uploads/' . $img_path);
                }

                $data[] = [
                    $row->id,
                    $row->title,
                    $row->description,
                    $row->img_path,
                    $row->status,
                    $img
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
    // SAVE DATA
    public function saveBanner()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'title' => 'required',
            'description' => 'required'
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

        // Get the instance
        $BannerModel = new BannerModel();

        // Extract data from POST request
        $id = $postData['id'] ?? '';

        // =========== Upload file =========== //
        /*
            if existing file is not empty and there was an uploaded file
            then check if the file still exists, if true, unlink it and upload the new file

        */

        $img_path = $postData['img_path'] ?? "";

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
            $result = $this->uploadFile('image', $allowedType);

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
            'title' => strtoupper($postData['title']),
            'description' => strtoupper($postData['description']),
            'img_path' => $img_path,
            'status' => $postData['status'],
            'brgy_id' => $this->brgy_id
        ];

        // CHECK UNIQUENESS
        $passData = [
            'id' => $id,
            'title' => $data['title'] ?? '',
        ];

        $isUnique = $this->isUnique($passData);
        if (!$isUnique) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => 'Title already taken.'
            ]);
        }

        try {
            // If an ID is provided, update the barangay code; otherwise, insert a new barangay code
            if ($id) {
                $BannerModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated banner with title: ' . $data['title'], session()->get("id"));
            } else {
                $BannerModel->insert($data);
                // Log activity 
                $this->activityLogService->logActivity('Added new banner with title: ' . $data['title'], session()->get("id"));
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
    public function getBanner($id)
    {
        $BannerModel = new BannerModel();
        $banner = $BannerModel->find($id);

        // Check if the official was found
        if ($banner) {
            return $this->response->setJSON($banner);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Banner not found'
            ]);
        }
    }

    // CHECK UNIQUENESS
    private function isUnique($data)
    {
        $id = $data['id'] ?? null; // Handle case when 'id' might not be set
        $title = $data['title'] ?? '';

        // Load model
        $BannerModel = new BannerModel();

        // Initialize count_rows
        $count_rows = 0;

        if ($id) {
            // Use ->where() correctly for chaining
            $count_rows = $BannerModel
                ->where('title', $title)
                ->where('id !=', $id)
                ->countAllResults();
        } else {
            $count_rows = $BannerModel
                ->where('title', $title)
                ->countAllResults();
        }

        // Check if the count is greater than 0
        $isUnique = $count_rows === 0; // TRUE if unique

        return $isUnique;
    }
}
