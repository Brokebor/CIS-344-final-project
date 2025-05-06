DELIMITER //
CREATE TRIGGER AfterPrescriptionInsert
AFTER INSERT ON Prescriptions
FOR EACH ROW
BEGIN
    -- Update inventory by reducing the prescribed quantity
    UPDATE Inventory
    SET quantityAvailable = quantityAvailable - NEW.quantity,
        lastUpdated = NOW()
    WHERE medicationId = NEW.medicationId;
END //
DELIMITER ;