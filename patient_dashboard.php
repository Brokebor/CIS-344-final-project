<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {

    header("Location: login.php");

    exit;
}

require_once 'PharmacyDatabase.php';

$db = new PharmacyDatabase();

$userData = $db->getUserDetails($_SESSION['user_id']);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Patient Dashboard</title>

    <style>

        .prescription {

            border: 1px solid #ddd;

            padding: 15px;

            margin-bottom: 20px;

            border-radius: 5px;

        }

        .prescription h3 {

            margin-top: 0;

            color: #2c3e50;

        }

    </style>

</head>
<!--ze tableru-->
<body>
    <h1>Welcome, <?php echo htmlspecialchars($userData['userName']); ?></h1>
    
    <h2>Your Prescriptions</h2>
    
    <!--the displayu-->
    
    <?php if (!empty($userData['prescriptions'])): ?>

        <?php foreach ($userData['prescriptions'] as $prescription): ?>

            <div class="prescription">

                <h3><?php echo htmlspecialchars($prescription['medicationName']); ?> 

                   (<?php echo htmlspecialchars($prescription['dosage']); ?>)</h3>

                <p><strong>Instructions:</strong> <?php echo htmlspecialchars($prescription['dosageInstructions']); ?></p>

                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($prescription['quantity']); ?></p>

                <p><strong>Prescribed on:</strong> <?php echo date('F j, Y', strtotime($prescription['prescribedDate'])); ?></p>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>You currently have no prescriptions.</p>

    <?php endif; ?>

    
   <a href="login.php?logout">Logout</a>
    
</body>
</html>