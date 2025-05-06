DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddOrUpdateUser`(
    IN param_userid INT,
    IN param_username VARCHAR(45),
    IN param_contact VARCHAR(200),
    IN param_type ENUM('pharmacist', 'patient')
)
BEGIN
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
DELIMITER ;