<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MapModel;

class MapController extends BaseController
{
    public function index()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') === "ADMIN") {
            return view('administrator/maps/index',$output);
        } else if (session()->get('role') === "MAIN") {
            return view('main/maps/index',$output);
        }
    }

    public function getGeoJson()
    {
        $MapModel = new MapModel();
        $map_data = $MapModel->orderBy('created_on', 'DESC')->first();

        $decodedContent = "";

        if (isset($map_data) && $map_data->filename) {
            $file = WRITEPATH . 'map_file/' . $map_data->filename;
            $fileContent = file_get_contents($file);
            $decodedContent = json_decode($fileContent, true);
        }
        // Output as JSON
        return $this->response->setJSON($decodedContent);
    }

    public function view_import_page()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') === "ADMIN") {
            return view('administrator/maps/import',$output);
        } else if (session()->get('role') === "MAIN") {
            return view('main/maps/import',$output);
        }
    }

    public function import()
    {
        // Get the uploaded files
        $uploadedFiles = $this->request->getFiles();

        // Check if the 'sample' key exists and contains files
        if (isset($uploadedFiles['fileUpload']) && count($uploadedFiles['fileUpload']) > 0) {
            $validGeoJSONFiles = [];

            // Loop through the uploaded files and check their extensions
            foreach ($uploadedFiles['fileUpload'] as $file) {
                // Check if the file is valid and has the correct extension
                if ($file->isValid() && !$file->hasMoved()) {
                    // Get the file extension
                    $fileExtension = strtolower($file->getClientExtension());

                    // Check if the file extension is geojson
                    if ($fileExtension === 'geojson') {
                        $validGeoJSONFiles[] = $file;
                    }
                }
            }

            // If no valid files found
            if (empty($validGeoJSONFiles)) {
                return $this->response->setJSON(['status' => false, 'message' => 'No valid GeoJSON files uploaded.']);
            }

            // Merge the valid GeoJSON files
            $mergedGeoJSON = $this->mergeGeoJSONFiles($validGeoJSONFiles);

            // Save the merged GeoJSON and return the filename
            $saveResult = $this->saveMergedGeoJSON($mergedGeoJSON);

            if ($saveResult['status']) {
                $MapModel = new MapModel();
                $MapModel->insert(['filename' => $saveResult['file_name']]);
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Files uploaded and merged successfully',
                    'file_name' => $saveResult['file_name']
                ]);
            } else {
                return $this->response->setJSON(['status' => false, 'message' => 'Failed to save merged GeoJSON file.']);
            }

            // // Return a JSON response with the collected file names that are geojson
            // return $this->response->setJSON(['status' => 'success', 'files' => $validGeoJSONFiles]);
        } else {
            // Return a response if no files were uploaded or if no 'sample' key is found
            return $this->response->setJSON(['status' => 'error', 'message' => 'No files uploaded or invalid file type.']);
        }
    }

    // Merges multiple GeoJSON files into one
    private function mergeGeoJSONFiles($validGeoJSONFiles)
    {
        $mergedGeoJSON = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        // Loop through the files and merge their features
        foreach ($validGeoJSONFiles as $file) {
            $fileContent = file_get_contents($file->getTempName());
            $decodedContent = json_decode($fileContent, true);

            if (isset($decodedContent['features'])) {
                $mergedGeoJSON['features'] = array_merge($mergedGeoJSON['features'], $decodedContent['features']);
            }
        }

        return $mergedGeoJSON;
    }

    // Save merged GeoJSON to the server
    private function saveMergedGeoJSON($mergedGeoJSON)
    {
        // Set the file path and filename
        $fileName = 'merged_' . time() . '.geojson';
        $filePath = WRITEPATH . 'map_file/' . $fileName;

        // Save the merged GeoJSON to the file
        if (file_put_contents($filePath, json_encode($mergedGeoJSON, JSON_PRETTY_PRINT))) {
            return [
                'status' => true,
                'file_name' => $fileName
            ];
        } else {
            return [
                'status' => false,
                'file_name' => ''
            ];
        }
    }
}
