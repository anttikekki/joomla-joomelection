<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewElection extends JViewLegacy
{

  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
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
    $text = $isNew ? JText::_( 'COM_JOOMELECTION_NEW' ) : JText::_( 'COM_JOOMELECTION_EDIT' );
    JToolBarHelper::title( JText::_( 'COM_JOOMELECTION_ELECTION' ).': ' . $text, 'box-add' );
    
    if ($isNew)  {
      JToolBarHelper::save('election.save');
      JToolBarHelper::cancel('election.showList');
    } else {
      // for existing items the button is renamed `close`, also do not allow to check election result if election not saved yet
      JToolBarHelper::custom('election.showResult', 'upload.png', 'upload.png', $alt = JText::_( 'COM_JOOMELECTION_ELECTION_RESULT' ), $listSelect = false);
      JToolBarHelper::save('election.save');
      JToolBarHelper::cancel('election.showList', JText::_( 'COM_JOOMELECTION_CLOSE' ));
    }
    
    if ($isNew)  {
      $election->confirm_vote_by_sign_description = JText::_( 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN_DESCRIPTION_EXAMPLE');
      $election->confirm_vote_by_sign_error       = JText::_( 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN_ERROR_EXAMPLE');
      $election->vote_success_description         = JText::_( 'COM_JOOMELECTION_ELECTION_VOTE_SUCCESS_DESCRIPTION_EXAMPLE' );
      $election->election_voter_email_text        = JText::_( 'COM_JOOMELECTION_VOTER_EMAIL_EXAMPLE');
      $election->election_voter_email_header      = JText::_( 'COM_JOOMELECTION_VOTER_EMAIL_SUBJECT_EXAMPLE');
    }
    
    $this->electionTypes = $electionTypes;
    $this->election = $election;
    $this->task = $input->getString( 'task', '');

    parent::display($tpl);
  }
}