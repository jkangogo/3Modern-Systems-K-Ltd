<?php
/**
* @copyright Copyright (C) 2009-2013 inch communications ltd. All rights reserved.
* @license     GNU General Public License version 2 or later.
*/

// no direct access
defined('_JEXEC') or die;

class FlexbannersTableClient extends JTable
{
	function __construct($_db)
	{
		parent::__construct('#__flexbannersclient', 'clientid', $_db);
	}

}
