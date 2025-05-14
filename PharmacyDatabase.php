<?php
class PharmacyDatabase {
    private $host = "127.0.0.1";
    
    private $port = "3306";

    private $database = "pharmacy_portal_db";

    private $user = "root";
    
    private $password = "";

    private $connection;

    public function __construct() {
        $this->connect();
    }
    //is what ze intial connection made
    private function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);

        if ($this->connection->connect_error) {

            die("Connection failed: " . $this->connection->connect_error);

        }
        echo "Successfully connected to the database";
    }
    //prescription creator
    public function addPrescription($patientUserName, $medicationId, $dosageInstructions, $quantity) {

        $stmt = $this->connection->prepare(
            
            "SELECT userId FROM Users WHERE userName = ? AND userType = 'patient'"
        );

        $stmt->bind_param("s", $patientUserName);

        $stmt->execute();

        $stmt->bind_result($patientId);

        $stmt->fetch();

        $stmt->close();
        
        if (!$patientId) {
            return false;
        }
        //insertion of the prescription
        $stmt = $this->connection->prepare(

            "INSERT INTO prescriptions (userId, medicationId, dosageInstructions, quantity, prescribedDate) 

             VALUES (?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param("iisi", $patientId, $medicationId, $dosageInstructions, $quantity);

        $result = $stmt->execute();

        $stmt->close();
        
        return $result;
    }
    //view grabber for pharmcist user
    public function getAllPrescriptions() {

        $result = $this->connection->query("SELECT * FROM  prescriptions join medications on prescriptions.medicationId= medications.medicationId");

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function viewInventory() {

        //view grabber that was made in sql for this purpose

        $result = $this->connection->query("SELECT * FROM MedicationInventoryView");

        if ($result) {

            return $result->fetch_all(MYSQLI_ASSOC);

        } else {

            echo "Error retrieving medication inventory.";

            return [];
        }
    }
    

    public function addUser($userName, $contactInfo, $userType, $password) {

        // hash ze password before storing

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        //mysql insertion into user table

        $stmt = $this->connection->prepare(

            "INSERT INTO users (userName, contactInfo, userType, password) VALUES (?, ?, ?, ?)"

        );
        $stmt->bind_param("ssss", $userName, $contactInfo, $userType, $hashedPassword);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }
    //makes a query to insert phar
    public function addMedication($name, $dosage, $maker = null) {
        try {

            $stmt = $this->connection->prepare(

                "INSERT INTO Medications (medicationName, dosage, manufacturer) VALUES (?, ?, ?)"
            );
            //failstate
            if (!$stmt) {

                error_log("Prepare failed: " . $this->connection->error);

                return false;
            }
            
            $stmt->bind_param("sss", $name, $dosage, $maker);
            
            if (!$stmt->execute()) {

                error_log("Execute failed: " . $stmt->error);

                return false;
            }
            
            $medicationId = $stmt->insert_id;
            $stmt->close();
            
            // Also add to inventory
            $inventoryStmt = $this->connection->prepare(

                "INSERT INTO Inventory (medicationId, quantityAvailable) VALUES (?, 0)"
            );
            $inventoryStmt->bind_param("i", $medicationId);

            $inventoryStmt->execute();

            $inventoryStmt->close();
            
            return $medicationId;

        } catch (Exception $e) {

            error_log("Error: " . $e->getMessage());

            return false;
        }
    }
    //for login 
    public function verifyUser($username, $password) {

        $stmt = $this->connection->prepare(
            // pulls data from the database
            "SELECT userId, userName, userType, password FROM Users WHERE userName = ?"
        );

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                return $user;
                
            }
        }
        return false;
    }
    public function getUserDetails($userId) {
        // initialize return array
        $userDetails = [];
        
        // 1. get basic user info

        $stmt = $this->connection->prepare(

            "SELECT userId, userName, contactInfo, userType 

             FROM Users 

             WHERE userId = ?"
        );
        $stmt->bind_param("i", $userId);

        $stmt->execute();

        $result = $stmt->get_result();

        $userDetails = $result->fetch_assoc();

        $stmt->close();
    
        if (!$userDetails) {

            return false; // usser not found
        }
    
        // 2. get user's prescriptions

        $stmt = $this->connection->prepare(

            "SELECT p.prescriptionId, p.prescribedDate, p.dosageInstructions, p.quantity, p.refillCount,

                    m.medicationId, m.medicationName, m.dosage, m.manufacturer

             FROM prescriptions p

             JOIN medications m ON p.medicationId = m.medicationId

             WHERE p.userId = ?"
        );

        //

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        $result = $stmt->get_result();

        $userDetails['prescriptions'] = $result->fetch_all(MYSQLI_ASSOC);
        
        $stmt->close();
    
        return $userDetails;
    }
    
    //Add Other needed functions here
}
?>
