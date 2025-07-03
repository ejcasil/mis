<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\ResidentModel;
use App\Models\OfficialModel;
use App\Models\BrgyCodeModel;
use CodeIgniter\Controller;
// ACCESSING DATABASE
use Config\Database;

class QueryController extends BaseController
{
    public function index()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        // Get cstatus
        $output['cstatus'] = $this->getListDescriptionBasedOnCategory('cstatus');
        // Get educational attainment
        $output['educ'] = $this->getListDescriptionBasedOnCategory('educ');
        // Get course
        $output['course'] = $this->getListDescriptionBasedOnCategory('course');
        // Get religion
        $output['rel'] = $this->getListDescriptionBasedOnCategory('rel');
        // Get occupation
        $output['occ'] = $this->getListDescriptionBasedOnCategory('occ');
        // Get relation (relation to household head/ family head)
        $output['relation'] = $this->getListDescriptionBasedOnCategory("relation");
        // Get trainings
        $output['training'] = $this->getListDescriptionBasedOnCategory("training");
        // Get gov't programs/assistance
        $output['gprograms'] = $this->getListDescriptionBasedOnCategory("gprograms");
        // Get disability
        $output['disability'] = $this->getListDescriptionBasedOnCategory("disability");
        // Get comorbidity
        $output['comor'] = $this->getListDescriptionBasedOnCategory("comor");
        // Get current brgy from barangay profile
        $BrgyCodeModel = new BrgyCodeModel();
        if (session()->get('role') === "ADMIN") {
            $brgy_data = $BrgyCodeModel->find($this->brgy_id);
        } else if (session()->get('role') === "MAIN" || session()->get('role') === "RESIDENT") {
            $brgy_data = $BrgyCodeModel->findAll();
        }

        $output['barangay'] = $brgy_data;

        // Gather list of purok based from brgy_id
        $listPurok = $this->getListOfPurok($this->brgy_id);

        if ($listPurok != false) {
            $output['purok'] = $listPurok;
        }

        if (session()->get('role') === "ADMIN") {
            return view('administrator/query_builder/index', $output);
        } else if (session()->get('role') === "MAIN") {
            return view('main/query_builder/index', $output);
        }
        
    }

    // FILTER RECORD
    public function filter()
    {
        // Access the database service
        $db = Database::connect();

        $post = $this->request->getPost();

        // Prepare base query
        $query = $db->table('tblresident AS resident')
            ->select('resident.resident_id, resident.household, resident.fullname, resident.cp, resident.age, resident.gender, resident.email')  // Select all fields from tblresident

            // Left joins for related tables
            ->join('training_entered AS training', 'training.res_id = resident.id', 'left')
            ->join('gprograms_entered AS gprograms', 'gprograms.res_id = resident.id', 'left')
            ->join('disability_entered AS disability', 'disability.res_id = resident.id', 'left')
            ->join('comorbidities_entered AS comorbidity', 'comorbidity.res_id = resident.id', 'left')
            ->join('zp_code AS purok', 'purok.id = resident.add_id', 'left');

        // Conditional filters based on POST data
        if ($post['gprograms'] ?? null) {
            $query->whereIn('gprograms.category_id', $post['gprograms']);
        }
        if ($post['training'] ?? null) {
            $query->whereIn('training.category_id', $post['training']);
        }
        if ($post['disability'] ?? null) {
            $query->whereIn('disability.category_id', $post['disability']);
        }
        if ($post['comorbidity'] ?? null) {
            $query->whereIn('comorbidity.category_id', $post['comorbidity']);
        }

        if ($post['ageFrom'] ?? null) {
            $query->where('resident.age >=', $post['ageFrom']);
        }
        if ($post['ageTo'] ?? null) {
            $query->where('resident.age <=', $post['ageTo']);
        }
        if ($post['bplace'] ?? null) {
            $query->where('resident.bplace', $post['bplace']);
        }
        if ($post['btype'] ?? null) {
            $query->where('resident.btype', $post['btype']);
        }
        if ($post['course'] ?? null) {
            $query->where('resident.course_id', $post['course']);
        }
        if ($post['cp'] ?? null) {
            $query->where('resident.cp', $post['cp']);
        }
        if ($post['cstatus'] ?? null) {
            $query->where('resident.cstatus_id', $post['cstatus']);
        }
        if ($post['education'] ?? null) {
            $query->where('resident.educ_id', $post['education']);
        }
        if ($post['email'] ?? null) {
            $query->where('resident.email', $post['email']);
        }
        if ($post['fname'] ?? null) {
            $query->where('resident.fname', $post['fname']);
        }
        if ($post['gender'] ?? null) {
            $query->where('resident.gender', $post['gender']);
        }
        if ($post['height'] ?? null) {
            $query->where('resident.height', $post['height']);
        }
        if ($post['house_no'] ?? null) {
            $query->where('resident.house_no', $post['house_no']);
        }
        if ($post['household_id'] ?? null) {
            $query->where('resident.household', $post['household_id']);
        }
        if ($post['lname'] ?? null) {
            $query->where('resident.lname', $post['lname']);
        }
        if ($post['m_income_from'] ?? null) {
            $query->where('resident.m_income >=', $post['m_income_from']);
        }
        if ($post['m_income_to'] ?? null) {
            $query->where('resident.m_income <=', $post['m_income_to']);
        }
        if ($post['mname'] ?? null) {
            $query->where('resident.mname', $post['mname']);
        }
        if ($post['nstatus'] ?? null) {
            $query->where('resident.nstatus', $post['nstatus']);
        }
        if ($post['occupation'] ?? null) {
            $query->where('resident.occ_id', $post['occupation']);
        }
        if ($post['philhealth'] ?? null) {
            $query->where('resident.phealth_no', $post['philhealth']);
        }
        if ($post['purok'] ?? null) {
            $query->where('resident.add_id', $post['purok']);
        }
        if ($post['religion'] ?? null) {
            $query->where('resident.rel_id', $post['religion']);
        }
        if ($post['resident_id'] ?? null) {
            $query->where('resident.resident_id', $post['resident_id']);
        }
        if ($post['street'] ?? null) {
            $query->where('resident.street', $post['street']);
        }
        if ($post['suffix'] ?? null) {
            $query->where('resident.suffix', $post['suffix']);
        }
        if ($post['weight'] ?? null) {
            $query->where('resident.weight', $post['weight']);
        }

        // RETURN ONLY TO RESPECTIVE BARANGAY
        if (session()->get('role') === "ADMIN") {
            $query->where('purok.brgy_id', $this->brgy_id);
        }


        // Ensure unique residents are returned by using DISTINCT
        $query->distinct();

        // Execute the query and get the results
        $results = $query->get()->getResult();

        // Return the results as JSON
        $data = [];

        foreach ($results as $row) {
            $data[] = [
                $row->resident_id ?? '',
                $row->household ?? '',
                $row->fullname ?? '',
                $row->gender ?? '',
                $row->age ?? '',
                $row->cp ?? '',
                $row->email ?? ''
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    // GET INITIAL DATA FOR TABLE DISPLAY
    public function getDefaultData()
    {

        try {
            // Access the database service
            $db = Database::connect();

            // Prepare base query
            $query = $db->table('tblresident AS resident')
                ->select('resident.resident_id, resident.household, resident.fullname, resident.cp, resident.age, resident.gender, resident.email')  // Select all fields from tblresident

                // Left joins for related tables
                ->join('zp_code AS purok', 'purok.id = resident.add_id', 'left');

            // RETURN ONLY TO RESPECTIVE BARANGAY
            if (session()->get('role') === "ADMIN") {
                $query->where('purok.brgy_id', $this->brgy_id);
            }

            // Execute the query and get the results
            $results = $query->get()->getResult();

            $data = [];

            foreach ($results as $row) {
                $data[] = [
                    $row->resident_id ?? '',
                    $row->household ?? '',
                    $row->fullname ?? '',
                    $row->gender ?? '',
                    $row->age ?? '',
                    $row->cp ?? '',
                    $row->email ?? ''
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

    // EXPORT DATA 
    public function export_data()
    {
        // Ensure you're processing POST requests here
        if ($this->request->getMethod() !== 'POST') {
            // If it's not POST, return an error
            return $this->response->setStatusCode(405)->setBody('Method Not Allowed');
        }

        // Get the filtered data sent via POST request
        $filteredData = $this->request->getPost('filteredData');

        // Decode the JSON string into a PHP array
        $filteredData = json_decode($filteredData, true);

        // Prepare data for CSV export
        $responseData = [];
        foreach ($filteredData as $row) {
            $responseData[] = [
                'Resident ID' => $row[0] ?? '',
                'Household ID' => $row[1] ?? '',
                'Resident Name' => $row[2] ?? '',
                'Gender' => $row[3] ?? '',
                'Age' => $row[4] ?? '',
                'Contact No.' => $row[5] ?? '',
                'Email' => $row[6] ?? ''
            ];
        }

        // Convert data to CSV format
        $csvData = implode(',', array_keys($responseData[0])) . "\n";  // Add header
        foreach ($responseData as $record) {
            $csvData .= '"' . implode('","', array_map(function ($value) {
                return str_replace('"', '""', $value);
            }, $record)) . '"' . "\n";
        }

        // Set the response as plain text (CSV content)
        return $this->response->setHeader('Content-Type', 'application/csv')
            ->setBody($csvData);
    }

    // SEND MESSAGE
    public function sendMessage() {
        // Ensure you're processing POST requests here
        if ($this->request->getMethod() !== 'POST') {
            // If it's not a POST request, return an error
            return $this->response->setStatusCode(405)->setBody('Method Not Allowed');
        }
    
        // Get the filtered data sent via POST request
        $filteredData = $this->request->getPost('filteredData');
        $filteredData = json_decode($filteredData, true); // Decode the JSON into a PHP array
    
        // Get the message sent via POST request (serialized form data)
        $message = $this->request->getPost('message');
    
        // Check if the message or filtered data is empty
        if (empty($message)) {
            return $this->response->setStatusCode(400)->setBody('Message is required.');
        }
    
        if (empty($filteredData)) {
            return $this->response->setStatusCode(400)->setBody('Invalid filtered data.');
        }
    
        // Process the data and send message to each recipient
        $responses = [];
        foreach ($filteredData as $row) {
            $recipientEmail = $row[6] ?? ''; // Assuming email is in the 7th column
            if (!empty($recipientEmail)) {
                $this->send_email($recipientEmail, $message, "Information Dissemination");
                $responses[] = "Message sent to: " . $recipientEmail;
            }
        }
    
        // Return success response with details about who received the message
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Messages sent successfully!',
            'details' => $responses // Array of recipient emails for confirmation
        ]);
    }

    // FETCH PUROK LIST BASED ON BRGY_ID
    public function fetchPurokList($brgy_id) {
        $purok_list = $this->getListOfPurok($brgy_id);
        return $this->response->setJSON($purok_list);
    }

}
