<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelOption extends JModelLegacy {
  var $_list = null;
  var $_page = null;

  
  function &getPaginatedOptions() {
    $input = JFactory::getApplication()->input;
    $limit = $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
    $limitstart = $input->getInt('limitstart', 0);
    $orderByColumn = $this->_db->escape($input->getString('filter_order', 'o.option_number'));
    $orderByDirection = $this->_db->escape($input->getString('filter_order_Dir', 'ASC'));
    $langTag = JFactory::getLanguage()->getTag();
    
    // Get the total number of records
    $query = 'SELECT COUNT(*)'
    . ' FROM #__joomelection_option';
    $this->_db->setQuery($query);
    $total = $this->_db->loadResult();
    
    // Create the pagination object
    jimport('joomla.html.pagination');
    $this->_page = new JPagination($total, $limitstart, $limit);

    //Sort order check. LEFT JOIN column name to column index
    if($orderByColumn == 'e.election_name') {
      $orderByColumn = '1';
    }
    else if($orderByColumn == 'o.name') {
      $orderByColumn = '5';
    }
    
    //Get candidates
    $query = " 
      SELECT 
        election_t.translationText AS election_name, 
        o.option_id,
        o.election_id, 
        o.list_id,  
        option_name_t.translationText AS name,
        option_desc_t.translationText AS description,
        o.option_number,
        o.published
      FROM #__joomelection_option AS o
      LEFT JOIN #__joomelection_translation AS election_t ON o.election_id = election_t.entity_id 
                                                    AND election_t.entity_type = 'election'
                                                    AND election_t.language = " . $this->_db->quote($langTag) . "
                                                    AND election_t.entity_field = 'election_name'
      LEFT JOIN #__joomelection_translation AS option_name_t ON o.option_id = option_name_t.entity_id 
                                                    AND option_name_t.entity_type = 'option'
                                                    AND option_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_name_t.entity_field = 'name'
      LEFT JOIN #__joomelection_translation AS option_desc_t ON o.option_id = option_desc_t.entity_id 
                                                    AND option_desc_t.entity_type = 'option'
                                                    AND option_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_desc_t.entity_field = 'description'
      ORDER BY $orderByColumn $orderByDirection
    ";
    $this->_list = $this->_getList( $query, $limitstart, $limit );

    //Load translations
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $translationModel->loadTranslationsToObjects($this->_list, 'option', 'option_id');
    
    return $this->_list;
  }
  
  
  
  function &getPagination() {
    if (is_null($this->_list) || is_null($this->_page)) {
      $this->getList();
    }
    return $this->_page;
  }
  

  function getOption() {
    $input = JFactory::getApplication()->input;
    $option_ids = $input->get('cid',  array(), 'array');
    $option_id = (int) $option_ids[0];

    $query = ' SELECT * FROM #__joomelection_option '.
        '  WHERE option_id = '.$option_id ;
    $this->_db->setQuery( $query );
    $option = $this->_db->loadObject();
    
    if($option == null) {
      $option = new stdClass();
      $option->option_id = 0;
      $option->published = 1;
      $option->election_id = 0;
      $option->list_id = 0;
      $option->option_number = null;
    }
    else {
      //Load translations to candidate
      $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
      $translationModel->loadTranslationsToObject($option, 'option', $option_id);
    }
    
    return $option;
  }
  
  
  function getOptionFromRequest()  {
    $input = JFactory::getApplication()->input;
    
    $option =& $this->getTable();
    $data = $input->getArray(); //Get all input
    
    if (!$option->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    return $option;
  }


  function store()  {
    $input = JFactory::getApplication()->input;
    $currentLang =& JFactory::getLanguage();
  
    $option     =& $this->getTable();
    $electionModel   =& $this->getInstance('election', 'JoomElectionModel');
    $data = $input->getArray(); //Get all input

    // Bind the form fields to the table
    if (!$option->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    
    //Validate option name is not empty
    $name = $input->getString('name_'.$currentLang->getTag(), '');
    if ((strlen($name) > 0) == false) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_CANDIDATE_NO_NAME_ERROR'), 'error');
      return false;
    }
    
    //Validate option number is number and not 0
    if ((((int) $option->option_number) > 0) == false) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_CANDIDATE_INVALID_NUMBER_ERROR'), 'error');
      return false;
    }
    
    //Validate that election isa selected
    if (($option->election_id > 0) == false) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_CANDIDATE_NO_ELECTION_ERROR'), 'error');
      return false;
    }
    
    $election = $electionModel->getElection($option->election_id);
    if($election->election_type_id == 2) {
      //List election. Validate that list is selected
      if (($option->list_id > 0) == false) {
        JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_CANDIDATE_NO_LIST_SELECTED_ERROR'), 'error');
        return false;
      }
    }
    
    // Store the table to the database
    if (!$option->store()) {
      $this->setError($option->getErrorMsg());
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_CANDIDATE_SAVE_ERROR'), 'error');
      return false;
    }

    // Store text field translations
    $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
    $languages = JLanguageHelper::getLanguages();
    foreach($languages as $language) {
      $langTag = $language->lang_code;
      $translationModel->saveTranslation($langTag, 'option', $option->option_id, 'name', $input->getString('name_'.$langTag, ''));
      $translationModel->saveTranslation($langTag, 'option', $option->option_id, 'description', $input->getRaw('description_'.$langTag, ''));
    }

    return true;
  }


  function delete() {
    $input = JFactory::getApplication()->input;
  
    $option_Ids = $input->get( 'cid', array(), 'array' );
    $row     =& $this->getTable();
    $voteModel   =& $this->getInstance('vote', 'JoomElectionModel');

    if (count( $option_Ids ))    {
      foreach($option_Ids as $option_id) {
        $voteModel->deleteOptionVotes($option_id);
        
        if (!$row->delete( $option_id )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }

        // Delete translations
        $translationModel->deleteTranslation('option', $option_id);
      }            
    }
    
    return true;
  }
  
  
  function deleteElectionOptions($election_id)
  {
    $row     =& $this->getTable();
    $voteModel   =& $this->getInstance('vote', 'JoomElectionModel');
    
    $query = 'SELECT * FROM #__joomelection_option '.
      ' WHERE election_id = '. (int) $election_id;
    $this->_db->setQuery( $query );
    $options = $this->_db->loadObjectList();
    
    if (count( $options ))    {
      foreach($options as $option) {
        $voteModel->deleteOptionVotes($option->option_id);
        
        if (!$row->delete( $option->option_id )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }

        // Delete translations
        $translationModel->deleteTranslation('option', $option->option_id);
      }            
    }
    
    return true;
  }
  
  
  function deleteListOptions($list_id)
  {
    $row     =& $this->getTable();
    $voteModel   =& $this->getInstance('vote', 'JoomElectionModel');
    
    $query = 'SELECT * FROM #__joomelection_option '.
      ' WHERE list_id = '. (int) $list_id;
    $this->_db->setQuery( $query );
    $options = $this->_db->loadObjectList();
    
    if (count( $options ))    {
      foreach($options as $option) {
        $voteModel->deleteOptionVotes($option->option_id);
        
        if (!$row->delete( $option->option_id )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }

        // Delete translations
        $translationModel->deleteTranslation('option', $option->option_id);
      }            
    }
    
    return true;
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