<?php
$error = $_GET['error'] ?? '';
$success = $_GET['message'] ?? '';
?>

<html>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

        .no-data {
            text-align: center;
        }
    </style>
    </html>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add New Medication</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
    <div class="container">

        <h1>Add New Medication</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>

        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            
        <?php endif; ?>

        
        <form method="POST" action="PharmacyServer.php?action=addMedications">

            <div class="form-group">

                <label for="medication_name">Medication Name:</label>

                <input type="text" id="medication_name" name="medication_name" required>
            </div>
            
            <div class="form-group">

                <label for="dosage">Dosage:</label>

                <input type="text" id="dosage" name="dosage" required>

            </div>
            
            <div class="form-group">

                <label for="manufacturer">Manufacturer (optional):</label>

                <input type="text" id="manufacturer" name="manufacturer">

            </div>
            
            <button type="submit" class="btn">Add Medication</button>
        </form>
        
        <div class="actions">
            <a href="PharmacyServer.php" class="btn">Back to Home</a>

            <a href="?action=viewInventory" class="btn">View Inventory</a>
        </div>
    </div>
</body>
</html>