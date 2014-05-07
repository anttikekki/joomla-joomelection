<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoter extends JViewLegacy
{

	function display($tpl = null)
	{
    $input = JFactory::getApplication()->input;
		
		//Get model
		$electionModel 	= $this->getModel('election');
		$voterModel 	= $this->getModel('voter');
		
		//Continue editing?
		$continue_edit = $input->getInt('continue_edit',  0);
		if($continue_edit == 1) {
			$voter = $voterModel->getVoterFromRequest();
		}
		else {
			$voter = $voterModel->getVoter();
		}
		
		$isNew				= ($voter->voter_id < 1);
		$stored_limit 		= $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
		$stored_limitstart 	= $input->getInt('limitstart', 0);
		$stored_search 		= $input->getString('search', '');
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
