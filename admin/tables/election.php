<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableElection extends JTable
{
	
	public $election_id = null;
	public $election_type_id = null;
	public $election_name = null;
	public $election_description = null;
	public $date_to_open = null;
	public $date_to_close = null;
	public $published = null;
	public $confirm_vote = null;
	public $confirm_vote_by_sign = null;
	public $confirm_vote_by_sign_description = null;
	public $confirm_vote_by_sign_error = null;
	public $vote_success_description = null;
	public $election_voter_email_header = null;
	public $election_voter_email_text = null;


	function TableElection(& $db) {
		parent::__construct('#__joomelection_election', 'election_id', $db);
	}
}
?>
