<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoters extends JViewLegacy
{

  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
  
    JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_VOTERS' ), 'users' );
    JToolBarHelper::addNew('voter.add');
    JToolBarHelper::editList('voter.edit');
    JToolBarHelper::deleteList('', 'voter.remove');
    JToolBarHelper::custom('voter.showVotersImport', 'upload.png', 'upload.png', $alt = JText::_( 'COM_JOOMELECTION_VOTER_IMPORT' ), $listSelect = false);
    JToolBarHelper::custom('voter.showGeneratePasswordForm', 'wand', 'wand', $alt = JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS' ), $listSelect = false);
    JToolBarHelper::custom('voter.removeAll', 'trash.png', 'trash.png', $alt = JText::_( 'COM_JOOMELECTION_VOTER_DELETE_ALL' ), $listSelect = false);
    
    //Get models
    $electionModel =& $this->getModel('election');
    $voterModel =& $this->getModel('voter');
    
    // Get data from the model
    $this->voters      =& $voterModel->getVoters();
    $this->pagination  =& $voterModel->getPagination();
    $this->search      = $input->getString('search', '');
    $this->elections   =& $electionModel->getAllElections();
    
    //Set default selected election for voted status
    $default_election_id = 0;
    if(count($elections) > 0) {
      $default_election_id = $elections[0]->election_id;
    }
    $this->election_id  = $input->getInt('election_id', $default_election_id);
    
    //Pass table sort parameters from last request
    $this->sortColumn = $input->getString('filter_order', 'u.name');
    $this->sortDirection = $input->getString('filter_order_Dir', 'ASC');

    parent::display($tpl);
  }
}