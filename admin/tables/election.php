<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableElection extends JTable
{
	
	var $election_id = null;
	var $election_type_id = null;
	var $election_name = null;
	var $election_description = null;
	var $date_to_open = null;
	var $date_to_close = null;
	var $published = null;
	var $confirm_vote = null;
	var $confirm_vote_by_sign = null;
	var $confirm_vote_by_sign_description = null;
	var $confirm_vote_by_sign_error = null;
	var $vote_success_description = null;
	var $election_voter_email_header = null;
	var $election_voter_email_text = null;


	function TableElection(& $db) {
		parent::__construct('#__joomelection_election', 'election_id', $db);
	}
}
?>
