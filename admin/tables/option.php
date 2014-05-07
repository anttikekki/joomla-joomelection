<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableOption extends JTable
{
	var $option_id = null;
	var $election_id = null;
	var $list_id = null;
	var $name = null;
	var $description = null;
	var $option_number = null;
	var $published = null;


	function TableOption(& $db) {
		parent::__construct('#__joomelection_option', 'option_id', $db);
	}
}
?>
