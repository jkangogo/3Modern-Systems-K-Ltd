<?php
/**
* @copyright Copyright (C) 2009-2012 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
JLoader::register('FlexbannersHelper', JPATH_COMPONENT.'/helpers/flexbanners.php');

class FlexbannersViewSize extends JViewLegacy
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

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
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
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->sizeid == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= FlexbannersHelper::getActions();

		JToolBarHelper::title($isNew ? JText::_('COM_FLEXBANNERS_MANAGER_SIZE_NEW') : JText::_('COM_FLEXBANNERS_MANAGER_SIZE_EDIT'), 'flexbanner.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||$canDo->get('core.create'))) {
			JToolBarHelper::apply('size.apply');
			JToolBarHelper::save('size.save');
		}
		if (!$checkedOut && $canDo->get('core.create')) {

			JToolBarHelper::save2new('size.save2new');
		}
		
		if (empty($this->item->sizeid))  {
			JToolBarHelper::cancel('size.cancel');
		} else {
			JToolBarHelper::cancel('size.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_COMPONENTS_FLEXBANNER_SIZES_EDIT');
	}
}
