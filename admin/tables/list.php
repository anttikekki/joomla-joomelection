<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableList extends JTable
{
	var $list_id = null;
	var $election_id = null;
	var $name = null;
	var $description = null;
	var $published = null;


	function TableList(& $db) {
		parent::__construct('#__joomelection_list', 'list_id', $db);
	}
}
?>
