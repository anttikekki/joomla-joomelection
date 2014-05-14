<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewOption extends JViewLegacy
{

  function display($tpl = null)
  {
    $input = JFactory::getApplication()->input;
  
    //Get models stored into view in controller
    $optionModel    = &$this->getModel('option');
    $electionModel    = &$this->getModel('election');
    $electionListModel  = &$this->getModel('list');
    
    //Continue editing?
    $continue_edit = $input->getInt('continue_edit',  0);
    if($continue_edit == 1) {
      $option  = $optionModel->getOptionFromRequest();
    }
    else {
      $option  = $optionModel->getOption();
    }
    
    $elections      = $electionModel->getAllElections();
    $electionLists  = $electionListModel->getAllElectionLists();
    $isNew          = ($option->option_id < 1);
    
    //Create toolbar
    $text = $isNew ? JText::_( 'COM_JOOMELECTION_NEW' ) : JText::_( 'COM_JOOMELECTION_EDIT' );
    JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_CANDIDATE' ).': ' . $text, 'user' );
    JToolBarHelper::save('option.save');
    
    if ($isNew)  {
      JToolBarHelper::cancel('option.cancel');
    } else {
      // for existing items the button is renamed `close`
      JToolBarHelper::cancel( 'option.cancel', JText::_( 'COM_JOOMELECTION_CLOSE' ) );
    }
    
    $this->option =  $option;
    $this->elections = $elections;
    $this->electionLists = $electionLists;

    parent::display($tpl);
  }
}