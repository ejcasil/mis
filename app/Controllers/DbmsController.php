<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Exceptions\DatabaseException;

use Ifsnop\Mysqldump\Mysqldump;

use Exception;

class DbmsController extends BaseController
{

    public function index()
    {
        // CHECK ENCODING SCHEDULE
        $output['check_encoding_schedule']  = $this->checkEncodingSchedule();

        if (session()->get('role') == "ADMIN") {
            return view("administrator/database/index", $output);
        } else if (session()->get('role') == "MAIN") {
            return view("main/database/index", $output);
        }
    }

    public function backup()
    {
        try {
            // Load the Mysqldump class
            $db = Database::connect(); // Get the current database connection
            $dsn = 'mysql:host=' . $db->hostname . ';dbname=' . $db->database;
            $username = $db->username;
            $password = $db->password;

            // Define backup file path
            $backupDirectory = WRITEPATH . 'backups/';
            if (!is_dir($backupDirectory)) {
                if (!mkdir($backupDirectory, 0777, true)) {
                    throw new \Exception("Error: Failed to create backup directory");
                }
            }

            // Generate backup file name with timestamp
            $backupFile = $backupDirectory . date('Y-m-d-His') . '.sql';

            // Perform backup
            $dump = new Mysqldump($dsn, $username, $password);
            $dump->start($backupFile);

            // Send backup file for download
            if (file_exists($backupFile)) {
                // Log activity 
                $this->activityLogService->logActivity('Created database backup file', session()->get("id"));

                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
                header('Content-Length: ' . filesize($backupFile));
                readfile($backupFile);
                exit;
            } else {
                throw new \Exception("Error: Backup file not found!");
            }
        } catch (\Exception $e) {
            error_log("Backup Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            session()->setFlashdata("fail", "Error: " . $e->getMessage());
        }
    }

    public function restore()
    {
        try {
            // Check if a file was uploaded
            $file = $this->request->getFile('backup_file');
        
            // Check if the file is valid
            if ($file->isValid() && !$file->hasMoved()) {
                // Check if the file has a .sql extension
                $fileExtension = $file->getClientExtension();
                if (strtolower($fileExtension) !== 'sql') {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => 'Error: Uploaded file must have a .sql extension.'
                    ]); 
                }
    
                // Get database connection
                $db = db_connect(); // Get the current database connection
    
                // Drop all existing tables
                $db->query("SET foreign_key_checks = 0"); // Disable foreign key checks
                $result = $db->query("SHOW TABLES");
                $tables = $result->getResult(); // Fetch all tables
    
                foreach ($tables as $table) {
                    $tableName = current($table); // Get the table name
                    $db->query("DROP TABLE IF EXISTS `$tableName`");
                }
                
                $db->query("SET foreign_key_checks = 1"); // Enable foreign key checks
    
                // Open the uploaded file for reading
                $fileHandle = fopen($file->getTempName(), "r");
                if (!$fileHandle) {
                    return $this->response->setJSON([
                        'success' => false,
                        'errors' => 'Error: Unable to open uploaded file.'
                    ]);
                }
    
                // Read SQL commands line by line and execute them
                $sqlCommand = '';
                while (!feof($fileHandle)) {
                    $line = trim(fgets($fileHandle));
    
                    // Skip empty lines and comments
                    if (empty($line) || strpos($line, '--') === 0) {
                        continue;
                    }
    
                    // Append the current line to the SQL command
                    $sqlCommand .= $line;
    
                    // If the line ends with a semicolon, execute the SQL command
                    if (substr($line, -1) === ';') {
                        if (!$db->query($sqlCommand)) {
                            return $this->response->setJSON([
                                'success' => false,
                                'errors' => "Error executing SQL command: " . $db->error()['message']
                            ]); 
                        }
                        // Reset the SQL command
                        $sqlCommand = '';
                    }
                }
    
                // Close the file handle
                fclose($fileHandle);

                // Log activity 
                $this->activityLogService->logActivity('Restored database backup file', session()->get("id"));
    
                return $this->response->setJSON([
                    'success' => true
                ]);
    
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => 'Error: No file uploaded or file upload error occurred.'
                ]);                
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => "Database restoration failed. Error: " . $e->getMessage()
            ]);
        }
    }
    
    
    
    
    

    
    
    


    

    
    
    
    
    
    
    

    
}
