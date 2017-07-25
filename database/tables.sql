CREATE TABLE IF NOT EXISTS `invoices` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `value` DECIMAL(6,2) NOT NULL,
  `due_date` DATE NOT NULL,
  `paid` TINYINT(1) NOT NULL DEFAULT 0,
  `pay_date` DATE NULL DEFAULT NULL,
  `recurrent` TINYINT(1) NOT NULL DEFAULT 0,
  `recurrence_interval` INT(3) NULL DEFAULT NULL,
  PRIMARY KEY (`id`));
  
ALTER TABLE `invoices` 
ADD COLUMN `invoice_id` INT NULL DEFAULT NULL AFTER `id`,
ADD INDEX `fk_invoices_1_idx` (`invoice_id` ASC);
ALTER TABLE `invoices` 
ADD CONSTRAINT `fk_invoices_1`
  FOREIGN KEY (`invoice_id`)
  REFERENCES `invoices` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
  
CREATE TABLE IF NOT EXISTS `discounts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `invoice_id` INT NOT NULL,
  `value` DECIMAL(6,2) NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `discounts` 
ADD INDEX `fk_discounts_1_idx` (`invoice_id` ASC);
ALTER TABLE `discounts` 
ADD CONSTRAINT `fk_discounts_1`
  FOREIGN KEY (`invoice_id`)
  REFERENCES `invoices` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
