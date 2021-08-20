<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
*/


// No direct access.
defined('_JEXEC') or die;

class FlexbannersControllerSizes extends JControllerAdmin
{
	/**
	 * @var		string	The context for persistent state.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_FLEXBANNERS_SIZES';

	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the model class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Size', $prefix = 'FlexbannersModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

}