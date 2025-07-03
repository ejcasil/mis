<?php

namespace App\Controllers;

use App\Models\LoginModel;
use CodeIgniter\Controller;

class UserController extends BaseController
{

    public function index()
    {
        $output['cstatus'] = $this->getListDescriptionBasedOnCategory('cstatus');

        $output['barangay'] = $this->getListOfBarangay();

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();
        
        if (session()->get('role') === "ADMIN") {
            return view("administrator/user-management/index", $output);
        } else if (session()->get('role') === "MAIN") {
            return view("main/user-management/index", $output);
        }
    }

    // GET LIST OF USERS FOR TABLE DISPLAY
    public function getUserData() {
        $LoginModel = new LoginModel();

        try {
            if (session()->get('role') === "ADMIN") {
                $userData = $LoginModel->where('brgy_id', $this->brgy_id)->findAll();
            } else if (session()->get('role') === "MAIN") {
                $userData = $LoginModel->findAll();
            }
            $data = [];

            foreach ($userData as $row) {
                $brgy_data = $this->getBrgyByID($row->brgy_id);
                $brgy = isset($brgy_data->brgy_name) ? $brgy_data->brgy_name : '';
                $data[] = [
                    $row->id,
                    $row->username,
                    $row->fullname,
                    $brgy,
                    $row->role,
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

    // SAVE DATA
    public function saveUser() {

        if (session()->get('role') !== "ADMIN" && session()->get('role') !== "MAIN") {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => 'Brgy Admin can only add account']);
        }

        helper(['form']);

        $role = "ADMIN";
        
        $res_id = "";

        // Define validation rules
        $rules = [
            'username' => 'required|alpha_numeric',
            'lname' => 'required|alpha_space|max_length[255]',
            'fname' => 'required|alpha_space|max_length[255]',
            'gender' => 'required',
            'bday' => 'required|valid_date[m-d-Y]',
            'cstatus' => 'required',
            'email' => 'required|valid_email',
            'cp' => 'required|numeric|min_length[10]|max_length[15]',
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

        // get barangay
        if (session()->get('role') === "ADMIN") {
            $brgy_id = $this->brgy_id;
        } else if (session()->get('role') === "MAIN") {
            $brgy_id = $postData['barangay'] ?? '';
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

        // FORMAT FULLNAME
        $fullname_data = [
            'lname' => $postData['lname'],
            'fname' => $postData['fname'],
            'mname' => $postData['mname'],
            'suffix' => $postData['suffix'],
        ];
        
        $fullname = $this->formatFullname($fullname_data);

        // USERNAME 
        $username = $postData['username'] ?? '';
        // GENERATE RANDOM PASSWORD
        $password = $this->generate_code();
        // Encrypt the generated string using bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Compute age
        $bday = isset($postData['bday']) ? $this->save_date($postData['bday']) : '';
        $age = $this->compute_age($bday);

        $data = [
            'username' => $username,
            'password' => $hashedPassword,
            'lname' => $postData['lname'] ?? '',
            'fname' => $postData['fname'] ?? '',
            'mname' => $postData['mname'] ?? '',
            'suffix' => $postData['suffix'] ?? '',
            'fullname' => $fullname,
            'gender' => $postData['gender'] ?? '',
            'bday' => $bday,
            'age' => $age,
            'cstatus_id' => $postData['cstatus'] ?? '',
            'email' => $postData['email'] ?? '',
            'cp' => $postData['cp'] ?? '',
            'role' => $role,
            'brgy_id' => $brgy_id,
            'res_id' => $res_id,
            'status' => $postData['status'] ?? '',
            'image' => $img_path,
        ];

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

        // CHECK UNIQUENESS EMAIL
        $passData = [
            'id' => $id,
            'email' => $data['email'] ?? '',
        ];

        $isUnique = $this->isUnique($passData);
        if (!$isUnique) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => 'Email already taken.'
            ]);
        }

        // MAXIMUM ACTIVE ADMIN ACCOUNT PER BRGY IS THREE (3) ONLY
        $passData = [
            'id' => $id,
            'status' => $data['status']
        ];
        $isValid = $this->chk_maximum_admin($passData);
        if (!$isValid) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => 'Maximum admin account already in its limit.'
            ]);
        }

        try {
            // If an ID is provided, update the barangay code; otherwise, insert a new barangay code
            if ($id) {
                $LoginModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated admin user with a username of: ' . $data['username'], session()->get("id"));
            } else {
                $LoginModel->insert($data);
                // Log activity 
                $this->activityLogService->logActivity('Added admin user with a username of: ' . $data['username'], session()->get("id"));
                // Send email
                $recipient = $data['email'] ?? '';
                $msg = "<h1>You have successfully gained an access to Web-based BIS.</h1>
                <br>
                <h4>Please use this login credential:</h4>
                <p>username: <b>$username</b></p>
                <p>default password: <b>$password</b></p>";
                $subject = "Login credentials";
                $emailSent = $this->send_email($data['email'],$msg, $subject);
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // GET USER DATA FOR UPDATE
    public function getUser($id) {
        $LoginModel = new LoginModel();
        $data = $LoginModel->find($id);

        // Check if the official was found
        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'User not found'
            ]);
        }
    }

    // APPROVE 
    public function approve($id) {
        if ($id) {
            $LoginModel = new LoginModel();
            $user = $LoginModel->find($id);
            $status = "ACTIVE";

            if ($user) {
                // GET DATA
                $username = $user->username ?? '';
                $email = $user->email ?? '';
                // UPDATE DATA
                $update_data = [
                    'status' => $status
                ];

                $update = $LoginModel->set($update_data)->where("id", $id)->update();
                // ACTIVITY LOG
                $this->activityLogService->logActivity('Approved user account with a username of: ' . $username, session()->get("id"));
                // Send email
                $recipient = $email;
                $msg = "<h1>Your account was being approved!</h1>
                <br>
                <h4>You can now use your login credentials.</h4>";
                $subject = "Account Approved";
                $emailSent = $this->send_email($email,$msg, $subject);

                return redirect()->to('/user_management/');
            }
        }
    }

    // DECLINE
    public function decline($id) {
        if ($id) {
            $LoginModel = new LoginModel();
            $user = $LoginModel->find($id);
            $status = "DECLINED";

            if ($user) {
                // GET DATA
                $username = $user->username ?? '';
                $email = $user->email ?? '';
                // UPDATE DATA
                $update_data = [
                    'status' => $status
                ];

                $update = $LoginModel->set($update_data)->where("id", $id)->update();
                // ACTIVITY LOG
                $this->activityLogService->logActivity('Declined user account with a username of: ' . $username, session()->get("id"));
                // Send email
                $recipient = $email;
                $msg = "<h1>Your account was being declined!</h1>
                <br>
                <h4>Please contact your barangay official for this matter.</h4>";
                $subject = "Account Declined";
                $emailSent = $this->send_email($email,$msg, $subject);

                return redirect()->to('/user_management/');
            }
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

    // CHECK IF IT MEANS MAXIMUM ACTIVE ADMIN ACCOUNTS
    private function chk_maximum_admin($data) {
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? '';
        $active = "ACTIVE";

        // Get the instance
        $LoginModel = new LoginModel();

        // Initialize count_rows
        $count_rows = 0;

        if (!empty($status) && $status === $active) {
            if ($id) {
                $count_rows = $LoginModel
                ->where('status', $status)
                ->where('id !=', $id)
                ->countAllResults();
            } else {
                $count_rows = $LoginModel
                ->where('status', $status)
                ->countAllResults();
            }
        }

        // Check if the count is greater than 0
        $isValid = $count_rows <= 3; // TRUE if unique

        return $isValid;
    }
}
