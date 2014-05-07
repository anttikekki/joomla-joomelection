<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );


class JoomElectionModelJoomElection extends JModel
{
	function getElections()
	{
		$query = 'SELECT election.*, election_type.type_name AS election_type_name '
		. ' FROM #__joomelection_election AS election '
		. ' LEFT JOIN #__joomelection_election_type AS election_type ON election.election_type_id = election_type.election_type_id'
		. ' WHERE election.published = 1'
		;
		$this->_db->setQuery( $query );
		$elections = $this->_db->loadObjectList();
		
		for($i = 0; $i < count($elections); $i++) 
		{ 
			$elections[$i]->valid_voter 	= $this->getElectionVoterStatus($elections[$i]->election_id );
			$elections[$i]->election_lists	= $this->getElectionListsForElection($elections[$i]->election_id );
			$elections[$i]->options	 		= $this->getOptions($elections[$i]->election_id );
			$elections[$i]->valid_election 	= $this->validElection($elections[$i]->election_id);
		}

		return $elections;
	}
	
	
	function getElection($election_id)
	{
		$query = 'SELECT * FROM #__joomelection_election'
		. ' WHERE election_id = ' . (int) $election_id
		;
		$this->_db->setQuery( $query );

		return $this->_db->loadObject();
	}
	
	
	
	function getElectionListsForElection($election_id)
	{
		$query = 'SELECT * '
		. ' FROM #__joomelection_list'
		. ' WHERE election_id = ' . (int) $election_id
		;
		$this->_db->setQuery( $query );
		return $this->_db->loadObjectList();
	}
	
	
	
	function getElectionListOptions($election_list_Id)
	{	
		$query = 'SELECT * '
		. ' FROM #__joomelection_option'
		. ' WHERE published = 1'
		. ' AND list_id = ' . (int) $election_list_Id
		. ' ORDER BY name DESC'
		;
		$this->_db->setQuery( $query );
		$options = $this->_db->loadObjectList();
		
		return $options;
	}
	
	
	
	function getOptions($electionId)
	{
		$orderBy = JRequest::getVar('orderBy', 'number', 'GET', 'string');
		$orderBySql = '';
		
		if($orderBy == 'number') {
			$orderBySql = 'op.option_number ASC';
		}
		else if($orderBy == 'name') {
			$orderBySql = 'op.name DESC';
		}
		else if($orderBy == 'listName') {
			$orderBySql = 'election_list.name DESC';
		}

		
		$query = 'SELECT op.*, election_list.name AS list_name '
		. ' FROM #__joomelection_option AS op'
		. ' LEFT JOIN #__joomelection_list AS election_list ON election_list.list_id = op.list_id'
		. ' WHERE op.published = 1'
		. ' AND op.election_id = ' . (int) $electionId
		. ' ORDER BY ' . $orderBySql
		;
		$this->_db->setQuery( $query );
		$options = $this->_db->loadObjectList();
		
		return $options;
	}
	
	function getOption($optionId)
	{
		$query = 'SELECT op.*, election_list.name AS list_name '
		. ' FROM #__joomelection_option AS op'
		. ' LEFT JOIN #__joomelection_list AS election_list ON election_list.list_id = op.list_id'
		. ' WHERE op.option_id = ' . (int) $optionId
		;
		$this->_db->setQuery( $query );
		$option = $this->_db->loadObject();

		return $option;
	}
	
	function getElectionVoterStatus($electionId)
	{
		$user	=& JFactory::getUser();
		$valid_voter = false;
		
		//If gid = 18 then registered User, meaning Voter
		if( $user->get('gid') == 18 ) {
			
			//Check if election is valid
			$valid_election = $this->validElection($electionId);
			
			if($valid_election) {
				//Get voter status for election. If voted-field = 1, voter has voted for this election.
				$query = 'SELECT voted FROM #__joomelection_election_voter_status'
				. ' WHERE voter_id = ' .$user->id. ' AND election_id = ' . (int) $electionId;
				$this->_db->setQuery( $query );
				$has_voted = $this->_db->loadResult();
				
				//If 1, voter has voted allready in this election
				if((int)$has_voted == 1) {
					$valid_voter = false;
				}
				else {
					//Is user Voter or just Registered Joomla User. Only Voters have right to vote.
					$query = 'SELECT voter_id FROM #__joomelection_voter'
					. ' WHERE voter_id = ' .$user->id;
					$this->_db->setQuery( $query );
					$is_voter = $this->_db->loadResult();
					
					if((int)$is_voter > 0) {
						//User id is found from Voter-table. This means that Voter is valid Voter.
						$valid_voter = true;
					}
					else {
						$valid_voter = false;
					}
				}
			}
			else {
				//If election is not valid
				$valid_voter = false;
			}
		}
		else {
			//If any other usegroup except Registered, or not logged-in, not allowed to vote
			$valid_voter = false;
		}

		return $valid_voter;
	}
	
	
	function validOption($optionId)
	{	
		global $mainframe;
		
		$now	= $mainframe->get('requestTime');
		$election_id = $this->getElectionIdFromOption($optionId);
		$valid_election = $this->validElection($election_id);
		
		return $valid_election;
		
		
	}
	
	
	function validElection($election_id)
	{	
		global $mainframe;
		
		$now	= $mainframe->get('requestTime');

		$query = 'SELECT election_id FROM #__joomelection_election'
		. ' WHERE published = 1'
		. ' AND date_to_open <= '.$this->_db->Quote($now)
		. ' AND date_to_close >= '.$this->_db->Quote($now)
		. ' AND election_id = ' . (int) $election_id
		;
		$this->_db->setQuery( $query );
		$election_id = $this->_db->loadResult();

		if((int)$election_id > 0) {
			$valid_election = true;
		}
		else {
			$valid_election = false;
		}

		return $valid_election;
	}
	
	
	function getElectionIdFromOption($optionId)
	{
		$query = 'SELECT election_id FROM #__joomelection_option'
		. ' WHERE published = 1'
		. ' AND option_id = ' . (int) $optionId
		;
		$this->_db->setQuery( $query );
		$election_id = $this->_db->loadResult();

		return (int) $election_id;
	}
	

	
	
	function storeVote($option_id, $election_id, $user_id)
	{
		$success = false;
		
		$query = "INSERT INTO #__joomelection_election_voter_status (election_id, voter_id, voted) "
			. "\n VALUES ('". (int) $election_id ."', '" . (int) $user_id . "', '1')"
			;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			// Insert Failed
		}
		else {
			$query = "INSERT INTO #__joomelection_vote (vote_id, option_id) "
			. "\n VALUES ('', '" . (int) $option_id . "')"
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				// Insert Failed
			}
			else {
				$success = true;
			}
		}
		return $success;
	}
	
	
	function getVoterName()
	{
		$user	=& JFactory::getUser();
		return $user->username;
	}
	
	function getLoggedInStatus()
	{
		$user	=& JFactory::getUser();
		return $user->id;
	}
	
	function getOptionIdEncrypted($option_id)
	{
		$user	=& JFactory::getUser();
		return base64_encode((int) $option_id + (int) $user->id);
	}

	function getOptionIdDecrypted($crypted_option_id)
	{
		$user	=& JFactory::getUser();
		return ((int) base64_decode($crypted_option_id)) - (int) $user->id;
	}
}
