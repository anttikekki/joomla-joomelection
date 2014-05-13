<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewElections extends JViewLegacy
{

  function display($tpl = null)
  {
    JToolBarHelper::title(   JText::_( 'Elections' ), 'box-add' );
    JToolBarHelper::addNew('election.add');
    JToolBarHelper::editList('election.edit');
    JToolBarHelper::deleteList('', 'election.remove');
    
    //Get model
    $electionModel = &$this->getModel('election');

    // Get data from the model
    $this->elections    = &$electionModel->getElections();
    $this->pagination   = &$electionModel->getPagination();
    
    if (count( $this->elections ))    {
      foreach($this->elections as $election) {
        $election->election_open = $electionModel->isElectionOpen($election);
      }
    }
    
    //Pass table sort parameters from last request
    $input = JFactory::getApplication()->input;
    $this->sortColumn = $input->getString('filter_order', 'election_name');
    $this->sortDirection = $input->getString('filter_order_Dir', 'ASC');

    parent::display($tpl);
  }
}
