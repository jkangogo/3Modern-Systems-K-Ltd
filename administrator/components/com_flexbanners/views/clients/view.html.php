<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
*/


// No direct access
defined('_JEXEC') or die;

JLoader::register('FlexbannersHelper', JPATH_ADMINISTRATOR . '/components/com_flexbanners/helpers/flexbanners.php');


class FlexbannersViewClients extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		FlexbannersHelper::addSubmenu('clients');
		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo	= FlexbannersHelper::getActions();

		JToolbarHelper::title(JText::_('COM_FLEXBANNERS_MANAGER_CLIENTS'), 'flexbanner.png');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('client.add');
		}
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('client.edit');
		}
		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('clients.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('clients.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('clients.archive');
			JToolbarHelper::checkin('clients.checkin');
		}
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'clients.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('clients.trash');
		}

		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_flexbanners');
			JToolBarHelper::divider();
		}

		JToolbarHelper::help('JHELP_COMPONENTS_FLEXBANNER_SIZES');

	}
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.clientname' => JText::_('ADMIN_FLEXBANNER_CLIENT'),
			'a.state' => JText::_('JSTATUS'),
			'a.contactname' => JText::_('ADMIN_FLEXBANNER_CONTACTNAME'),
			'a.contactemail' => JText::_('ADMIN_FLEXBANNER_CONTACTEMAIL'),
			'a.clientid' => JText::_('JGRID_HEADING_ID'),
		);
	}
}
