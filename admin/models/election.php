<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelElection extends JModel
{
	var $_list = null;
	var $_page = null;
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function &getElections()
	{
		global $mainframe;
		
		// Initialize variables
		$db		=& $this->getDBO();
		
		$limit = JRequest::getVar('limit', $mainframe->getCfg('list_limit')); 
		$limitstart = JRequest::getVar('limitstart', 0);
		
		// Get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__joomelection_election';
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// Create the pagination object
		jimport('joomla.html.pagination');
		$this->_page = new JPagination($total, $limitstart, $limit);
		
		//Get list data
		$query = ' SELECT * '
		. ' FROM #__joomelection_election'
		;
		$this->_list = $this->_getList( $query, $limitstart, $limit );
		
		return $this->_list;
	}
	
	
	
	function &getPagination()
	{
		if (is_null($this->_list) || is_null($this->_page)) {
			$this->getList();
		}
		return $this->_page;
	}
	
	
	function &getAllElections()
	{
		//Get list data
		$query = ' SELECT * '
		. ' FROM #__joomelection_election'
		;
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	function &getListElections()
	{
		//Get list data
		$query = ' SELECT * '
		. ' FROM #__joomelection_election'
		. ' WHERE election_type_id = 2'
		;
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	
	function publish()	{
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$task		= JRequest::getCmd( 'task' );
		$publish	= ($task == 'publish');
		$n			= count( $cid );

		if (empty( $cid )) {
			return false;
		}

		JArrayHelper::toInteger( $cid );
		$cids = implode( ',', $cid );

		$query = 'UPDATE #__joomelection_election'
		. ' SET published = ' . (int) $publish
		. ' WHERE election_id IN ( '. $cids.'  )'
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			return false;
		}
		
		return true;
	}


	function getElection($election_id=0)
	{
		if($election_id == 0) {
			$array = JRequest::getVar('cid',  array(0), '', 'array');
			$election_id = $array[0];
		}
		
		$query = ' SELECT * FROM #__joomelection_election '.
				'  WHERE election_id = '.(int) $election_id;
		$this->_db->setQuery( $query );
		$election = $this->_db->loadObject();
		
		if($election == null) {
			$election = new stdClass();
			$election->election_id = 0;
			$election->election_type_id = 0;
			$election->election_name = null;
			$election->published = 0;
			$election->confirm_vote = 1;
			$election->confirm_vote_by_sign = 0;
			$election->date_to_open = null;
			$election->date_to_close = null;
			$election->election_description = null;
		}
		
		return $election;
	}


	function store()	{
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		jimport('joomla.utilities.date');
		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		
		$row->election_description 				= JRequest::getVar( 'election_description', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$row->vote_success_description 			= JRequest::getVar( 'vote_success_description', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		$row->date_to_open 			= substr(JRequest::getVar( 'date_to_open', '', 'post', 'string'), 0, 10) ." ". JRequest::getVar( 'time_to_open', '', 'post', 'string');
		$row->date_to_close 		= substr(JRequest::getVar( 'date_to_close', '', 'post', 'string'), 0, 10) ." ". JRequest::getVar( 'time_to_close', '', 'post', 'string');

		$date = new JDate($row->date_to_open, $tzoffset);
		$row->date_to_open = $date->toMySQL();
		
		$date = new JDate($row->date_to_close, $tzoffset);
		$row->date_to_close = $date->toMySQL();
		
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}


	function delete()
	{
		$election_ids 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$listModel 		=& $this->getInstance('list', 'JoomElectionModel');
		$optionModel 	=& $this->getInstance('option', 'JoomElectionModel');
		$row 			=& $this->getTable();

		if (count( $election_ids ))		{
			foreach($election_ids as $election_id) {
				if (!$row->delete( $election_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
				
				$query = 'SELECT * FROM #__joomelection_list '.
				' WHERE election_id = '. (int) $election_id;
				$this->_db->setQuery( $query );
				$election_lists = $this->_db->loadObjectList();
				
				//Does election have lists
				if(count($election_lists) > 0) {
					// Delete lists, options and votes
					$listModel->deleteElectionLists($election_id);
				}
				else {
					$optionModel->deleteElectionOptions($election_id);
				}
				
				$query = ' DELETE FROM #__joomelection_election_voter_status '
				. '  WHERE election_id = '. (int) $election_id;
				$this->_db->setQuery( $query );
				$this->_db->query();
			}						
		}
		return true;
	}
			

}
?>
