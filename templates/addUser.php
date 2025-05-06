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
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="container">

        <h1>Add New User</h1>
        
        <!-- le form for le user to enter info-->
         <!-- le error maker for no connection to database-->
        <?php if ($error): ?>

            <div class="error"><?= htmlspecialchars($error) ?></div>

        <?php endif; ?>
        
        <?php if ($success): ?>

            <div class="success"><?= htmlspecialchars($success) ?></div>

        <?php endif; ?>
        
        <form method="POST" action="?action=addUser">

            <div class="form-group">
                
                <label for="username">Username:</label>

                <input type="text" id="username" name="username" required>

            </div>
            
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>

                <input type="text" id="contact_info" name="contact_info" required>
                
            </div>
            
            <div class="form-group">
                <label for="user_type">User Type:</label>

                <select id="user_type" name="user_type" required>

                    <option value="patient">Patient</option>

                    <option value="pharmacist">Pharmacist</option>

                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>

                <input type="password" id="password" name="password" required minlength="8">

            </div>
            
            <button type="submit" class="btn">Add User</button>

        </form>
        
        <div class="actions">

            <a href="PharmacyServer.php" class="btn">Back to Home</a>
        </div>
    </div>
</body>
</html>
 </html>