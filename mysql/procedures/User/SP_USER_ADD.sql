DROP PROCEDURE IF EXISTS SP_USER_ADD;
DELIMITER $
CREATE PROCEDURE SP_USER_ADD(
    IN _idGender INT,
    IN _username VARCHAR(64),
    IN _password CHAR(32),
    IN _firstName VARCHAR(64),
    IN _lastName VARCHAR(64),
    IN _documentNumber VARCHAR(64))
BEGIN
    INSERT INTO User(
        Id_Gender,
        Username,
        Password,
        FirstName,
        LastName,
        DocumentNumber,
        Active)
    VALUES (
        _idGender,
        _username,
        _password,
        _firstName,
        _lastName,
        _documentNumber,
        1);
END $
DELIMITER ;
