<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_trainingforms
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a trainingform.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_trainingforms
 * @since       1.5
 */
class TrainingformsViewTrainingform extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item	= $this->get('Item');
		$this->form	= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		// Since we don't track these assets at the item level, use the category id.
		$canDo		= TrainingformsHelper::getActions($this->item->catid, 0);

		JToolbarHelper::title(JText::_('COM_TRAININGFORMS_MANAGER_TRAININGFORM'), 'trainingforms.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_trainingforms', 'core.create')))))
		{
			JToolbarHelper::apply('trainingform.apply');
			JToolbarHelper::save('trainingform.save');
		}
		if (!$checkedOut && (count($user->getAuthorisedCategories('com_trainingforms', 'core.create')))){
			JToolbarHelper::save2new('trainingform.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_trainingforms', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('trainingform.save2copy');
		}
		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('trainingform.cancel');
		}
		else
		{
			JToolbarHelper::cancel('trainingform.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMPONENTS_TRAININGFORMS_LINKS_EDIT');
	}
}
