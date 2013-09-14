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
 * Trainingforms helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_trainingforms
 * @since       1.6
 */
class TrainingformsHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string	The name of the active view.
	 * @since   1.6
	 */
	public static function addSubmenu($vName = 'trainingforms')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_TRAININGFORMS_SUBMENU_TRAININGFORMS'),
			'index.php?option=com_trainingforms&view=trainingforms',
			$vName == 'trainingforms'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_TRAININGFORMS_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_trainingforms',
			$vName == 'categories'
		);
		if ($vName == 'categories')
		{
			JToolbarHelper::title(
				JText::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', JText::_('com_trainingforms')),
				'trainingforms-categories');
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The category ID.
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_trainingforms';
			$level = 'component';
		}
		else
		{
			$assetName = 'com_trainingforms.category.'.(int) $categoryId;
			$level = 'category';
		}

		$actions = JAccess::getActions('com_trainingforms', $level);

		foreach ($actions as $action)
		{
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
