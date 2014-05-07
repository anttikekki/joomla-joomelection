<?php


jimport('joomla.application.component.controller');


class JoomElectionController extends JController
{

	function display()
	{
		parent::display();
	}
	
	
	function confirmVote()
	{
		$user	=& JFactory::getUser();
		
		//If gid = 18 then registered User, meaning Voter
		if( $user->get('gid') == 18 ) {
			
			$model = $this->getModel('joomelection');
			$voted_option = $model->getOptionIdDecrypted(JRequest::getVar('vote_option', 0));
			$option_is_valid = $model->validOption($voted_option);
			
			if($option_is_valid) {
				$election = $model->getElectionIdFromOption($voted_option);
				$voter_is_valid = $model->getElectionVoterStatus($election);
				if($voter_is_valid) {
					$model = &$this->getModel('joomelection' );
					$view = & $this->getView('confirmvote', 'html');
					$view->setModel( $model, true );
					$view->setLayout("default");
					$view->display(); 
				}
				else {
					//User has allready voted
					JError::raiseError( 403, JText::_('You Have Allready Voted In This Election') );
				}
			}
			else {
				//Invalid option is voted
				JError::raiseError( 403, JText::_('You Have Allready Voted In This Election') );
			}
			
		}
		else {
			//Invalid user group and if user is not logged in
			JError::raiseError( 403, JText::_('You Have Not Logged In. You Have To Log-In To Vote') );
		}
	}
	
	
	function vote()
	{
		$user			=& JFactory::getUser();
		$confirm_vote 	= JRequest::getVar('confirm_vote', 0);
		
		//If gid = 18 then registered User, meaning Voter
		if( $user->get('gid') == 18 ) {
			
			$model = $this->getModel('joomelection');
			$voted_option = $model->getOptionIdDecrypted(JRequest::getVar('vote_option', 0));
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
					JError::raiseError( 403, JText::_('You Have Allready Voted In This Election') );
				}
			}
			else {
				//Invalid option is voted
				JError::raiseError( 403, JText::_('You Have Allready Voted In This Election') );
			}
			
		}
		else {
			//Invalid user group and if user is not logged in
			JError::raiseError( 403, JText::_('You Have Not Logged In. You Have To Log-In To Vote') );
		}
	}

}
?>
