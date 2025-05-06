<?php
require_once 'PharmacyDatabase.php';

class PharmacyPortal {
    private $db;

    public function __construct() {
        $this->db = new PharmacyDatabase();
    }

    public function handleRequest() {

        $action = $_GET['action'] ?? 'home';

        switch ($action) {
           
            case 'addPrescription':
                $this->addPrescription();
                break;
            case 'viewPrescriptions':
                $this->viewPrescriptions();
                break;
            case 'viewInventory':
                $this->viewInventory();
                break;
            case 'addUser':
                $this->addUser();
                break;
            case 'addMedications':
                $this->addMedication();
                break;
           
            default:
                $this->home();
        }
    }
   

 
    private function home() {
        include 'templates/home.php';
    }

    private function addPrescription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // process form submission
            $patientUsername = trim($_POST['patient_username'] ?? '');

            $medicationId = (int)($_POST['medication_id'] ?? 0);

            $dosageInstructions = trim($_POST['dosage_instructions'] ?? '');

            $quantity = (int)($_POST['quantity'] ?? 0);
            
            // basic validation
            $error = '';
            if (empty($patientUsername)) {
                $error = "Patient username is required";

            } elseif ($medicationId <= 0) {
                $error = "Invalid medication ID";

            } elseif (empty($dosageInstructions)) {
                $error = "Dosage instructions are required";

            } elseif ($quantity <= 0) {
                $error = "Quantity must be greater than 0";
            }
            
            if (empty($error)) {
                if ($this->db->addPrescription($patientUsername, $medicationId, $dosageInstructions, $quantity)) {
                    header("Location: ?action=viewPrescriptions&message=Prescription+Added");

                    exit();

                } else {
                    $error = "Failed to add prescription. Please verify the patient exists and medication ID is valid.";

                }
            }
            
            // if error, show the form with error message
            include 'templates/addPrescription.php';
        } else {
            // show the form for GET requests
            include 'templates/addPrescription.php';
        }
    }

    private function viewPrescriptions() {
        //picks the public fucntion to see all the info from the database
    
        $prescriptions = $this->db->getAllPrescriptions();
        //uses the template
        include 'templates/viewPrescriptions.php';
    }
    private function viewInventory() {

        $inventory = $this->db->viewInventory();

        include 'templates/viewInventory.php';
    }
    private function addUser() {
        //form grabber
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];

            $contactInfo = $_POST['contact_info'];

            $userType = $_POST['user_type'];

            $password = $_POST['password'];
            //useraddittion
            if ($this->db->addUser($username, $contactInfo, $userType, $password)) {
                header("Location: ?action=viewUsers&message=User+Added");

                exit();

            } else {
                $error = "Failed to add user";

                include 'templates/addUser.php';
            }
        } else {
            include 'templates/addUser.php';
        }
    }
    private function addMedication() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['medication_name']);

            $dosage = trim($_POST['dosage']);

            $maker = isset($_POST['manufacturer']) ? trim($_POST['manufacturer']) : null;
            
            // Shrimple validation
            if (empty($name)) {
                $error = "Medication name is required";

                include 'templates/addMedications.php';
                
                return;
            }
            if (empty($dosage)) {

                $error = "Dosage is required";

                include 'templates/addMedications.php';
                return;
            }
            
            // Debuug output
            error_log("Adding medication: $name, $dosage, $maker");
            
            $result = $this->db->addMedication($name, $dosage, $maker);
            
            if ($result !== false) {
                header("Location: ?action=viewInventory&message=Medication+Added+Successfully");
                exit();
            } else {
                $error = "Failed to add medication. Please check error logs.";

                include 'templates/addMedications.php';

            }
        } else {

            include 'templates/addMedications.php';
        }
    }

}
$portal = new PharmacyPortal();
$portal->handleRequest();
