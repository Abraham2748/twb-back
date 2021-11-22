DROP PROCEDURE IF EXISTS SP_BARBER_GET_LIST;
DELIMITER $
CREATE PROCEDURE SP_BARBER_GET_LIST(
    IN _page INT,
    IN _rowsPerPage INT)
BEGIN
    DECLARE _initialRow INT DEFAULT 0;
    IF _page > 0 THEN
        SET _initialRow = (_page - 1) * _rowsPerPage;
    END IF;
    SELECT * FROM Barber LIMIT _initialRow, _rowsPerPage;
END $
DELIMITER ;
