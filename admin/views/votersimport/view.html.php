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
		
		JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_VOTER_IMPORT' ), 'upload' );
		JToolBarHelper::custom('voter.importVoters', 'save.png', 'save.png', $alt = JText::_( 'COM_JOOMELECTION_VOTER_IMPORT_UPLOAD' ), $listSelect = false);
		JToolBarHelper::cancel('voter.cancel');

		parent::display($tpl);
	}
}