DROP PROCEDURE IF EXISTS SP_PRODUCT_CATEGORY_ADD;
DELIMITER $
CREATE PROCEDURE SP_PRODUCT_CATEGORY_ADD(
    IN _name VARCHAR(64),
    IN _photo LONGTEXT)
BEGIN
    INSERT INTO ProductCategory(
        Name,
        Photo,
        Active)
    VALUES (
        _name,
        _photo,
        1);
END $
DELIMITER ;
