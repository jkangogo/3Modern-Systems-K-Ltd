<?php
/**
 * @copyright	Copyright (C) 2008 - 2011 Inch Communications Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldFlexbannerLocation extends JFormFieldList
{

	protected $type = 'FlexbannerLocation';

	public function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('locationid AS value, locationname AS text');
		$query->from('#__flexbannerslocations AS a');
		$query->order('a.locationname');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);

		array_unshift($options, JHtml::_('select.option', '0', JText::_('MOD_FLEXBANNER_LOCATIONID')));
//		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('MOD_FLEXBANNER_LOCATIONID').' -', 'locationid','locationname'));

		return $options;
	}
}