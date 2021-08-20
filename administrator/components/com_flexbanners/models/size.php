<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class FlexbannersModelSize extends JModelAdmin
{
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->sizeid)) {
				if ($record->state != -2) {
					return ;
				}
			$user = JFactory::getUser();

			if (!empty($record->catid)) {
				return $user->authorise('core.delete', 'com_flexbanners.category.'.(int) $record->catid);
			}
			else {
				return $user->authorise('core.delete', 'com_flexbanners');
			}
		}
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_flexbanners.category.'.(int) $record->catid);
		}
		else {
			return $user->authorise('core.edit.state', 'com_flexbanners');
		}
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
	public function getTable($type = 'Size', $prefix = 'FlexbannersTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_flexbanners.size', 'size', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_flexbanners.edit.size.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param	JTable	A JTable object.
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		$table->sizename = htmlspecialchars_decode($table->sizename, ENT_QUOTES);
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param   JForm   $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 *
	 */
	public function validate($form, $data, $group = null)
	{
		$return = parent::validate($form, $data, $group);

		// Check for duplication of an existing link name
		if (!empty($data['sizename']))
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('count(*)')
				->from('#__flexbannerssize')
				->where('sizename=' . $db->quote($data['sizename']))
				->where('state IN (0,1)');

			if (!empty($data['sizeid']))
			{
				$query->where('sizeid<>' . (int) $data['sizeid']);
			}

			$db->setQuery($query);
			$db->query();

			$count = (int) $db->loadResult();

			// Check for a database error.
			if ($db->getErrorNum()) {
				JError::raiseWarning(500, $db->getErrorMsg());
			}

			if ($count > 0)
			{
				// A duplicate record has been detected
				$return = false;
				$this->setError(JText::_('ADMIN_FLEXBANNER_DUPLICATE_SIZENAME'));
			}
		}

		return $return;
	}
}