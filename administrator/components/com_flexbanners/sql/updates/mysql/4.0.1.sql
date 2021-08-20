ALTER TABLE `#__flexbanners` ADD `modified` datetime NOT NULL default '0000-00-00 00:00:00';
ALTER TABLE `#__flexbanners` ADD `modified_by` int(10) unsigned NOT NULL default '0';
ALTER TABLE `#__flexbanners` ADD COLUMN `version` int(10) unsigned NOT NULL DEFAULT '1';
ALTER TABLE `#__flexbanners` CHANGE `categoryid` `newwin` TINYINT(1) NOT NULL default '0';
ALTER TABLE `#__flexbanners` MODIFY COLUMN `catid` INT(11) UNSIGNED DEFAULT 0;
ALTER TABLE `#__flexbanners` MODIFY COLUMN `newwin` TINYINT(1) DEFAULT NULL;