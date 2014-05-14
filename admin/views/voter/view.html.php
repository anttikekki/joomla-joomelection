<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoter extends JViewLegacy
{

  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
    
    //Get model
    $electionModel   = $this->getModel('election');
    $voterModel   = $this->getModel('voter');
    
    //Continue editing?
    $continue_edit = $input->getInt('continue_edit',  0);
    if($continue_edit == 1) {
      $this->voter = $voterModel->getVoterFromRequest();
    }
    else {
      $this->voter = $voterModel->getVoter();
    }
    
    $isNew                    = ($voter->voter_id < 1);
    $this->stored_limit       = $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
    $this->stored_limitstart  = $input->getInt('limitstart', 0);
    $this->stored_search      = $input->getString('search', '');
    $this->elections          = &$electionModel->getAllElections();
    
    $text = $isNew ? JText::_( 'COM_JOOMELECTION_NEW' ) : JText::_( 'COM_JOOMELECTION_EDIT' );
    JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_VOTER' ).': ' . $text, 'user' );
    JToolBarHelper::save('voter.save');
    if ($isNew)  {
      JToolBarHelper::cancel('voter.cancel');
    } else {
      // for existing items the button is renamed `close`
      JToolBarHelper::cancel( 'voter.cancel', JText::_( 'COM_JOOMELECTION_CLOSE' ) );
    }

    parent::display($tpl);
  }
}