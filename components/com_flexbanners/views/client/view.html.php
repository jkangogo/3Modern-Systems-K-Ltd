<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class FlexbannersViewClient extends JViewLegacy
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
		$this->locations	= $this->get('LocationOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

//		require_once JPATH_COMPONENT .'/models/fields/bannerclient.php';
	$this->sortDirection = $this->state->get('list.direction');
	$this->sortColumn = $this->state->get('list.ordering');
		
		parent::display($tpl);
	}

}
