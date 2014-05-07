<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewList extends JView
{

	function display($tpl = null)
	{
		//Get models stored to view in controller
		$listModel			= &$this->getModel('list');
		$electionModel		= &$this->getModel('election');
		
		$list					= $listModel->getElectionList();
		$electionList			= $electionModel->getListElections();
		$isNew					= ($list->list_id < 1);
		
		//Add '-- Select Election--' text to be first value in list of elections so its going to be Combo box default value
		$emptyElection = new stdClass();
		$emptyElection->election_id = 0;
		$emptyElection->election_name = JText::_( '--' .JText::_( 'Select election' ). '--' );
		$electionList = array_merge(array($emptyElection), $electionList);
		
		$list->published 		= JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $list->published);
		$electionComboBox		= JHTML::_('select.genericlist', $electionList, 'election_id', 'class="inputbox" ', 'election_id', 'election_name', $list->election_id );

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Candidate List' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		$electionListEmpty = false;
		if(count($electionList) == 0) {
			$electionListEmpty = true;
		}

		$this->assignRef('electionList', 		$list);
		$this->assignRef('electionComboBox', 	$electionComboBox);
		$this->assignRef('electionListEmpty', 	$electionListEmpty);
		
		parent::display($tpl);
	}
}
