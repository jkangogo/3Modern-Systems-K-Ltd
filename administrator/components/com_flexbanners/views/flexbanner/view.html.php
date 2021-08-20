<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

JLoader::register('FlexbannersHelper', JPATH_ADMINISTRATOR . '/components/com_flexbanners/helpers/flexbanners.php');

class FlexbannersViewFlexbanner extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{	
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

 		
		JHtml::_('jquery.framework');
		JHtml::_('script', 'media/com_flexbanners/flexbanner.js');

		return parent::display($tpl);
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
		$userId		= $user->id;
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Since we don't track these assets at the item level, use the category id.
		$canDo = JHelperContent::getActions('com_flexbanners', 'category', $this->item->catid);

		JToolBarHelper::title($isNew ? JText::_('COM_FLEXBANNERS_MANAGER_BANNER_NEW') : JText::_('COM_FLEXBANNERS_MANAGER_BANNER_EDIT'), 'bookmark flexbanners');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_flexbanners', 'core.create')) > 0)) {
			JToolBarHelper::apply('flexbanner.apply');
			JToolBarHelper::save('flexbanner.save');

			if ($canDo->get('core.create')) {
				JToolBarHelper::save2new('flexbanner.save2new');
			}
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolbarHelper::save2copy('flexbanner.save2copy');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('flexbanner.cancel');
		}
		else
		{
			if (JComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $canDo->get('core.edit'))
			{
				JToolbarHelper::versions('com_flexbanners.flexbanner', $this->item->id);
			}

			JToolbarHelper::cancel('flexbanner.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_FLEXBANNERS_FLEXBANNERS_EDIT');
	}
}
