<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionControllerOption extends JControllerLegacy
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask('add', 'edit');
	}
	
	
	function showList()
	{		
		$model = &$this->getModel('option');
		
		$view = & $this->getView('options', 'html');
		$view->setModel( $model, true );
		$view->setLayout('default');
		$view->display();
	}
	


	function edit()
	{
		$optionModel 	= &$this->getModel('option');
		$electionModel 	= &$this->getModel('election');
		$listModel 		= &$this->getModel('list');
		
		$view = & $this->getView('option', 'html');
		$view->setModel( $optionModel, true );
		$view->setModel( $electionModel);
		$view->setModel( $listModel);
		$view->setLayout('form');
		$view->display();
	}


	function save()
	{
		$model = $this->getModel('option');

		if ($model->store()) {
			$msg = JText::_( 'Option Saved' );
			$link = 'index.php?option=com_joomelection&task=option.showList';
			$this->setRedirect($link, $msg);
		} else {
      $input = JFactory::getApplication()->input;
			$input->setVar('continue_edit', '1');
			$this->edit();
		}
	}


	function remove()
	{
		$model = $this->getModel('option');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Option Could not be Deleted' );
		} else {
			$msg = JText::_( 'Option(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_joomelection&task=option.showList', $msg );
	}


	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_joomelection&task=option.showList', $msg );
	}
	
	
	function publish()
	{
		$model = $this->getModel('option');

		if ($model->publish()) {
			$msg = JText::_( 'Option(s) published' );
		} else {
			$msg = JText::_( 'Error when publishing option(s)' );
		}

		$link = 'index.php?option=com_joomelection&task=option.showList';
		$this->setRedirect($link, $msg);
	}
	
	function unpublish()
	{
		$model = $this->getModel('option');

		if ($model->publish()) {
			$msg = JText::_( 'Option(s) unpublished' );
		} else {
			$msg = JText::_( 'Error when unpublishing option(s)' );
		}

		$link = 'index.php?option=com_joomelection&task=option.showList';
		$this->setRedirect($link, $msg);
	}
	
	function listElectionListsForElection()
	{
    $input = JFactory::getApplication()->input;
		$election_id = $input->getInt( 'election_id', 0);
		$electionListModel = $this->getModel('list');
		$electionLists = $electionListModel->getElectionListsForElection($election_id);
		
		if(count($electionLists) > 0) {
			$electionListsComboBox	= JHTML::_('select.genericlist', $electionLists, 'list_id', 'class="inputbox" ', 'list_id', 'name', 0 );
		}
		else {
			$electionListsComboBox = JText::_( 'Election is not list election. Candidate list is not needed.' );
		}
		
		//Print select box
    echo $electionListsComboBox;
    
    // Return to keep the application from going anywhere else. Echoed content returns to browser.
    return;
	}
}