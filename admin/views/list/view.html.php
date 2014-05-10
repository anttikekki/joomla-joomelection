<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionViewList extends JViewLegacy
{

  function display($tpl = null)
  {
    //Get models stored to view in controller
    $listModel      = &$this->getModel('list');
    $electionModel  = &$this->getModel('election');
    
    $list       = $listModel->getElectionList();
    $elections  = $electionModel->getListElections();
    $isNew      = ($list->list_id < 1);

    $text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
    JToolBarHelper::title(   JText::_( 'Candidate List' ).': <small><small>[ ' . $text.' ]</small></small>' );
    JToolBarHelper::save('list.save');
    
    if ($isNew)  {
      JToolBarHelper::cancel('list.showList');
    } else {
      // for existing items the button is renamed `close`
      JToolBarHelper::cancel( 'list.showList', 'Close' );
    }
    
    //Elections with type list election is required
    if(count($elections) == 0) {
      $error = JText::_( 'You have to create at least one election first before you can create a list. You can not save list with no election.' );
      JFactory::getApplication()->enqueueMessage($error, 'error');
    }

    $this->elections    = $elections;
    $this->electionList = $list;
    
    parent::display($tpl);
  }
}