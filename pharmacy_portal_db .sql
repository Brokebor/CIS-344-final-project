-- 1. Create the database
CREATE DATABASE pharmacy_portal_db;
USE pharmacy_portal_db;

-- 2. Create Users table
CREATE TABLE Users (
    userId INT NOT NULL AUTO_INCREMENT,
    userName VARCHAR(45) NOT NULL,
    contactInfo VARCHAR(200),
    userType ENUM('pharmacist', 'patient') NOT NULL,
    PRIMARY KEY (userId),
    UNIQUE KEY (userName)
);

-- 3. Create Medications table
CREATE TABLE Medications (
    medicationId INT NOT NULL AUTO_INCREMENT,
    medicationName VARCHAR(45) NOT NULL,
    dosage VARCHAR(45) NOT NULL,
    manufacturer VARCHAR(100),
    PRIMARY KEY (medicationId)
) ;

-- 4. Create Prescriptions table
CREATE TABLE Prescriptions (
    prescriptionId INT NOT NULL AUTO_INCREMENT,
    userId INT NOT NULL,
    medicationId INT NOT NULL,
    prescribedDate DATETIME NOT NULL,
    dosageInstructions VARCHAR(200),
    quantity INT NOT NULL,
    refillCount INT DEFAULT 0,
    PRIMARY KEY (prescriptionId),
    FOREIGN KEY (userId) REFERENCES Users(userId),
    FOREIGN KEY (medicationId) REFERENCES Medications(medicationId)
) ;

-- 5. Create Inventory table
CREATE TABLE Inventory (
    inventoryId INT NOT NULL AUTO_INCREMENT,
    medicationId INT NOT NULL,
    quantityAvailable INT NOT NULL,
    lastUpdated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (inventoryId),
    FOREIGN KEY (medicationId) REFERENCES Medications(medicationId),
    UNIQUE KEY (medicationId)
) ;

-- 6. Create Sales table
CREATE TABLE Sales (
    saleId INT NOT NULL AUTO_INCREMENT,
    prescriptionId INT NOT NULL,
    saleDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quantitySold INT NOT NULL,
    saleAmount DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (saleId),
    FOREIGN KEY (prescriptionId) REFERENCES Prescriptions(prescriptionId)
) ;
ALTER TABLE Users ADD COLUMN password VARCHAR(255) NOT NULL;
