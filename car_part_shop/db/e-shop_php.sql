CREATE SCHEMA `e_shop_php` ;

CREATE TABLE `e_shop_php`.`products` (
  `proid` INT NOT NULL AUTO_INCREMENT,
  `proname` VARCHAR(45) NULL,
  `price` FLOAT NULL,
  `quantity` INT NULL,
  `images` VARCHAR(45) NULL,
  PRIMARY KEY (`proid`));
  
  INSERT INTO products (proname, price, quantity,image) VALUES('Φίλτρο λαδιού', '5.25', '45','oil1.jpg');
  INSERT INTO products (proname, price, quantity,image) VALUES('Φίλτρο αέρα', '11.39', '55','air1.jpg');
  INSERT INTO products (proname, price, quantity,image) VALUES('Μπουζί', '6.22', '132','spark1.jpg');
  INSERT INTO products (proname, price, quantity,image) VALUES('Φίλτρο βενζίνης', '18.14', '22','gas1.jpg');
  INSERT INTO products (proname, price, quantity,image) VALUES('Ιμάντας χρονισμού', '84.13', '7','label1.jpg');
  
  CREATE TABLE `e_shop_php`.`orders` (
  `ord_id` INT NOT NULL AUTO_INCREMENT,
  `pro_id` INT NULL,
  `pro_name` VARCHAR(45) NULL,
  `pro_quant` INT NULL,
  `pro_price` FLOAT NULL,
  `pro_finalprice` FLOAT NULL,
  `ord_date` DATETIME NULL,
  `lastname` VARCHAR(45) NULL,
  `firstname` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  PRIMARY KEY (`ord_id`));
  
 
