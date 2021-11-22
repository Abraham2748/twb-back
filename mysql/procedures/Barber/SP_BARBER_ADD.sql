DROP PROCEDURE IF EXISTS SP_BARBER_ADD;
DELIMITER $
CREATE PROCEDURE SP_BARBER_ADD(
    IN _idGender INT,
    IN _firstName VARCHAR(64),
    IN _lastName VARCHAR(64),
    IN _documentNumber VARCHAR(64),
    IN _photo LONGTEXT)
BEGIN
    INSERT INTO Barber(
        Id_Gender,
        FirstName,
        LastName,
        DocumentNumber,
        Photo,
        Active)
    VALUES (
        _idGender,
        _firstName,
        _lastName,
        _documentNumber,
        _photo,
        1);
END $
DELIMITER ;
