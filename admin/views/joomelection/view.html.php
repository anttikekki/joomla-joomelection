<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewJoomElection extends JViewLegacy
{

	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'JoomElection' ), 'generic.png' );

		parent::display($tpl);
	}
}
