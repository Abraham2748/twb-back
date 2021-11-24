DROP PROCEDURE IF EXISTS SP_USER_UPDATE;
DELIMITER $
CREATE PROCEDURE SP_USER_UPDATE(
    IN _id INT,
    IN _idUserRole INT,
    IN _idGender INT,
    IN _username VARCHAR(64),
    IN _password CHAR(32),
    IN _firstName VARCHAR(64),
    IN _lastName VARCHAR(64),
    IN _documentNumber VARCHAR(64))
BEGIN
    UPDATE User SET
        Id_UserRole = _idUserRole,
        Id_Gender = _idGender,
        Username = _username,
        Password = _password,
        FirstName = _firstName,
        LastName = _lastName,
        DocumentNumber = _documentNumber
    WHERE Id = _id;
END $
DELIMITER ;
