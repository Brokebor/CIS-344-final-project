-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 06:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy_portal_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddOrUpdateUser` (IN `param_userid` INT, IN `param_username` VARCHAR(45), IN `param_contact` VARCHAR(200), IN `param_type` ENUM('pharmacist','patient'))   BEGIN
    IF param_userid IS NOT NULL THEN
        UPDATE Users 
        SET userName = param_username,
            contactInfo = param_contact,
            userType = param_type
        WHERE userId = param_userid;
        SELECT 'User updated successfully' AS message;
    ELSE
        INSERT INTO Users (userName, contactInfo, userType)
        VALUES (param_username, param_contact, param_type);
        SELECT 'User added successfully' AS message;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProcessSale` (IN `param_prescriptionId` INT, IN `param_quantitySold` INT)   BEGIN
    DECLARE var_medicationId INT;
    DECLARE var_currentStock INT;
    DECLARE var_unitPrice DECIMAL(10,2) DEFAULT 10.00; -- Set your price per unit here
    
    -- Get medication ID from prescription
    SELECT medicationId INTO var_medicationId 
    FROM Prescriptions 
    WHERE prescriptionId = param_prescriptionId;
    
    -- Check current inventory
    SELECT quantityAvailable INTO var_currentStock
    FROM Inventory
    WHERE medicationId = var_medicationId;
    
    -- Process sale if stock available
    IF var_currentStock >= param_quantitySold THEN
        -- Update inventory
        UPDATE Inventory 
        SET quantityAvailable = quantityAvailable - param_quantitySold,
            lastUpdated = NOW()
        WHERE medicationId = var_medicationId;
        
        -- Record sale
        INSERT INTO Sales (prescriptionId, quantitySold, saleAmount)
        VALUES (param_prescriptionId, param_quantitySold, param_quantitySold * var_unitPrice);
        
        -- Update prescription refill count
        UPDATE Prescriptions
        SET refillCount = refillCount + 1
        WHERE prescriptionId = param_prescriptionId;
        
        SELECT 'Sale processed successfully' AS result;
    ELSE
        SELECT 'Error: Insufficient stock available' AS result;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventoryId` int(11) NOT NULL,
  `medicationId` int(11) NOT NULL,
  `quantityAvailable` int(11) NOT NULL,
  `lastUpdated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventoryId`, `medicationId`, `quantityAvailable`, `lastUpdated`) VALUES
(1, 8, 200, '2025-05-05 12:14:42'),
(2, 9, 300, '2025-05-05 12:12:45'),
(3, 10, 300, '2025-05-05 12:12:54'),
(4, 11, 446, '2025-05-05 12:21:01'),
(5, 12, 197, '2025-05-05 12:43:22');

-- --------------------------------------------------------

--
-- Stand-in structure for view `medicationinventoryview`
-- (See below for the actual view)
--
CREATE TABLE `medicationinventoryview` (
`medicationId` int(11)
,`medicationName` varchar(45)
,`dosage` varchar(45)
,`manufacturer` varchar(100)
,`quantityAvailable` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `medicationId` int(11) NOT NULL,
  `medicationName` varchar(45) NOT NULL,
  `dosage` varchar(45) NOT NULL,
  `manufacturer` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medications`
--

INSERT INTO `medications` (`medicationId`, `medicationName`, `dosage`, `manufacturer`) VALUES
(1, 'Ibuprofen', '200mg', 'Generic Pharma'),
(2, 'Ibuprofen', '200mg', 'Advil Pharmaceuticals'),
(3, 'Amoxicillin', '500mg', 'Pfizer'),
(4, 'Lisinopril', '10mg', 'AstraZeneca'),
(5, 'Ibuprofen', '200mg', 'Advil Pharmaceuticals'),
(6, 'Amoxicillin', '500mg', 'Pfizer'),
(7, 'Lisinopril', '10mg', 'AstraZeneca'),
(8, 'chlorpromazine', '200mg', 'ACME corp'),
(9, 'Pervtin', '3000mg', 'Blaco firm'),
(10, 'Prozac', '200mg', 'Lickerticker'),
(11, 'Interfectum', '1000g', 'Payne Corp'),
(12, 'Aliceliddellaum', '200mg', 'The Crawling Chaos incorp');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescriptionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `medicationId` int(11) NOT NULL,
  `prescribedDate` datetime NOT NULL,
  `dosageInstructions` varchar(200) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `refillCount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`prescriptionId`, `userId`, `medicationId`, `prescribedDate`, `dosageInstructions`, `quantity`, `refillCount`) VALUES
(5, 4, 8, '2025-05-05 12:14:42', 'Take as much as needed, the war is not over', 2, 0),
(6, 2, 11, '2025-05-05 12:21:01', 'Take them, they only dull the pain so u can move forward', 4, 0),
(7, 7, 12, '2025-05-05 12:43:22', 'Im going to aliceIm going to aliceIm going to aliceIm going to aliceIm going to aliceIm going to', 3, 0);

--
-- Triggers `prescriptions`
--
DELIMITER $$
CREATE TRIGGER `AfterPrescriptionInsert` AFTER INSERT ON `prescriptions` FOR EACH ROW BEGIN
    -- Update inventory by reducing the prescribed quantity
    UPDATE Inventory
    SET quantityAvailable = quantityAvailable - NEW.quantity,
        lastUpdated = NOW()
    WHERE medicationId = NEW.medicationId;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `saleId` int(11) NOT NULL,
  `prescriptionId` int(11) NOT NULL,
  `saleDate` datetime NOT NULL DEFAULT current_timestamp(),
  `quantitySold` int(11) NOT NULL,
  `saleAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`saleId`, `prescriptionId`, `saleDate`, `quantitySold`, `saleAmount`) VALUES
(4, 7, '2025-05-01 20:17:03', 3, 24.99),
(5, 6, '2025-05-01 20:17:03', 3, 24.99),
(6, 5, '2025-05-01 20:17:03', 3, 24.99);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(45) NOT NULL,
  `contactInfo` varchar(200) DEFAULT NULL,
  `userType` enum('pharmacist','patient') NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `userName`, `contactInfo`, `userType`, `password`) VALUES
(1, 'Wyrmuser', 'johndoe@gmail.com', 'patient', '$2y$10$Ur.8mE1d0x41M93Z3QuaHu6dfa7dJY00Oha6SkAiJG738RWMIhD7C'),
(2, 'test_user', 'test@example.com', 'patient', ''),
(4, 'patient1', 'patient1@example.com', 'patient', 'worker45'),
(5, 'patient2', 'patient2@example.com', 'patient', 'murker34'),
(6, 'pharmacist1', 'pharmacist1@pharmacy.com', 'pharmacist', 'lurker420'),
(7, 'Luis Carroll', 'LuisCarrol@gmail,com', 'patient', '$2y$10$L7mMYiyHDN4RnV/f76xML.atQcqg8sicwo.X33Bihy6mNbeLQhZrG');

-- --------------------------------------------------------

--
-- Structure for view `medicationinventoryview`
--
DROP TABLE IF EXISTS `medicationinventoryview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `medicationinventoryview`  AS SELECT `m`.`medicationId` AS `medicationId`, `m`.`medicationName` AS `medicationName`, `m`.`dosage` AS `dosage`, `m`.`manufacturer` AS `manufacturer`, `i`.`quantityAvailable` AS `quantityAvailable` FROM (`medications` `m` join `inventory` `i` on(`m`.`medicationId` = `i`.`medicationId`)) ORDER BY `m`.`medicationName` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventoryId`),
  ADD UNIQUE KEY `medicationId` (`medicationId`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`medicationId`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescriptionId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `medicationId` (`medicationId`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`saleId`),
  ADD KEY `prescriptionId` (`prescriptionId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `medicationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescriptionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `saleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`medicationId`) REFERENCES `medications` (`medicationId`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`medicationId`) REFERENCES `medications` (`medicationId`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`prescriptionId`) REFERENCES `prescriptions` (`prescriptionId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
