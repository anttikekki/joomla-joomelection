<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewElections extends JViewLegacy
{

  function display($tpl = null)
  {
    jimport('joomla.utilities.date');
    
    JToolBarHelper::title(   JText::_( 'Elections' ), 'generic.png' );
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
        $date_to_open   = new JDate($election->date_to_open);
        $date_to_close  = new JDate($election->date_to_close);
        $now            = new JDate();
        
        if($date_to_open->toUnix() <= $now->toUnix() && $date_to_close->toUnix() >= $now->toUnix()) {
          $election->election_open = true;
        }
        else {
          $election->election_open = false;
        }
      }
    }
    
    //Pass table sort parameters from last request
    $input = JFactory::getApplication()->input;
    $this->sortColumn = $input->getString('filter_order', 'election_name');
    $this->sortDirection = $input->getString('filter_order_Dir', 'ASC');

    parent::display($tpl);
  }
}
