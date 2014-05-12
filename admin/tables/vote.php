<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableVote extends JTable
{
	public $vote_id = null;
	public $option_id = null;


	function TableVote(& $db) {
		parent::__construct('#__joomelection_vote', 'vote_id', $db);
	}
}
?>
