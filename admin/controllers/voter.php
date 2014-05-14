<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionControllerVoter extends JControllerLegacy
{

  function __construct()
  {
    parent::__construct();

    // Register Extra tasks
    $this->registerTask( 'add', 'edit' );
  }
  
  
  function showList()
  {  
    $voterModel = &$this->getModel('voter');
    $electionModel = &$this->getModel('election');
    
    $view = & $this->getView('voters', 'html');
    $view->setModel( $voterModel, true );
    $view->setModel( $electionModel);
    $view->setLayout('default');
    $view->display();
  }
  


  function edit()
  {    
    $voterModel = &$this->getModel('voter');
    $electionModel = &$this->getModel('election');
    
    $view = & $this->getView('voter', 'html');
    $view->setModel( $voterModel, true );
    $view->setModel( $electionModel);
    $view->setLayout('form');
    $view->display();
  }


  function save()
  {
    $model = $this->getModel('voter');

    if ($model->store()) {
      $msg = JText::_( 'COM_JOOMELECTION_VOTER_SAVE_OK' );
      $link = 'index.php?option=com_joomelection&task=voter.showList';
      $this->setRedirect($link, $msg);
    } else {
      $input = JFactory::getApplication()->input;
      $input->setVar('continue_edit', '1');
      $this->edit();
    }
  }


  function remove()
  {
    $model = $this->getModel('voter');
    if(!$model->delete()) {
      $msg = JText::_( 'Error: One or More Voter Could not be Deleted' );
    } else {
      $msg = JText::_( 'Voter(s) Deleted' );
    }

    $this->setRedirect( 'index.php?option=com_joomelection&task=voter.showList', $msg );
  }
  
  function removeAll()
  {
    $model = $this->getModel('voter');
    if(!$model->deleteAll()) {
      $msg = JText::_( 'COM_JOOMELECTION_VOTER_DELETE_ERROR' );
    } else {
      $msg = JText::_( 'COM_JOOMELECTION_VOTER_DELETE_OK' );
    }

    $this->setRedirect( 'index.php?option=com_joomelection&task=voter.showList', $msg );
  }


  function cancel()
  {
    $msg = JText::_( 'COM_JOOMELECTION_OPERATION_CANCELLED' );
    $this->setRedirect( 'index.php?option=com_joomelection&task=voter.showList', $msg );
  }
  
  
  function showVotersImport()
  {
    $model = &$this->getModel('election' );
    
    $view  = &$this->getView('votersimport', 'html');
    $view->setModel( $model, true );
    $view->setLayout("default");
    $view->display(); 
  }
  
  
  
  function importVoters()
  {
    $model = $this->getModel('voter');
    $result = $model->importVotersFromCsv();
    
    if($result) {
      $msg = JText::_( 'COM_JOOMELECTION_VOTER_IMPORT_OK' );
      $this->setRedirect( 'index.php?option=com_joomelection&task=voter.showList', $msg );
    }
    else {
      $electionModel = &$this->getModel('election' );
    
      $view  = &$this->getView('votersimport', 'html');
      $view->setModel( $electionModel, true );
      $view->setLayout("default");
      $view->display(); 
    }
  }
  
  
  function showGeneratePasswordForm()
  {    
    $electionModel = &$this->getModel('election');
    
    $view = & $this->getView('voterspasswordgeneration', 'html');
    $view->setModel( $electionModel, true );
    $view->setLayout('form');
    $view->display();
  }
  
  
  function generatePasswords() 
  {
    $model = $this->getModel('voter');
    
    if(!$model->generatePasswordAndSendEmail()) {
      $msg = JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_ERROR' );
    } else {
      $msg = JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_OK' );
    }

    $this->setRedirect( 'index.php?option=com_joomelection&task=voter.showList', $msg );
  }
}