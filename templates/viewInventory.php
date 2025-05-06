<?php
//the direct access to the database
require_once 'PharmacyDatabase.php';

$db = new PharmacyDatabase();
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

 <body>
    <h1>Medication Inventory</h1>
    
    <?php
    try {
        $inventory = $db->viewInventory();
        
        if (!empty($inventory)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Medication</th>

                        <th>Dosage</th>

                        <th>Manufacturer</th>

                        <th>Quantity Available</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ze view-->
                    <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['medicationName']) ?></td>

                        <td><?= htmlspecialchars($item['dosage']) ?></td>

                        <td><?= htmlspecialchars($item['manufacturer'] ?? 'N/A') ?></td>

                        <td class="<?= $item['quantityAvailable'] < 20 ? 'low-stock' : '' ?>">
                            
                            <?= htmlspecialchars($item['quantityAvailable']) ?>

                            <?= $item['quantityAvailable'] < 20 ? ' (Low Stock)' : '' ?>

                        </td>
                    </tr>

                    <?php endforeach; ?>
                </tbody>

            </table>

        <?php else: ?>


            <p class="no-data">No medication inventory data available.</p>

        <?php endif;

        
    } catch (Exception $e) {
        echo '<div class="error">Error loading inventory: ' . htmlspecialchars($e->getMessage()) . '</div>';

    }
    ?>
    
    <div class="actions">

        <a href="PharmacyServer.php" class="btn">Back to Home</a>

    </div>
</body>
</html>   
    </html>