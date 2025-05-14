<?php
session_start();
require_once 'PharmacyDatabase.php';

// handlele logout
if (isset($_GET['logout'])) {

    session_destroy();

    header("Location: login.php");

    exit;
}

// handlele login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $db = new PharmacyDatabase();

    $user = $db->verifyUser($_POST['username'], $_POST['password']);

    
    if ($user) {

        $_SESSION['user_id'] = $user['userId'];

        $_SESSION['user_type'] = $user['userType'];

        
        // redirecter based on role
        header("Location: " . ($user['userType'] === 'pharmacist' ?

              "PharmacyServer.php" : "patient_dashboard.php"));

        exit;

    } else {

        $error = "Invalid username or password";
    }
}
?>

<!-- Shrimple Login Form -->
<form method="POST">

    <h2>Pharmacy Login</h2>

    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <input type="text" name="username" placeholder="Username" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>
    
</form>