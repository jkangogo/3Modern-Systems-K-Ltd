<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

require_once __DIR__ . '/../../helpers/flexbanners.php';
class JFormFieldBannerClient extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'BannerClient';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	public function getOptions()
	{
		return BannersHelper::getClientOptions();
	}
}
