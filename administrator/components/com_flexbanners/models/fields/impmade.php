<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldImpMade extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'ImpMade';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$onclick = ' onclick="document.getElementById(\'' . $this->id . '\').value=\'0\';"';

		return '<input class="input-small" type="text" name="' . $this->name . '" id="' . $this->id . '" value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" readonly="readonly" /> <a class="btn" ' . $onclick . '>'
			. '<span class="icon-refresh" aria-hidden="true"></span> ' . JText::_('ADMIN_FLEXBANNER_IMP_RESET') . '</a>';
	}
}