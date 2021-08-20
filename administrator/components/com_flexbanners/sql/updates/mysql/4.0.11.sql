ALTER TABLE `#__flexbannerslink` ADD `linkname` VARCHAR(30) NOT NULL AFTER `linkid`;
ALTER TABLE `#__flexbannerslink` ADD `clientid` INT(11) AFTER `linkurl`;
ALTER TABLE `#__flexbannersclient` DROP KEY `uniquename`;