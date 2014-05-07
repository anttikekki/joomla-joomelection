<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewLists extends JView
{

	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Candidate Lists' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		
		$listModel 		= $this->getModel('list');
		$lists			= & $listModel->getElectionLists();
		$pagination 	= & $listModel->getPagination();

		$this->assignRef('lists',		$lists);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}
}
