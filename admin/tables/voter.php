<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableVoter extends JTable
{
	var $voter_id = null;
	var $email_sent = null;

	function TableVoter(& $db) {
		parent::__construct('#__joomelection_voter', 'voter_id', $db);
	}
}
?>
