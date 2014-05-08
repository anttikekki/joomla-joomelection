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
    
    if (count( $electionTypes ))    {
      foreach($electionTypes as $electionType) {
        $electionType->type_name = JText::_( $electionType->type_name );
      }
    }
    
    $election->election_type_id   = JHTML::_('select.genericlist', $electionTypes, 'election_type_id', 'class="inputbox" ', 'election_type_id', 'type_name', $election->election_type_id );
    $election->published          = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $election->published);
    $election->confirm_vote       = JHTML::_('select.booleanlist', 'confirm_vote', 'class="inputbox"', $election->confirm_vote);
    $election->confirm_vote_by_sign = JHTML::_('select.booleanlist', 'confirm_vote_by_sign', 'class="inputbox"', $election->confirm_vote_by_sign);
    $task                         = $input->getString( 'task', '');

    $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
    JToolBarHelper::title(   JText::_( 'Election' ).': <small><small>[ ' . $text.' ]</small></small>' );
    
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
      $election->confirm_vote_by_sign_error    = JText::_( 'Vote failed because you didnt aprove that you were not under influence');
      $election->vote_success_description     = JText::_( 'Your vote has been registered. Remember to clear browser cache to ensure secresy of vote' );
      $election->election_voter_email_text    = JText::_( 'VOTER_EMAIL_EXAMPLE');
      $election->election_voter_email_header    = JText::_( 'VOTER_EMAIL_SUBJECT_EXAMPLE');
    }
    
    $calendars = array();
    $calendars['date_to_open'] = JHTML::_('calendar', JHTML::_('date',  $election->date_to_open, 'Y-m-d'), 'date_to_open', 'date_to_open', '%Y-%m-%d', array(' READONLY '));
    $calendars['date_to_close'] = JHTML::_('calendar', JHTML::_('date',  $election->date_to_close, 'Y-m-d'), 'date_to_close', 'date_to_close', '%Y-%m-%d', array(' READONLY '));
    
    $this->assignRef('election',    $election);
    $this->assignRef('calendars',    $calendars);
    $this->assignRef('task',      $task);

    parent::display($tpl);
  }
}
