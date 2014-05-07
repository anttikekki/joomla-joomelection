<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewOption extends JViewLegacy
{

	function display($tpl = null)
	{
    $input = JFactory::getApplication()->input;
  
		//Get models stored to view in controller
		$optionModel		= &$this->getModel('option');
		$electionModel		= &$this->getModel('election');
		$electionListModel	= &$this->getModel('list');
		
		//Continue editing?
		$continue_edit = $input->getInt('continue_edit',  0);
		if($continue_edit == 1) {
			$option	= $optionModel->getOptionFromRequest();
		}
		else {
			$option	= $optionModel->getOption();
		}
		
		$electionList			= $electionModel->getAllElections();
		$isNew					= ($option->option_id < 1);
		
		//Add '-- Select Election--' text to be first value in list of elections so its going to be Combo box default value
		$emptyElection 					= new stdClass();
		$emptyElection->election_id 	= 0;
		$emptyElection->election_name 	= JText::_( '-- ' .JText::_( 'Select election' ). ' --' );
		$array1 						= array($emptyElection);
		$electionList 					= array_merge($array1, $electionList);
		
		$isNew					= ($option->option_id < 1);
		$option->published 		= JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $option->published);
		$electionComboBox		= JHTML::_('select.genericlist', $electionList, 'election_id', 'class="inputbox" ', 'election_id', 'election_name', $option->election_id );
		$text 					= $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		
		if($isNew == false) {
			$electionLists = $electionListModel->getElectionListsForElection($option->election_id);
			$electionListsComboBox	= JHTML::_('select.genericlist', $electionLists, 'list_id', 'class="inputbox" ', 'list_id', 'name', $option->list_id );
		}
		
		
		//Create toolbar
		JToolBarHelper::title(   JText::_( 'Option' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		//Assign variables to template
		$this->assignRef('option',			$option);
		$this->assignRef('electionList',	$electionComboBox);
		$this->assignRef('electionListsComboBox',	$electionListsComboBox);

		parent::display($tpl);
	}
}
