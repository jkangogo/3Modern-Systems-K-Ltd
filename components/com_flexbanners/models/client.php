<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class FlexbannersModelClient extends JModelList
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
			$config['filter_fields'] = array(
				'id',
				'name',
				'locationid',
				'language',
				'state',
			);

		parent::__construct($config);
	}

	/**
	 * Method to get the maximum ordering value for each category.
	 *
	 * @since	1.6
	 */
	function &getCategoryOrders()
	{
		if (!isset($this->cache['categoryorders'])) {
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			$query->select('MAX(ordering) as `max`, catid');
			$query->select('catid');
			$query->from('#__flexbanners');
			$query->group('catid');
			$db->setQuery($query);
			$this->cache['categoryorders'] = $db->loadAssocList('catid', 0);
		}
		return $this->cache['categoryorders'];

	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id AS id, a.name AS name,'.
				'a.sizeid AS sizeid, a.type AS type,'.
				'a.locationid AS locationid,'.
				'a.imageurl AS imageurl, a.imagealt AS imagealt,' .
				'a.cloud_imageurl AS cloud_imageurl,' .
				'a.customcode AS customcode,'.
				'a.clicks AS clicks, '.
				'a.maxclicks AS maxclicks, '.
				'a.maximpressions AS maximpressions, '.
				'a.impmade AS impmade,' .
				'a.dailyimpressions AS dailyimpressions,' .
				'a.state AS state, a.ordering AS ordering,'.
				'a.published,' .
				'a.language, a.startdate, a.enddate'
				)
		);
		$query->from('`#__flexbanners` AS a');

		// Join over the language
		$query->select('la.title AS language_title');
		$query->join('LEFT', '`#__languages` AS la ON la.lang_code = a.language');
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the clients.
		$query->select('cl.juserid AS juserid');
		$query->join('LEFT', '#__flexbannersclient AS cl ON cl.clientid = a.clientid');

		// Join over the location
		$query->select('loc.locationname as locationname');
		$query->join('LEFT', '#__flexbannerslocations AS loc ON a.locationid = loc.locationid');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.bannerd = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('a.clientname LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
//		$query->order($db->escape($orderCol.' '.$orderDirn));
	$query->order($db->escape($this->getState('list.ordering', 'ordering')).' '.
		$db->escape($this->getState('list.direction', 'ASC')));
		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.state');
		$id .= ':'.$this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Flexbanner', $prefix = 'FlexbannersTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('name', 'asc');
	}
}