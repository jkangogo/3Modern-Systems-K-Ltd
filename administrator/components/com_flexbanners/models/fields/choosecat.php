<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldChooseCat extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'ChooseCat';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
 protected function getInput()
	{
		// Initialize variables.
		$catlist = array();
		$categorylist = array();
		$selectedcategories = array();
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

       // Build Category select list
	$query->select('id, title');
	$query->from('#__categories');
	$query->where('extension = "com_content"');
	$db->setQuery($query);

	$categorylist = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

       	$query	= $db->getQuery(true);

	if ($this->form->getValue('id')){
		$query->select('bannerid, categoryid as id');
  		$query->from('#__flexbannersin');
		$query->where('bannerid = '. (int)$this->form->getValue('id'));
       	$db->setQuery($query);

	  $selectedcategories = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

	}

	foreach ($categorylist as $categoryentry){
          $catlist[] = JHTML::_('select.option',$categoryentry->id, $categoryentry->title, 'id','title');
        }
        return JHTML ::_('select.genericlist',$catlist, 'categoryid[]', 'class="inputbox" size="5" multiple="multiple"', 'id', 'title', $selectedcategories);
	}
}
