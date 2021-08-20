<?php
/**
 * @copyright Copyright (C) 2009 - 2013 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die ;

require_once JPATH_SITE . '/components/com_flexbanners/helpers/flexbanner.php';

jimport('joomla.application.component.helper');
$id = intval(JRequest::getVar('id', NULL));
$task = JRequest::getVar('task', NULL);

switch($task) {
	case 'click' :
		clickFlexBanner($id);
		break;
	default :
		disableOldBanners();
		activateBanners();
		resetImpressions();
		break;
}

function clickFlexBanner($id) {

	if ($id) {

		$botlist = "/(google|msnbot|rambler|yahoo|abachobot|accoona|dotbot|aciorobot|aspseek|cococrawler|dumbot|fast-webcrawler|geonabot|gigabot|lycos|msrbot|scooter|altavista|idbot|estyle|scrubby|googlebot|yahoo! slurp|voilabot|zyborg|webcrawler|deepindex|teoma|appie|henrilerobotmirago|psbot|szukacz|openbot|naver)+/i";
		$isBrowser = true;

		if (!isset($_SERVER['HTTP_USER_AGENT'])) {
			$isBrowser = false;
		} else {
			if (preg_match($botlist, strtolower($_SERVER['HTTP_USER_AGENT'])))
				$isBrowser = false;
		}

		$mainframe = JFactory::getApplication();
		$database = JFactory::getDBO();
		$config = JFactory::getConfig();
		$flexbanner = new flexAdBanner($database);
		$flexbanner -> load($id);

		if ($flexbanner -> id) {
			if ($isBrowser == true) {
				$flexbanner -> clicks += 1;
				$flexbanner -> store();
			}
			$link = new flexAdLink($database);
			$link -> load($flexbanner -> linkid);
			$mainframe -> redirect($link -> linkurl);
		} else { $mainframe -> redirect($link -> linkurl);
		}
	}
}

function resetImpressions() {
	$mainframe = JFactory::getApplication();
	$database = JFactory::getDBO();
	$config = JFactory::getConfig();
	$sql = "UPDATE `#__flexbanners` SET dailyimpressions=0, lastreset='" . date('Y-m-d') . "'
          WHERE lastreset< '" . date('Y-m-d') . "' or lastreset IS NULL ";
	$database -> setQuery($sql);
	$database -> query();
}

function disableOldBanners() {
	$mainframe = JFactory::getApplication();
	$database = JFactory::getDBO();
	$config = JFactory::getConfig();
	$sql = "UPDATE `#__flexbanners` SET finished=1, state=0
          WHERE (enddate < '" . date('Y-m-d H:i:s') . "' and enddate <> '0000-00-00 00:00:00'  and state<>2 and state<>-2)
             OR (impmade >= maximpressions AND maximpressions <> 0 )
             OR (clicks >= maxclicks AND maxclicks <> 0)";
	$database -> setQuery($sql);
	$database -> query();
}

function activateBanners() {
	$mainframe = JFactory::getApplication();
	$database = JFactory::getDBO();
	$config = JFactory::getConfig();
	$sql = "UPDATE `#__flexbanners` SET state=1
          WHERE startdate<= '" . date('Y-m-d H:i:s') . "' and finished=0  and startdate <> '0000-00-00 00:00:00' and state<>2 and state<>-2";
	$database -> setQuery($sql);
	$database -> query();
}
