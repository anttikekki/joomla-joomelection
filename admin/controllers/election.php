<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionControllerElection extends JControllerLegacy
{

  function __construct()
  {
    parent::__construct();

    // Register Extra tasks
    $this->registerTask( 'add',  'edit' );
  }
  
  
  function showList()
  {    
    $model = &$this->getModel('election');
    
    $view = & $this->getView('elections', 'html');
    $view->setModel( $model, true );
    $view->setLayout('default');
    $view->display();
  }
  
  
  function showResult()
  {
    $resultModel = &$this->getModel('result');
    $electionModel = &$this->getModel('election');
    
    $view = & $this->getView('result', 'html');
    $view->setModel( $resultModel, true );
    $view->setModel( $electionModel );
    $view->setLayout('default');
    $view->display();
  }
  


  function edit()
  {
    $electionModel = &$this->getModel('election');
    $electionTypeModel = &$this->getModel('electiontype');
    
    $view = & $this->getView('election', 'html');
    $view->setModel( $electionModel, true );
    $view->setModel( $electionTypeModel);
    $view->setLayout('form');
    $view->display();
  }


  function save()
  {
    $model = $this->getModel('election');

    if ($model->store()) {
      $msg = JText::_( 'Election Saved' );
    } else {
      $msg = JText::_( 'Error Saving Election' );
    }
    
    $this->setRedirect('index.php?option=com_joomelection&task=election.showList', $msg);
  }


  function remove()
  {
    $model = $this->getModel('election');
    if(!$model->delete()) {
      $msg = JText::_( 'Error: One or More Election Could not be Deleted' );
    } else {
      $msg = JText::_( 'Election(s) Deleted' );
    }

    $this->setRedirect( 'index.php?option=com_joomelection&task=election.showList', $msg );
  }


  function cancel()
  {
    $task_opened_from = $this->input->getString( 'task_opened_from', '');
    $msg = JText::_( 'Operation Cancelled' );
    
    if($task_opened_from == 'showList' || $task_opened_from == '') {
      $this->setRedirect( 'index.php?option=com_joomelection&task=election.showList', $msg );
    }
    else if ($task_opened_from == 'edit') {
      $election_id = $this->input->getInt( 'election_id', 0);
      $this->setRedirect( 'index.php?option=com_joomelection&task=election.edit&cid[]=' .$election_id, $msg );
    }
  }
  
  
  function resultsInCsv()
  {
    $model = $this->getModel('result');
    $model->resultsInCsv();
  }
  
  
  function publish()
  {
    $model = &$this->getModel('election');

    if ($model->publish()) {
      $msg = JText::_( 'Election(s) published' );
    } else {
      $msg = JText::_( 'Error when publishing election(s)' );
    }
    
    $this->setRedirect('index.php?option=com_joomelection&task=election.showList', $msg);
  }
  
  function unpublish()
  {
    $model = $this->getModel('election');

    if ($model->publish()) {
      $msg = JText::_( 'Election(s) unpublished' );
    } else {
      $msg = JText::_( 'Error when unpublishing election(s)' );
    }
    
    $this->setRedirect('index.php?option=com_joomelection&task=election.showList', $msg);
  }
}
?>
