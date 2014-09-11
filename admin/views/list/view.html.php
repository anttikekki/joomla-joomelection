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

    $text = $isNew ? JText::_( 'COM_JOOMELECTION_NEW' ) : JText::_( 'COM_JOOMELECTION_EDIT' );
    JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST' ).': ' . $text, 'list' );
    JToolBarHelper::save('list.save');
    
    if ($isNew)  {
      JToolBarHelper::cancel('list.showList');
    } else {
      // for existing items the button is renamed `close`
      JToolBarHelper::cancel( 'list.showList', JText::_( 'COM_JOOMELECTION_CLOSE' ) );
    }
    
    //Elections with type of list election is required
    if(count($elections) == 0) {
      $error = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_NO_ELECTIONS' );
      JFactory::getApplication()->enqueueMessage($error, 'error');
    }

    $this->elections    = $elections;
    $this->electionList = $list;
    
    parent::display($tpl);
  }
}