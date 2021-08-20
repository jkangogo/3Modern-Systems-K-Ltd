<?php
/**
 * @copyright Copyright (C) 2009 - 2021 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class Com_FlexbannersInstallerScript {

	/**
	 * Renders the post-installation message
	 */
	function postflight($type, $parent) {

		// Install modules and plugins -- BEGIN

		// -- General settings
		jimport('joomla.installer.installer');
		$db = JFactory::getDBO();
		$status = new JObject();
		$status -> modules = array();
		$src = $parent->getParent()->getPath('source');

		// -- FlexBanner module
		$installer = new JInstaller;
		$result = $installer->install($src . '/administrator/modules/mod_flexbanners');
		$status -> modules[] = array('name' => 'mod_flexbanners', 'client' => 'administrator', 'result' => $result);

		// Install modules and plugins -- END

	}
function com_uninstall() {

 
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
	// remove all language files
	$files = JFolder::files(JPATH_SITE.'/administrator/language/flexbanners', true, true);
	foreach ($files as $file) {
		if (JFile::exists($file)) {
			JFile::delete($file);
		}
	}
  //echo JText::_("FlexBanners Uninstalled");
}



}
?>   