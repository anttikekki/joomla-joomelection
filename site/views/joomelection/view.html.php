<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class JoomElectionViewJoomElection extends JViewLegacy
{
  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
    
    $model          = $this->getModel('joomelection');
    $elections      = $model->getElections();
    $voter_name     = $model->getVoterName();
    $LoggedInStatus = $model->getLoggedInStatus();
    $orderBy        = $input->getString('orderBy', 'number');
    $selectedViewTab = $input->getString('selectedViewTab', 'view_election_candidates');
    
    for($i = 0; $i < count($elections); $i++) 
    { 
      if($elections[$i]->confirm_vote) {
        $task_used = "confirmvote";
      }
      else {
        $task_used = "vote";
      }
      
      if($selectedViewTab == 'view_election_candidates') {
        for($k = 0; $k < count($elections[$i]->options); $k++) 
        { 
          $cryptedOptionId = $model->getOptionIdEncrypted($elections[$i]->options[$k]->option_id);
          $elections[$i]->options[$k]->vote_link = JRoute::_( 'index.php?option=com_joomelection&view=joomelection&task=' .$task_used. '&vote_option='. $cryptedOptionId );
        }
      }
      
      if($selectedViewTab == 'view_election_lists') {
        for($k = 0; $k < count($elections[$i]->election_lists); $k++) {
          $elections[$i]->election_lists[$k]->list_options = $model->getElectionListOptions($elections[$i]->election_lists[$k]->list_id);
        }
      }
    }

    $this->elections        = $elections;
    $this->voter_name       = $voter_name;
    $this->user_logged_in   = $LoggedInStatus;
    $this->orderBy          = $orderBy;
    $this->selectedViewTab  = $selectedViewTab;
    
    parent::display($tpl);
  }
}