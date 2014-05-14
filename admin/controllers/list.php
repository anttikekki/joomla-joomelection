<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionControllerList extends JControllerLegacy
{

  function __construct()
  {
    parent::__construct();

    // Register Extra tasks
    $this->registerTask('add', 'edit');
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
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_SAVE_OK' );
    } else {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_SAVE_ERROR' );
    }

    $link = 'index.php?option=com_joomelection&task=list.showList';
    $this->setRedirect($link, $msg);
  }


  function remove()
  {
    $model = $this->getModel('list');
    if(!$model->delete()) {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_DELETE_ERROR' );
    } else {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_DELETE_OK' );
    }

    $this->setRedirect( 'index.php?option=com_joomelection&task=list.showList', $msg );
  }


  function cancel()
  {
    $msg = JText::_( 'COM_JOOMELECTION_OPERATION_CANCELLED' );
    $this->setRedirect( 'index.php?option=com_joomelection&task=list.showList', $msg );
  }
  
  
  function publish()
  {
    $model = $this->getModel('list');

    if ($model->publish()) {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_PUBLISH_OK' );
    } else {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_PUBLISH_ERROR' );
    }

    $link = 'index.php?option=com_joomelection&task=list.showList';
    $this->setRedirect($link, $msg);
  }
  
  function unpublish()
  {
    $model = $this->getModel('list');

    if ($model->publish()) {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_UNPUBLISH_OK' );
    } else {
      $msg = JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_UNPUBLISH_ERROR' );
    }

    $link = 'index.php?option=com_joomelection&task=list.showList';
    $this->setRedirect($link, $msg);
  }
}