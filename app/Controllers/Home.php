<?php

namespace App\Controllers;

use App\Models\BannerModel;
use App\Models\PostModel;
use App\Models\OfficialModel;
use App\Models\BrgyProfileModel;

class Home extends BaseController
{
    public function index(): string
    {
        $BannerModel = new BannerModel();
        $Banners = $BannerModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC')->findAll();
        $output['banners'] = $Banners;

        $PostModel = new PostModel();
        $Posts = $PostModel->where('status', 'ACTIVE')->limit(8)->orderBy('created_on', 'DESC')->findAll();
        $output['posts'] = $Posts;
        return view('my_website', $output);
    }

    public function view_post($id)
    {
        $BannerModel = new BannerModel();
        $Banners = $BannerModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC')->findAll();
        $output['banners'] = $Banners;

        $PostModel = new PostModel();
        $PostData = $PostModel->find($id);
        // Change category_id to description
        $PostData->category_id = $PostData->category_id ? $this->getCategoryDescription($PostData->category_id) : '';
        
        $output['post'] = $PostData;

        return view('view-post', $output);
    }

    public function all_post()
    {
        // Load Banner Model
        $BannerModel = new BannerModel();
        $Banners = $BannerModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC')->findAll();
        $output['banners'] = $Banners;

        // Load Post Model
        $PostModel = new PostModel();

        // Get month and year from query parameters (if available)
        $month = $this->request->getVar('month');
        $year = $this->request->getVar('year');

        // Define the number of posts per page
        $postsPerPage = 5;

        // Get the current page number, default is page 1
        $currentPage = $this->request->getVar('page') ?? 1;

        // Prepare the query to fetch posts, applying month and year filters if provided
        $query = $PostModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC');

        if ($month && $year) {
            // Filter posts by the selected month and year
            $query->where('MONTH(created_on)', $month)
                ->where('YEAR(created_on)', $year);
        }

        $category = $this->request->getVar('category');

        // Sorted by category
        if ($category) {
            $query->where('category_id', $category);
        }

        // Select only active posts
        $query->where('status', 'ACTIVE');

        // Paginate the results
        $posts = $query->paginate($postsPerPage, 'default', $currentPage);

        // Get the list of available archives (distinct years and months)
        $archives = $PostModel->select('YEAR(created_on) AS year, MONTH(created_on) AS month, MAX(created_on) AS latest_created_on')
            ->groupBy('YEAR(created_on), MONTH(created_on)')
            ->orderBy('latest_created_on', 'DESC')
            ->findAll();

        // Get the pager instance to pass to the view
        $pager = \Config\Services::pager();

        // Prepare data for the view
        $output['posts'] = $posts;
        $output['archives'] = $archives;
        $output['pager'] = $pager; // Make sure pager is passed to the view
        $output['categories'] = $this->getListDescriptionBasedOnCategory('webCategory');

        // Pass the posts and archives data to the view
        return view('all-posts', $output);
    }

    public function brief_history()
    {
        $BannerModel = new BannerModel();
        $Banners = $BannerModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC')->findAll();
        $output['banners'] = $Banners;

        return view('brief-history', $output);
    }

    public function brgy_officials()
    {
        try {
            $OfficialModel = new OfficialModel();

            // Get banners
            $BannerModel = new BannerModel();
            $Banners = $BannerModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC')->findAll();
            $output['banners'] = $Banners;
    
            // Get the official id of the current captain
            $BrgyProfileModel = new BrgyProfileModel();
            $brgy_id = "2"; // Or use dynamic value for `brgy_id`
            $current_captain = $BrgyProfileModel->where('brgy_id', $brgy_id)->first();
    
            // Check if the captain exists
            if (!$current_captain) {
                throw new \Exception("No captain found for Barangay ID: $brgy_id");
            }
    
            // Get the captain's id (Ensure this is the correct field name)
            $current_captain_id = $current_captain->official_id;
            // Get the position of the current captain
            $OfficialModel = new OfficialModel();
            $captain_data = $OfficialModel->find($current_captain_id);
            $current_captain_position_id = $captain_data->position_id ?? '';
    
            // Get all the officials and loop through them
            
            $official_array = [];
            $Officials = $OfficialModel->where('brgy_id', $brgy_id)->orderBy('created_on', 'DESC')->findAll();
    
            if (!$Officials) {
                throw new \Exception("No officials found in the database.");
            }
    
            foreach ($Officials as $official) {
                // Check if the official is the captain
                $isCaptain = ($official->position_id === $current_captain_position_id);
    
                // Check if the term exists in the array, if not, initialize it
                if (!isset($official_array[$official->term])) {
                    $official_array[$official->term] = [];
                }
    
                // Add official information to the term
                $official_array[$official->term][] = [
                    'name' => $official->fullname,
                    'position' => $official->position_id ? $this->getCategoryDescription($official->position_id) : '',
                    'img_path' => $official->img_path, 
                    'isCaptain' => $isCaptain
                ];
            }
    
            // Sort the officials for each term to ensure the captain is displayed first
            foreach ($official_array as &$officials) {
                usort($officials, function ($a, $b) {
                    // Sort by captain first
                    return $a['isCaptain'] ? -1 : 1;
                });
            }
    
            // Pass the official array and banners to the view
            $output['brgy_officials'] = $official_array;
    
            // Return the view with the data
            return view('brgy-officials', $output);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            log_message('error', 'Error in brgy_officials method: ' . $e->getMessage());
    
            // Return a user-friendly message or redirect to an error page
            return view('error_page', ['error_message' => 'Something went wrong. Please try again later.']);
        }
    }

    public function issuances() {
         // Get banners
         $BannerModel = new BannerModel();
         $Banners = $BannerModel->where('status', 'ACTIVE')->orderBy('created_on', 'DESC')->findAll();
         $output['banners'] = $Banners;

        return view('issuances', $output);
    }

    
}
