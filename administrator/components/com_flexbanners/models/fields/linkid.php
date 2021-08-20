<?php
/**
* @copyright Copyright (C) 2009-2012 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLinkId extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'LinkId';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('linkid AS value, linkname AS text');
		$query->from('#__flexbannerslink AS a');
		$query->order('a.linkname');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

//		array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_FLEXBANNERS_NO_CLIENT')));

		return $options;
	}
}
