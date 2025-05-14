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
<html>
<head><title>Pharmacy Portal</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Pharmacy Portal</h1>
    <nav>
        <a href="?action=addPrescription" class="nav-link">Add Prescription</a>

        <a href="?action=viewPrescriptions" class="nav-link">View Prescriptions</a>

        <a href="?action=viewInventory" class="nav-link">View Inventory</a>

        <a href="?action=addUser" class="nav-link">Add User</a>

        <a href="?action=addMedications" class="nav-link">Add Medications</a>

        <a href="login.php?logout">Logout</a>



    </nav>
</body>
</html>
