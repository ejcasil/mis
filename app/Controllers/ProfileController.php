<?php

namespace App\Controllers;

use App\Models\LoginModel;

class ProfileController extends BaseController
{
    public function profile()
    {
        $id = session()->get('id');
        $LoginModel = new LoginModel();
        $data = $LoginModel->find($id);

        // Check if the official was found
        if ($data) {
            $output['profile'] = $data;
        }

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();


        if (session()->get('role') === "ADMIN") {
            return view('administrator/profile/my-profile', $output);
        } else if (session()->get('role') === "MAIN") { 
            return view('main/profile/my-profile', $output);
        } else {
            return view('resident/profile/my-profile', $output);
        }
        
    }

    public function saveUser()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'username' => 'required|alpha_numeric',
            'old_password' => 'required',
            'new_password' => 'required',
            'rnew_password' => 'required'
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
        $LoginModel = new LoginModel();

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

        // USERNAME 
        $username = $postData['username'] ?? '';
        // VERIFY OLD PASSWORD
        $old_password = $postData['old_password'] ?? '';
        $user_data = $LoginModel->find($id);
        $db_password = $user_data->password ? $user_data->password : '';

        if (password_verify($old_password, $db_password) || session()->has('verification_code')) {
            // Verify if new_password = confirm_password
            $new_password = $postData['new_password'] ?? '';
            $rnew_password = $postData['rnew_password'] ?? '';
            if ($new_password !== $rnew_password) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => 'Password mismatch'
                ]);
            }

            // Regular expression pattern to check:
            $pattern = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_]).{10,}$/';

            if (!preg_match($pattern, $new_password)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => 'Password must contain alphanumeric, symbol, and atleast 10 characters long'
                ]);
            }



            // Encrypt the generated string using bcrypt
            $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

            // CHECK UNIQUENESS USERNAME
            $passData = [
                'id' => $id,
                'username' => $data['username'] ?? '',
            ];

            $isUnique = $this->isUnique($passData);
            if (!$isUnique) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => 'Username already taken.'
                ]);
            }

            $data = [
                'username' => $username,
                'password' => $hashedPassword,
                'image' => $img_path,
            ];

            try {
                // If an ID is provided, update the barangay code; otherwise, insert a new barangay code
                if ($id) {
                    $LoginModel->update($id, $data);
                    // Log activity 
                    $this->activityLogService->logActivity('Updated account', session()->get("id"));
                }

                return $this->response->setJSON(['success' => true]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'errors' => 'Old password is incorrect'
            ]);
        }
    }

    // CHECK UNIQUENESS
    private function isUnique($data)
    {
        $id = $data['id'] ?? null; // Handle case when 'id' might not be set
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';

        // Load model
        $LoginModel = new LoginModel();

        // Initialize count_rows
        $count_rows = 0;

        // CHECK USERNAME
        if (!empty($username)) {
            if ($id) {
                $count_rows = $LoginModel
                    ->where('username', $username)
                    ->where('id !=', $id)
                    ->countAllResults();
            } else {
                $count_rows = $LoginModel
                    ->where('username', $username)
                    ->countAllResults();
            }
        }

        // CHECK EMAIL
        if (!empty($email)) {
            if ($id) {
                $count_rows = $LoginModel
                    ->where('email', $email)
                    ->where('id !=', $id)
                    ->countAllResults();
            } else {
                $count_rows = $LoginModel
                    ->where('email', $email)
                    ->countAllResults();
            }
        }

        // Check if the count is greater than 0
        $isUnique = $count_rows === 0; // TRUE if unique

        return $isUnique;
    }
}
