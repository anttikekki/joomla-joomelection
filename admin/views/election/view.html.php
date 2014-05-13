<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewElection extends JViewLegacy
{

  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
  
    //Get model
    $electionModel     = &$this->getModel('election');
    $electionTypeModel = &$this->getModel('electionType');
    
    $election       = $electionModel->getElection();
    $electionTypes  = $electionTypeModel->getElectionTypes();
    $isNew          = ($election->election_id < 1);
    
    //Get election type names from translations
    if (count( $electionTypes ))    {
      foreach($electionTypes as $electionType) {
        $electionType->type_name = JText::_( $electionType->type_name );
      }
    }

    //Create toolbar
    $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
    JToolBarHelper::title(   JText::_( 'Election' ).': <small><small>[ ' . $text.' ]</small></small>', 'box-add' );
    
    if ($isNew)  {
      JToolBarHelper::save('election.save');
      JToolBarHelper::cancel('election.showList');
    } else {
      // for existing items the button is renamed `close`, also do not allow to check election result if election not saved yet
      JToolBarHelper::custom('election.showResult', 'upload.png', 'upload.png', $alt = JText::_( 'Result' ), $listSelect = false);
      JToolBarHelper::save('election.save');
      JToolBarHelper::cancel('election.showList', 'Close');
    }
    
    if ($isNew)  {
      $election->confirm_vote_by_sign_description = JText::_( 'I confirm that I have not beign under any outside influence when executing this vote');
      $election->confirm_vote_by_sign_error       = JText::_( 'Vote failed because you didnt aprove that you were not under influence');
      $election->vote_success_description         = JText::_( 'Your vote has been registered. Remember to clear browser cache to ensure secresy of vote' );
      $election->election_voter_email_text        = JText::_( 'VOTER_EMAIL_EXAMPLE');
      $election->election_voter_email_header      = JText::_( 'VOTER_EMAIL_SUBJECT_EXAMPLE');
    }
    
    $this->electionTypes = $electionTypes;
    $this->election = $election;
    $this->task = $input->getString( 'task', '');

    parent::display($tpl);
  }
}
