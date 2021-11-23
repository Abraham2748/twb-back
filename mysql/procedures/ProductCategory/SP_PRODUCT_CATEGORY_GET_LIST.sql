DROP PROCEDURE IF EXISTS SP_PRODUCT_CATEGORY_GET_LIST;
DELIMITER $
CREATE PROCEDURE SP_PRODUCT_CATEGORY_GET_LIST(
    IN _page INT,
    IN _rowsPerPage INT)
BEGIN
    DECLARE _initialRow INT DEFAULT 0;
    IF _page > 0 THEN
        SET _initialRow = (_page - 1) * _rowsPerPage;
    END IF;
    SELECT * FROM ProductCategory LIMIT _initialRow, _rowsPerPage;
END $
DELIMITER ;
