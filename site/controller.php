<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


class JoomElectionController extends JControllerLegacy {
	
	
	function confirmVote() {
		$user	=& JFactory::getUser();
    $input = JFactory::getApplication()->input;
		
		//Guest is unregistered user
		if( !$user->guest ) {
			
			$model = $this->getModel('joomelection');
			$voted_option = $model->getOptionIdDecrypted($input->getCmd('vote_option', 0));
			$option_is_valid = $model->validOption($voted_option);
			
			if($option_is_valid) {
				$election = $model->getElectionIdFromOption($voted_option);
				$voter_is_valid = $model->getElectionVoterStatus($election);
				if($voter_is_valid) {
					$model =& $this->getModel('joomelection' );
					$view =& $this->getView('confirmvote', 'html');
					$view->setModel( $model, true );
					$view->setLayout("default");
					$view->display(); 
				}
				else {
					//User has allready voted
					JError::raiseError( 403, JText::_('COM_JOOMELECTION_ALLREADY_VOTED_ERROR') );
				}
			}
			else {
				//Invalid option is voted
				JError::raiseError( 403, JText::_('COM_JOOMELECTION_ALLREADY_VOTED_ERROR') );
			}
			
		}
		else {
			//Invalid user group and if user is not logged in
			JError::raiseError( 403, JText::_('COM_JOOMELECTION_NOT_LOGGED_IN_ERROR') );
		}
	}
	
	
	function vote() {
		$user			=& JFactory::getUser();
    $input = JFactory::getApplication()->input;
		$confirm_vote 	= $input->getInt('confirm_vote', 0);
		
		//Guest is unregistered user
		if( !$user->guest ) {
			
			$model = $this->getModel('joomelection');
			$voted_option = $model->getOptionIdDecrypted($input->getCmd('vote_option', 0));
			$option_is_valid = $model->validOption($voted_option);
			
			if($option_is_valid) {
				$election_id = $model->getElectionIdFromOption($voted_option);
				$voter_is_valid = $model->getElectionVoterStatus($election_id);
				
				if($voter_is_valid) {
					$election = $model->getElection($election_id);
					
					//If vote confirmation is not used, allways pass
					if((int) $election->confirm_vote_by_sign == 0) {
						$confirm_vote = 1;
					}
					if((int) $election->confirm_vote == 0) {
						$confirm_vote = 1;
					}
					
					if($confirm_vote) {
						$model->storeVote($voted_option, $election_id, $user->id);
						
						$model = &$this->getModel('joomelection' );
						$view  = &$this->getView('votesuccess', 'html');
						$view->setModel( $model, true );
						$view->setLayout("default");
						$view->display();
					}
					else {
						$model = &$this->getModel('joomelection' );
						$view = & $this->getView('votefailed', 'html');
						$view->setModel( $model, true );
						$view->setLayout("default");
						$view->display();
					}
				}
				else {
					//User has allready voted
					JError::raiseError( 403, JText::_('COM_JOOMELECTION_ALLREADY_VOTED_ERROR') );
				}
			}
			else {
				//Invalid option is voted
				JError::raiseError( 403, JText::_('COM_JOOMELECTION_ALLREADY_VOTED_ERROR') );
			}
			
		}
		else {
			//Invalid user group and if user is not logged in
			JError::raiseError( 403, JText::_('COM_JOOMELECTION_NOT_LOGGED_IN_ERROR') );
		}
	}

}