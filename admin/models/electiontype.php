<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelElectiontype extends JModel
{
	
	function __construct()
	{
		parent::__construct();
	}


	function getElectionTypes()
	{
		$query = ' SELECT * FROM #__joomelection_election_type ';
		return $this->_getList($query );
	}

}
?>
