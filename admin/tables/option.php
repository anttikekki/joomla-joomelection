<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableOption extends JTable
{
	public $option_id = null;
	public $election_id = null;
	public $list_id = null;
	public $option_number = null;
	public $published = null;


	function TableOption(& $db) {
		parent::__construct('#__joomelection_option', 'option_id', $db);
	}
}
?>
