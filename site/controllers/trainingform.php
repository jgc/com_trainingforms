<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_trainingforms
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_trainingforms
 * @since       1.5
 */
class trainingformsControllertrainingform extends JControllerForm
{
	/**
	 * @since   1.6
	 */
	protected $view_item = 'form';

	/**
	 * @since   1.6
	 */
	protected $view_list = 'categories';

	/**
	 * Method to add a new record.
	 *
	 * @return  boolean  True if the article can be added, false if not.
	 * @since   1.6
	 */
	public function add()
	{

		if (!parent::add())
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data	An array of input data.
	 * @return  boolean
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', $this->input->getInt('id'), 'int');
		$allow		= null;

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow	= $user->authorise('core.create', $this->option.'.category.'.$categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * @param   array  $data	An array of input data.
	 * @param   string	$key	The name of the key for the primary key.
	 *
	 * @return  boolean
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId)
		{
			$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId)
		{
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option.'.category.'.$categoryId);
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string	$key	The name of the primary key of the URL variable.
	 *
	 * @return  Boolean	True if access level checks pass, false otherwise.
	 * @since   1.6
	 */
	public function cancel($key = 'w_id')
	{
		parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string	$key	The name of the primary key of the URL variable.
	 * @param   string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  Boolean	True if access level check and checkout passes, false otherwise.
	 * @since   1.6
	 */
	public function edit($key = null, $urlVar = 'w_id')
	{
		$result = parent::edit($key, $urlVar);

		return $result;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string	$name	The model name. Optional.
	 * @param   string	$prefix	The class prefix. Optional.
	 * @param   array  $config	Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 * @since   1.5
	 */
	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId	The primary key id for the item.
	 * @param   string	$urlVar		The name of the URL variable for the id.
	 *
	 * @return  string	The arguments to append to the redirect URL.
	 * @since   1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId	= $this->input->getInt('Itemid');
		$return	= $this->getReturnPage();

		if ($itemId)
		{
			$append .= '&Itemid='.$itemId;
		}

		if ($return)
		{
			$append .= '&return='.base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string	The return URL.
	 * @since   1.6
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JUri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		return;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string	$key	The name of the primary key of the URL variable.
	 * @param   string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  Boolean	True if successful, false otherwise.
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = 'w_id')
	{
		// http://stackoverflow.com/questions/16280955/overwriting-jcontrollerform-save-method-to-trim-post-data-has-no-effect
		// http://stackoverflow.com/questions/18108683/jooma-custom-component-router-php-not-being-called
		
		if($_POST['jform']){
	        // Get the original POST data
        	$original = JRequest::getVar('jform', array(), 'post', 'array');
	        $wlink =$original['title'];
	        $wurl =$original['url'];
	        $wdesc =$original['description'];
	        // Trim each of the fields
        	// foreach($original as $key=>$value){
	        //   	$original[$key] = trim($value);
        	//	}
        	// Save it back to the $_POST global variable
        	// JRequest::setVar('jform', $postData, 'post');
    		}
   		 // Finally, save the processed form data
   		 //return parent::save('id', $urlVar);
		//--
			
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result)
		{
			// JC edit
			// http://stackoverflow.com/questions/13500803/getting-post-with-joomla-2-5?rq=1
			// http://stackoverflow.com/questions/16109709/joomla-2-5-creating-component-and-saving-data
			$user = JFactory::getUser();
			$message = '<a href="' . $wurl . '" target="_blank">link to ' . $wlink . ' ' . $wdesc . '</a>';
			$this->setMessage('Well done '.$user->name . ' ' . $message, 'notice');
	                // end of JC edit 
	                	                		
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}

	/**
	 * Go to a trainingform
	 *
	 * @return  void
	 * @since   1.6
	 */
	public function go()
	{
		// Get the ID from the request
		$id = $this->input->getInt('id');

		// Get the model, requiring published items
		$modelLink	= $this->getModel('trainingform', '', array('ignore_request' => true));
		$modelLink->setState('filter.published', 1);

		// Get the item
		$link	= $modelLink->getItem($id);

		// Make sure the item was found.
		if (empty($link))
		{
			return JError::raiseWarning(404, JText::_('COM_TRAININGFORMS_ERROR_TRAININGFORM_NOT_FOUND'));
		}

		// Check whether item access level allows access.
		$user	= JFactory::getUser();
		$groups	= $user->getAuthorisedViewLevels();

		if (!in_array($link->access, $groups))
		{
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Check whether category access level allows access.
		$modelCat = $this->getModel('Category', 'trainingformsModel', array('ignore_request' => true));
		$modelCat->setState('filter.published', 1);

		// Get the category
		$category = $modelCat->getCategory($link->catid);

		// Make sure the category was found.
		if (empty($category))
		{
			return JError::raiseWarning(404, JText::_('COM_TRAININGFORMS_ERROR_TRAININGFORM_NOT_FOUND'));
		}

		// Check whether item access level allows access.
		if (!in_array($category->access, $groups))
		{
			return JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		// Redirect to the URL
		// TODO: Probably should check for a valid http link
		if ($link->url)
		{
			$modelLink->hit($id);
			JFactory::getApplication()->redirect($link->url);
		}
		else
		{
			return JError::raiseWarning(404, JText::_('COM_TRAININGFORMS_ERROR_TRAININGFORM_URL_INVALID'));
		}
	}
}
