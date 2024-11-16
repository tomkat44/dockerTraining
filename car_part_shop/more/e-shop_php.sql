CREATE SCHEMA `e_shop_php` ;

CREATE TABLE `e_shop_php`.`products` (
  `proid` INT NOT NULL AUTO_INCREMENT,
  `proname` VARCHAR(45) NULL,
  `price` FLOAT NULL,
  `quantity` INT NULL,
  `images` VARCHAR(45) NULL,
  `show_product` INT NULL,
  PRIMARY KEY (`proid`));
  
  INSERT INTO products (proname, price, quantity,images,show_product) VALUES('Φίλτρο λαδιού', '5.25', '45','oil1.jpg','1');
  INSERT INTO products (proname, price, quantity,images,show_product) VALUES('Φίλτρο αέρα', '11.39', '55','air1.jpg','1');
  INSERT INTO products (proname, price, quantity,images,show_product) VALUES('Μπουζί', '6.22', '132','spark1.jpg','1');
  INSERT INTO products (proname, price, quantity,images,show_product) VALUES('Φίλτρο βενζίνης', '18.14', '22','gas1.jpg','1');
  INSERT INTO products (proname, price, quantity,images,show_product) VALUES('Ιμάντας χρονισμού', '84.13', '7','label1.jpg','1');
  
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
  
  ALTER TABLE `e_shop_php`.`orders` 
ADD INDEX `fk1_idx` (`pro_id` ASC) VISIBLE;
;
ALTER TABLE `e_shop_php`.`orders` 
ADD CONSTRAINT `fk1`
  FOREIGN KEY (`pro_id`)
  REFERENCES `e_shop_php`.`products` (`proid`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
  


 CREATE TABLE `e_shop_php`.`users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NULL,
  `pass` CHAR(64) NULL,
  PRIMARY KEY (`user_id`));
  
  INSERT INTO users (username, pass) VALUES('b', '3e23e8160039594a33894f6564e1b1348bbd7a0088d42c4acb73eeaed59c009d');