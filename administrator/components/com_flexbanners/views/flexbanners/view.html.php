<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;


class FlexbannersViewFlexbanners extends JViewLegacy
{
	protected $categories;
	protected $locations;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->categories	= $this->get('CategoryOrders');
		$this->locations	= $this->get('LocationOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		FlexbannersHelper::addSubmenu('flexbanners');
		$this->addToolbar();

			$this->sidebar = JHtmlSidebar::render();

		require_once JPATH_COMPONENT .'/models/fields/bannerclient.php';
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/flexbanners.php';

		$canDo = FlexbannersHelper::getActions($this->state->get('filter.category_id'));
		$user = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('ADMIN_FLEXBANNER_BANNERMANAGER'), 'flexbanner.png');

			JToolbarHelper::addNew('flexbanner.add');

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('flexbanner.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			if ($this->state->get('filter.state') != 2)
			{
				JToolbarHelper::publish('flexbanners.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('flexbanners.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.state') != -1)
			{
				if ($this->state->get('filter.state') != 2)
				{
					JToolbarHelper::archiveList('flexbanners.archive');
				}
				elseif ($this->state->get('filter.state') == 2)
				{
					JToolbarHelper::unarchiveList('flexbanners.publish');
				}
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::checkin('flexbanners.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'flexbanners.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('flexbanners.trash');
		}
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_flexbanners');
		}
		JToolbarHelper::help('JHELP_COMPONENTS_FLEXBANNERS_BANNERS');

		if(version_compare(JVERSION, '3.0', 'ge')) {
			JHtmlSidebar::setAction('index.php?option=com_flexbanners&view=flexbanners');

			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_CATEGORY'),
				'filter_category_id',
				JHtml::_('select.options', JHtml::_('category.options', 'com_flexbanners'), 'value', 'text', $this->state->get('filter.category_id'))
			);

			JHtmlSidebar::addFilter(
				JText::_('COM_FLEXBANNERS_SELECT_CLIENT'),
				'filter_clientid',
				JHtml::_('select.options', FlexbannersHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.clientid'))
			);

			JHtmlSidebar::addFilter(
				JText::_('COM_FLEXBANNERS_SELECT_LOCATION'),
				'filter_locationid',
				JHtml::_('select.options', FlexbannersHelper::getLocationOptions(), 'value', 'text', $this->state->get('filter.locationid'))
			);

			JHtmlSidebar::addFilter(
				JText::_('COM_FLEXBANNERS_SELECT_SIZE'),
				'filter_sizeid',
				JHtml::_('select.options', FlexbannersHelper::getSizeOptions(), 'value', 'text', $this->state->get('filter.sizeid'))
			);

			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_LANGUAGE'),
				'filter_language',
				JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
			);

			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_state',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
		}
	}

	protected function getSortFields()
	{
		return array(
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.name' => JText::_('COM_FLEXBANNERS_HEADING_NAME'),
			'cl.clientname' => JText::_('ADMIN_FLEXBANNER_CLIENT'),
			'loc.locationname' => JText::_('ADMIN_FLEXBANNER_LOCATION'),
			'size.sizename' => JText::_('ADMIN_FLEXBANNER_SIZE'),
			'a.impmade' => JText::_('ADMIN_FLEXBANNER_BANNERIMPMADE'),
			'a.clicks' => JText::_('ADMIN_FLEXBANNER_BANNERCLICKS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.state' => JText::_('JSTATUS'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
