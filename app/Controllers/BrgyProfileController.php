<?php

namespace App\Controllers;

use App\Models\BrgyProfileModel;
use App\Models\BrgyCodeModel;
use App\Models\OfficialModel;

class BrgyProfileController extends BaseController
{

    public function index()
    {
        // Display existing barangay profile
        $BrgyProfileModel = new BrgyProfileModel();
        $data = $BrgyProfileModel->where('brgy_id', $this->brgy_id)->first();

        $output = [];

        if ($data && isset($data->id)) {
            // Get barangay description
            $brgy_id = $data->brgy_id;
            $BrgyCodeModel = new BrgyCodeModel();
            $barangay = $BrgyCodeModel->find($brgy_id);

            if ($barangay && isset($barangay->brgy_name)) {
                // Check logo if exists
                $logo_path = isset($data->logo) ? $data->logo : "";
                $isLogoExists = $this->checkFile($logo_path);
                $new_logo_path = ($isLogoExists) ? base_url('writable/uploads/' . $logo_path) : base_url('public/assets/images/bangan-logo.png');

                // Get official name based on ID
                $official_id = $data->official_id ?? '';
                $officialModel = new OfficialModel();
                $officialData = $officialModel->find($official_id);

                $profile = array(
                    "id" => $data->id,
                    "logo" => $new_logo_path,
                    "brgy_id" => $data->brgy_id,
                    "brgy_name" => $barangay->brgy_name,
                    "municipality" => $data->municipality,
                    "province" => $data->province,
                    "region" => $data->region,
                    "official_id" => $officialData->id ?? '',
                    "official_name" => $officialData->fullname ?? ''
                );
                $output['profile'] = $profile;
            }
        }

        // Gather list of barangays
        $BrgyCodeModel = new BrgyCodeModel();
        $output['barangay'] = $BrgyCodeModel->where('id', $this->brgy_id)->where("status", "ACTIVE")->findAll();

        // Gather list of officials
        $OfficialModel = new OfficialModel();
        $output['official'] = $OfficialModel->where('brgy_id', $this->brgy_id)->findAll();

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        return view('administrator/brgy_profile/index', $output);
    }

    // Get barangay profile for update
    public function getProfile()
    {
        $BrgyProfileModel = new BrgyProfileModel();
        $profile = $BrgyProfileModel->where('brgy_id', $this->brgy_id)->first();

        if ($profile) { // Check if profile is not null
            return $this->response->setJSON([
                'success' => true,
                'data' => $profile
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Profile not found'
            ]);
        }
    }

      // // SAVE DATA
      public function saveProfile()
      {
          helper(['form']);
  
          // Define validation rules
          $rules = [
              'municipality' => 'required',
              'province' => 'required',
              'region' => 'required',
              'official' => 'required'
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
  
          // Get the model instance
          $BrgyProfileModel = new BrgyProfileModel();
  
          // Extract data from POST request
          $id = isset($postData['id']) && $postData['id'] !== '' ? $postData['id'] : null;
  
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
              'logo' => $img_path,
              'brgy_id' => ($postData['barangay']) ? $postData['barangay'] : '',
              'municipality' => strtoupper($postData['municipality']),
              'province' => strtoupper($postData['province']),
              'region' => strtoupper($postData['region']),
              'official_id' => ($postData['official']) ? $postData['official'] : '',
          ];
  
          try {
              // If an ID is provided, update the barangay profile
              if ($id) {
                  $BrgyProfileModel->update($id, $data);
                  // Log activity 
                  $this->activityLogService->logActivity('Updated barangay profile', session()->get("id"));
              } else {
                  $BrgyProfileModel->insert($data);
                   // Log activity 
                   $this->activityLogService->logActivity('Added new barangay profile', session()->get("id"));
              }

              // Fetch updated profile
              $BrgyProfileModel = new BrgyProfileModel();
              $data = $BrgyProfileModel->first();
      
              $output = [];
      
              if ($data && isset($data->id)) {
                  // Get barangay description
                  $brgy_id = $data->brgy_id;
                  $BrgyCodeModel = new BrgyCodeModel();
                  $barangay = $BrgyCodeModel->find($brgy_id);
      
                  if ($barangay && isset($barangay->brgy_name)) {
                      // Check logo if exists
                      $logo_path = isset($data->logo) ? $data->logo : "";
                      $isLogoExists = $this->checkFile($logo_path);
                      $new_logo_path = ($isLogoExists) ? base_url('writable/uploads/' . $logo_path) : base_url('public/assets/images/bangan-logo.png');
      
                      // Get official name based on ID
                      $official_id = isset($data->official_id) ? $data->official_id : '';
                      $officialModel = new OfficialModel();
                      $officialData = $officialModel->find($official_id);
      
                      $profile = array(
                          "id" => $data->id,
                          "logo" => $new_logo_path,
                          "brgy_id" => $data->brgy_id,
                          "brgy_name" => $barangay->brgy_name,
                          "municipality" => $data->municipality,
                          "province" => $data->province,
                          "region" => $data->region,
                          "official_id" => ($officialData->id) ? $officialData->id : '',
                          "official_name" => ($officialData->fullname) ? $officialData->fullname : ''
                      );
                  }
              }
  
              return $this->response->setJSON(['success' => true, 'data' => $profile ]);
          } catch (\Exception $e) {
              return $this->response->setJSON([
                  'success' => false,
                  'error' => $e->getMessage()
              ]);
          }
      }

}
