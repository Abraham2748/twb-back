DROP PROCEDURE IF EXISTS SP_PRODUCT_ADD;
DELIMITER $
CREATE PROCEDURE SP_PRODUCT_ADD(
    IN _idProductCategory INT,
    IN _name VARCHAR(64),
    IN _price CHAR(32),
    IN _photo LONGTEXT)
BEGIN
    INSERT INTO Product(
        Id_ProductCategory,
        Name,
        Price,
        Photo,
        Active)
    VALUES (
        _idProductCategory,
        _name,
        _price,
        _photo,
        1);
END $
DELIMITER ;
