<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class JoomElectionViewVoteSuccess extends JViewLegacy
{
  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
    
    $model        = $this->getModel('joomelection');
    $voted_option = $model->getOptionIdDecrypted($input->getString('vote_option', 0));
    $option       = $model->getOption($voted_option);
    $election     = $model->getElection($option->election_id);
    
    $this->election = $election;
    
    parent::display($tpl);
  }
}