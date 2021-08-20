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
if ($fb_language=1) {
// Create the pagination object
jimport('joomla.html.pagination');
$pageNav = new JPagination($total, $limitstart, $limit);

// get list of active languages
$langOptions[] = JHTML::_('select.option', '-1', JText::_('Select Language') );
$langOptions[] = JHTML::_('select.option', 'NULL', JText::_('Select no Translation'));
JoomfishControllerHelper::_setupContentElementCache();

$langActive = $this->_joomfishManager->getLanguages( false ); // all languages even non active once

if ( count($langActive)>0 ) {

// Sort languages before building dropdown list
$sorter = new objectArraySorter();
$sorter->sort($langActive, "name");

foreach( $langActive as $language )
{
$langOptions[] = JHTML::_('select.option', $language->id, $language->name );
}
}
$langlist = JHTML::_('select.genericlist', $langOptions, 'select_language_id',
'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text',
$language_id );

// get list of element names
$elementNames[] = JHTML::_('select.option', '', JText::_('Please select') );
//$elementNames[] = JHTML::_('select.option', '-1', '- All Content elements' );
// force reload to make sure we get them all
$elements = $this->_joomfishManager->getContentElements(true);


if ( count($elements)>0 ) {
// Sort lcontent elements before building dropdown list
$sorter = new objectArraySorter();
$sorter->sort($elements, "Name");

foreach( $elements as $key => $element )
{
$elementNames[] = JHTML::_('select.option', $key, $element->Name );
}

}

$clist = JHTML::_('select.genericlist', $elementNames, 'catid',
'class="inputbox" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $catid );

		$db = &JFactory::getDBO();

		$query = 'SELECT locationid, locationname' .
				' FROM #__flexbannerslocations' .
				' ORDER BY locationid';
		$db->setQuery($query);
		$options = $db->loadObjectList();

		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('MOD_FLEXBANNER_LOCATIONID').' -', 'locationid','locationname'));

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'locationid', 'locationname', $value, $control_name.$name );


	
} else {
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('locationid AS value, locationname AS text');
		$query->from('#__flexbannerslocations AS a');
		$query->order('a.locationid');

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
}