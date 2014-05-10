<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionModelElection extends JModelLegacy
{
  var $_list = null;
  var $_page = null;
  
  
  function &getElections()
  {    
    $input = JFactory::getApplication()->input;
    $limit = $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
    $limitstart = $input->getInt('limitstart', 0);
    $orderByColumn = $this->_db->escape($input->getString('filter_order', 'election_name'));
    $orderByDirection = $this->_db->escape($input->getString('filter_order_Dir', 'ASC'));
    
    // Get the total number of records
    $query = 'SELECT COUNT(*)'
    . ' FROM #__joomelection_election';
    
    $this->_db->setQuery($query);
    $total = $this->_db->loadResult();
    
    // Create the pagination object
    jimport('joomla.html.pagination');
    $this->_page = new JPagination($total, $limitstart, $limit);
    
    //Get list data
    $query = ' SELECT * '
    . ' FROM #__joomelection_election'
    . ' ORDER BY ' .$orderByColumn. ' ' . $orderByDirection;
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
    $query = ' SELECT * '
    . ' FROM #__joomelection_election'
    ;
    $this->_db->setQuery( $query );
    return $this->_db->loadObjectList();
  }
  
  function &getListElections()
  {
    $query = ' SELECT * '
    . ' FROM #__joomelection_election'
    . ' WHERE election_type_id = 2'
    ;
    $this->_db->setQuery( $query );
    return $this->_db->loadObjectList();
  }
  
  
  function publish()  {
    $input = JFactory::getApplication()->input;
    $cid    = $input->get( 'cid', array(), 'array' );
    $task    = $input->getCmd( 'task' );
    $publish  = ($task == 'publish');
    $n      = count( $cid );

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
    $input = JFactory::getApplication()->input;
    if($election_id == 0) {
      $array = $input->get('cid', array(), 'array');
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


  function store()  {
    $row =& $this->getTable();

    $input = JFactory::getApplication()->input;
    $data = $input->getArray(); //Get all input

    // Bind the form fields to the table
    if (!$row->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    
    $row->election_description      = $input->getHtml( 'election_description', '');
    $row->vote_success_description  = $input->getHtml( 'vote_success_description', '');
    
    //Append time to date
    $row->date_to_open   = substr($input->getString( 'date_to_open', ''), 0, 10) ." ". $input->getString( 'time_to_open', '');
    $row->date_to_close  = substr($input->getString( 'date_to_close', ''), 0, 10) ." ". $input->getString( 'time_to_close', '');
    
    $user = JFactory::getUser();
    $timezone = $user->getParam('timezone');
    
    //Convert to UTC from current server/user timezone so that database allways stores UTC dates
    $date = new JDate($row->date_to_open, $timezone);
    $row->date_to_open = $date->toSql();
    $date = new JDate($row->date_to_close, $timezone);
    $row->date_to_close = $date->toSql();
    
    //UTC conversion without JDate
    //$row->date_to_open  = gmdate($this->_db->getDateFormat(), strtotime($row->date_to_open . ' ' .$timezone) );
    //$row->date_to_close = gmdate($this->_db->getDateFormat(), strtotime($row->date_to_close . ' ' .$timezone));
    
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
    $input = JFactory::getApplication()->input;
    $election_ids   = $input->get( 'cid', array(), 'array' );
    $listModel     =& $this->getInstance('list', 'JoomElectionModel');
    $optionModel   =& $this->getInstance('option', 'JoomElectionModel');
    $row       =& $this->getTable();

    if (count( $election_ids ))    {
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
  
  function isElectionOpen($election) {
    $date_to_open   = new JDate($election->date_to_open);
    $date_to_close  = new JDate($election->date_to_close);
    $now            = new JDate();
    
    if($date_to_open->toUnix() <= $now->toUnix() && $date_to_close->toUnix() >= $now->toUnix()) {
      return true;
    }
    else {
      return false;
    }
  }
}