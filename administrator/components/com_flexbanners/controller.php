<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

JLoader::register('FlexbannersHelper', JPATH_ADMINISTRATOR . '/components/com_flexbanners/helpers/flexbanners.php');


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
	public function display($cachable = false, $urlparams = array())
	{

		FlexbannersHelper::updateReset();

		$view   = $this->input->get('view', 'flexbanners');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		// Check for edit form.
		if ($view == 'flexbanner' && $layout == 'edit' && !$this->checkEditId('com_flexbanners.edit.flexbanner', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_flexbanners&view=flexbanners', false));

			return false;
		}
		else if ($view == 'client' && $layout == 'edit' && !$this->checkEditId('com_flexbanners.edit.client', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_flexbanners&view=clients', false));

			return false;
		}
		else if ($view == 'location' && $layout == 'edit' && !$this->checkEditId('com_flexbanners.edit.location', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_flexbanners&view=locations', false));

			return false;
		}
		else if ($view == 'size' && $layout == 'edit' && !$this->checkEditId('com_flexbanners.edit.size', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_flexbanners&view=locations', false));

			return false;
		}

 		return parent::display();

	}
}
