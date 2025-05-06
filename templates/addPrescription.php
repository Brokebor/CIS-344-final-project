<!DOCTYPE html>
<html>
<head>
    <title>Add Prescription</title>

    <link rel="stylesheet" href="css/style.css">

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
        form {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 20px auto;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Add Prescription</h1>

    <?php if (!empty($error)): ?>

        <div class="error"><?php echo htmlspecialchars($error); ?></div>
        
    <?php endif; ?>
    
    <form method="POST" action="PharmacyServer.php?action=addPrescription">

        Patient Username: <input type="text" name="patient_username" 

            value="<?php echo htmlspecialchars($_POST['patient_username'] ?? ''); ?>" required><br>

        Medication ID: <input type="number" name="medication_id" 
        
            value="<?php echo htmlspecialchars($_POST['medication_id'] ?? ''); ?>" required min="1"><br>

        Dosage Instructions: <textarea name="dosage_instructions" required><?php 

            echo htmlspecialchars($_POST['dosage_instructions'] ?? ''); ?></textarea><br>

        Quantity: <input type="number" name="quantity" 

            value="<?php echo htmlspecialchars($_POST['quantity'] ?? ''); ?>" required min="1"><br>

        <button type="submit">Save</button>

    </form>
    
    <a href="PharmacyServer.php">Back to Home</a>
</body>
</html>