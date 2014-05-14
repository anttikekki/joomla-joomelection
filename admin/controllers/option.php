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
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_SAVE_OK' );
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
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_DELETE_ERROR' );
		} else {
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_DELETE_OK' );
		}

		$this->setRedirect( 'index.php?option=com_joomelection&task=option.showList', $msg );
	}


	function cancel()
	{
		$msg = JText::_( 'COM_JOOMELECTION_OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_joomelection&task=option.showList', $msg );
	}
	
	
	function publish()
	{
		$model = $this->getModel('option');

		if ($model->publish()) {
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_PUBLISH_OK' );
		} else {
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_PUBLISH_ERROR' );
		}

		$link = 'index.php?option=com_joomelection&task=option.showList';
		$this->setRedirect($link, $msg);
	}
	
	function unpublish()
	{
		$model = $this->getModel('option');

		if ($model->publish()) {
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_UNPUBLISH_OK' );
		} else {
			$msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_UNPUBLISH_ERROR' );
		}

		$link = 'index.php?option=com_joomelection&task=option.showList';
		$this->setRedirect($link, $msg);
	}
}