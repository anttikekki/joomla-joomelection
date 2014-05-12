<?php


// no direct access
defined('_JEXEC') or die('Restricted access');



class TableVoter extends JTable
{
	public $voter_id = null;
	public $email_sent = null;

	function TableVoter(& $db) {
		parent::__construct('#__joomelection_voter', 'voter_id', $db);
    
    //Voter id primary key is same as Joomla! User id so autoincrement is not needed
    $this->_autoincrement = false;
	}
}
?>
