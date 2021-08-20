<?php
/**
 * @copyright Copyright (C) 2009 - 2012 inch communications ltd
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die ;

// Include the functions only once
require_once JPATH_SITE . '/components/com_flexbanners/helpers/flexbanners.php';

// locationid must be an integer
$locationid = intval($params -> get('locationid', ''));
$moduleclass_sfx = htmlspecialchars($params -> get('moduleclass_sfx'));

$botlist = "/(google|msnbot|rambler|yahoo|abachobot|accoona|dotbot|aciorobot|aspseek|cococrawler|dumbot|fast-webcrawler|geonabot|gigabot|lycos|msrbot|scooter|altavista|idbot|estyle|scrubby|googlebot|yahoo! slurp|voilabot|zyborg|webcrawler|deepindex|teoma|appie|henrilerobotmirago|psbot|szukacz|openbot|naver)+/i";
$isBrowser = true;

if( !isset( $_SERVER['HTTP_USER_AGENT'])){
    $isBrowser = false;
}else{
if (preg_match($botlist, strtolower($_SERVER['HTTP_USER_AGENT'])))
	$isBrowser = false;
}
$app = JFactory::getApplication();
$flexbannerid = intval(JRequest::getVar('id', NULL));
$task = NULL;
$menu = $app -> getMenu();
//if ($menu -> getActive() == $menu -> getDefault()) { $task = "frontpage";
//} ELSE {
	$task = JRequest::getVar('view', NULL);
//}
$loadlast = ($params -> get('loadlast', 0));
$enablecsa = ($params -> get('enablecsa', 0));
$enabletrans = ($params -> get('enabletrans', 0));
$enablenofollow = ($params -> get('enablenofollow', 0));
$details = array("sectionid" => NULL, "categoryid" => NULL, "contentid" => NULL, "langaugeid" => NULL, "frontpage" => NULL);
$blankimageurl = JURI::base() . JRoute::_('modules/mod_flexbanners/trans.gif');
$numberbanner = ($params -> get('numberbanner', 1));
$orderbanner = ($params -> get('ordering', 1));
$headerText	= trim($params->get('header_text'));
$footerText	= trim($params->get('footer_text'));
$nofollow = '';
if ($enablenofollow) {
	$nofollow = ' rel="nofollow"';
}
$database = JFactory::getDBO();
$query = $database -> getQuery(true);
$conf = JFactory::getConfig();
$fb_language = 0;
$iso_client_lang = $conf -> get('language');
$iso_client_lang = '"' . $iso_client_lang . '"';
$flexbanners2 = array();
$user   = JFactory::getUser();
$groups = implode(',', $user->getAuthorisedViewLevels());


//Get the active menu item
switch($task) {

	case 'article' :
		$contentitem = new flexAdContent($database);
		$contentitem -> load($flexbannerid);
		$details = array("sectionid" => $contentitem -> sectionid, "categoryid" => $contentitem -> catid, "contentid" => $contentitem -> id);
		break;
	case 'blogcategory' :

	case 'category' :
		$categoryid = $flexbannerid;
		$category = new flexAdCategories($database);
		$category -> load($flexbannerid);
		$details = array("sectionid" => $category -> section, "categoryid" => $category -> id, "contentid" => NULL);
		break;
	case 'blogsection' :

	case 'section' :
		$details = array("sectionid" => $flexbannerid, "categoryid" => NULL, "contentid" => NULL);
		break;
	case 'frontpage' :
		$details = array("sectionid" => NULL, "categoryid" => NULL, "contentid" => NULL, "langaugeid" => NULL, "frontpage" => 1);
		break;
	default :

		// echo "Not in a category, section or content item view";
		break;
}

$contentif = '';

if ($enablecsa) {
	$contentif = modFlexBannersHelper::FlexBannersQuery($details);
}

		$query->select(
		[
					'a.id',
					'a.catid',
		            'a.imageurl',
		            'a.flash',
		            'a.cloud_imageurl',
		            'a.imagealt',
		            'a.type',
		            'a.customcode',
		            'a.startdate',
		            'a.enddate',
		            'a.lastreset',
		            'a.impmade',
		            'a.clicks',
		            'a.maximpressions',
		            'a.maxclicks',
		            'a.linkid',
		            'a.language',
		            'a.newwin',
		            'a.restrictbyid',
		            'a.dailyimpressions',
		            's.height',
       		        's.width',
						            
		     ]
			)
		->from('#__flexbanners AS a')
			->join('INNER', '#__flexbannerssize AS s USING (sizeid)')
			->join('INNER', '#__categories AS c')
			->where(('a.locationid') . ' = ' . $locationid ." ". $contentif)
			->where(('a.state') . ' = 1')
			->where(('a.finished') . ' = 0')
			->where('('.(('a.language') . ' = ' . $iso_client_lang . ' or ' . ('a.language') . ' = "*"').')')
			->where(('c.id') . ' = ' . ('a.catid'))
			->where(('c.access') . ' IN ( ' . $groups . ' )'
			); 

		$database -> setQuery($query);

		try {
				$flexbanners = $database -> loadObjectList();
			} catch (RuntimeException $e) {
				JError::raiseWarning(500, $e -> getMessage());
				return false;
			}
	
	$newwindow = ($params -> get('newwin', 0));


if (count($flexbanners) > 0) {

	if ($orderbanner == "up") { asort($flexbanners);
	}
	if ($orderbanner == "random") {
		// Randomise the banner sequence
		shuffle($flexbanners);
	}

	// Adjust the banner count if too few selected
	$numberbanner = min($numberbanner, count($flexbanners));
	$flexbanners2 = array_slice($flexbanners, 0, $numberbanner);
}
if (!empty($flexbanners2)) {
// Display the selected banners
require (JModuleHelper::getLayoutPath('mod_flexbanners','header'));
foreach ($flexbanners2 as $flexbannernow) {
	$flexbannerdetails = new flexAdBanner($database);
	$flexbannerdetails -> load($flexbannernow -> id);

	$link = JRoute::_('index.php?option=com_flexbanners&amp;task=click&amp;id=' . $flexbannernow -> id);
	if ( $flexbannernow -> type == 3 ) {
		$imageurl = $flexbannernow -> cloud_imageurl;
	} elseif ( $flexbannernow -> type == 1 ){	
		$imageurl = JURI::base() . JRoute::_('/images/banners/' . str_replace(" ", "%20", $flexbannernow -> flash));
	} else {
		$imageurl = JURI::base() . JRoute::_(str_replace(" ", "%20", $flexbannernow -> imageurl));
	}
	$flexbannerwidth = $flexbannernow -> width;
	$flexbannerheight = $flexbannernow -> height;
	$flexbannerimagealt = $flexbannernow -> imagealt;
	$newwindow = $flexbannernow -> newwin;

	if ($flexbannernow -> type == 2) {
		trim($flexbannernow -> customcode);
		echo stripslashes($flexbannernow -> customcode);
	} elseif (($flexbannernow -> type == 1) && (preg_match("/swf/", $imageurl))) {
		$flexbannerdisplay = modFlexBannersHelper::FlexBannersSWF($flexbannerwidth, $flexbannerheight, $link, $imageurl, $blankimageurl, $newwindow, $moduleclass_sfx, $nofollow);
		require (JModuleHelper::getLayoutPath('mod_flexbanners'));
	} elseif ($flexbannernow -> type == 1)  {
		$flexbannerdisplay = modFlexBannersHelper::FlexBannersHtml5($flexbannerwidth, $flexbannerheight, $link, $imageurl, $blankimageurl, $newwindow, $moduleclass_sfx, $nofollow, $blankimageurl);
		require (JModuleHelper::getLayoutPath('mod_flexbanners'));
	} else {
		if ($loadlast) {
			$flexbannerdisplay = modFlexBannersHelper::FlexBannersloadlast($flexbannerwidth, $flexbannerheight, $link, $imageurl, $flexbannerimagealt, $newwindow, $moduleclass_sfx, $nofollow);
			require (JModuleHelper::getLayoutPath('mod_flexbanners'));
		} else {
			$flexbannerdisplay = modFlexBannersHelper::FlexBannersloadfirst($flexbannerwidth, $flexbannerheight, $link, $imageurl, $flexbannerimagealt, $newwindow, $moduleclass_sfx, $nofollow);
			require (JModuleHelper::getLayoutPath('mod_flexbanners'));
		}
	}
	if ($isBrowser == 1) {
		$flexbannerdetails -> impmade ++;
		$flexbannerdetails -> dailyimpressions ++;
	}

	$flexbannerdetails -> store();
}
require (JModuleHelper::getLayoutPath('mod_flexbanners','footer'));
}
?>
