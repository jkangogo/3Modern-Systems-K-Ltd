ALTER TABLE `#__flexbanners` 
ADD COLUMN `params` text NOT NULL AFTER `modified_by`,
ADD COLUMN `alias` VARCHAR(255) NULL DEFAULT NULL AFTER `name`,
ADD COLUMN `created_by` int(10) unsigned NOT NULL DEFAULT 0 AFTER `created`,
ADD COLUMN `created_by_alias` varchar(255) NOT NULL DEFAULT 0 AFTER `created_by`;
UPDATE `#__flexbanners` SET `alias` = `name` WHERE `alias` = NULL;