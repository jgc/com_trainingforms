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
 * trainingforms Component Category Tree
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_trainingforms
 * @since       1.6
 */
class trainingformsCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__trainingforms';
		$options['extension'] = 'com_trainingforms';
		parent::__construct($options);
	}
}
