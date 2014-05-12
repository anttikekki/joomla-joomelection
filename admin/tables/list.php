<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableList extends JTable
{
	public $list_id = null;
	public $election_id = null;
	public $name = null;
	public $description = null;
	public $published = null;


	function TableList(& $db) {
		parent::__construct('#__joomelection_list', 'list_id', $db);
	}
}
?>
