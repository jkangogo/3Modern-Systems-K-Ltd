CREATE TABLE IF NOT EXISTS `#__flexbanners` (
  `id` int(11) NOT NULL auto_increment,
  `clientid` int(11) default NULL,
  `linkid` int(11) default NULL,
  `sizeid` int(11) default NULL,
  `locationid` int(11) default NULL,
  `type` tinyint(1) default NULL,
  `name` varchar(255) default NULL,
  `imageurl` varchar(255) default NULL,
  `flash` varchar(255) default NULL,
  `cloud_imageurl` varchar(255) default NULL,
  `imagealt` varchar(255) default NULL,
  `customcode` text,
  `restrictbyid` tinyint(1) NOT NULL default '0',
  `frontpage` tinyint(1) default NULL,
  `clicks` int(11) NOT NULL default '0',
  `impmade` int(11) NOT NULL default '0',
  `startdate` DATETIME default NULL,
  `enddate` DATETIME default NULL,
  `maximpressions` int(11) default NULL,
  `maxclicks` int(11) default NULL,
  `dailyimpressions` int(11) default '0',
  `lastreset` date default NULL,
  `published` tinyint(1) default '0',
  `finished` tinyint(1) default '0',
  `checked_out` INTEGER UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `reset` DATETIME default NULL,
  `state` TINYINT(3) NOT NULL,
  `catid` int(11) default '0',
  `editor` varchar(50) default NULL,
  `ordering` integer,
  `created` DATETIME NOT NULL,
  `language` CHAR(7) default NULL,
  `newwin` TINYINT(1) default NULL,
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL default '0',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__flexbannersin` (
  `bannerid` int(11) default NULL,
  `sectionid` int(11) default NULL,
  `categoryid` int(11) default NULL,
  `contentid` int(11) default NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__flexbannerslocations` (
  `locationid` int(11) NOT NULL auto_increment,
  `locationname` varchar(50),
  `checked_out` INTEGER UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50),
  `state` TINYINT(3) NOT NULL,
  PRIMARY KEY  (`locationid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__flexbannersclient` (
  `clientid` int(11) NOT NULL auto_increment,
  `clientname` varchar(255) default NULL,
  `contactname` varchar(255) default NULL,
  `contactemail` varchar(255) default NULL,
  `barred` tinyint(1) NOT NULL default '0',
  `checked_out` INTEGER UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  `juserid` integer default NULL,
  `state` TINYINT(3) NOT NULL,
  PRIMARY KEY  (`clientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__flexbannerslink` (
  `linkid` int(11) NOT NULL auto_increment,
  `linkname` varchar(30) default NULL,
  `linkurl` text,
  `clientid` int(11) default NULL,
  `checked_out` INTEGER UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  `state` TINYINT(3) NOT NULL,
  PRIMARY KEY  (`linkid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__flexbannerssize` (
  `sizeid` int(11) NOT NULL auto_increment,
  `sizename` varchar(50) default NULL,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  `maxfilesize` int(11) default NULL,
  `checked_out` INTEGER UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL,
  `editor` varchar(50) default NULL,
  `state` TINYINT(3) NOT NULL,
  PRIMARY KEY  (`sizeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__flexbannerssize` VALUES ('1', 'Full Banner (468x60)', '468', '60', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('2', 'Half Banner (234x60)', '234', '60', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('3', 'Micro Bar (88x31)', '88', '31', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('4', 'Button 1 (120x90)', '120', '90', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('5', 'Button 2 (120x60)', '120', '60', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('6', 'Vertical Banner (120x240)', '120', '240', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('7', 'Square Button (125x125)', '125', '125', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('8', 'Leaderboard (728x90)', '728', '90', '20000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('9', 'Wide Skyscraper (160x600)', '160', '600', '20000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('10', 'Skyscraper (120x600)', '120', '600', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('11', 'Half Page Ad (300x600)', '300', '600', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('12', 'Medium Rectangle (300x250)', '300', '250', '20000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('13', 'Square Pop-up (250x250)', '250', '250', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('14', 'Vertical Rectangle (240x400)', '240', '400', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('15', 'Large Rectangle (336x280)', '336', '280', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT IGNORE INTO `#__flexbannerssize` VALUES ('16', 'Rectangle (180x150)', '180', '150', '15000', '0', '0000-00-00 00:00:00', '','1');
INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) VALUES
('FlexBanner', 'com_flexbanners.banner', '{"special":{"dbtable":"#__banners","key":"id","type":"Banner","prefix":"BannersTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"name","core_state":"published","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"description", "core_hits":"null","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"link", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"null", "asset_id":"null"}, "special":{"imptotal":"imptotal", "impmade":"impmade", "clicks":"clicks", "clickurl":"clickurl", "custombannercode":"custombannercode", "cid":"cid", "purchase_type":"purchase_type", "track_impressions":"track_impressions", "track_clicks":"track_clicks"}}', '','{"formFile":"administrator\\/components\\/com_banners\\/models\\/forms\\/banner.xml", "hideFields":["checked_out","checked_out_time","version", "reset"],"ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time", "version", "imptotal", "impmade", "reset"], "convertToInt":["publish_up", "publish_down", "ordering"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}, {"sourceColumn":"cid","targetTable":"#__banner_clients","targetColumn":"id","displayColumn":"name"}, {"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"} ]}'),
('FlexBanners Category', 'com_flexbanners.category', '{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special": {"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}','','{"formFile":"administrator\\/components\\/com_categories\\/models\\/forms\\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}');
