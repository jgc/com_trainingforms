<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_trainingforms
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

/**
 * trainingforms Component Model for a trainingform record
 *
 * @package     Joomla.Site
 * @subpackage  com_trainingforms
 * @since       1.5
 */
class trainingformsModeltrainingform extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $_context = 'com_trainingforms.trainingform';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();
		$params	= $app->getParams();

		// Load the object state.
		$id	= $app->input->getInt('id');
		$this->setState('trainingform.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer	The id of the object to get.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('trainingform.id');
			}

			// Get a level row instance.
			$table = JTable::getInstance('trainingform', 'trainingformsTable');

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			}
			elseif ($error = $table->getError())
			{
				$this->setError($error);
			}
		}

		return $this->_item;
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
	public function getTable($type = 'trainingform', $prefix = 'trainingformsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to increment the hit counter for the trainingform
	 *
	 * @param   integer  $id  Optional ID of the trainingform.
	 *
	 * @return  boolean  True on success
	 */
	public function hit($id = null)
	{
		if (empty($id))
		{
			$id = $this->getState('trainingform.id');
		}

		$trainingform = $this->getTable('trainingform', 'trainingformsTable');
		return $trainingform->hit($id);
	}
}
