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

class JFormFieldChooseArticle extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'ChooseArticle';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
 protected function getInput()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query1	= $db->getQuery(true);

       // Build Article select list
//	$query->select('id, title');
//	$query->select($query->concatenate(array('title', 'id'),'/'));
//	$query->from('#__content');
//	$query->order('title');
	$query="select id, title, CONCAT(title, '(' , id, ')') AS titleid from #__content order by title";

	$db->setQuery($query);
	$contentlist = $db->loadObjectList();
	if(count($contentlist) < 1)
	$contentlist = array(id=> 0);

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

       	$selectedcontent = array();

	if ($this->form->getInput('id')){
		$query1->select('contentid as id');
  		$query1->from('#__flexbannersin');
		$query1->where('bannerid = '. (int)$this->form->getValue('id'));
       	$db->setQuery($query1);

	  $selectedcontent = $db->loadObjectList();
	}


        return JHTML ::_('select.genericlist',$contentlist, 'contentid[]', 'class="inputbox2" size="20", multiple="multiple"', 'id', 'titleid', $selectedcontent);

	}
}
