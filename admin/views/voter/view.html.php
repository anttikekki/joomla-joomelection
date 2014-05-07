<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoter extends JView
{

	function display($tpl = null)
	{
		global $mainframe;
		
		//Get model
		$electionModel 	= $this->getModel('election');
		$voterModel 	= $this->getModel('voter');
		
		//Continue editing?
		$continue_edit = JRequest::getVar('continue_edit',  0, 'post', 'int');
		if($continue_edit == 1) {
			$voter = $voterModel->getVoterFromRequest();
		}
		else {
			$voter = $voterModel->getVoter();
		}
		
		$isNew				= ($voter->voter_id < 1);
		$stored_limit 		= JRequest::getVar('limit', $mainframe->getCfg('list_limit')); 
		$stored_limitstart 	= JRequest::getVar('limitstart', 0);
		$stored_search 		= JRequest::getVar('search', '');
		$elections			= & $electionModel->getAllElections();
		$elections_list		= JHTML::_('select.genericlist', $elections, 'election_id', 'class="inputbox" ', 'election_id', 'election_name' );
		
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Voter' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('voter',				$voter);
		$this->assignRef('elections_list',		$elections_list);
		$this->assignRef('elections_count',		count($elections));
		$this->assignRef('stored_limit',		$stored_limit);
		$this->assignRef('stored_limitstart',	$stored_limitstart);
		$this->assignRef('stored_search',		$stored_search);

		parent::display($tpl);
	}
}
