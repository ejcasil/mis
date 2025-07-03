<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use InvalidArgumentException;

use App\Models\CategoryModel;
use App\Models\BrgyCodeModel;
use App\Models\OfficialModel;
use App\Models\ZpCodeModel;
use App\Models\UploadModel;
use App\Models\ResidentModel;
use App\Models\BrgyProfileModel;
use App\Models\EncodingScheduleModel;

// Activity Log
use App\Services\ActivityLogService;
use CodeIgniter\Commands\Help;

// Email notication
use Config\Email as EmailConfig;
use CodeIgniter\Email\Email;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * For setting the Barangay ID and For logging activities done
     * 
     */
    protected $activityLogService;
    protected $brgy_id;

    public function __construct()
    {
        // Initialize activityLogService
        $this->activityLogService = service('activityLogService');

        // Set brgy_id from session or default to an empty string
        $this->brgy_id = session()->get("brgy_id") ?? '';
    }
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function save_date($this_date)
    {
        // Ensure the input is a string
        if (!is_string($this_date)) {
            throw new InvalidArgumentException('Input must be a string.');
        }

        $date = \DateTime::createFromFormat('m-d-Y', $this_date);

        // Check if the date was created successfully and matches the input format
        if ($date && $date->format('m-d-Y') === $this_date) {
            return $date->format('Y-m-d');
        } else {
            // Log the invalid date input for debugging
            // error_log("Invalid date input: '$this_date'. Throwing exception.");
            // throw new InvalidArgumentException("Invalid date format: '$this_date'. Expected format is 'm-d-Y'.");
            return date("Y-m-d");
        }
    }

    public function display_date($this_date)
    {
        // Attempt to create a DateTime object from the provided date string
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this_date);

        // Check if $date is a valid DateTime object
        if ($date instanceof \DateTime) {
            // Format the date to 'm-d-Y' format
            return $date->format('m-d-Y');
        } else {
            // Return null if the date cannot be parsed
            return null;
        }
    }

    // Get Description only based on the id provided (other category)
    public function getCategoryDescription(int $id): string
    {
        // Initialize description as an empty string
        $description = '';

        // Check if the ID is valid
        if ($id > 0) {
            try {
                // Create an instance of the CategoryModel
                $categoryModel = new CategoryModel();

                // Fetch the category data
                $categoryData = $categoryModel->find($id);

                // Check if the category data was found and has a description
                if ($categoryData && isset($categoryData->description)) {
                    $description = $categoryData->description;
                }
            } catch (\Exception $e) {
                // Log the exception or handle it appropriately
                error_log('Failed to fetch category description: ' . $e->getMessage());
            }
        }

        return $description;
    }

    // Get all rows based on the category provided
    public function getListDescriptionBasedOnCategory($category)
    {
        // Initialize category as an empty string
        $data = [];

        // Check if the category is not empty
        if (!empty($category)) {
            try {
                // Create an instance of the CategoryModel
                $categoryModel = new CategoryModel();

                // Fetch the category data
                $categoryData = $categoryModel->where('category', $category)->where('status', 'ACTIVE')->findAll();

                // Check if the category data was found and has a description
                if ($categoryData) {
                    $data = $categoryData;
                }
            } catch (\Exception $e) {
                // Log the exception or handle it appropriately
                error_log('Failed to fetch description based on category: ' . $e->getMessage());
            }
        }

        return $data;
    }

    // Format fullname
    public function formatFullname($data)
    {
        $lname = trim($data['lname']);
        $fname = trim($data['fname']);
        $mname = trim($data['mname']);
        $suffix = trim($data['suffix']);

        $fullname = "";

        if (!empty($fname)) {
            $fullname .= $fname . " ";
        }

        if (!empty($mname)) {
            $fullname .= $mname . " ";
        }

        if (!empty($lname)) {
            $fullname .= $lname . " ";
        }

        if (!empty($suffix)) {
            $fullname .= $suffix;
        }

        return strtoupper($fullname);
    }

    // GENRATE RANDOM CODE
    public function generate_code($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';

        // Generate a random string until it reaches the desired length
        for ($i = 0; $i <= $length; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }

        return $random_string;
    }

    // SEND EMAIL NOTIFICATION
    public function send_email($recipientEmail, $msg, $subject)
    {
        $email = \Config\Services::email();
        $emailConfig = new EmailConfig();  // Initialize the email config class

        try {
            // Check recipient email validity
            if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                log_message('error', 'Invalid recipient email: ' . $recipientEmail);
                return false;  // Early exit if email is invalid
            }

            // Initialize email configuration using class properties
            $config = [
                'protocol'  => 'smtp',
                'smtp_host' => $emailConfig->SMTPHost,
                'smtp_user' => $emailConfig->SMTPUser,
                'smtp_pass' => $emailConfig->SMTPPass,
                'smtp_port' => $emailConfig->SMTPPort,
                'smtp_crypto' => $emailConfig->SMTPSecure,
                'smtp_timeout' => $emailConfig->SMTPTimeout,
                'mailtype' => $emailConfig->mailType,
                'charset' => $emailConfig->charset,
                'wordwrap' => $emailConfig->wordWrap,
                'priority' => $emailConfig->priority,
            ];

            $email->initialize($config);  // Initialize the email service with the configuration

            // Set email parameters
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);  // Use the config class for sender info
            $email->setTo($recipientEmail);
            $email->setSubject($subject);

            // Format message with styles
            $formatted_msg = "
        <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color:rgb(153, 153, 153);
                        color: #333;
                    }
                    .email-container {
                        padding: 20px;
                        background: #fff;
                        max-width: 600px;
                        width:100%;
                        margin: 1rem;
                        border:1px solid rgb(54, 54, 54);
                    }
                    h1 {
                        color:rgb(22, 22, 22);
                    }
                    p {
                        font-size: 16px;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <h1>$subject</h1>
                    <p>$msg</p>
                    <pre>This message is system-generated. Please do not reply.</pre>
                </div>
            </body>
        </html>";

            $email->setMessage($formatted_msg);  // Set the HTML formatted message

            // Send email and return result
            if ($email->send()) {
                return true;  // Email sent successfully
            } else {
                // Log the email send failure with debug info
                log_message('error', 'Email to ' . $recipientEmail . ' failed. Debug info: ' . $email->printDebugger());
                return false;  // Return false if email failed
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur during email sending
            log_message('error', 'Error sending email: ' . $e->getMessage());
            return false;  // Return false on exception
        }
    }

    // COMPUTE AGE
    public function compute_age($bday)
    {
        date_default_timezone_set('Asia/Manila');

        // Ensure the birthday is a valid date
        if (empty($bday)) {
            log_message('error', "Birthday input is empty or null.");
            return null; // Or you can return a default value like 0 if you prefer
        }

        // Create DateTime object from the provided birthday string
        $birthDate = \DateTime::createFromFormat('Y-m-d', $bday);

        // Check for errors in date creation
        if (!$birthDate) {
            $errors = \DateTime::getLastErrors();
            log_message('error', "Failed to create DateTime from '$bday'. Errors: " . print_r($errors, true));
            return null; // Handle invalid date format
        }

        // Log the valid birth date (you can remove this in production)
        log_message('info', "Successfully created DateTime for birthday: " . $birthDate->format('Y-m-d'));

        // Get today's date
        $today = new \DateTime();

        // Calculate age
        $age = $today->diff($birthDate)->y; // Calculate the age in years

        // Log the computed age (you can remove this in production)
        log_message('info', "Computed age: $age for birthday: $bday");

        return $age;
    }

    // UPLOAD FILE
    protected function uploadFile($fileInputName, $allowedTypes, $maxSize = 10000)
    {
        $uploadPath = WRITEPATH . 'uploads'; 

        // Get the file
        $file = $this->request->getFile($fileInputName);

        // Validate the file
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return [
                'status' => false,
                'error' => "No uploaded file",
            ];
        }

        // Validate file type and size
        if (!$this->validate([
            $fileInputName => [
                'rules' => 'max_size[' . $fileInputName . ',' . $maxSize . ']|ext_in[' . $fileInputName . ',' . implode(',', $allowedTypes) . ']',
                'errors' => [
                    'max_size' => 'File size must not exceed ' . $maxSize . ' KB.',
                    'ext_in' => 'File type not allowed. Only ' . implode(', ', $allowedTypes) . ' are allowed.',
                ],
            ],
        ])) {
            return [
                'status' => false,
                'error' => $this->validator->getErrors(),
            ];
        }

        // Generate a unique file name
        $uniqueName = uniqid('', true) . '.' . $file->getClientExtension();

        // Move the file
        if ($file->move($uploadPath, $uniqueName)) {
            // INSERT TO UPLOAD RECORDS
            $this->saveFileToTable($uniqueName);

            return [
                'status' => true,
                'file_name' => $uniqueName,
            ];
        }

        return [
            'status' => false,
            'error' => 'File upload failed.',
        ];
    }

    // CHECK IF FILE EXISTS IN UPLOAD FOLDER
    public function checkFile($fileName)
    {
        $uploadPath = WRITEPATH . 'uploads/' . $fileName; // Adjust the path as needed

        if (file_exists($uploadPath)) {
            return true;
        } else {
            return false;
        }
    }

    // UNLINK THE FILE FROM THE UPLOAD FOLDER
    public function deleteFile($fileName)
    {
        $filePath = WRITEPATH . 'uploads/' . $fileName;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                return ['success' => true, 'message' => 'File deleted successfully.'];
            } else {
                return ['success' => false, 'message' => 'Error deleting file.'];
            }
        } else {
            return json_encode(['success' => false, 'message' => 'File does not exist.']);
        }
    }

    // GET BRGY NAME
    public function getBrgyName($brgy_id = null)
    {
        if ($brgy_id === null) {
            return false;
        }

        $BrgyCodeModel = new BrgyCodeModel();

        $brgy_data = $BrgyCodeModel->find($brgy_id);
        if ($brgy_data->brgy_name) {
            return $brgy_data->brgy_name;
        } else {
            return false;
        }
    }

    // GET BRGY_ID AND BRGY_NAME FROM ADD_ID (zpcodeModel)
    public function getBrgyDescription($add_id = null)
    {
        if (is_null($add_id)) {
            return false;  // Return false if no address ID is provided
        }

        $BrgyCodeModel = new BrgyCodeModel();
        $ZpCodeModel = new ZpCodeModel();

        // Retrieve the zp code data based on the provided address ID
        $zpCode_data = $ZpCodeModel->find($add_id);

        // Check if the zp code data exists and has a brgy_id
        if (!$zpCode_data || !$zpCode_data->brgy_id) {
            return false;  // If no brgy_id is found, return false
        }

        // Get the barangay data using the brgy_id from the zp code data
        $brgy_id = $zpCode_data->brgy_id;
        $brgyCode_data = $BrgyCodeModel->find($brgy_id);

        // Check if barangay data is found and has a valid ID
        if ($brgyCode_data && $brgyCode_data->id) {
            return $brgyCode_data;  // Return the barangay data
        } else {
            return false;  // If no barangay data is found, return false
        }
    }

    // GET BRGY_ID AND BRGY_NAME FROM ADD_ID (zpcodeModel)
    public function getBrgyByID($brgy_id = null)
    {
        if (is_null($brgy_id)) {
            return false;  // Return false if no address ID is provided
        }

        $BrgyCodeModel = new BrgyCodeModel();

        // Get the barangay data using the brgy_id from the zp code data
        $brgyCode_data = $BrgyCodeModel->find($brgy_id);

        // Check if barangay data is found and has a valid ID
        if ($brgyCode_data && $brgyCode_data->id) {
            return $brgyCode_data;  // Return the barangay data
        } else {
            return false;  // If no barangay data is found, return false
        }
    }

    // GET PUROK DESCRIPTION FROM ADD_ID (zpcodeModel)
    public function getPurokDescription($add_id = null)
    {
        if (is_null($add_id)) {
            return false;  // Return false if no address ID is provided
        }

        $ZpCodeModel = new ZpCodeModel();

        // Retrieve the zp code data based on the provided address ID
        $zpCode_data = $ZpCodeModel->find($add_id);

        // Check if the zp code data exists and has a brgy_id
        if (!$zpCode_data || !$zpCode_data->brgy_id) {
            return false;  // If no brgy_id is found, return false
        }

        if ($zpCode_data) {
            return $zpCode_data;
        } else {
            return false;
        }
    }

    // GET LIST OF PUROK/ZONE BASED ON BRGY_ID
    public function getListOfPurok($brgy_id = null)
    {
        $ZpCodeModel = new ZpCodeModel();

        if (is_null($brgy_id)) {
            return false;
        }

        $purokData = $ZpCodeModel->where('brgy_id', $brgy_id)->findAll();

        // Return the list of purok or zones, or false if no data is found
        return $purokData ?: false;
    }

    // GET LIST OF BARANGAYS
    public function getListOfBarangay()
    {
        $BrgyCodeModel = new BrgyCodeModel();
        $brgy_data = $BrgyCodeModel->where('status', 'ACTIVE')->findAll();

        return $brgy_data;
    }

    // SET HOUSEHOLD ID 
    public function setHouseholdID($data = null)
    {
        if (is_null($data)) {
            return false;
        }

        $brgy_id = $this->brgy_id;

        $add_id = $data['add_id'];
        $house_no = $data['house_no'];
        // Get brgy code
        $brgy_data = $this->getBrgyDescription($add_id);
        if ($brgy_data) {
            $brgy_code = $brgy_data->code ?: '';
        }

        // Get purok/zone code
        $ZpCodeModel = new ZpCodeModel();
        $zp_data = $this->getPurokDescription($add_id);
        $zp_code = $zp_data->code ?? '';
        // Concatenate household id
        if (!empty($house_no)) {
            $household_id = $brgy_code . "-" . $zp_code . "-" . $house_no;
            return $household_id;
        } else {
            return false;
        }
    }

    // SET RESIDENT ID
    public function setResidentID($data = null)
    {
        if (is_null($data)) {
            return false;
        }

        // Get household id
        $household_id = $data['household_id'];
        $res_id = $data['res_id'];

        return $household_id . "-" . $res_id;
    }

    // Helper function to check if description already exists
    public function descriptionExists($category, $description)
    {
        $categoryModel = new CategoryModel();
        $data = $categoryModel->where("category", $category)->where("description", $description)->first();
        return $data ? true : false;
    }

    // SAVE UPLOADED FILE TO TABLE; IF THE FILE IS UNUSED THEN DELETE IT AUTOMATICALLY
    public function saveFileToTable($fileName = null)
    {
        if (is_null($fileName)) {
            return false;
        }

        $UploadModel = new UploadModel();
        $insert = $UploadModel->insert(["file_path" => $fileName]);
        return true;
    }

    // CONVERT STRING TO ACCOUNTING (ex: 10,000)
    public function convert_to_accounting($str = null)
    {
        if (is_null($str)) {
            return "";
        }

        $formattedNumber = number_format((int)$str);

        return $formattedNumber;
    }

    // GET RESIDENT DATA USING THEIR ID
    public function getResidentDataFromID($id = null)
    {
        if ($id === null) {
            return false;
        }

        $ResidentModel = new ResidentModel();

        $resident_data = $ResidentModel->find($id);

        if ($resident_data) {
            return $resident_data;
        } else {
            return false;
        }
    }

    // GET OFFICIAL DATA USING THEIR ID
    public function getOfficialDataFromID($id = null)
    {
        if ($id === null) {
            return false;
        }

        $OfficialModel = new OfficialModel();

        $official_data = $OfficialModel->find($id);

        if ($official_data) {
            return $official_data;
        } else {
            return false;
        }
    }

    // GET DOCUMENT NAME
    public function getDocName($code = null)
    {
        if ($code === null) {
            return false;
        }

        $doc_name = "";

        switch ($code) {
            case "BC":
                $doc_name = "Barangay Clearance";
                break;
            case "BsC":
                $doc_name = "Business Clearance";
                break;
            case "CI":
                $doc_name = "Certificate of Indigency";
                break;
            case "OSP":
                $doc_name = "One & Same Person";
                break;
            case "PHC":
                $doc_name = "Poor Health Condition";
                break;
            case "PWD":
                $doc_name = "Person W/ Disability";
                break;
            case "HB":
                $doc_name = "House/Shelter Burn-out";
                break;
            case "HDT":
                $doc_name = "House Damaged by Typhoon";
                break;
            default:
                $doc_name = "Document not found";
                break;
        }

        return $doc_name;
    }

    // Auto send greetings to birthday celebrants
    public function bday_greetings()
    {
        date_default_timezone_set('Asia/Manila');
        $ResidentModel = new ResidentModel();

        $now = date("Y-m-d");

        $currentMonthDay = date("m-d", strtotime($now));

        // Query the residents whose birthday matches today's month and day
        // Get brgy_id 
        $brgy_id = $this->brgy_id;
        $residents = $ResidentModel->select('fname, bday, gender, email')->where("DATE_FORMAT(bday, '%m-%d')", $currentMonthDay)->where('age > 0')->findAll();

        $subject = "BIRTHDAY GREETINGS!";
        $from = "Greetings from the Barangay System Administrator";

        // Send greetings to each resident
        foreach ($residents as $resident) {
            $fname = $resident->fname ?? "Sanchez Miranian";
            // Logic to send birthday greeting (e.g., email, SMS, etc.)
            $email = $resident->email ?? '';

            $message = "Happiest Birthday $fname," . "<br><br></br>" . $this->get_birthday_greeting() . "<br>" . $from;

            $this->send_email($email, $message, $subject);
        }
    }

    public function get_birthday_greeting()
    {
        $list_of_greetings = [
            "Happy Birthday! May this year bring you closer to the person you're meant to be. Life is a beautiful journey, and with each passing year, you gather new experiences, wisdom, and strength. Keep pushing forward, embracing every challenge as an opportunity to grow. You're capable of achieving incredible things, and this new year of life is the perfect chance to chase your dreams with even more determination.
            <br>Remember that success isn’t defined by perfection, but by perseverance and growth. So, celebrate all that you’ve accomplished and look forward to the amazing things yet to come. Keep believing in yourself—you are unstoppable!",

            "Wishing you a wonderful birthday! May this year be filled with new adventures, meaningful connections, and moments that take your breath away. Every year brings us closer to the person we are becoming, and I have no doubt that you're destined for greatness. Keep shining your light brightly, as the world needs more of your energy and passion.
            <br>Take this day to reflect on how far you've come, and know that the best is yet to come. Here's to another year of growth, joy, and limitless possibilities. You’ve got this!",

            "Happy Birthday! Another year of life is an opportunity to become even stronger, wiser, and more in tune with your dreams. Keep focusing on what matters most, and don’t be afraid to take bold steps toward your goals. You have everything you need within you to make this year your best one yet!
            <br>Celebrate yourself today—your resilience, your kindness, and your ability to keep moving forward, no matter what. The world is full of endless possibilities, and I’m excited to see where this new year of life takes you.",

            "Happy Birthday! May this year bring you an abundance of joy, growth, and new opportunities. You've come so far and achieved so much already, and this year will be another chapter of amazing accomplishments. The key to success lies in persistence and faith in yourself, and you’ve already shown you have both in abundance.
            <br>So, as you celebrate today, remember how powerful you truly are. Trust the journey, and keep believing in your ability to turn dreams into reality. The best is yet to come!",

            "Wishing you an incredible birthday! Life is full of endless opportunities, and each year brings new chances to grow, learn, and evolve. Embrace the challenges that come your way, as they are stepping stones toward your greatness. You have the power to make this year your best one yet.
            <br>Take today to reflect on your achievements, and be proud of all the progress you’ve made. You are unstoppable, and I can’t wait to see the amazing things this next chapter holds for you!",

            "Happy Birthday! As you celebrate today, know that you are capable of achieving everything you set your mind to. Every challenge you’ve faced has only made you stronger, and this year will bring even more opportunities to shine. Stay focused on your dreams, and don’t be afraid to take risks—they often lead to the greatest rewards.
            <br>You’ve got so much potential, and there’s no doubt that you’re destined for greatness. Keep moving forward with confidence, and make this year one to remember!",

            "Happy Birthday! Each year brings a chance to reinvent yourself, to grow, and to step into your fullest potential. Embrace the journey ahead, knowing that your heart, mind, and spirit are all aligned to create something amazing. The best version of yourself is always within reach—just keep striving to be better each day.
            <br>As you celebrate today, remember how much you’ve already accomplished and how much more you can achieve. You’re doing incredible things, and the world is ready for the magic you’ll bring in the year ahead.",

            "Happy Birthday! On your special day, take time to reflect on your growth, your successes, and the journey that has shaped you. You have so much strength and wisdom within you, and this new year of life will open even more doors. Stay determined, and know that the universe is aligned in your favor.
            <br>Here’s to another year of taking bold steps toward your dreams. You’re an inspiration to everyone around you, and I can’t wait to see where this journey leads you next!",

            "Happy Birthday! May this new year of life bring you even closer to the person you are meant to be. Life’s challenges are opportunities for growth, and you’ve proven time and time again that you have the strength to overcome them. Keep moving forward with courage and confidence, knowing that your best days are ahead of you.
            <br>Take today to celebrate how far you’ve come and to get excited for all the incredible things yet to come. You’re capable of achieving everything you dream—this year is yours to make!",

            "Wishing you a very Happy Birthday! May this year be one of endless possibilities, growth, and personal transformation. You have the power to shape your future and turn your dreams into reality. Keep striving for what sets your soul on fire, and don’t ever lose sight of your incredible potential.
            <br>As you reflect on the journey ahead, know that every step you take brings you closer to the amazing things that are waiting for you. The best is yet to come, and I’m so excited to see all the success this year holds for you!",

            "Happy Birthday! Your resilience and hard work inspire everyone around you. This year, may your dreams take flight, and may you continue to grow in the most beautiful ways. With each new year, we evolve and become even more powerful, and I know this one will be filled with amazing achievements.
            <br>Remember, the sky’s the limit. Don’t be afraid to aim high—you're more than capable of achieving everything you desire!",

            "Another year has passed, and you’ve only grown more amazing! I hope this birthday brings you as much joy as you’ve brought to everyone around you. May this year open doors to even more exciting opportunities, and may you continue to move forward with grace and determination.
            <br>The future is bright, and it’s yours for the taking!",

            "Happy Birthday! This year is yours to shape and define. Don’t be afraid to take chances, to grow, and to discover new strengths. You have everything it takes to make this the best year yet.
            <br>May each day be filled with passion, joy, and new adventures—this year is yours to make magic happen!",

            "Happy Birthday! Your journey so far has been inspiring, and I know this year will bring even more growth and opportunities. Continue embracing every challenge with a positive attitude, and remember that every setback is just a stepping stone toward your dreams.
            <br>Celebrate today, and get ready for all the blessings this year will bring!",

            "Wishing you a birthday full of love, laughter, and exciting new beginnings! You’re stronger than you think, and this year will be full of opportunities for you to shine brighter than ever. Keep being true to yourself, and keep chasing your dreams.
            <br>Here’s to a year of incredible growth and success!",

            "Happy Birthday! May this year bring you more clarity, more success, and more peace. Take time to celebrate how far you’ve come and where you’re headed next. Your journey is a beautiful one, and you’re just getting started.
            <br>Keep being the amazing person you are—you’re capable of accomplishing so much more!",

            "Another year, another chance to become the best version of yourself. You’ve done so much already, but I know that this year will bring even more growth, joy, and opportunities. Believe in yourself and continue to dream big—you have so much to offer.
            <br>Happy Birthday, and cheers to a year of endless possibilities!",

            "Happy Birthday! Today is the perfect day to look back at everything you've accomplished, and to get excited about the future. There are endless possibilities ahead, and you have all the strength and determination to make them a reality.
            <br>Wishing you a year of fulfillment, growth, and joy!",

            "Happy Birthday! As you celebrate today, know that this year will be filled with incredible transformations. The challenges you face will only make you stronger, and the victories will be even sweeter. Keep believing in yourself—this year will be one for the books!
            <br>Wishing you a year of beautiful moments and endless happiness!",

            "Wishing you the happiest of birthdays! Every year brings new experiences and new opportunities for growth. This year, embrace the challenges, seize every opportunity, and continue to become the person you’ve always meant to be.
            <br>May your year ahead be full of love, laughter, and success!",

            "Happy Birthday! As you add another year to your life, remember that each year brings new opportunities to make amazing things happen. Stay focused on your dreams, and don’t be afraid to take bold steps toward them.
            <br>The best is yet to come, and I can’t wait to see what you’ll achieve this year!",

            "Wishing you the happiest of birthdays! May this year bring even more strength, wisdom, and courage to overcome any obstacles. You have everything you need to achieve greatness, and I believe this year will be your best one yet!
            <br>Here's to making this year unforgettable!",

            "Happy Birthday! May you embrace every challenge as an opportunity to grow and evolve. The journey ahead is full of possibilities, and I know that you’re going to make the most of every moment.
            <br>Here's to another year of personal growth and success!",

            "Wishing you a year filled with opportunities, joy, and success. You’ve already accomplished so much, and this year will be no different. Keep pushing forward and continue to believe in your potential—you’re capable of amazing things.
            <br>Happy Birthday, and here’s to all the incredible things to come!",

            "Happy Birthday! May this year be the one where all your dreams start to come true. The future is bright, and your potential is limitless. Celebrate your growth and look forward to all the wonderful experiences that are on the horizon.
            <br>Here's to another year of greatness!",

            "Happy Birthday! Another year of wisdom, growth, and new possibilities is upon you. You’ve already made so much progress, and I can’t wait to see where this year takes you. Keep being fearless, keep being passionate, and keep moving forward with purpose.
            <br>This year is going to be amazing!",

            "Wishing you a birthday as wonderful as you are! May your day be filled with love, and the year ahead filled with success, joy, and fulfillment. Every challenge is an opportunity to grow, and I know you’ll make the most of it.
            <br>Here's to another year of growth and greatness!",

            "Happy Birthday! Another year older, but also another year wiser and more capable than ever before. This is your year to truly shine—take every opportunity and embrace every moment with confidence.
            <br>Wishing you a year filled with endless possibilities and big accomplishments!",

            "Wishing you a birthday filled with laughter, love, and amazing moments. You’re capable of so much, and this year will bring you closer to everything you’ve ever dreamed of. Keep reaching for the stars—you’ve got this!
            <br>Happy Birthday and here’s to a fantastic year ahead!",

            "Happy Birthday! Your passion and determination have already brought you so far, and I know this year will bring you even further. Keep believing in yourself, and continue striving for the greatness you are destined to achieve.
            <br>Here’s to a year filled with new possibilities and big wins!",

            "Wishing you a birthday full of joy, love, and celebration. You’ve already done so much, but this year is going to be even bigger and better. Embrace every challenge and enjoy every success.
            <br>Happy Birthday to you, and here's to a year of amazing accomplishments!",

            "Happy Birthday! This year is going to be your year—filled with new opportunities and exciting journeys. Stay true to yourself, and keep pushing toward your dreams. You’ve got what it takes to make this year unforgettable.
            <br>Enjoy your day and here’s to another amazing year ahead!",
        ];


        // Get a random index from the list
        $random_index = rand(0, count($list_of_greetings) - 1);

        // Return a random greeting from the list
        return $list_of_greetings[$random_index];
    }

    // GET BRGY LOGO FROM THE USER DATA
    public function getBrgyLogo($brgy_id)
    {
        if (!$brgy_id) {
            return false;
        }

        $BrgyProfileModel = new BrgyProfileModel();
        $brgy_profile = $BrgyProfileModel->where('brgy_id', $brgy_id)->first();
        if ($brgy_profile && $brgy_profile->logo) {
            return base_url('writable/uploads/' . $brgy_profile->logo);
        } else {
            return base_url('public/assets/images/logo.png');
        }
    }

    // ADD "K" FOR THOUSAND IN COUNTING NUMBERS
    public function total_count($number) {
        $return_var = "0";
        
        if ($number) {
            if ($number >= 1000000) {
                $return_var = round($number / 1000000, 1) . "M"; // For numbers in the millions
            } else if ($number >= 1000) {
                $return_var = round($number / 1000, 1) . "k"; // For numbers in the thousands
            } else {
                $return_var = $number; // For numbers less than 1000
            }
        }
    
        return $return_var;
    }

    // CHECK ENCODING SCHEDULE
    public function checkEncodingSchedule()
    {
        $EncodingScheduleModel = new EncodingScheduleModel();
        $data = $EncodingScheduleModel->findAll();

        $disabled = "disabled";

        if ($data && is_array($data)) {

            foreach ($data as $row) {
                // Format the dates to 'Y-m-d' format
                $db_start_date = $row->start_date;
                $db_end_date = $row->end_date;

            }

            // Format the dates to 'Y-m-d' format
            $formattedStartDate = date('Y-m-d', strtotime($db_start_date));
            $formattedEndDate = date('Y-m-d', strtotime($db_end_date));

            // Check if today's date is within the range
            $today = date('Y-m-d');

            if ($today >= $formattedStartDate && $today <= $formattedEndDate) {
                //"Today's date is within the submission schedule range.";
                $disabled = "";
            } else {
                //"Today's date is outside the submission schedule range.";
                $disabled = "disabled";
            }
        }

        return $disabled;
    }

        // GET ENCODING SCHEDULE
        public function getEncodingSchedule() {
            $EncodingScheduleModel = new EncodingScheduleModel();
            $data = $EncodingScheduleModel->findAll();
    
            $start_date = "";
            $end_date = "";
            if ($data && is_array($data)) {
                foreach($data AS $row) {
                    $start_date = $this->display_date($row->start_date);
                    $end_date = $this->display_date($row->end_date);
                }
            }
    
            $output = [
                'start_date' => $start_date,
                'end_date' => $end_date
            ];
    
            return $output;
        }


}
