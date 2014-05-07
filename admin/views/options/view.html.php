<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewOptions extends JViewLegacy
{

	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_( 'Options' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		
		$optionModel 	= $this->getModel('option');
		$options		= & $optionModel->getOptions();
		$pagination 	= & $optionModel->getPagination();

		$this->assignRef('options',		$options);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}
}
