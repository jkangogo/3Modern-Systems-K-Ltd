<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

JLoader::register('FlexbannersHelper', JPATH_COMPONENT.'/helpers/flexbanners.php');

class FlexbannersViewClient extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->clientid == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= FlexbannersHelper::getActions();

		JToolbarHelper::title($isNew ? JText::_('COM_FLEXBANNERS_MANAGER_CLIENT_NEW') : JText::_('COM_FLEXBANNERS_MANAGER_CLIENT_EDIT'), 'flexbanner.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||$canDo->get('core.create')))
		{
			JToolbarHelper::apply('client.apply');
			JToolbarHelper::save('client.save');
		}
		if (!$checkedOut && $canDo->get('core.create')) {

			JToolbarHelper::save2new('client.save2new');
		}
		if (empty($this->item->clientid))
		{
			JToolbarHelper::cancel('client.cancel');
		}
		else
		{
			JToolbarHelper::cancel('client.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_FLEXBANNER_CLINTS_EDIT');
	}
}
