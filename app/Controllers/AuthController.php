<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\ResidentModel;

class AuthController extends BaseController
{

    // View login page
    public function index()
    {
        $session = session();
        $user = $session->get('isLoggedIn');
        $role = $session->get('role');

        if ($user) {
            if ($role === 'ADMIN') {
                return redirect()->to('administrator/dashboard');
            } elseif ($role === 'RESIDENT') {
                return redirect()->to('resident/dashboard');
            }
        }

        return view('login_page');
    }
    // View registration page
    public function registration_form()
    {
        return view('registration_form');
    }
    // Sign In
    public function authenticate()
    {
        if ($this->request->getMethod() == "POST") {
            helper(['form']);

            $post = $this->request->getPost(['username', 'password']);
            $login_model = new LoginModel();
            $session = session();

            // Fetch user by username
            $user = $login_model->where('username', $post['username'])->where('status', 'ACTIVE')->first();

            if ($user && password_verify($post['password'], $user->password)) {
                $user_role = $user->role;
                // SET USER SESSION
                $this->setUserSession($user);
                // REDIRECT USER DEPENDING ON USER ROLE ('ADMIN', 'RESIDENT', 'CAPTAIN', 'BRGY-ADMIN'); 
                switch ($user_role) {
                    case "ADMIN":
                        // Log activity 
                        $this->activityLogService->logActivity('User Logged In', session()->get("id"));
                        // Redirect User to respective dashboard
                        return redirect()->to('administrator/dashboard');
                        break;
                    case "RESIDENT":
                        // Log activity 
                        $this->activityLogService->logActivity('User Logged In', session()->get("id"));
                        // Redirect User to respective dashboard
                        return redirect()->to('resident/dashboard');
                        break;
                    case "MAIN":
                        // Log activity 
                        $this->activityLogService->logActivity('User Logged In', session()->get("id"));
                        // Redirect User to respective dashboard
                        return redirect()->to('main/dashboard');
                        break;
                    default:
                        return view("login_page");
                        break;
                }
            } else if ($user && session()->has('verification_code')) {
                // VERIFICATION CODE (FORGOT PASSWORD)
                $saved_code_data = session()->get('verification_code');
                // Check if the verification code has the same user id from the saved session.
                if ($saved_code_data['id'] === $user->id) {
                    // Verify if could still valid
                    $isValidCode = $this->verifyCode($post['password']);

                    if ($isValidCode['status'] === "success") {
                        // Successfully logged in
                        $user_role = $user->role;
                        // SET USER SESSION
                        $this->setUserSession($user);
                        // SET SESSION FOR USER THAT HAS VERIFICATION CODE
                        $this->hasVerificationCode();
                        // REDIRECT USER DEPENDING ON USER ROLE ('ADMIN', 'VERIFIER', 'ENCODER'); 
                        switch ($user_role) {
                            case "ADMIN":
                                // Log activity 
                                $this->activityLogService->logActivity('User Logged In', session()->get("id"));
                                // Redirect User to respective dashboard
                                return redirect()->to('administrator/dashboard');
                                break;
                            case "RESIDENT":
                                // Log activity 
                                $this->activityLogService->logActivity('User Logged In', session()->get("id"));
                                // Redirect User to respective dashboard
                                return redirect()->to('resident/dashboard');
                                break;
                            case "MAIN":
                                // Log activity 
                                $this->activityLogService->logActivity('User Logged In', session()->get("id"));
                                // Redirect User to respective dashboard
                                return redirect()->to('main/dashboard');
                                break;
                            default:
                                return view("login_page");
                                break;
                        }
                    } else {
                        $session->setFlashdata('invalid', $isValidCode['message']);
                        return view('login_page');
                    }
                }
            } else {
                $session->setFlashdata('invalid', 'Invalid username or password');
                return view('login_page');
            }
        } else {
            print_r('method is not post');
        }

        return view('login_page');
    }
    // Log Out
    public function logout()
    {
        // Log activity 
        $this->activityLogService->logActivity('User Logged Out', session()->get("id"));
        session()->destroy();
        return redirect()->to('login');
    }

    // REGISTER USER
    public function register_account()
    {
        $post = $this->request->getPost();

        $LoginModel = new LoginModel();
        $ResidentModel = new ResidentModel();

        // Extract input values with a fallback
        $data = [
            'username' => $post['username'] ?? '',
            'lname' => $post['lname'] ?? '',
            'fname' => $post['fname'] ?? '',
            'mname' => $post['mname'] ?? '',
            'suffix' => $post['suffix'] ?? '',
            'email' => $post['email'] ?? '',
        ];

        $role = "RESIDENT";
        $status = "PENDING";
        $brgy_id = "";
        $username = $data['username'];
        $email = $data['email'];
        // Generate default random password 
        $password = $this->generate_code();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $fullname = $this->formatFullname($data);

          /**
         * TODO LIST:
         * CHECK IF THE USERNAME ALREADY EXISTS
         * CHECK IF EMAIL IS ALREADY REGISTERED TO TBLUSER
         * CHECK IF THE RESIDENT NAME IS ALREADY REGISTERED TO TBLUSER
         * ===================================================
         * IF ALL THE ABOVE VERIFICATION ARE FALSE, THEN 
         * CHECK IF THE NAME OF THE REGISTRANTS ALREADY REGISTERED TO TBLRESIDENT
         * IF TRUE, GET THE HOUSEHOLD INFORMATION LIKE, BRGY_ID, RES_ID
         * IF FALSE, LEAVE BRGY_ID AND RES_ID BLANK
         */

        // Check username in the tbluser
        $user_data = $LoginModel->where('username', $username)->first();
        if ($user_data) {
            // username already exists
            session()->setFlashdata('invalid', "Username already registered");
            return redirect()->to('/register/registration_form')->withInput();
        }

        // Check if email already saved in the tbluser
        $user_data = $LoginModel->where('email', $email)->first();
        if ($user_data) {
            // username already exists
            session()->setFlashdata('invalid', "Email already registered");
            return redirect()->to('/register/registration_form')->withInput();
        }

        // Check if resident name already saved in the tbluser
        $user_data = $LoginModel->where('fullname', $fullname)->first();
          if ($user_data) {
              // username already exists
              session()->setFlashdata('invalid', "Resident already registered");
              return redirect()->to('/register/registration_form')->withInput();
          }

        // Check if resident exists
        $resident = $ResidentModel->where('fullname', $fullname)->first();

        if ($resident) {
            // IF TRUE, GET THE HOUSEHOLD INFORMATION LIKE, BRGY_ID, RES_ID
            // Get brgy id 
            $add_id = $resident->add_id ?? '';
            $brgy_data = $this->getBrgyDescription($add_id);
            $brgy_id = $brgy_data->id ?? '';
            // Prepare data for registration
            $insertData = [
                'username' => $data['username'],
                'password' => $hashedPassword,
                'lname' => $data['lname'],
                'fname' => $data['fname'],
                'mname' => $data['mname'],
                'suffix' => $data['suffix'],
                'fullname' => $fullname,
                'gender' => $resident->gender ?? '',
                'bday' => $resident->bday ?? '',
                'age' => $resident->age ?? '',
                'cstatus_id' => $resident->cstatus_id ?? '',
                'email' => $data['email'],
                'cp' => $resident->cp ?? '',
                'role' => $role,
                'brgy_id' => $brgy_id,
                'res_id' => $resident->id ?? '',
                'status' => $status,
                'image' =>$resident->img_path ?? '',
            ];

            if ($LoginModel->insert($insertData)) {
                // Send email notification
                $recipient = $insertData['email'];

                $msg = "<h1>Your account will be verified by the System Administrator</h1>
                <br>
                <p>We will send you another email notification regarding this.</p>
                <h4>Please use this login credential:</h4>
                <p>username: <b>$username</b></p>
                <p>default password: <b>$password</b></p>";
                $subject = "Login credentials";
                $emailSent = $this->send_email($data['email'], $msg, $subject);
                session()->setFlashdata('success', "Successfully registered account. Please check your email.");
                return redirect()->to('/register/registration_form'); // Redirect to clear the form
            } else {
                session()->setFlashdata('invalid', "Error occurred while registering account.");
            }
        } else {
            // USER NOT YET REGISTERED TO DATABASE (TBLRESIDENT)
            $insertData = [
                'username' => $data['username'],
                'password' => $hashedPassword,
                'lname' => $data['lname'],
                'fname' => $data['fname'],
                'mname' => $data['mname'],
                'suffix' => $data['suffix'],
                'fullname' => $fullname,
                'gender' => '',
                'bday' => '',
                'age' => '',
                'cstatus_id' => '',
                'email' => $data['email'],
                'cp' => '',
                'role' => $role,
                'brgy_id' => $brgy_id,
                'res_id' =>$resident->id ?? '',
                'status' => $status,
                'image' => $resident->img_path ?? '',
            ];

            if ($LoginModel->insert($insertData)) {
                // Send email notification
                $recipient = $insertData['email'];
                
                $msg = "<h1>Your account will be verified by the System Administrator</h1>
                <br>
                <p>We will send you another email notification regarding this.</p>
                <h4>Please use this login credential:</h4>
                <p>username: <b>$username</b></p>
                <p>default password: <b>$password</b></p>";
                $subject = "Login credentials";
                $emailSent = $this->send_email($recipient, $msg, $subject);
                session()->setFlashdata('success', "Successfully registered account. Please check your email.");
                return redirect()->to('/register/registration_form'); // Redirect to clear the form
            } else {
                session()->setFlashdata('invalid', "Error occurred while registering account.");
            }
        }

    }


    // SET USER SESSION
    public function setUserSession($user)
    {
        // GET HOUSEHOLD HEAD ID
        $ResidentModel = new ResidentModel();
        $resident_data = $ResidentModel->find($user->res_id);
        if ($resident_data) {
            $household_id = $resident_data->household ?? '';
            $household_head_data = $ResidentModel->where('isHead', 'TRUE')->where('household', $household_id)->first();

            $household_head_id = $household_head_data ? $household_head_data->id : '';
            $household_id = $household_head_data ? $household_head_data->household : '';
        }

        // GET BRGY LOGO IF AVAILABLE BASED ON THE BRGY_ID
        $brgy_logo = $user->brgy_id ? $this->getBrgyLogo($user->brgy_id) : null;

        // GET BRGY NAME 
        $brgy_name = $user->brgy_id ? $this->getBrgyName($user->brgy_id) : '';

        $data = [
            'id' => $user->id,
            'fname' => $user->fname,
            'username' => $user->username,
            'fullname' => $user->fullname,
            'res_id' => $user->res_id,
            'brgy_id' => $user->brgy_id,
            'img' => $user->image,
            'role' => $user->role,
            'household_head_id' => $household_head_id ?? '',
            'household_id' => $household_id ?? '',
            'user_brgy_logo' => $brgy_logo ?? base_url('public/assets/images/logo.png'),
            'my_brgy' => $brgy_name ?? ':)',
            'isLoggedIn' => true,
        ];

        session()->set($data);
    }

    // FORGOT PASSWORD
    public function forgot_password()
    {
        $post = $this->request->getPost();
        $email = $post['email'] ?? "";

        if ($email && !empty($email)) {
            // Check if email exists in the database
            $LoginModel = new LoginModel();
            $user = $LoginModel->where('email', $email)->first();
            if ($user) {
                // Email found
                $code = $this->generate_code();
                // Set verification code
                session()->set('verification_code', [
                    'id' => $user->id,
                    'code' => $code,
                    'created_at' => time()
                ]);
                // Send new password to the registered email
                $isSent = $this->send_email(
                    $email,
                    "<h2>Requested Code</h2>
                    <p>Use this code as your temporary password: <b>$code</b></p>
                    <p>Note: This code is valid only for five (5) minutes.</p>
                    <a href='". base_url('login') ."'>Click here</a>",
                    "Password Recovery"
                );

                if ($isSent) {
                    $message = "Please check your registered email for the code.";
                    session()->setFlashdata("success", $message);
                } else {
                    $message = "Error occurred sending code. Please try again later.";
                    session()->setFlashdata("invalid", $message);
                }
            } else {
                // Email not found
                session()->setFlashdata("invalid", "Email doesn't exists.");
            }
        } else {
            session()->setFlashdata("invalid", "Email is undefined.");
        }
        return redirect()->to('login/');
    }

    // VERIFY CODE IF STILL VALID
    public function verifyCode($verification_code)
    {
        // Retrieve verification code and creation time from session
        $saved_code_data = session()->get('verification_code');

        if ($saved_code_data && $saved_code_data['code'] === $verification_code) {
            // Check if code is still valid (within 5 minutes)
            $expiration_time = $saved_code_data['created_at'] + (5 * 60); // 5 minutes in seconds
            if (time() <= $expiration_time) {
                // Code is valid
                $status = "success";
                $message = "Verification successful!";
            } else {
                // Code expired
                $status = "failed";
                $message = "Verification code has expired. Please request a new one.";
            }
        } else {
            // Incorrect or missing code
            $status = "failed";
            $message = "Invalid verification code.";
        }

        $output = [
            'status' => $status,
            'message' => $message
        ];

        // return output
        return $output;
    }

    // SET SESSION THAT WILL TRIGGER AUTOMATIC DISPLAY OF CHANGE PASSWORD MODAL IF THE USER HAS VERIFICATION CODE
    public function hasVerificationCode()
    {
        $data = ['hasVerificationCode' => true];

        session()->set($data);
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
