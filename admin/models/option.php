<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelOption extends JModel
{
	var $_list = null;
	var $_page = null;
	
	function __construct()
	{
		parent::__construct();
	}

	
	function &getOptions()
	{
		global $mainframe;
		
		// Initialize variables
		$db		=& $this->getDBO();
		
		$limit 		= JRequest::getVar('limit', $mainframe->getCfg('list_limit')); 
		$limitstart = JRequest::getVar('limitstart', 0);
		
		// Get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__joomelection_option';
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// Create the pagination object
		jimport('joomla.html.pagination');
		$this->_page = new JPagination($total, $limitstart, $limit);
		
		//Get list data
		$query = ' SELECT o.*, e.election_name '
		. ' FROM #__joomelection_option AS o'
		. ' LEFT JOIN #__joomelection_election AS e ON e.election_id = o.election_id'
		. ' ORDER BY o.option_number'
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
	

	function getOption()
	{
		$option_ids = JRequest::getVar('cid',  array(0), '', 'array');
		
		$query = ' SELECT * FROM #__joomelection_option '.
				'  WHERE option_id = '.(int) $option_ids[0];
		$this->_db->setQuery( $query );
		$option = $this->_db->loadObject();
		
		if($option == null) {
			$option = new stdClass();
			$option->option_id = 0;
			$option->published = 1;
			$option->election_id = 0;
			$option->list_id = 0;
			$option->description = null;
			$option->name = null;
			$option->option_number = null;
		}
		
		return $option;
	}
	
	
	function getOptionFromRequest()	{
		$option =& $this->getTable();
		$data 	= JRequest::get( 'post' );
		if (!$option->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $option;
	}


	function store()	{
		global $mainframe;
		$option 		=& $this->getTable();
		$electionModel 	=& $this->getInstance('election', 'JoomElectionModel');
		$data 			= JRequest::get( 'post' );

		// Bind the form fields to the table
		if (!$option->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		//Validate option name is not empty
		if ((strlen($option->name) > 0) == false) {
			$mainframe->enqueueMessage('You have to give a name to option', 'error');
			return false;
		}
		
		//Validate option number is number and not 0
		if ((((int) $option->option_number) > 0) == false) {
			$mainframe->enqueueMessage('Option number have to valid number (zero is not valid)', 'error');
			return false;
		}
		
		//Validate that election isa selected
		if (($option->election_id > 0) == false) {
			$mainframe->enqueueMessage('You have to select election for option', 'error');
			return false;
		}
		
		$election = $electionModel->getElection($option->election_id);
		if($election->election_type_id == 2) {
			//List election. Validate that list is selected
			if (($option->list_id > 0) == false) {
				$mainframe->enqueueMessage('You have to select election list for option. Create one first i there is none', 'error');
				return false;
			}
		}
		
		$option->description = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWRAW );
		
		// Store the table to the database
		if (!$option->store()) {
			$this->setError( $row->getErrorMsg());
			$mainframe->enqueueMessage('Unable to save option', 'error');
			return false;
		}

		return true;
	}


	function delete()
	{
		$option_Ids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row 		=& $this->getTable();
		$voteModel 	=& $this->getInstance('vote', 'JoomElectionModel');

		if (count( $option_Ids ))		{
			foreach($option_Ids as $option_id) {
				$voteModel->deleteOptionVotes($option_id);
				
				if (!$row->delete( $option_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}						
		}
		
		return true;
	}
	
	
	function deleteElectionOptions($election_id)
	{
		$row 		=& $this->getTable();
		$voteModel 	=& $this->getInstance('vote', 'JoomElectionModel');
		
		$query = 'SELECT * FROM #__joomelection_option '.
			' WHERE election_id = '. (int) $election_id;
		$this->_db->setQuery( $query );
		$options = $this->_db->loadObjectList();
		
		if (count( $options ))		{
			foreach($options as $option) {
				$voteModel->deleteOptionVotes($option->option_id);
				
				if (!$row->delete( $option->option_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}						
		}
		
		return true;
	}
	
	
	function deleteListOptions($list_id)
	{
		$row 		=& $this->getTable();
		$voteModel 	=& $this->getInstance('vote', 'JoomElectionModel');
		
		$query = 'SELECT * FROM #__joomelection_option '.
			' WHERE list_id = '. (int) $list_id;
		$this->_db->setQuery( $query );
		$options = $this->_db->loadObjectList();
		
		if (count( $options ))		{
			foreach($options as $option) {
				$voteModel->deleteOptionVotes($option->option_id);
				
				if (!$row->delete( $option->option_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}						
		}
		
		return true;
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

		$query = 'UPDATE #__joomelection_option'
		. ' SET published = ' . (int) $publish
		. ' WHERE option_id IN ( '. $cids.'  )'
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			return false;
		}
		
		return true;
	}
}
?>
