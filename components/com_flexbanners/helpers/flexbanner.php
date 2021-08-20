<?php
/**
* @copyright Copyright (C) 2009 - 2012 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('_JEXEC') or die;

class FlexbannersHelper
{

	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId)) {
			$assetName = 'com_flexbanners';
		} else {
			$assetName = 'com_flexbanners.category.'.(int) $categoryId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * @return	boolean
	 * @since	1.6
	 */
	public static function updateReset()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$nullDate = $db->getNullDate();
		$now = JFactory::getDate();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__flexbanners');
		$query->where("'".$now."' >= ".$db->quoteName('reset'));
		$query->where($db->quoteName('reset').' != '.$db->quote($nullDate).' AND '.$db->quoteName('reset').'!=NULL');
		$query->where('('.$db->quoteName('checked_out').' = 0 OR '.$db->quoteName('checked_out').' = '.(int) $db->Quote($user->id).')');
		$db->setQuery((string)$query);
		$rows = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
			return false;
		}

		JTable::addIncludePath(JPATH_COMPONENT . '/tables');
		foreach ($rows as $row) {
				$client = JTable::getInstance('Client','FlexbannersTable');
				$client->load($row->clientid);

			// Update the row ordering field.
			$query->clear();
			$query->update($db->quoteName('#__flexbanners'));
			$query->set($db->quoteName('reset').' = '.$db->quote($reset));
			$query->set($db->quoteName('impmade').' = '.$db->quote(0));
			$query->set($db->quoteName('clicks').' = '.$db->quote(0));
			$query->where($db->quoteName('id').' = '.$db->quote($row->id));
			$db->setQuery((string)$query);
			$db->query();

			// Check for a database error.
			if ($db->getErrorNum()) {
				JError::raiseWarning(500, $db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
}
class flexAdBanner extends JTable {
	var $id     = null;
	var $clientid         = null;
	var $linkid           = null;
	var $sizeid           = null;
	var $imageurl         = null;
	var $imagealt         = null;
	var $customcode       = null;
	var $restrictbyid     = 0;
	var $frontpage    	  = 0;
	var $clicks           = 0;
	var $impmade          = 0;
	var $startdate        = null;
	var $enddate          = null;
	var $maximpressions   = null;
	var $maxclicks        = null;
	var $dailyimpressions = 0;
	var $lastreset        = '0000-00-00';
	var $state        	  = 0;
	var $finished         = 0;
    var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';
	var $juserid          = null;

	function __construct( &$_db ) {
		parent::__construct( '#__flexbanners', 'id', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdClient extends JTable {
	var $clientid         = null;
	var $clientname       = null;
	var $contactname      = null;
	var $contactemail     = null;
	var $barred           = 0;
    var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function __construct( &$_db ) {
		parent::__construct( '#__flexbannersclient', 'clientid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdLink extends JTable {
	var $linkid      = null;
	var $clientid    = null;
	var $linkurl     = '';
	var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function __construct( &$_db ) {
		parent::__construct( '#__flexbannerslink', 'linkid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdLocation extends JTable {
	var $locationid      = null;
	var $locationname     = '';
	var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function __construct( &$_db ) {
		parent::__construct( '#__flexbannerslocations', 'locationid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdImage extends JTable {
        var $imageid  = null;
        var $imageurl = '';
        var $width    = 0;
	var $height   = 0;
	var $filesize = 0;

	function __construct( &$_db ) {
		parent::__construct( '#__faimage', 'imageid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdSize extends JTable {
        var $sizeid           = null;
        var $sizename         = '';
        var $width            = 0;
        var $height           = 0;
        var $maxfilesize      = 0;
        var $checked_out      = 0;
	var $checked_out_time = 0;
	var $editor           = '';

	function __construct( &$_db ) {
		parent::__construct( '#__flexbannerssize', 'sizeid', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdContent extends JTable {
        var $id               = null;
        var $sectionid        = 0;
        var $catid            = 0;

	function __construct( &$_db ) {
		parent::__construct( '#__content', 'id', $_db );
	}

	function check() {

		return true;
	}
}

class flexAdCategories extends JTable {
        var $id               = null;
        var $parent_id        = '';
        var $title            = '';
        var $name             = '';
        var $image            = '';
        var $section          = 0;
        var $image_position   = 0;
        var $description      = 0;
        var $state            = 0;
        var $checked_out      = 0;
	var $checked_out_time = 0;
        var $editor           = '';
        var $ordering         = 0;
        var $access           = 0;
        var $count            = 0;
        var $params           = 0;

	function __construct( &$_db ) {
		parent::__construct( '#__categories', 'id', $_db );
	}

	function check() {

		return true;
	}
}
class modFlexBannersHelper {
	public static function FlexBannersQuery($details) {
		if($details['sectionid'] == "com_weblinks") { $details['sectionid'] = "1";
		}
		$menu = JFactory::getApplication()->getMenu();
		if($menu -> getActive() == $menu -> getDefault()) {
			$contentif = "AND IF((select count(distinct a.id) from `#__flexbanners` 
        				where `a`.`frontpage` = 1)>0,
                       `a`.`id` in (SELECT `a`.`id` 
                       								WHERE `a`.`frontpage` = 1) 
                       								AND `a`.`restrictbyid`=1,
                       `a`.`restrictbyid`=0)";
		} elseif(!is_null($details['contentid']) and $details['categoryid'] == 0 and $details['sectionid'] == 0) {
			$contentif = "AND IF((select count(distinct bannerid) from `#__flexbannersin` as i
                        WHERE `i`.`contentid` = " . $details['contentid'] . ")>0,
                       `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i
                                                    WHERE `i`.`contentid` = '" . $details['contentid'] . "') 
                                                    AND `a`.`restrictbyid`=1,
                       `a`.`restrictbyid`=0)";

		} elseif(!is_null($details['contentid'])) {
			$contentif = "AND IF((select count(distinct a.id) from `#__flexbannersin` as i where `i`.`contentid` = " . $details['contentid'] . ")>0,
                       `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i WHERE `i`.`contentid` = '" . $details['contentid'] . "') AND `a`.`restrictbyid`=1,
                       IF(`a`.`restrictbyid` and (select count(distinct a.id) FROM `#__flexbannersin` as i WHERE `i`.`categoryid` = " . $details['categoryid'] . ")>0,
                          `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i WHERE `i`.`categoryid` = '" . $details['categoryid'] . "') AND `a`.`restrictbyid`=1,
                          IF(`a`.`restrictbyid` and (select count(distinct a.id) FROM `#__flexbannersin` as i WHERE `i`.`sectionid` = " . $details['sectionid'] . ")>0,
                             `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i WHERE `i`.`sectionid` = '" . $details['sectionid'] . "') AND `a`.`restrictbyid`=1,
                             `a`.`restrictbyid`=0)))";

		} elseif(!is_null($details['categoryid'])) {
			$contentif = "AND IF((select count(distinct a.id) from `#__flexbannersin` as i WHERE `i`.`categoryid` = " . $details['categoryid'] . ")>0,
                        `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i WHERE `i`.`categoryid` = '" . $details['categoryid'] . "') AND `a`.`restrictbyid`=1,
                        IF(`a`.`restrictbyid` and (select count(distinct a.id) from `#__flexbannersin` as i WHERE `i`.`sectionid` = " . $details['sectionid'] . ")>0,
                           `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i WHERE `i`.`sectionid` = '" . $details['sectionid'] . "') AND `a`.`restrictbyid`=1,
                           `a`.`restrictbyid`=0))";
		} elseif(!is_null($details['sectionid'])) {
			$contentif = "AND IF((select count(distinct a.id) from `#__flexbannersin` as i WHERE `i`.`sectionid` = " . $details['sectionid'] . ")>0,
                       `a`.`id` in (SELECT `i`.`bannerid` FROM `#__flexbannersin` as i WHERE `i`.`sectionid` = '" . $details['sectionid'] . "') AND `a`.`restrictbyid`=1,
                       `a`.`restrictbyid`=0)";
		} else {
			$contentif = "AND `a`.`restrictbyid`=0";
		}
		return $contentif;
	}

public static function FlexBannersSWF($flexbannerwidth, $flexbannerheight, $link, $imageurl, $blankimageurl, $newwindow, $moduleclass_sfx, $nofollow) {
$flexbannerie = $flexbannerheight + 20;
if($newwindow) {
return '
<div class="flashcontent' . $moduleclass_sfx . '" style="overflow: hidden; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px;">
	<!-- this iframe is above the Flash, but below the div -->
	<iframe src="javascript:false" style="position:relative; top: 0px; left: 0px; display: none; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px; z-index: 5;" class="iframe" frameborder="0" scrolling="no"></iframe>
	<!-- iframe width is width of the div + borders, so 100 + 1 + 1 = 102 -->
	<!-- the div we want to be displayed above the Flash -->
	<div style="position: relative; top: 0px; left: 0px; z-index: 10; display: block; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px; background: none">
		<div class="advert' . $moduleclass_sfx . '" style="width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;">
			<a ' . $nofollow . ' href="' . $link . '" style="width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;display:block;margin:0;padding:0;border:0;text-decoration:none;"
			target="_blank" rel="noopener noreferrer"
			><img src="' . $blankimageurl . '" style="position: relative;float:left; top: 0px; left: 0px;width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;display:block;cursor: pointer;" alt="trans" />&nbsp;</a>
		</div>
	</div>
	<!-- this is the Flash element which we want as background -->
	<script type="text/javascript" src="' . JURI::base() . 'modules/mod_flexbanners/swfobject.js"></script>
	<script type="text/javascript">
var params = { wmode: "transparent", movie: "' . $imageurl . '" };
swfobject.registerObject("myFlashContent", "9.0.0");
	</script>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" onclick="window.location.href=\'' . $link . '\'" width=" ' . $flexbannerwidth . '" height=" ' . $flexbannerheight . '" style="position:relative;top:-' . $flexbannerheight . 'px!important;top:-'

	. $flexbannerie . 'px;">
		<param name="movie" value="' . $imageurl . '" />
		<param name="wmode" value="transparent"/>
		<!--[if !IE]>-->
		<object type="application/x-shockwave-flash" data="' . $imageurl . '" width="' . $flexbannerwidth . '" height="' . $flexbannerheight . '" >
			<param name="wmode" value="transparent"/>
			<!--<![endif]-->
			<a href="http://www.adobe.com/go/getflashplayer" > <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /> </a>
			<!--[if !IE]>-->
		</object>
		<!--<![endif]-->
	</object>
</div>
';
}else{
return '
<div class="flashcontent' . $moduleclass_sfx . '" style="overflow: hidden; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px;">
	<!-- this iframe is above the Flash, but below the div -->
	<iframe src="javascript:false" style="position:relative; top: 0px; left: 0px; display: none; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px; z-index: 5;" class="iframe" frameborder="0" scrolling="no"></iframe>
	<!-- iframe width is width of the div + borders, so 100 + 1 + 1 = 102 -->
	<!-- the div we want to be displayed above the Flash -->
	<div style="position: relative; top: 0px; left: 0px; z-index: 10; display: block; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px; background: none">
		<div class="advert' . $moduleclass_sfx . '" style="width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;">
			<a ' . $nofollow . ' href="' . $link . '" style="width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;display:block;margin:0;padding:0;border:0;text-decoration:none;"
			><img src="' . $blankimageurl . '" style="position: relative;float:left; top: 0px; left: 0px;width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;display:block;cursor: pointer;" alt="trans" />&nbsp;</a>
		</div>
	</div>
	<!-- this is the Flash element which we want as background -->
	<script type="text/javascript" src="' . JURI::base() . 'modules/mod_flexbanners/swfobject.js"></script>
	<script type="text/javascript">
var params = { wmode: "transparent", movie: "' . $imageurl . '" };
swfobject.registerObject("myFlashContent", "9.0.0");
	</script>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" onclick="window.location.href=\'' . $link . '\'" width=" ' . $flexbannerwidth . '" height=" ' . $flexbannerheight . '" style="position:relative;top:-' . $flexbannerheight . 'px!important;top:-'

	. $flexbannerie . 'px;">
		<param name="movie" value="' . $imageurl . '" />
		<param name="wmode" value="transparent"/>
		<!--[if !IE]>-->
		<object type="application/x-shockwave-flash" data="' . $imageurl . '" width="' . $flexbannerwidth . '" height="' . $flexbannerheight . '" >
			<param name="wmode" value="transparent"/>
			<!--<![endif]-->
			<a href="http://www.adobe.com/go/getflashplayer" > <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /> </a>
			<!--[if !IE]>-->
		</object>
		<!--<![endif]-->
	</object>
</div>
';
}
}

public static function FlexBannersloadlast($flexbannerwidth, $flexbannerheight, $link, $imageurl, $flexbannerimagealt, $newwindow, $moduleclass_sfx, $nofollow) {
if($newwindow) {
return '
<div class="advert' . $moduleclass_sfx  .'" style="display:block;width:' . $flexbannerwidth .'px;height:' . $flexbannerheight .'px;background:url(' . $imageurl .') no-repeat;">
	<a' . $nofollow . ' href="' . $link .'" style="width:' . $flexbannerwidth . 'px;height:' . $flexbannerheight . 'px;display:block;margin:0;padding:0;border:0;text-decoration:none;" target="_blank" rel="noopener noreferrer" >
		&nbsp;</a>
</div>
';
} else {
return '
<div class="advert' . $moduleclass_sfx . '" style="display:block;width:' . $flexbannerwidth .'px;height:' . $flexbannerheight. 'px;background:url(' . $imageurl . ') no-repeat;">
	<a' . $nofollow . ' href="' . $link . '" style="width:' . $flexbannerwidth . 'px;height:' . $flexbannerheight . 'px;display:block;margin:0;padding:0;border:0;text-decoration:none;">
		&nbsp;</a>
</div>
';
}
}
public static function FlexBannersHtml5($flexbannerwidth, $flexbannerheight, $link, $imageurl, $flexbannerimagealt, $newwindow, $moduleclass_sfx, $nofollow, $blankimageurl) {
if ($newwindow) {$newwiny=' target="_blank"';}	
$flexbannerwidth=$flexbannerwidth+2;$flexbannerheight=$flexbannerheight+2;
return '	
<div class="advert' . $moduleclass_sfx . '" style="overflow: hidden; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px;">
	<!-- this iframe is above the Flash, but below the div -->
	<iframe src="javascript:false" style="position:relative; top: 0px; left: 0px; display: none; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px; z-index: 5;" class="iframe" frameborder="0" scrolling="no"></iframe>
	<!-- iframe width is width of the div + borders, so 100 + 1 + 1 = 102 -->
	<!-- the div we want to be displayed above the html5 -->
	<div style="position: relative; top: 0px; left: 0px; z-index: 10; display: block; width: ' . $flexbannerwidth . 'px; height: ' . $flexbannerheight . 'px; background: none">
		<div class="advert' . $moduleclass_sfx . '" style="width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;">
			<a ' . $nofollow . ' href="' . $link . '" style="width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;display:block;margin:0;padding:0;border:0;text-decoration:none;"'
			. $newwiny . 'rel="noopener noreferrer"
			><img src="' . $blankimageurl . '" style="position: relative;float:left; top: 0px; left: 0px;width: ' . $flexbannerwidth . 'px;height: ' . $flexbannerheight . 'px;display:block;cursor: pointer;" alt="trans" />&nbsp;</a>
		</div>
	</div>
<iframe src="' . $imageurl . '" width="'.$flexbannerwidth.'" height="'.$flexbannerheight.'" style="position:relative;top:-' . $flexbannerheight . 'px!important;'. $flexbannerie . 'px;border=0px;" frameborder="0"></iframe>
</div>
'; }
public static function FlexBannersloadfirst($flexbannerwidth, $flexbannerheight, $link, $imageurl, $flexbannerimagealt, $newwindow, $moduleclass_sfx, $nofollow) {
if($newwindow) {
return '
<div class="advert' . $moduleclass_sfx . '">
	<a' . $nofollow . ' href="' . $link . '" target="_blank" rel="noopener noreferrer" >
		<img src="' . $imageurl . '" alt="' . $flexbannerimagealt . '" title="' . $flexbannerimagealt . '" ' . ($flexbannerwidth ? 'width="' . $flexbannerwidth . '"' : '') . ($flexbannerheight ? ' height="' . $flexbannerheight . '"' : '') . '  />
		</a>
</div>
';
} else {
return '
<div class="advert' . $moduleclass_sfx . '">
	<a' . $nofollow . ' href="' . $link . '" >
		<img src="' . $imageurl . '" alt="' . $flexbannerimagealt . '" title="' . $flexbannerimagealt . '" ' . ($flexbannerwidth ? 'width="' . $flexbannerwidth . '"' : '') . ($flexbannerheight ? ' height="' . $flexbannerheight . '"' : '') . '  />
		</a>
</div>
'; 		}
	}
}