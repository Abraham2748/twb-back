DROP PROCEDURE IF EXISTS SP_USER_UPDATE;
DELIMITER $
CREATE PROCEDURE SP_USER_UPDATE(
    IN _id INT,
    IN _username VARCHAR(64),
    IN _password CHAR(32),
    IN _firstName VARCHAR(64),
    IN _lastName VARCHAR(64),
    IN _documentNumber VARCHAR(64),
    IN _active BOOLEAN)
BEGIN
    UPDATE User SET
        Username = _username,
        Password = _password,
        FirstName = _firstName,
        LastName = _lastName,
        DocumentNumber = _documentNumber,
        Active = _active
    WHERE Id = _id;
END $
DELIMITER ;
