<?php
/**
 * @copyright Copyright (C) 2009 - 2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die ;

	class FlexbannersHelper  extends JHelperContent
	{
		public static function addSubmenu($vName) 
		{
			JHtmlSidebar::addEntry(
			JText::_('ADMIN_FLEXBANNER_MENU_BANNERS'), 
			'index.php?option=com_flexbanners&view=flexbanners', 
			$vName == 'flexbanners'
			);

			JHtmlSidebar::addEntry(
			JText::_('COM_FLEXBANNERS_SUBMENU_CATEGORIES'), 
			'index.php?option=com_categories&extension=com_flexbanners', 
			$vName == 'categories'
			);

			JHtmlSidebar::addEntry(
			JText::_('ADMIN_FLEXBANNER_MENU_CLIENTS'), 
			'index.php?option=com_flexbanners&view=clients', 
			$vName == 'clients'
			);

			JHtmlSidebar::addEntry(
			JText::_('ADMIN_FLEXBANNER_MENU_LINKS'), 
			'index.php?option=com_flexbanners&view=links', 
			$vName == 'links'
			);

			JHtmlSidebar::addEntry(
			JText::_('ADMIN_FLEXBANNER_MENU_LOCATIONS'), 
			'index.php?option=com_flexbanners&view=locations', 
			$vName == 'locations'
			);

			JHtmlSidebar::addEntry(
			JText::_('ADMIN_FLEXBANNER_MENU_SIZES'), 
			'index.php?option=com_flexbanners&view=sizes', 
			$vName == 'sizes'
			);
		}

		public static function getActions($component = '', $section = '', $id = 0) {
			$user = JFactory::getUser();
			$result = new JObject;

			if (empty($categoryId)) {
				$assetName = 'com_flexbanners';
				$level = 'component';
			} else {
				$assetName = 'com_flexbanners.category.' . (int)$categoryId;
				$level = 'category';
			}

			$actions = JAccess::getActions('com_flexbanners', $level);

			foreach ($actions as $action) {
				$result -> set($action -> name, $user -> authorise($action -> name, $assetName));
			}

			return $result;
		}

		/**
		 * @return	boolean
		 * @since	1.6
		 */
		public static function updateReset() {
			$user = JFactory::getUser();
			$db = JFactory::getDBO();
			$nullDate = $db -> getNullDate();
			$now = JFactory::getDate();
			$query = $db -> getQuery(true) -> select('*') -> from('#__flexbanners') -> where($db -> quote($now) . ' >= ' . $db -> quote('reset')) -> where($db -> quoteName('reset') . ' != ' . $db -> quote($nullDate) . ' AND ' . $db -> quoteName('reset') . '!=NULL') -> where('(' . $db -> quoteName('checked_out') . ' = 0 OR ' . $db -> quoteName('checked_out') . ' = ' . (int)$db -> quote($user -> id) . ')');
			$db -> setQuery($query);

			try {
				$rows = $db -> loadObjectList();
			} catch (RuntimeException $e) {
				JError::raiseWarning(500, $e -> getMessage());
				return false;
			}

			JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
			foreach ($rows as $row) {
				$client = JTable::getInstance('Client', 'FlexbannersTable');
				$client -> load($row -> clientid);

				// Update the row ordering field.
				$query -> clear() -> update($db -> quoteName('#__flexbanners')) -> set($db -> quoteName('reset') . ' = ' . $db -> quote($reset)) -> set($db -> quoteName('impmade') . ' = ' . $db -> quote(0)) -> set($db -> quoteName('clicks') . ' = ' . $db -> quote(0)) -> where($db -> quoteName('id') . ' = ' . $db -> quote($row -> id));
				$db -> setQuery($query);

				try {
					$db -> execute();
				} catch (RuntimeException $e) {
					JError::raiseWarning(500, $db -> getMessage());
					return false;
				}
			}
			return true;
		}

		public static function getClientOptions() {
			$options = array();

			$db = JFactory::getDbo();
			$query = $db -> getQuery(true);

			$query -> select('clientid As value, clientname As text');
			$query -> from('#__flexbannersclient AS a');
			$query -> order('a.clientname');

			// Get the options.
			$db -> setQuery($query);

			try {
				$options = $db -> loadObjectList();
			} catch (RuntimeException $e) {
				JError::raiseWarning(500, $e -> getMessage());
			}


			return $options;
		}
	public static function getLocationOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('locationid AS value, locationname AS text');
		$query->from('#__flexbannerslocations AS a');
		$query->order('a.locationname');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		return $options;
	}

	public static function getSizeOptions()
	{
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('sizeid AS value, sizename AS text');
		$query->from('#__flexbannerssize AS a');
		$query->order('a.sizename');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}


		return $options;
	}
	public static function countItems(&$items)
	{
		$config = (object) array(
			'related_tbl'   => 'banners',
			'state_col'     => 'state',
			'group_col'     => 'catid',
			'relation_type' => 'category_or_group',
		);

		return parent::countRelations($items, $config);
	}


}