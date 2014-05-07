<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelVote extends JModelLegacy
{

	function __construct()
	{
		parent::__construct();
	}

	
	function deleteOptionVotes($option_id)
	{
		$query = ' DELETE FROM #__joomelection_vote '
			. '  WHERE option_id = '. (int) $option_id;
		$this->_db->setQuery( $query );
		$this->_db->query();
	}

}
?>
