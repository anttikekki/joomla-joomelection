<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewVotersImport extends JViewLegacy
{

	function display($tpl = null)
	{
		$model 		= $this->getModel('election');
		$elections	= $model->getAllElections();
		$elections_list	= JHTML::_('select.genericlist', $elections, 'election_id', 'class="inputbox" ', 'election_id', 'election_name' );
		
		JToolBarHelper::title(   JText::_( 'Voters Import' ), 'generic.png' );
		JToolBarHelper::custom('importVoters', 'save.png', 'save.png', $alt = JText::_( 'Upload' ), $listSelect = false);
		JToolBarHelper::cancel();
		
		$this->assignRef( 'elections_list',	$elections_list );
		$this->assignRef( 'elections_count', count($elections));

		parent::display($tpl);
	}
}
