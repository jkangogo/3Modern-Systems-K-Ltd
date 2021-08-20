ALTER TABLE `#__flexbanners` ADD `flash` varchar(255) default NULL AFTER `imageurl`;
UPDATE `#__flexbanners` SET `type`  = 3 WHERE (`type` = 2 AND `id` <> 0);
UPDATE `#__flexbanners` SET `type`  = 2 WHERE (`type` = 1 AND `id` <> 0);
UPDATE `#__flexbanners` SET `flash` = `imageurl` WHERE (`type` = 0 AND `imageurl` LIKE '%.swf' AND `id` <> 0);
UPDATE `#__flexbanners` SET `type`  = 1 WHERE (`flash` IS NOT NULL AND `type` = 0 AND `id` <> 0);
UPDATE `#__flexbanners` SET `imageurl` = concat('images/banners/',`imageurl`) WHERE (`type` = 0 AND `id` <> 0);