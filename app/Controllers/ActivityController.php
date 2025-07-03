<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\LoginModel;

class ActivityController extends BaseController
{

    public function index() {
        // Fetch all distinct user IDs from activities
        $activityModel = new ActivityModel();
        $listOfUsers = $activityModel->distinct()->select('user_id')->findAll();
          // Loop through distinct user IDs and fetch corresponding usernames
        foreach ($listOfUsers as $user) {
            $user_id = $user->user_id;
            // Get user info
            $loginModel = new LoginModel();
            $user_data = $loginModel->where('id', $user_id)->first();
            $username = isset($user_data->username) ? $user_data->username : '';

            // Store user ID and username in $dbUsers array
            $dbUsers[] = ['id' => $user_id, 'username' => $username];
        }

        $output['users'] = $dbUsers;

        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') == "ADMIN") {
            return view('administrator/activity/index', $output);
        } else if (session()->get('role') == "MAIN") {
            return view('main/activity/index', $output);
        }
    }

    public function getActivities() {
        $activityModel = new ActivityModel();

        try {
            $activityData = $activityModel->orderBy('created_on', 'DESC')->findAll();
            $data = [];

            foreach ($activityData as $row) {

                $user_id = $row->user_id;
                $task_done = $row->task_done;
                $created_on = $row->created_on;

                $loginModel = new LoginModel();

                // GET USER INFO
                $user_data = $loginModel->where('id', $user_id)->first();
                $username = isset($user_data->username) ? $user_data->username : '';
                $fullname = isset($user_data->fullname) ? $user_data->fullname : '';
                $role = isset($user_data->role) ? $user_data->role : '';

                $data[] = [
                    $username,
                    $fullname,
                    $task_done,
                    $role,
                    $created_on
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

    public function filter() {
        $post = $this->request->getPost();
        $user_id = isset($post['username']) ? $post['username'] : '';
        $date_from = isset($post['date-from']) ? $this->save_date($post['date-from']) : '';
        $date_to = isset($post['date-to']) ? $this->save_date($post['date-to']) : '';

        // Query database
        $activityModel = new ActivityModel();
        // Check if $user_id is not empty else select all user_id likewise to $date_from and $date_to
        // Construct the query
        $query = $activityModel;

        // Check if $user_id is not empty else select all user_id likewise to $date_from and $date_to
        if (!empty($date_from) && !empty($date_to)) {
            $query->where('created_on >=', $date_from)
                ->where('created_on <=', $date_to);
        }

        // Check if $user_id is not empty
        if (!empty($user_id)) {
            $query->where('user_id', $user_id);
        }

        // Execute the query
        $data = $query->orderBy('created_on', 'DESC')->findAll();

        $responseData = [];

        if ($data && is_array($data) && count($data) > 0) {
            foreach ($data as $row) {
                $user_id = $row->user_id;
                $task_done = $row->task_done;
                $created_on = $row->created_on;

                // Get user info
                $loginModel = new LoginModel();
                $userInfo = $loginModel->where("id", $user_id)->first();
                $username = isset($userInfo->username) ? $userInfo->username : "";
                $name = isset($userInfo->fullname) ? $userInfo->fullname : "";
                $role = isset($userInfo->role) ? $userInfo->role : "";

                // Build data array
                $responseData[] = [
                    $username,
                    $name,
                    $task_done,
                    $role,
                    $created_on
                ];
            }
        } 

        return $this->response->setJSON([
            'data' => $responseData
        ]);
    }

    public function download()
    {
        // Log activity 
        $this->activityLogService->logActivity('Downloaded activity logs',session()->get("id"));
        
        $post = $this->request->getPost();
    
        $user_id = isset($post['username']) ? $post['username'] : "";
        $date_from = isset($post['from']) ? $this->save_date($post['from']) : "";
        $date_to = isset($post['to']) ? $this->save_date($post['to']) : "";
    
        $activityModel = new ActivityModel();
        // Check if $user_id is not empty else select all user_id likewise to $date_from and $date_to
        // Construct the query
        $query = $activityModel;

        // Check if $user_id is not empty else select all user_id likewise to $date_from and $date_to
        if (!empty($date_from) && !empty($date_to)) {
            $query->where('created_on >=', $date_from)
                ->where('created_on <=', $date_to);
        }

        // Check if $user_id is not empty
        if (!empty($user_id)) {
            $query->where('user_id', $user_id);
        }

        // Execute the query
        $data = $query->findAll();
    
        $responseData = [];
    
        if ($data && is_array($data) && count($data) > 0) {
            foreach ($data as $row) {
                $user_id = $row->user_id;
                $task_done = $row->task_done;
                $created_on = $this->display_date($row->created_on);
    
                // Get user info
                $loginModel = new LoginModel();
                $userInfo = $loginModel->where("id", $user_id)->first();
                $username = isset($userInfo->username) ? $userInfo->username : "";
                $name = isset($userInfo->fullname) ? $userInfo->fullname : "";
                $role = isset($userInfo->role) ? $userInfo->role : "";
    
                // Build data array
                $responseData[] = [
                    'Username' => $username,
                    'Name' => $name,
                    'Task Done' => $task_done,
                    'Role' => $role,
                    'Created On' => $created_on // Adjusted key to match array key
                ];
            }
        } else {
            // If no records found, set response data accordingly
            $responseData[] = [
                'Username' => "",
                'Name' => "",
                'Task Done' => "No record found",
                'Role' => "",
                'Created On' => "" // Adjusted key to match array key
            ];
        }
    
        // Convert data array to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";
        foreach ($responseData as $record) {
            // Ensure each value is properly escaped and enclosed in quotes
            $csvData .= '"' . implode('","', array_map(function($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }
    
        // Set headers to force download
        $response = $this->response
            ->setHeader('Content-Type', 'application/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="activity_data.csv"')
            ->setBody($csvData);
    
        return $response;

    }

}
