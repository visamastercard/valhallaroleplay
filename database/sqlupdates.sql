ALTER TABLE `vehicles` ADD COLUMN `variant1` TINYINT UNSIGNED NOT NULL DEFAULT 255  AFTER `headlights` , ADD COLUMN `variant2` TINYINT UNSIGNED NOT NULL DEFAULT 255  AFTER `variant1` ;
ALTER TABLE `factions` ADD COLUMN `note` TEXT NULL  AFTER `motd` ;
ALTER TABLE `worlditems` ADD COLUMN `protected` INT NOT NULL DEFAULT 0  AFTER `creator` ;
ALTER TABLE characters
	DROP COLUMN ammo,
	DROP COLUMN weapons,
	DROP COLUMN fat,
	DROP COLUMN muscles,
	DROP COLUMN masked,
	DROP COLUMN radiochannel;
ALTER TABLE interiors
	DROP COLUMN tennant,
	DROP COLUMN items_values,
	DROP COLUMN items,
	DROP COLUMN max_items,
	DROP COLUMN money,
	DROP COLUMN rent,
	DROP COLUMN rentable;


