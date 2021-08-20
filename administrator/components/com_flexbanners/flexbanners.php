<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_flexbanners')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}


// Execute the task.
$controller	= JControllerLegacy::getInstance('Flexbanners');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
