<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class FlexbannersControllerClient extends JControllerAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_FLEXBANNERS_BANNER';

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Client', $prefix = 'FlexbannersModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

}