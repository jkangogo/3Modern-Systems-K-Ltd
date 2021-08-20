<?php
/**
* @copyright Copyright (C) 2009-2012 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');


class FlexbannersController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/flexbanners.php';
		FlexbannersHelper::updateReset();

		$view	= JRequest::getCmd('view', 'client');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');

 		parent::display();

		return $this;
	}
}
