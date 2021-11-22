DROP PROCEDURE IF EXISTS SP_BARBER_UPDATE;
DELIMITER $
CREATE PROCEDURE SP_BARBER_UPDATE(
    IN _id INT,
    IN _idGender INT,
    IN _firstName VARCHAR(64),
    IN _lastName VARCHAR(64),
    IN _documentNumber VARCHAR(64),
    IN _photo LONGTEXT)
BEGIN
    UPDATE User SET
        Id_Gender = _idGender,
        FirstName = _firstName,
        LastName = _lastName,
        DocumentNumber = _documentNumber,
        Photo = _photo
    WHERE Id = _id;
END $
DELIMITER ;
