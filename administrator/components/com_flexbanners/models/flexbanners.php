<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

defined('_JEXEC') or die;
	jimport('joomla.application.component.modellist');

class FlexbannersModelFlexbanners extends JModelList
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
				'id', 'a.id',
				'clientid', 'a.clientid', 'clientname', 'cl.clientname',
				'name', 'a.name',
				'imageurl', 'a.imageurl',
				'cloud_imageurl', 'a.cloud_imageurl',
				'imagealt', 'a.imagealt',
				'restrictbyid', 'a.restrictbyid',
				'frontpage', 'a.frontpage',
				'startdate', 'a.stardate',
				'enddate', 'a.enddate',
				'state', 'a.state',
				'ordering', 'a.ordering',
				'language', 'a.language',
				'catid', 'a.catid', 'category_title',
				'locationid', 'a.locationid', 'locationname', 'loc.locationname',
				'sizeid', 'a.sizeid', 'sizename', 'size.sizename',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'created', 'a.created',
				'impmade', 'a.impmade',
				'clicks', 'a.clicks',
				'maxclicks', 'a.maxclicks',
				'published', 'a.published',
				'state',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get the maximum ordering value for each category.
	 *
	 * @since	1.6
	 */
	public function &getCategoryOrders()
	{
		if (!isset($this->cache['categoryorders']))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->select('MAX(ordering) as ' . $db->quoteName('max') . ', catid')
				->select('catid')
				->from('#__flexbanners')
				->group('catid');
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
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id AS id, a.name AS name,'.
				'a.clientid AS clientid, a.linkid AS linkid,'.
				'a.sizeid AS sizeid, a.type AS type,'.
				'a.locationid AS locationid,'.
				'a.imageurl AS imageurl, a.imagealt AS imagealt,' .
				'a.cloud_imageurl AS cloud_imageurl,' .
				'a.restrictbyid AS restrictbyid, a.frontpage AS frontpage,' .
				'a.customcode AS customcode,'.
				'a.checked_out AS checked_out,' .
				'a.checked_out_time AS checked_out_time, a.catid AS catid,' .
				'a.clicks AS clicks, '.
				'a.maxclicks AS maxclicks, '.
				'a.maximpressions AS maximpressions, '.
				'a.impmade AS impmade,' .
				'a.dailyimpressions AS dailyimpressions,' .
				'a.state AS state, a.ordering AS ordering,'.
				'a.startdate AS startdate,'.
				'a.enddate AS enddate,'.
				'a.published,' .
				'a.language, a.startdate, a.enddate'
				)
		);
		$query->from($db->quoteName('#__flexbanners') . ' AS a');

		// Join over the language
		$query->select('l.title AS language_title')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the categories.
		$query->select('c.title AS category_title')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the clients.
		$query->select('cl.clientname AS client_name')
			->join('LEFT', '#__flexbannersclient AS cl ON cl.clientid = a.clientid');

		// Join over the linkid
		$query->select('link.linkid AS linkid')
			->join('LEFT', '#__flexbannerslink AS link ON link.linkid = a.linkid');

		// Join over the location
		$query->select('loc.locationname as locationname')
		->join('LEFT', '#__flexbannerslocations AS loc ON loc.locationid = a.locationid');

		// Join over the size
		$query->select('size.sizename AS sizename')
		->join('LEFT', '#__flexbannerssize AS size ON size.sizeid = a.sizeid');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$query->where('a.catid = ' . (int) $categoryId);
		}
		// Filter by client.
		$clientId = $this->getState('filter.clientid');
		if (is_numeric($clientId)) {
			$query->where('a.clientid = ' . (int) $clientId);
		}

		// Filter by location.
		$locationId = $this->getState('filter.locationid');
		if (is_numeric($locationId)) {
			$query->where('a.locationid = ' . (int) $locationId);
		}
		// Filter by size.
		$sizeId = $this->getState('filter.sizeid');
		if (is_numeric($sizeId)) {
			$query->where('a.sizeid = ' . (int) $sizeId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE '.$search. ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'ordering');
		$orderDirn = $this->state->get('list.direction', 'ASC');
		if ($orderCol == 'ordering' || $orderCol == 'category_title')
		{
			$orderCol = 'c.title ' . $orderDirn . ', a.ordering';
		}
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

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
		$id .= ':' . $this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.locationid');
		$id	.= ':'.$this->getState('filter.category_id');
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
	protected function populateState($ordering = 'a.name', $direction = 'asc')
	{
		// Initialise variables.

		// Load the filter state.
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
		$this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
		$this->setState('filter.published', $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string'));
		$this->setState('filter.category_id', $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '', 'cmd'));
		$this->setState('filter.locationid', $this->getUserStateFromRequest($this->context . '.filter.locationid', 'filter_locationid', '', 'cmd'));
		$this->setState('filter.clientid', $this->getUserStateFromRequest($this->context . '.filter.clientid', 'filter_clientid', '', 'cmd'));
		$this->setState('filter.sizeid', $this->getUserStateFromRequest($this->context . '.filter.size_id', 'filter_sizeid', '', 'cmd'));
		$this->setState('filter.language', $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '', 'string'));
		$this->setState('filter.level', $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level', '', 'cmd'));

		// Load the parameters.
		$this->setState('params', JComponentHelper::getParams('com_flexbanners'));

		// List state information.
		parent::populateState($ordering, $direction);
	}
}