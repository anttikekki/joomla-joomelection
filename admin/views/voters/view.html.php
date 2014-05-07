<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVoters extends JViewLegacy
{

	function display($tpl = null)
	{
    $input = JFactory::getApplication()->input;
  
		JToolBarHelper::title(   JText::_( 'Voters' ), 'generic.png' );
		JToolBarHelper::custom('showVotersImport', 'upload.png', 'upload.png', $alt = JText::_( 'Import voters' ), $listSelect = false);
		JToolBarHelper::custom('showGeneratePasswordForm', 'send.png', 'send.png', $alt = JText::_( 'Generate passwords' ), $listSelect = false);
		JToolBarHelper::custom('removeAll', 'trash.png', 'trash.png', $alt = JText::_( 'Delete all' ), $listSelect = false);
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		
		//Get models
		$electionModel =& $this->getModel('election');
		$voterModel =& $this->getModel('voter');
		
		// Get data from the model
		$voters			=& $voterModel->getVoters();
		$pagination 	=& $voterModel->getPagination();
		$search 		= $input->getInt('search', '');
		
		$electionList	=& $electionModel->getAllElections();
		$default_election_id = 0;
		if(count($electionList) > 0) {
			$default_election_id = $electionList[0]->election_id;
		}
		$election_id	= $input->getInt('election_id', $default_election_id);
		$electionList	= JHTML::_('select.genericlist', $electionList, 'election_id', 'class="inputbox" onchange="document.adminForm.submit();" '. '', 'election_id', 'election_name', $election_id );
		
		$this->assignRef('voters',			$voters);
		$this->assignRef('pagination',		$pagination);
		$this->assignRef('search',			$search);
		$this->assignRef('electionList',	$electionList);

		parent::display($tpl);
	}
}
