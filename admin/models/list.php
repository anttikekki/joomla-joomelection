<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelList extends JModelLegacy
{  
  var $_list = null;
  var $_page = null;

  
  function &getPaginatedElectionLists()
  {
      $input = JFactory::getApplication()->input;
    $limit     = $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
    $limitstart = $input->getInt('limitstart', 0);
      $orderByColumn = $this->_db->escape($input->getString('filter_order', 'list.election_id'));
      $orderByDirection = $this->_db->escape($input->getString('filter_order_Dir', 'ASC'));
      $langTag = JFactory::getLanguage()->getTag();
    
    // Get the total number of records
    $query = "
      SELECT COUNT(*)
      FROM #__joomelection_list
    ";
    $this->_db->setQuery($query);
    $total = $this->_db->loadResult();
    
    // Create the pagination object
    $this->_page = new JPagination($total, $limitstart, $limit);

      //Sort order check. LEFT JOIN column name to column index
      if($orderByColumn == 'e.election_name') {
        $orderByColumn = '1';
      }
      else if($orderByColumn == 'list.name') {
        $orderByColumn = '4';
      }
    
    //Get list data
    $query = "
      SELECT 
        election_t.translationText AS election_name, 
        list.election_id, 
        list.list_id,
        list_name_t.translationText AS name,
        list_desc_t.translationText AS description,
        list.published
      FROM #__joomelection_list AS list
      LEFT JOIN #__joomelection_translation AS election_t ON list.election_id = election_t.entity_id 
                                                    AND election_t.entity_type = 'election'
                                                    AND election_t.language = " . $this->_db->quote($langTag) . "
                                                    AND election_t.entity_field = 'election_name'
      LEFT JOIN #__joomelection_translation AS list_name_t ON list.list_id = list_name_t.entity_id 
                                                    AND list_name_t.entity_type = 'list'
                                                    AND list_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND list_name_t.entity_field = 'name'
      LEFT JOIN #__joomelection_translation AS list_desc_t ON list.list_id = list_desc_t.entity_id 
                                                    AND list_desc_t.entity_type = 'list'
                                                    AND list_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND list_desc_t.entity_field = 'description'
        ORDER BY $orderByColumn $orderByDirection
    ";
    $this->_list = $this->_getList( $query, $limitstart, $limit );

      //Load translations
      $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
      $translationModel->loadTranslationsToObjects($this->_list, 'list', 'list_id');
    
    return $this->_list;
  }
  
  
  function &getPagination()
  {
    if (is_null($this->_list) || is_null($this->_page)) {
      $this->getList();
    }
    return $this->_page;
  }
  
  function &getAllElectionLists()
  {
    $query = ' SELECT list.* '
    . ' FROM #__joomelection_list AS list'
    . ' LEFT JOIN #__joomelection_election AS e ON e.election_id = list.election_id'
    ;
    $this->_db->setQuery( $query );
      $result = $this->_db->loadObjectList();

      //Load translations
      $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
      $translationModel->loadTranslationsToObjects($result, 'list', 'list_id');

      return $result;
  }
  

  function getElectionList()
  {
      $input = JFactory::getApplication()->input;
    $array = $input->get('cid',  array(), 'array');
    $election_list_id = (int)$array[0];
    
    $query = " 
      SELECT * 
      FROM #__joomelection_list 
      WHERE list_id = $election_list_id
    ";
    $this->_db->setQuery( $query );
    $list = $this->_db->loadObject();
    
    if($list == null) {
      $list = new stdClass();
      $list->list_id = 0;
      $list->published = 1;
      $list->election_id = 0;
    }
      else {
        //Load translations to candidate list
        $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
        $translationModel->loadTranslationsToObject($list, 'list', $election_list_id);
      }
    
    return $list;
  }
  
  
  function getElectionListsForElection($election_id) {
    //List lists for election
    $query = ' SELECT list.* '
    . ' FROM #__joomelection_list AS list'
    . ' WHERE list.election_id = '. (int)$election_id
    ;
    $this->_db->setQuery( $query );
    $lists = $this->_db->loadObjectList();

      //Load translations
      $translationModel =& $this->getInstance('translation', 'JoomElectionModel');
      $translationModel->loadTranslationsToObjects($lists, 'list', 'list_id');

      return $lists;
  }


  function store()  {
      $input = JFactory::getApplication()->input;
    $row =& $this->getTable();

    $data = $input->getArray(); //Get all input

    // Bind the form fields to the table
    if (!$row->bind($data)) {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }
    
    //Election id is required
    if ($row->election_id == 0) {
      JFactory::getApplication()->enqueueMessage(JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_NO_ELECTIONS' ), 'error');
      return false;
    }
  
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
        $translationModel->saveTranslation($langTag, 'list', $row->list_id, 'name', $input->getString('name_'.$langTag, ''));
        $translationModel->saveTranslation($langTag, 'list', $row->list_id, 'description', $input->getRaw('description_'.$langTag, ''));
      }

    return true;
  }


  function delete()
  {
    $input = JFactory::getApplication()->input;
    $list_ids     = $input->get('cid',  array(), 'array');
    $row       =& $this->getTable();
    $optionModel   =& $this->getInstance('option', 'JoomElectionModel');
      $translationModel =& $this->getInstance('translations', 'JoomElectionModel');

    if (count( $list_ids ))    {
      foreach($list_ids as $list_id) {
        //Delete list
        if (!$row->delete( $list_id )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }
            
            // Delete translations
            $translationModel->deleteTranslation('list', $list_id);
        
        //If list delete is succesfull
        //Delete options for this list and option votes
        $optionModel->deleteListOptions($list_id);
      }            
    }
    
    return true;
  }
  
  
  function deleteElectionLists($election_id)
  {
    $row       =& $this->getTable();
    $optionModel   =& $this->getInstance('option', 'JoomElectionModel');
      $translationModel =& $this->getInstance('translations', 'JoomElectionModel');
    
    //Get all lists for given election id
    $query = 'SELECT * FROM #__joomelection_list '.
      ' WHERE election_id = '. (int) $election_id;
    $this->_db->setQuery( $query );
    $lists = $this->_db->loadObjectList();
    
    if (count( $lists ))    {
      foreach($lists as $list) {
        //Delete list
        if (!$row->delete( $list->list_id )) {
          $this->setError( $row->getErrorMsg() );
          return false;
        }
            
            // Delete translations
            $translationModel->deleteTranslation('list', $list->list_id);
        
        //If list delete is succesfull
        //Delete options for this list and option votes
        $optionModel->deleteListOptions($list->list_id);
      }            
    }
    
    return true;
  }
  
  
  function publish()  {
    $input = JFactory::getApplication()->input;
    $cid    = $input->get('cid',  array(), 'array');
    $task    = $input->getCmd( 'task' );
    $publish  = ($task == 'publish');
    $n      = count( $cid );
    
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
}