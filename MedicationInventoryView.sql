CREATE VIEW MedicationInventoryView AS
SELECT 
    m.medicationId,
    m.medicationName,
    m.dosage,
    m.manufacturer,
    i.quantityAvailable
FROM 
    Medications m
JOIN 
    Inventory i ON m.medicationId = i.medicationId
ORDER BY 
    m.medicationName;