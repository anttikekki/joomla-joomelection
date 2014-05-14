<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewResult extends JViewLegacy
{

  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
  
    $resultModel      = &$this->getModel('result');
    $electionModel    = &$this->getModel('election');
    $statistics       = $resultModel->getStatistics();
    $election         = $electionModel->getElection($statistics->election_id);
    $task_opened_from = $input->getString( 'opener_task', '');
    $results;
    
    if($election->election_type_id == 1) {
      //Candidate election
      $results = $resultModel->getCandidateElectionResult();
    }
    else if($election->election_type_id == 2) {
      //List election
      $results = $resultModel->getListElectionResult();
    }
    

    JToolBarHelper::title( JText::_( 'COM_JOOMELECTION_ELECTION_RESULT' ) );
    JToolBarHelper::custom('election.resultsInCsv', 'upload.png', 'upload.png', $alt = JText::_( 'COM_JOOMELECTION_ELECTION_RESULT_TO_CSV' ), $listSelect = false);
    JToolBarHelper::cancel('election.cancel', JText::_( 'COM_JOOMELECTION_CLOSE'));

    $this->assignRef('results',        $results);
    $this->assignRef('election_type_id',  $election->election_type_id);
    $this->assignRef('statistics',      $statistics);
    $this->assignRef('task_opened_from',  $task_opened_from);

    parent::display($tpl);
  }
}
