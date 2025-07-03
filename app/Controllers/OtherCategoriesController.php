<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class OtherCategoriesController extends BaseController
{

    public function index()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') == "ADMIN") {
            return view("/administrator/other_category/index", $output);
        } else if (session()->get('role') == "MAIN") {
            return view("/main/other_category/index", $output);
        }
        
    }

    // GET LIST OF CATEGORIES FOR TABLE DISPLAY
    public function getCategoryData()
    {
        $categoryModel = new CategoryModel();

        try {
            $categoryData = $categoryModel->findAll();
            $data = [];

            foreach ($categoryData as $row) {
                // category code to description
                $category = '';
                switch ($row->category) {
                    case 'cstatus':
                        $category = 'CIVIL STATUS';
                        break;
                    case 'educ':
                        $category = 'EDUCATIONAL ATTAINMENT';
                        break;
                    case 'rel':
                        $category = 'RELIGION';
                        break;
                    case 'occ':
                        $category = 'OCCUPATION';
                        break;
                    case 'course':
                        $category = 'COURSE';
                        break;
                    case 'dialect':
                        $category = 'DIALECT';
                        break;
                    case 'ethnic':
                        $category = 'ETHNICITY';
                        break;
                    case 'relation':
                        $category = 'RELATIONSHIP';
                        break;
                    case 'training':
                        $category = 'TRAINING/SKILL';
                        break;
                    case 'gprograms':
                        $category = "GOV'T PROGRAM/ASSISTANCE";
                        break;
                    case 'sincome':
                        $category = 'SOURCE OF INCOME';
                        break;
                    case 'app':
                        $category = 'APPLIANCE/GADGET';
                        break;
                    case 'disability':
                        $category = 'TYPE OF DISABILITY';
                        break;
                    case 'comor':
                        $category = 'TYPE OF COMORBIDITY';
                        break;
                    case 'water':
                        $category = 'WATER SOURCE';
                        break;
                    case 'power':
                        $category = 'POWER SOURCE';
                        break;
                    case 'san':
                        $category = 'SANITATION (toilet facility)';
                        break;
                    case 'cook':
                        $category = 'WAY OF COOKING';
                        break;
                    case 'comm':
                        $category = 'COMMUNICATION LINE';
                        break;
                    case 'vhcl':
                        $category = 'TYPE OF VEHICLE';
                        break;
                    case 'amach':
                        $category = 'AGRICULTURAL MACHINERY';
                        break;
                    case 'alive':
                        $category = 'AGRICULTURAL LIVESTOCK';
                        break;
                    case 'amenities':
                        $category = 'BUILDING AMENITY';
                        break;
                    case 'bldgtype':
                        $category = 'BUILDING TYPE';
                        break;
                    case 'position':
                        $category = 'POSITION/DESIGNATION';
                        break;
                    case 'webCategory':
                        $category = 'MANAGE WEBSITE';
                        break;
                    case 'doctype':
                        $category = 'DOCUMENT TYPE';
                        break;
                    default:
                        $category = 'CATEGORY NOT FOUND';
                        break;
                }
                $data[] = [
                    $row->id,
                    $category,
                    $row->description,
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
    public function saveCategory()
    {
        helper(['form']);

        // Define validation rules
        $rules = [
            'category' => 'required',
            'description' => 'required',
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

        // Get the CategoryModel instance
        $categoryModel = new CategoryModel();

        // Extract data from POST request
        $id = isset($postData['id']) && $postData['id'] !== '' ? $postData['id'] : null;
        $data = [
            'category' => $postData['category'],
            'description' => strtoupper($postData['description']),
            'status' => strtoupper($postData['status'])
        ];

        // CHECK UNIQUENESS
        $passData = [
            'id' => $id,
            'category' => $data['category'],
            'description' => $data['description'],
        ];

        $isUnique = $this->isUnique_category($passData);
        if (!$isUnique) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['category' => 'Category description already taken.']
            ]);
        }

        try {
            // If an ID is provided, update the category; otherwise, insert a new category
            if ($id) {
                $categoryModel->update($id, $data);
                // Log activity 
                $this->activityLogService->logActivity('Updated category: '. $data['category']. ', description: '. $data['description'] . ', status: '. $data['status'], session()->get("id"));
            } else {
                $categoryModel->insert($data);
                 // Log activity 
                 $this->activityLogService->logActivity('Added new category: '. $data['category']. ', description: '. $data['description'] . ', status: '. $data['status'], session()->get("id"));
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
    public function getCategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if ($category) {
            return $this->response->setJSON($category);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Category not found'
            ]);
        }
    }

    // CHECK UNIQUE CATEGORY
    public function isUnique_category($data)
    {
        $id = $data['id'];
        $category = $data['category'] ?? '';
        $description = $data['description'] ?? '';

        // load model
        $categoryModel = new CategoryModel();

        $count_rows = 0;

        if ($id) {
            $count_rows = $categoryModel->where('category', $category)->where('description', $description)->where('id !=', $id)->countAllResults();
        } else {
            $count_rows = $categoryModel->where('category', $category)->where('description', $description)->countAllResults();
        }

        $isUnique = $count_rows > 0 ? FALSE : TRUE;

        return $isUnique;
    }
}
