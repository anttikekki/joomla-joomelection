<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionControllerList extends JoomElectionController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}
	
	
	function showList()
	{	
		$listModel = &$this->getModel('list');
		
		$view = & $this->getView('lists', 'html');
		$view->setModel( $listModel, true );
		$view->setLayout('default');
		$view->display();
	}
	


	function edit()
	{	
		$listModel = &$this->getModel('list');
		$electionModel = &$this->getModel('election');
		
		$view = & $this->getView('list', 'html');
		$view->setModel( $listModel, true );
		$view->setModel( $electionModel);
		$view->setLayout('form');
		$view->display();
	}


	function save()
	{
		$model = $this->getModel('list');

		if ($model->store()) {
			$msg = JText::_( 'List Saved' );
		} else {
			$msg = JText::_( 'Error Saving List' );
		}

		$link = 'index.php?option=com_joomelection&controller=list&task=showList';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('list');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Lists Could not be Deleted' );
		} else {
			$msg = JText::_( 'List(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_joomelection&controller=list&task=showList', $msg );
	}


	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_joomelection&controller=list&task=showList', $msg );
	}
	
	
	function publish()
	{
		$model = $this->getModel('list');

		if ($model->publish()) {
			$msg = JText::_( 'List(s) published' );
		} else {
			$msg = JText::_( 'Error when publishing list(s)' );
		}

		$link = 'index.php?option=com_joomelection&controller=list&task=showList';
		$this->setRedirect($link, $msg);
	}
	
	function unpublish()
	{
		$model = $this->getModel('list');

		if ($model->publish()) {
			$msg = JText::_( 'List(s) unpublished' );
		} else {
			$msg = JText::_( 'Error when unpublishing list(s)' );
		}

		$link = 'index.php?option=com_joomelection&controller=list&task=showList';
		$this->setRedirect($link, $msg);
	}
}
?>
