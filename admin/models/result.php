<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelResult extends JModelLegacy
{

  function getCandidateElectionResult()
  {
    $input = JFactory::getApplication()->input;
    $election_id = $input->getInt('election_id', 0);
    
    $query = ' SELECT o.option_number, o.name, COUNT(v.option_id) AS votes '
    . ' FROM #__joomelection_option AS o '
    . ' LEFT JOIN #__joomelection_vote AS v ON o.option_id = v.option_id '
    . ' WHERE o.election_id = '. (int) $election_id
    . ' GROUP BY o.option_number'
    . ' ORDER BY 3 DESC'
    ;
    $this->_db->setQuery( $query );
    return $this->_db->loadObjectList();
  }
  
  
  
  function getListElectionResult()
  {
    $input = JFactory::getApplication()->input;
    $election_id = $input->getInt('election_id', 0);
    
    $query = ' SELECT o. option_id, o.option_number, o.name, COUNT(v.option_id) AS votes, list_totals.list_name, list_totals.list_votes '
    . ' FROM #__joomelection_option AS o '
    . ' LEFT JOIN #__joomelection_vote AS v ON o.option_id = v.option_id '
    . ' INNER JOIN ('
      . ' SELECT l.list_id, COUNT(vo.option_id) AS list_votes, l.name AS list_name '
      . ' FROM #__joomelection_list AS l '
      . ' LEFT JOIN #__joomelection_option AS op ON l.list_id = op.list_id '
      . ' LEFT JOIN #__joomelection_vote AS vo ON op.option_id = vo.option_id '
      . ' WHERE l.election_id = '. (int) $election_id
      . ' GROUP BY l.list_id'
    . ' ) list_totals ON list_totals.list_id = o.list_id'
    . ' WHERE o.election_id = '. (int) $election_id
    . ' GROUP BY o.option_id'
    . ' ORDER BY 3 DESC'
    ;
    $this->_db->setQuery( $query );
    $options =  $this->_db->loadObjectList();
    
    return $options;
  }
  
  
  
  function getStatistics()
  {
    $input = JFactory::getApplication()->input;
    $election_id = $input->getInt('election_id', 0);
    
    $query = ' SELECT COUNT(election_id) AS voters_who_voted'
    . ' FROM #__joomelection_election_voter_status '
    . ' WHERE election_id = '. (int) $election_id
    . ' AND voted = 1'
    ;
    $this->_db->setQuery( $query );
    $statistics = $this->_db->loadObject();
    $statistics->voters_who_voted = (int) $statistics->voters_who_voted;
    
    $query = 'SELECT COUNT(v.voter_id)'
    . ' FROM #__joomelection_voter AS v'
    . ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
    ;
    $this->_db->setQuery( $query );
    $statistics->voter_total = $this->_db->loadResult();
    
    if ((int) $statistics->voter_total > 0) {
      $statistics->voted_percentage = ((double) $statistics->voters_who_voted / (double) $statistics->voter_total) * 100;
    }
    else { //Division by zero error
      $statistics->voted_percentage = 0;
    }
    
    $query = 'SELECT election_name '
    . ' FROM #__joomelection_election '
    . ' WHERE election_id = '. (int) $election_id
    ;
    $this->_db->setQuery( $query );
    $statistics->election_name = $this->_db->loadResult();
    $statistics->election_id = $election_id;
    
    return $statistics;
  }
  
  
  
  function resultsInCsv()
  {
    jimport('joomla.utilities.date');
    
    $now            = new JDate('now');    
    $statistics     = $this->getStatistics();
    $electionModel  =& $this->getInstance('election', 'JoomElectionModel');
    $election       = $electionModel->getElection($statistics->election_id);
    $cr             = "\n"; //Carriage return == line change
    $sep            = ";"; //CSV data separator
    $results;
    $csvData        = '';
    
    if($election->election_type_id == 1) {
      //Candidate election
      $results = $this->getCandidateElectionResult();
    }
    else if($election->election_type_id == 2) {
      //List election
      $results = $this->getListElectionResult();
    }
    
    
    //Write election statistics to csv
    $csvData .= utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_FOR' ))                     .$sep. utf8_decode($statistics->election_name) . $cr;
    $csvData .= utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_EXPORTED' ))                .$sep. utf8_decode(JHTML::_('date',  $now, 'd-m-Y H:i:s')) . $cr;
    $csvData .= utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_NUMBER_OF_VOTERS' ))        .$sep. utf8_decode($statistics->voter_total) . $cr;
    $csvData .= utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_NUMBER_VOTERS_WHO_VOTED' )) .$sep. $statistics->voters_who_voted . $cr;
    $csvData .= utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_TURNOUT' ))                 .$sep. $statistics->voted_percentage . $cr;
    
    //Separator
    $csvData .= $cr;
    
    //Write headers to csv
    $csvData .= utf8_decode(JText::_( 'COM_JOOMELECTION_CANDIDATE_NUMBER' )) 
    . $sep . utf8_decode(JText::_( 'COM_JOOMELECTION_CANDIDATE_NAME' )) 
    . $sep . utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_CANDIDATE_VOTES' ));
    
    if($election->election_type_id == 2) {
      $csvData .= $sep . utf8_decode(JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_NAME' )) 
      . $sep . utf8_decode(JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_CANDIDATE_LIST_VOTES' ));
    }
    $csvData .= $cr;
    
    
    //Write data to csv
    for ($i=0, $n=count( $results ); $i < $n; $i++) {    
      $row = &$results[$i];
      $csvData .= $row->option_number . $sep . utf8_decode($row->name) . $sep . $row->votes;
      
      if($election->election_type_id == 2) {
        $csvData .= $sep . utf8_decode($row->list_name) . $sep . $row->list_votes;
      }
      $csvData .= $cr;
    }
    
    
    //Set reasponse headers so browser detects response as csv-file
    header("Content-Type: text/csv;");
    header("Content-Disposition: inline; filename=\"election_result.csv\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    print $csvData;
    
    exit();
  }

}