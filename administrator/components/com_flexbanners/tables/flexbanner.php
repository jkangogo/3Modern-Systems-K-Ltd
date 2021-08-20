<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

class FlexbannersTableFlexbanner extends JTable
{
	/**
	 * Constructor
	 *
	 * @since	1.5
	 */
	public function __construct($_db)
	{
		parent::__construct('#__flexbanners', 'id', $_db);

		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_flexbanners.flexbanner'));

		$this->created = JFactory::getDate()->toSql();
		$this->setColumnAlias('published', 'state');
	}

	public function clicks()
	{
		$query = 'UPDATE #__flexbanners'
		. ' SET clicks = (clicks + 1)'
		. ' WHERE id = ' . (int) $this->id
		;
		$this->_db->setQuery($query);
		$this->_db->query();
	}

	/**
	 * Overloaded check function
	 *
	 * @return	boolean
	 * @see		JTable::check
	 * @since	1.5
	 */
	public function check()
	{
		// Set name
		$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

		// Set alias
		if (trim($this->alias) == '')
		{
			$this->alias = $this->name;
		}

		$this->alias = JApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		// Set imagealt
		$this->imagealt = htmlspecialchars_decode($this->imagealt, ENT_QUOTES);

		// Check the publish down date is not earlier than publish up.
		if ($this->enddate > $this->_db->getNullDate() && $this->enddate < $this->startdate) {
			// Swap the dates.
			$temp = $this->startdate;
			$this->startdate = $this->enddate;
			$this->enddate = $temp;
		}

		// Set ordering
		if ($this->state < 0) {
			// Set ordering to 0 if state is archived or trashed
			$this->ordering = 0;
		} else if (empty($this->ordering)) {
			// Set ordering to last if ordering was 0
			$this->ordering = self::getNextOrder($this->_db->quoteName('catid').'=' . $this->_db->Quote($this->catid).' AND state>=0');
		}

		return true;
	}
	/**
	 * Overloaded bind function
	 *
	 * @param	array		$hash named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = array())
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);

			if((int) $registry->get('width', 0) < 0){
				$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_NEGATIVE_NOT_PERMITTED', JText::_('COM_FLEXBANNERS_FIELD_WIDTH_LABEL')));
				return false;
			}

			if((int) $registry->get('height', 0) < 0){
				$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_NEGATIVE_NOT_PERMITTED', JText::_('COM_FLEXBANNERS_FIELD_HEIGHT_LABEL')));
				return false;
			}

			// Converts the width and height to an absolute numeric value:
			$width = abs((int) $registry->get('width', 0));
			$height = abs((int) $registry->get('height', 0));

			// Sets the width and height to an empty string if = 0
			$registry->set('width', ($width ? $width : ''));
			$registry->set('height', ($height ? $height : ''));

			$array['params'] = (string)$registry;
		}

		if (isset($array['imptotal'])) {
			$array['imptotal'] = abs((int) $array['imptotal']);
		}

		return parent::bind($array, $ignore);
	}
	/**
	 * method to store a row
	 *
	 * @param boolean $updateNulls True to update fields even if they are null.
	 */
	public function store($updateNulls = false)
	{
		$db = $this->getDbo();

			// Get the old row
			$oldrow = JTable::getInstance('Flexbanner', 'FlexbannersTable');

			if (!$oldrow->load($this->id) && $oldrow->getError())
			{
				$this->setError($oldrow->getError());
			}

			// Store the new row
			parent::store($updateNulls);

			// Need to reorder ?
			if ($oldrow->state>=0 && ($this->state < 0 || $oldrow->catid != $this->catid))
			{
				// Reorder the oldrow
				$this->reorder($this->_db->quoteName('catid').'=' . $this->_db->Quote($oldrow->catid).' AND state>=0');
			}
		return count($this->getErrors())==0;
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param	mixed	An optional array of primary key values to update.  If not
	 *					set the instance property value is used.
	 * @param	integer The publishing state. eg. [0 = unpublished, 1 = published, 2=archived, -2=trashed]
	 * @param	integer The user id of the user performing the operation.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Flexbanner','FlexbannersTable');

		// For all keys
		foreach ($pks as $pk)
		{
			// Load the banner
			if(!$table->load($pk))
			{
				$this->setError($table->getError());
			}

			// Verify checkout
			if($table->checked_out==0 || $table->checked_out==$userId)
			{
				// Change the state
				$table->state = $state;
				$table->checked_out=0;
				$table->checked_out_time=$this->_db->getNullDate();

				// Check the row
				$table->check();

				// Store the row
				if (!$table->store())
				{
					$this->setError($table->getError());
				}
			}
		}
		return count($this->getErrors())==0;
	}

	function flexAdBanner( &$_db ) {
		parent::__construct( '#__flexbanners', 'id', $_db );
	}
}


