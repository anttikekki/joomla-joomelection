<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoterspasswordgeneration extends JView
{

	function display($tpl = null)
	{
		$selectedVoters	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		if(((int) $selectedVoters[0]) == 0) {
			$selectedVoutersCount = 0;
		}
		else {
			$selectedVoutersCount = sizeof($selectedVoters);
		}
		
		$electionModel 	= $this->getModel('election');
		$elections		= $electionModel->getAllElections();
		$elections_list	= JHTML::_('select.genericlist', $elections, 'election_id', 'class="inputbox" ', 'election_id', 'election_name' );
		
		JToolBarHelper::title(   JText::_( 'Generate passwords' ), 'generic.png' );
		JToolBarHelper::custom('generatePasswords', 'apply.png', 'apply.png', $alt = JText::_( 'Generate and send' ), $listSelect = false);
		JToolBarHelper::cancel();
		
		$this->assignRef( 'elections_list',	$elections_list );
		$this->assignRef( 'selectedVoutersCount', $selectedVoutersCount);
		$this->assignRef( 'selectedVotersStringList', implode(",", $selectedVoters));
		$this->assignRef( 'elections_count', count($elections));
		
		parent::display($tpl);
	}
}
