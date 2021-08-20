<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later.
*/


// No direct access
defined('_JEXEC') or die;

JLoader::register('FlexbannersHelper', JPATH_ADMINISTRATOR . '/components/com_flexbanners/helpers/flexbanners.php');

class FlexbannersViewLinks extends JViewLegacy
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

		FlexbannersHelper::addSubmenu('links');
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

		JToolbarHelper::title(JText::_('COM_FLEXBANNERS_MANAGER_LINKS'), 'flexbanner.png');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('link.add');
		}
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('link.edit');
		}
		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::publish('links.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('links.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('links.archive');
			JToolbarHelper::checkin('links.checkin');
		}
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'links.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('links.trash');
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
			'a.linkname' => JText::_('ADMIN_FLEXBANNER_LINKNAME'),
			'a.linkurl' => JText::_('ADMIN_FLEXBANNER_LINKURL'),
			'cl.clientname' => JText::_('ADMIN_FLEXBANNER_CLIENT'),
			'a.state' => JText::_('JSTATUS'),
			'a.linkid' => JText::_('JGRID_HEADING_ID')
		);
	}
}
