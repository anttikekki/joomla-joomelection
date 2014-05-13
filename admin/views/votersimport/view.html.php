<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVotersImport extends JViewLegacy
{

	function display($tpl = null)
	{
		$model 		= $this->getModel('election');
		$this->elections	= $model->getAllElections();
		
		JToolBarHelper::title(   JText::_( 'Voters Import' ), 'upload' );
		JToolBarHelper::custom('voter.importVoters', 'save.png', 'save.png', $alt = JText::_( 'Upload' ), $listSelect = false);
		JToolBarHelper::cancel('voter.cancel');

		parent::display($tpl);
	}
}