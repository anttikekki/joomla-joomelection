<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );


class JoomElectionModelJoomElection extends JModelLegacy {

  function getElections() {
    $langTag = JFactory::getLanguage()->getTag();

    $query = "
      SELECT 
        election.*, 
        election_name_t.translationText AS election_name,
        election_desc_t.translationText AS election_description,
        election_type.type_name AS election_type_name
      FROM #__joomelection_election AS election
      LEFT JOIN #__joomelection_election_type AS election_type ON election.election_type_id = election_type.election_type_id
      LEFT JOIN #__joomelection_translation AS election_name_t ON election.election_id = election_name_t.entity_id 
                                                    AND election_name_t.entity_type = 'election'
                                                    AND election_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND election_name_t.entity_field = 'election_name'
      LEFT JOIN #__joomelection_translation AS election_desc_t ON election.election_id = election_desc_t.entity_id 
                                                    AND election_desc_t.entity_type = 'election'
                                                    AND election_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND election_desc_t.entity_field = 'election_description'
      WHERE election.published = 1
    ";
    $this->_db->setQuery( $query );
    $elections = $this->_db->loadObjectList();
    
    for($i = 0; $i < count($elections); $i++) 
    { 
      $elections[$i]->valid_voter   = $this->getElectionVoterStatus($elections[$i]->election_id );
      $elections[$i]->election_lists  = $this->getElectionListsForElection($elections[$i]->election_id );
      $elections[$i]->options       = $this->getOptions($elections[$i]->election_id );
      $elections[$i]->valid_election   = $this->validElection($elections[$i]->election_id);
    }

    return $elections;
  }
  
  
  function getElection($election_id) {
    $langTag = JFactory::getLanguage()->getTag();

    $query = "
      SELECT 
        election.*,
        election_name_t.translationText AS election_name,
        election_desc_t.translationText AS election_description
      FROM #__joomelection_election AS election
      LEFT JOIN #__joomelection_translation AS election_name_t ON election.election_id = election_name_t.entity_id 
                                                    AND election_name_t.entity_type = 'election'
                                                    AND election_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND election_name_t.entity_field = 'election_name'
        LEFT JOIN #__joomelection_translation AS election_desc_t ON election.election_id = election_desc_t.entity_id 
                                                    AND election_desc_t.entity_type = 'election'
                                                    AND election_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND election_desc_t.entity_field = 'election_description'
      WHERE election.election_id = ". (int) $election_id
    ;
    $this->_db->setQuery( $query );

    return $this->_db->loadObject();
  }
  
  
  
  function getElectionListsForElection($election_id) {
    $langTag = JFactory::getLanguage()->getTag();

    $query = "
      SELECT 
        list.*,
        list_name_t.translationText AS name,
        list_desc_t.translationText AS description
      FROM #__joomelection_list AS list
      LEFT JOIN #__joomelection_translation AS list_name_t ON list.list_id = list_name_t.entity_id 
                                                    AND list_name_t.entity_type = 'list'
                                                    AND list_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND list_name_t.entity_field = 'name'
    LEFT JOIN #__joomelection_translation AS list_desc_t ON list.list_id = list_desc_t.entity_id 
                                                    AND list_desc_t.entity_type = 'list'
                                                    AND list_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND list_desc_t.entity_field = 'description'
      WHERE list.election_id = ". (int) $election_id
    ;
    $this->_db->setQuery( $query );
    return $this->_db->loadObjectList();
  }
  
  
  
  function getElectionListOptions($election_list_Id) {
    $langTag = JFactory::getLanguage()->getTag();
  
    $query = "
      SELECT
        option_name_t.translationText AS name,
        option_desc_t.translationText AS description,
        option.*
      FROM #__joomelection_option AS option
      LEFT JOIN #__joomelection_translation AS option_name_t ON o.option_id = option_name_t.entity_id 
                                                    AND option_name_t.entity_type = 'option'
                                                    AND option_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_name_t.entity_field = 'name'
      LEFT JOIN #__joomelection_translation AS option_desc_t ON o.option_id = option_desc_t.entity_id 
                                                    AND option_desc_t.entity_type = 'option'
                                                    AND option_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_desc_t.entity_field = 'description'
      WHERE option.published = 1
      AND option.list_id = ". (int) $election_list_Id ."
      ORDER BY 1 ASC
    ";
    $this->_db->setQuery( $query );
    $options = $this->_db->loadObjectList();
    
    return $options;
  }
  
  
  
  function getOptions($electionId) {
    $langTag = JFactory::getLanguage()->getTag();
    $input = JFactory::getApplication()->input;
    $orderBy = $input->getString('orderBy', 'number');
    $orderBySql = '';
    
    if($orderBy == 'number') {
      $orderBySql = 'option.option_number ASC';
    }
    else if($orderBy == 'name') {
      $orderBySql = '1 ASC';
    }
    else if($orderBy == 'listName') {
      $orderBySql = '3 ASC';
    }
    
    $query = "
      SELECT
        option_name_t.translationText AS name,
        option_desc_t.translationText AS description,
        list_name_t.translationText AS list_name,
        option.*
      FROM #__joomelection_option AS option
      LEFT JOIN #__joomelection_translation AS option_name_t ON o.option_id = option_name_t.entity_id 
                                                    AND option_name_t.entity_type = 'option'
                                                    AND option_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_name_t.entity_field = 'name'
      LEFT JOIN #__joomelection_translation AS option_desc_t ON o.option_id = option_desc_t.entity_id 
                                                    AND option_desc_t.entity_type = 'option'
                                                    AND option_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_desc_t.entity_field = 'description'
      LEFT JOIN #__joomelection_translation AS list_name_t ON option.list_id = list_name_t.entity_id 
                                                    AND list_name_t.entity_type = 'list'
                                                    AND list_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND list_name_t.entity_field = 'name'
      WHERE option.published = 1
      AND option.election_id = ". (int) $electionId . "
      ORDER BY ". $orderBySql
    ;
    $this->_db->setQuery( $query );
    $options = $this->_db->loadObjectList();
    
    return $options;
  }
  
  function getOption($optionId) {
    $langTag = JFactory::getLanguage()->getTag();

    $query = "
      SELECT
        option_name_t.translationText AS name,
        option_desc_t.translationText AS description,
        list_name_t.translationText AS list_name,
        option.*
      FROM #__joomelection_option AS option
      LEFT JOIN #__joomelection_translation AS option_name_t ON o.option_id = option_name_t.entity_id 
                                                    AND option_name_t.entity_type = 'option'
                                                    AND option_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_name_t.entity_field = 'name'
      LEFT JOIN #__joomelection_translation AS option_desc_t ON o.option_id = option_desc_t.entity_id 
                                                    AND option_desc_t.entity_type = 'option'
                                                    AND option_desc_t.language = " . $this->_db->quote($langTag) . "
                                                    AND option_desc_t.entity_field = 'description'
      LEFT JOIN #__joomelection_translation AS list_name_t ON option.list_id = list_name_t.entity_id 
                                                    AND list_name_t.entity_type = 'list'
                                                    AND list_name_t.language = " . $this->_db->quote($langTag) . "
                                                    AND list_name_t.entity_field = 'name'
      WHERE option.option_id = ". (int) $optionId
    ;
    $this->_db->setQuery( $query );
    $option = $this->_db->loadObject();

    return $option;
  }
  
  function getElectionVoterStatus($electionId) {
    $user  =& JFactory::getUser();
    $valid_voter = false;
    
    //All logged in users can vote
    if( !$user->guest ) {
      
      //Check if election is valid
      $valid_election = $this->validElection($electionId);
      
      if($valid_election) {
        //Get voter status for election. If voted-field = 1, voter has voted for this election.
        $query = "
          SELECT voted 
          FROM #__joomelection_election_voter_status
          WHERE voter_id = ". $user->id ." 
          AND election_id = ". (int) $electionId
        ;
        $this->_db->setQuery( $query );
        $has_voted = $this->_db->loadResult();
        
        //If 1, voter has voted allready in this election
        if((int)$has_voted == 1) {
          $valid_voter = false;
        }
        else {
          //Is user Voter or just Registered Joomla User. Only Voters have right to vote.
          $query = "
            SELECT voter_id 
            FROM #__joomelection_voter
            WHERE voter_id = ". $user->id
          ;
          $this->_db->setQuery( $query );
          $is_voter = $this->_db->loadResult();
          
          if((int)$is_voter > 0) {
            //User id is found from Voter-table. This means that Voter is valid Voter.
            $valid_voter = true;
          }
          else {
            $valid_voter = false;
          }
        }
      }
      else {
        //If election is not valid
        $valid_voter = false;
      }
    }
    else {
      //If any other usegroup except Registered, or not logged-in, not allowed to vote
      $valid_voter = false;
    }

    return $valid_voter;
  }
  
  
  function validOption($optionId) {
    $election_id = $this->getElectionIdFromOption($optionId);
    $valid_election = $this->validElection($election_id);
    
    return $valid_election;
  }
  
  
  function validElection($election_id) {  
    $now = JFactory::getDate();

    $query = "
      SELECT election_id 
      FROM #__joomelection_election
      WHERE published = 1
      AND date_to_open <= ". $this->_db->quote($now->toSql()) ."
      AND date_to_close >= ". $this->_db->quote($now->toSql()) ."
      AND election_id = ". (int) $election_id
    ;
    $this->_db->setQuery( $query );
    $election_id = $this->_db->loadResult();

    if((int)$election_id > 0) {
      $valid_election = true;
    }
    else {
      $valid_election = false;
    }

    return $valid_election;
  }
  
  
  function getElectionIdFromOption($optionId) {
    $query = "
      SELECT election_id 
      FROM #__joomelection_option
      WHERE published = 1
      AND option_id = ". (int) $optionId
    ;
    $this->_db->setQuery( $query );
    $election_id = $this->_db->loadResult();

    return (int) $election_id;
  }
  

  
  
  function storeVote($option_id, $election_id, $user_id) {
    $success = false;
    
    $query = "
      INSERT INTO #__joomelection_election_voter_status (election_id, voter_id, voted)
      VALUES (". $this->_db->quote((int) $election_id) .", ". $this->_db->quote((int) $user_id) .", '1')
    ";
    $this->_db->setQuery( $query );
    if (!$this->_db->query()) {
      // Insert Failed
    }
    else {
      $query = "
        INSERT INTO #__joomelection_vote (option_id)
        VALUES (". $this->_db->quote((int) $option_id) .")
      ";
      $this->_db->setQuery( $query );
      if (!$this->_db->query()) {
        // Insert Failed
      }
      else {
        $success = true;
      }
    }
    return $success;
  }
  
  
  function getVoterName() {
    $user  =& JFactory::getUser();
    return $user->username;
  }
  
  function getLoggedInStatus() {
    $user  =& JFactory::getUser();
    return !$user->guest;
  }
  
  function getOptionIdEncrypted($option_id) {
    $user  =& JFactory::getUser();
    return base64_encode((int) $option_id + (int) $user->id);
  }

  function getOptionIdDecrypted($crypted_option_id) {
    $user  =& JFactory::getUser();
    return ((int) base64_decode($crypted_option_id)) - (int) $user->id;
  }
}
