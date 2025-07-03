<?php

namespace App\Controllers;

class ResidentAccountController extends BaseController
{
    public function dashboard()
    {
        // GET LIST OF Barangays
        $output['barangays'] = $this->getListOfBarangay();
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();
        // GET ENCODING SCHEDULE
        $output['encoding_schedule'] = $this->getEncodingSchedule();

        return view('resident/dashboard', $output);
    }
}
