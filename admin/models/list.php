<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelList extends JModel
{	
	var $_list = null;
	var $_page = null;
	
	function __construct()
	{
		parent::__construct();
	}

	
	function &getElectionLists()
	{
		global $mainframe;
		
		// Initialize variables
		$db		=& $this->getDBO();
		
		$limit 		= JRequest::getVar('limit', $mainframe->getCfg('list_limit')); 
		$limitstart = JRequest::getVar('limitstart', 0);
		
		// Get the total number of records
		$query = 'SELECT COUNT(*)'
		. ' FROM #__joomelection_list';
		$db->setQuery($query);
		$total = $db->loadResult();
		
		// Create the pagination object
		jimport('joomla.html.pagination');
		$this->_page = new JPagination($total, $limitstart, $limit);
		
		//Get list data
		$query = ' SELECT list.*, e.election_name '
		. ' FROM #__joomelection_list AS list'
		. ' LEFT JOIN #__joomelection_election AS e ON e.election_id = list.election_id'
		. ' ORDER BY list.election_id'
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
	

	function getElectionList()
	{
		$array = JRequest::getVar('cid',  0, '', 'array');
		
		$query = ' SELECT * FROM #__joomelection_list '.
				'  WHERE list_id = '.(int)$array[0];
		$this->_db->setQuery( $query );
		$list = $this->_db->loadObject();
		
		if($list == null) {
			$list = new stdClass();
			$list->list_id = 0;
			$list->published = 1;
			$list->election_id = 0;
			$list->description = null;
			$list->name = null;
		}
		
		return $list;
	}


	function store()	{
		global $mainframe;
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if ($row->election_id > 0) {
			$row->description = JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML );
	
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
		else {
			$mainframe->enqueueMessage('You have to create at least one election first before you can create a list. You can not save list with no election.', 'message');
			return false;
		}
	}


	function delete()
	{
		//Get list ids from form POST variable
		$list_ids 		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$row 			=& $this->getTable();
		$optionModel 	=& $this->getInstance('option', 'JoomElectionModel');

		if (count( $list_ids ))		{
			foreach($list_ids as $list_id) {
				//Delete list
				if (!$row->delete( $list_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
				
				//If list delete is succesfull
				//Delete options for this list and option votes
				$optionModel->deleteListOptions($list_id);
			}						
		}
		
		return true;
	}
	
	
	function deleteElectionLists($election_id)
	{
		$row 			=& $this->getTable();
		$optionModel 	=& $this->getInstance('option', 'JoomElectionModel');
		
		//Get all lists for given election id
		$query = 'SELECT * FROM #__joomelection_list '.
			' WHERE election_id = '. (int) $election_id;
		$this->_db->setQuery( $query );
		$lists = $this->_db->loadObjectList();
		
		if (count( $lists ))		{
			foreach($lists as $list) {
				//Delete list
				if (!$row->delete( $list->list_id )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
				
				//If list delete is succesfull
				//Delete options for this list and option votes
				$optionModel->deleteListOptions($list->list_id);
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

		$query = 'UPDATE #__joomelection_list'
		. ' SET published = ' . (int) $publish
		. ' WHERE list_id IN ( '. $cids.'  )'
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			return false;
		}
		
		return true;
	}
	
	
	
	function getElectionListsForElection($election_id) {
		// Initialize variables
		$db	=& $this->getDBO();
		
		//List lists for election
		$query = ' SELECT list.* '
		. ' FROM #__joomelection_list AS list'
		. ' WHERE list.election_id = '. (int)$election_id
		;
		$db->setQuery( $query );
		return $db->loadObjectList();
	}
}
?>
