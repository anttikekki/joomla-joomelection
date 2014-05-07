<?php

jimport( 'joomla.application.component.view');

class JoomElectionViewVoteFailed extends JView
{
	function display($tpl = null)
	{
		$model 			= $this->getModel('joomelection');
		$voted_option 	= $model->getOptionIdDecrypted(JRequest::getVar('vote_option', 0));
		$option 		= $model->getOption($voted_option);
		$election		= $model->getElection($option->election_id);
		
		$this->assignRef( 'election',	$election );
		
		parent::display($tpl);
		
	}
}
?>
