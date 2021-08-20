<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/


defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class FlexbannersModelLinks extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'linkid', 'a.linkid',
				'linkname', 'a.linkname',
				'linkurl', 'a.linkurl',
				'clientid', 'a.clientid',
				'clientname', 'cl.clientname',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'state', 'a.state',
			);
		}

		parent::__construct($config);
	}

	/**
	 * @since	1.6
	 */

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		$clientId = $this->getUserStateFromRequest($this->context.'.filter.client_id', 'filter_clientid', '');
		$this->setState('filter.clientid', $clientId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_flexbanners');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.linkurl', 'asc');
	}
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.linkid as linkid, '.
				'a.linkname AS linkname, '.
				'a.linkurl as linkurl, '.
				'a.clientid AS clientid, '.
				'a.checked_out as checked_out, '.
				'a.checked_out_time AS checked_out_time, ' .
				'a.state AS state'
			)
		);
		$query->from($db->quoteName('#__flexbannerslink').' AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the clients.
		$query->select('cl.clientname AS clientname')
			->join('LEFT', '#__flexbannersclient AS cl ON cl.clientid = a.clientid');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by client.
		$clientId = $this->getState('filter.clientid');
		if (is_numeric($clientId)) {
			$query->where('a.clientid = '.(int) $clientId);
		}

		$query->group('a.linkid, a.linkname, a.clientid, a.checked_out, a.checked_out_time, a.state, editor');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.linkid = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('a.linkname LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'linkname');
		$query->order($db->escape($orderCol).' '.$db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

}