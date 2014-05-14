<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoterspasswordgeneration extends JViewLegacy
{

  function display($tpl = null)
  {
    $input            = JFactory::getApplication()->input;
    $selectedVoterIds = $input->get( 'cid', array(), 'array' );
    $electionModel    = $this->getModel('election');
    
    $this->selectedVoutersCount     = count($selectedVoterIds);
    $this->elections                = $electionModel->getAllElections();
    $this->selectedVotersStringList = implode(",", $selectedVoterIds);
    
    JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS' ), 'wand' );
    JToolBarHelper::custom('voter.generatePasswords', 'wand', 'wand', $alt = JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_AND_SEND' ), $listSelect = false);
    JToolBarHelper::cancel('voter.cancel');
    
    parent::display($tpl);
  }
}