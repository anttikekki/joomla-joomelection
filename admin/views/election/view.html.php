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
    
    $this->electionTypes = $electionTypes;
    $this->election = $election;
    $this->task = $input->getString( 'task', '');

    parent::display($tpl);
  }
}