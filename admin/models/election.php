<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionModelElection extends JModelLegacy {
  var $_list = null;
  var $_page = null;
  
  
  function &getPaginatedElections() {    
    $input = JFactory::getApplication()->input;
    $limit = $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
    $limitstart = $input->getInt('limitstart', 0);
    $orderByColumn = $this->_db->escape($input->getString('filter_order', 'election_name'));
    $orderByDirection = $this->_db->escape($input->getString('filter_order_Dir', 'ASC'));
    $langTag = JFactory::getLanguage()->getTag();

    //Sort order check. LEFT JOIN column name to column index
    if($orderByColumn == 'election_name') {
      $orderByColumn = '1';
    }
    
    // Get the total number of records
    $query = 'SELECT COUNT(*)'
    . ' FROM #__joomelection_election';
    
    $this->_db->setQuery($query);
    $total = $this->_db->loadResult();
    
    // Create the pagination object
    jimport('joomla.html.pagination');
    $this->_page = new JPagination($total, $limitstart, $limit);
    
    //Get list data
    $query = " 
      SELECT t.translationText AS election_name, e.*
      FROM #__joomelection_election AS e
      LEFT JOIN #__joomelection_translation AS t ON e.election_id = t.entity_id 
                                                    AND t.entity_type = 'election'
                                                    AND t.language = " . $this->_db->quote($langTag) . "
                                                    AND t.entity_field = 'election_name'
      ORDER BY $orderByColumn $orderByDirection
    ";
    $this->_db->setQuery($query, $limitstart, $limit);
    $this->_list = $this->_db->loadObjectList();

    //Load translations
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $translationModel->loadTranslationsToObjects($this->_list, 'election', 'election_id');
    
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
    $query = " 
      SELECT * 
      FROM #__joomelection_election
    ";
    $this->_db->setQuery( $query );
    $result = $this->_db->loadObjectList();

    //Load translations
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $translationModel->loadTranslationsToObjects($result, 'election', 'election_id');

    return $result;
  }
  
  function &getListElections()
  {
    $query = " 
    SELECT * 
    FROM #__joomelection_election
    WHERE election_type_id = 2
    ";
    $this->_db->setQuery( $query );
    $result = $this->_db->loadObjectList();

    //Load translations
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $translationModel->loadTranslationsToObjects($result, 'election', 'election_id');

    return $result;
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


  function getElection($election_id=0) {
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $input = JFactory::getApplication()->input;
    
    //If no election id is given as parameter, try to find one from Request parameters
    if($election_id == 0) {
      $array = $input->get('cid', array(), 'array');
      $election_id = (int) $array[0];
    }
    
    $query = "
      SELECT * 
      FROM #__joomelection_election
      WHERE election_id = ".(int) $election_id;
    $this->_db->setQuery( $query );
    $election = $this->_db->loadObject();
    
    if($election == null) {
      $election = new stdClass();
      $election->election_id = 0;
      $election->election_type_id = 0;
      $election->published = 0;
      $election->confirm_vote = 1;
      $election->confirm_vote_by_sign = 0;
      $election->date_to_open = null;
      $election->date_to_close = null;

      //Load default texts from language files
      $languages = JLanguageHelper::getLanguages();
      foreach($languages as $language) {
        $langTag = $language->lang_code;
        $election->{'confirm_vote_by_sign_description_'.$langTag}  = $translationModel->getLanguageFileString($langTag, 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN_DESCRIPTION_EXAMPLE');
        $election->{'confirm_vote_by_sign_error_'.$langTag}        = $translationModel->getLanguageFileString($langTag, 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN_ERROR_EXAMPLE');
        $election->{'vote_success_description_'.$langTag}          = $translationModel->getLanguageFileString($langTag, 'COM_JOOMELECTION_ELECTION_VOTE_SUCCESS_DESCRIPTION_EXAMPLE' );
        $election->{'election_voter_email_text_'.$langTag}         = $translationModel->getLanguageFileString($langTag, 'COM_JOOMELECTION_ELECTION_VOTER_EMAIL_EXAMPLE');
        $election->{'election_voter_email_header_'.$langTag}       = $translationModel->getLanguageFileString($langTag, 'COM_JOOMELECTION_ELECTION_VOTER_EMAIL_SUBJECT_EXAMPLE');
      }
    }
    else {
      //Load translations to existing election from database
      $translationModel->loadTranslationsToObject($election, 'election', (int) $election_id);
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

    // Store text field translations
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $languages = JLanguageHelper::getLanguages();
    foreach($languages as $language) {
      $langTag = $language->lang_code;
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'election_name', $input->getString('election_name_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'election_description', $input->getRaw('election_description_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'vote_success_description', $input->getRaw('vote_success_description_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'confirm_vote_by_sign_description', $input->getRaw('confirm_vote_by_sign_description_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'confirm_vote_by_sign_error', $input->getRaw('confirm_vote_by_sign_error_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'election_voter_email_header', $input->getString('election_voter_email_header_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'election', $row->election_id, 'election_voter_email_text', $input->getString('election_voter_email_text_'.$langTag, ''));
    }

    return true;
  }


  function delete()
  {
    $input = JFactory::getApplication()->input;
    $election_ids   = $input->get( 'cid', array(), 'array' );
    $listModel     =& $this->getInstance('list', 'JoomElectionModel');
    $optionModel   =& $this->getInstance('option', 'JoomElectionModel');
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $row       =& $this->getTable();

    if (count( $election_ids ))    {
      foreach($election_ids as $election_id) {
        if (!$row->delete( $election_id )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }

        // Delete translations
        $translationModel->deleteTranslation('election', $election_id);
        
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