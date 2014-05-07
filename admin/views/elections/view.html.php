<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewElections extends JViewLegacy
{

	function display($tpl = null)
	{
		jimport('joomla.utilities.date');
		
		JToolBarHelper::title(   JText::_( 'Elections' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		
		//Get model
		$electionModel = &$this->getModel('election');

		// Get data from the model
		$elections		= & $electionModel->getElections();
		$pagination 	= & $electionModel->getPagination();
		
		if (count( $elections ))		{
			foreach($elections as $election) {
				$date_to_open 	= new JDate($election->date_to_open);
				$date_to_close 	= new JDate($election->date_to_close);
				$now			= new JDate();
				
				if($date_to_open->toUnix() <= $now->toUnix() && $date_to_close->toUnix() >= $now->toUnix()) {
					$election->election_open = true;
				}
				else {
					$election->election_open = false;
				}
			}
		}

		$this->assignRef('elections',		$elections);
		$this->assignRef('pagination',		$pagination);

		parent::display($tpl);
	}
}
