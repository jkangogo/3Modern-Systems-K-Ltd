<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('_JEXEC') or die;
// Include the functions only once
require_once JPATH_SITE. '/components/com_flexbanners/helpers/flexbanners.php';



if (JRequest::getCmd('option')== "com_flexbanners") {

// Execute the task.
$controller	= JControllerLegacy::getInstance('Flexbanners');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
}