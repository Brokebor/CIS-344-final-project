DELIMITER //
CREATE PROCEDURE ProcessSale(
    IN param_prescriptionId INT,
    IN param_quantitySold INT
)
BEGIN
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
END //
DELIMITER ;