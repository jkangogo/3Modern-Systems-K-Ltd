<?php
/**
* @copyright Copyright (C) 2009-2020 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// No direct access.
defined('_JEXEC') or die;

class FlexbannersModelFlexbanner extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_FLEXBANNERS_FLEXBANNER';
	public $typeAlias = 'com_flexbanners.flexbanner';
	protected $batch_copymove = 'category_id';

	protected $batch_commands = array(
		'client_id'   => 'batchClient',
		'language_id' => 'batchLanguage'
	);

	protected function batchClient($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();

		/** @var FlexbannersTableFlexbanner $table */
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if (!$user->authorise('core.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			$table->reset();
			$table->load($pk);
			$table->cid = (int) $value;

			if (!$table->store())
			{
				$this->setError($table->getError());

				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		if (empty($record->id) || $record->state != -2)
		{
			return false;
		}

		if (!empty($record->catid))
		{
			return JFactory::getUser()->authorise('core.delete', 'com_flexbanners.category.' . (int) $record->catid);
		}

		return parent::canDelete($record);
	}

	public function generateTitle($categoryId, $table)
	{
		// Alter the title & alias
		$data = $this->generateNewTitle($categoryId, $table->alias, $table->name);
		$table->name = $data['0'];
		$table->alias = $data['1'];
	}

	protected function canEditState($record)
	{
		// Check against the category.
		if (!empty($record->catid))
		{
			return JFactory::getUser()->authorise('core.edit.state', 'com_flexbanners.category.' . (int) $record->catid);
		}

		// Default to component settings if category not known.
		return parent::canEditState($record);
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
		$form = $this->loadForm('com_flexbanners.flexbanner', 'flexbanner', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('flexbanner.id'))
		{
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('startdate', 'disabled', 'true');
			$form->setFieldAttribute('enddate', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('startdate', 'filter', 'unset');
			$form->setFieldAttribute('enddate', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
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
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_flexbanners.edit.flexbanner.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('flexbanner.id') == 0) 
			{
				$filters     = (array) $app->getUserState('com_flexbanners.flexbanners.filter');
				$filterCatId = isset($filters['category_id']) ? $filters['category_id'] : null;

				$data->set('catid', $app->input->getInt('catid', $filterCatId));
			}
		}

		return $data;
	}

	 /**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		return array(
			'catid = ' . (int) $table->catid,
			'state >= 0'
		);
	}

	/**
	 * @since  3.0
	 */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if (empty($table->id))
		{
			// Set the values
			$table->created	= $date->toSql();
			$table->created_by = $user->id;

			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from('#__flexbanners');

				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified    = $date->toSql();
			$table->modified_by = $user->id;
		}
		// Increment the content version number.
		$table->version++;
	}

	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		if ($this->canCreateCategory())
		{
			$form->setFieldAttribute('catid', 'allowAdd', 'true');

			// Add a prefix for categories created on the fly.
			$form->setFieldAttribute('catid', 'customPrefix', '#new#');
		}

		parent::preprocessForm($form, $data, $group);
	}
	public function save($data)
	{
		$input = JFactory::getApplication()->input;

		$context		= "$this->option.edit.$this->context";

		JLoader::register('CategoriesHelper', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/categories.php');

		// Create new category, if needed.
		$createCategory = true;

		// If category ID is provided, check if it's valid.
		if (is_numeric($data['catid']) && $data['catid'])
		{
			$createCategory = !CategoriesHelper::validateCategoryId($data['catid'], 'com_flexbanners');
		}

		// Save New Category
		if ($createCategory && $this->canCreateCategory())
		{
			$table              = array();

			// Remove #new# prefix, if exists.
			$table['title'] = strpos($data['catid'], '#new#') === 0 ? substr($data['catid'], 5) : $data['catid'];
			$table['parent_id'] = 1;
			$table['extension'] = 'com_flexbanners';
			$table['language']  = $data['language'];
			$table['published'] = 1;

			// Create new category and get catid back
			$data['catid'] = CategoriesHelper::createCategory($table);
		}

		// Alter the name for save as copy
		if ($input->get('task') == 'save2copy')
		{
			/** @var FlexbannersTableFlexbanner $origTable */
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['name'] == $origTable->name)
			{
				list($name, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['name']);
				$data['name']       = $name;
				$data['alias']      = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['state'] = 0;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Deal with restrictions
		$key = JRequest::getInt('id');
		$query->delete();
		$query->from($db->quoteName('#__flexbannersin'));

		// Filter by banner id
		$query->where('bannerid = '. (int)$key);
		$db->setQuery((string)$query);

		$db->setQuery($query);
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			// Throw database error exception.
			throw new Exception($db->getErrorMsg(), 500);
		}

		if ($data[restrictbyid])
		{
			$flexbannercategories = JRequest::getVar('categoryid', array(), 'post', 'array');

			foreach($flexbannercategories as $flexbannercategory)
			{
				$query = "INSERT into #__flexbannersin SET bannerid= $data[id], categoryid = $flexbannercategory";
				$db->setQuery((string)$query);
				$db->query();

				// Check for a database error.
				if ($db->getErrorNum())
				{
					// Throw database error exception.
					throw new Exception($db->getErrorMsg(), 500);
				}
			}

			$flexbannercontents = JRequest::getVar('contentid', array(), 'post', 'array');

			foreach($flexbannercontents as $flexbannercontent)
			{
				$query = "INSERT into #__flexbannersin SET bannerid= $data[id], contentid = $flexbannercontent";
				$db->setQuery((string)$query);
				$db->query();

				// Check for a database error.
				if ($db->getErrorNum())
				{
					// Throw database error exception.
					throw new Exception($db->getErrorMsg(), 500);
				}
			}
//			return false;
		}

		// Reset finished if in date
		if ($data[enddate]>date("Y-m-d H:i:s") or $data[enddate]="0000-00-00 00:00:00") { $data[finished]=0;}
		
		// Attempt to save the configuration.
		return parent::save($data);

	}


	private function canCreateCategory()
	{
		return JFactory::getUser()->authorise('core.create', 'com_flexbanners');
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

		// Check for duplication of an existing banner name
		if (!empty($data['name']))
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('count(*)')
				->from('#__flexbanners')
				->where('name=' . $db->quote($data['name']))
				->where('state IN (0,1)');

			if (!empty($data['id']))
			{
				$query->where('id<>' . (int) $data['id']);
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
				$this->setError(JText::_('ADMIN_FLEXBANNER_DUPLICATE_BANNERNAME'));
			}
		}

		return $return;
	}
}

