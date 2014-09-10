<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableElection extends JTable
{
	
	public $election_id = null;
	public $election_type_id = null;
	public $date_to_open = null;
	public $date_to_close = null;
	public $published = null;
	public $confirm_vote = null;
	public $confirm_vote_by_sign = null;


	function TableElection(& $db) {
		parent::__construct('#__joomelection_election', 'election_id', $db);
	}
}
?>
