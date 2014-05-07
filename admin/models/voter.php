<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
define('BUFFER_READ_LEN', 4096);

jimport('joomla.application.component.model');


class JoomElectionModelVoter extends JModel
{	
	var $_list = null;
	var $_page = null;
	
	
	function __construct()
	{
		$this->_sendEmailToVoter = 0;
		$this->_election_id = 0;
		parent::__construct();
	}
	

	function &getVoters()
	{
		global $mainframe;
		$electionModel = $this->getInstance('election', 'JoomElectionModel');
		
		$limit 		= JRequest::getVar('limit', $mainframe->getCfg('list_limit')); 
		$limitstart = JRequest::getVar('limitstart', 0);
		$search 	= JRequest::getVar('search', '');
		
		$electionList	=& $electionModel->getAllElections();
		$default_election_id = 0;
		if(count($electionList) > 0) {
			$default_election_id = $electionList[0]->election_id;
		}
		$election_id= JRequest::getVar('election_id', $default_election_id);
		
		
		// Get the total number of voters
		$query = 'SELECT COUNT(v.voter_id)'
		. ' FROM #__joomelection_voter AS v'
		. ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
		. ' LEFT JOIN #__joomelection_election_voter_status AS evs ON evs.voter_id = v.voter_id AND evs.election_id = '.$election_id
		;
		if ($search) {
			$query = $query . ' WHERE LOWER(u.name) LIKE "%'.$this->_db->getEscaped($search).'%"';
		}
		$query = $query . ' ORDER BY u.name';
		
		$this->_db->setQuery($query);
		$total = $this->_db->loadResult();
		
		// Create the pagination object
		jimport('joomla.html.pagination');
		$this->_page = new JPagination($total, $limitstart, $limit);
		
		
		
		//Get list data
		$query = ' SELECT v.voter_id, u.name, u.username, u.email, evs.voted, v.email_sent '
		. ' FROM #__joomelection_voter AS v'
		. ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
		. ' LEFT JOIN #__joomelection_election_voter_status AS evs ON evs.voter_id = v.voter_id AND evs.election_id = '. (int) $election_id
		;
		if ($search) {
			$query = $query . ' WHERE LOWER(u.name) LIKE "%'.$this->_db->getEscaped($search).'%"';
		}
		$query = $query . ' ORDER BY u.name';
		
		$this->_list = $this->_getList( $query, $limitstart, $limit );
		
		return $this->_list;
	}
	
	
	
	function &getPagination()
	{
		if (is_null($this->_list) || is_null($this->_page)) {
			$this->getVoters();
		}
		return $this->_page;
	}
	

	function getVoter()
	{
		$array = JRequest::getVar('cid',  0, '', 'array');
		
		$query = 'SELECT v.voter_id, v.email_sent, u.name, u.username, u.email '
		. ' FROM #__joomelection_voter AS v'
		. ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
		. ' WHERE v.voter_id = '.(int)$array[0];
		;
		$this->_db->setQuery( $query );
		$voter = $this->_db->loadObject();
		
		if($voter == null) {
			$voter = new stdClass();
			$voter->voter_id = 0;
			$voter->name = null;
			$voter->password = null;
			$voter->username = null;
			$voter->email = null;
		}
		else {
			$voter->password = null;
		}
		
		return $voter;
	}
	
	
	
	
	function getVoterFromRequest() {
		$voter = new stdClass();
		$voter->voter_id = JRequest::getVar( 'id', 0, 'post', 'int');
		$voter->name = JRequest::getVar('name', '', 'post', 'string');
		$voter->username = JRequest::getVar('username', '', 'post', 'string');
		$voter->password = JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$voter->email = JRequest::getVar('email', '', 'post', 'string');
		
		return $voter;
	}

	
	

	function store()	{
		global $mainframe;
		$electionModel 	=& $this->getInstance('election', 'JoomElectionModel');
		$voter 			=& $this->getTable();
	
	 	// Create a new voter
	 	$user_id_from_post 	= JRequest::getVar( 'id', 0, 'post', 'int');
		$sendEmailToVoter 	= JRequest::getVar( 'sendEmailToVoter', 0, 'post', 'int');
		$election_id	 	= JRequest::getVar( 'election_id', 0, 'post', 'int');
	
		$user 					= new JUser($user_id_from_post);
		$userData 				= array();
		$userData['name']		= JRequest::getVar('name', '', 'post', 'string');
		$userData['username']	= JRequest::getVar('username', '', 'post', 'string');
		$userData['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$userData['password2']	= $userData['password'];
		$userData['email']		= JRequest::getVar('email', '', 'post', 'string');
		$userData['gid']		= 18; //Userlevel
		$clearPassword 			= $userData['password'];
		
		//Validate data
		if($this->validateUser($userData, $user->id) == false) {
			return false;
		}
		
		if (!$user->bind($userData)) {
			$mainframe->enqueueMessage($user->getError(), 'error');
			return false;
		}
		
		if(!$user->save()) {
			$mainframe->enqueueMessage(JText::_('Cannot save the user information for user ') . $userData['username'], 'message');
			$mainframe->enqueueMessage($user->getError(), 'error');
			return false;
		}
		
		$email_sent = 0;
		if($sendEmailToVoter) {
			if($election_id > 0) {
				$election = $electionModel->getElection($election_id);
				$this->sendPasswordEmail($user, $clearPassword, $election);
				$email_sent = 1;
			}
			else {
				$mainframe->enqueueMessage(JText::_( 'Cannot send password to voter because you didnt select election that email is used' ), 'message');
				return false;
			}
		}
		
		if($voter->load($user_id_from_post)) {
			//Existing voter
			$voter->email_sent = $email_sent;
			if (!$voter->store()) {
				$mainframe->enqueueMessage(JText::_('Cannot create voter information for voter ') . $userData['username'], 'message');
				$mainframe->enqueueMessage($this->_db->getErrorMsg(), 'error');
				return false;
			}
		}
		else {
			//New voter
			//Manual insert because voter->store tryes to update existing voter
			$query = "INSERT INTO #__joomelection_voter (voter_id, email_sent) "
			. "\n VALUES ('" .(int) $user->id . "', '" . (int) $email_sent . "')"
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$mainframe->enqueueMessage(JText::_('Cannot create voter information for voter ') . $userData['username'], 'message');
				$mainframe->enqueueMessage($this->_db->getErrorMsg(), 'error');
				return false;
			}
		}
		
		return true;
	}
	
	
	
	
	
	function validateUser($userData, $user_id) {
		global $mainframe;
		
		//Validate name is not empty
		if ((strlen(trim($userData['name'])) > 0) == false) {
			$mainframe->enqueueMessage('Voter name can not be empty', 'error');
			return false;
		}
		
		
		//Validate username is not empty
		if ((strlen(trim($userData['username'])) > 0) == false) {
			$mainframe->enqueueMessage('Voter username can not be empty', 'error');
			return false;
		}
		else {
			//copyed from \libraries\joomla\database\table\user.php
			// check for existing username
			$query = 'SELECT id'
			. ' FROM #__users '
			. ' WHERE username = ' . $this->_db->Quote($userData['username'])
			. ' AND id != '. (int) $user_id;
			;
			$this->_db->setQuery( $query );
			$id_from_db = intval( $this->_db->loadResult() );
			if ($id_from_db && $id_from_db != intval( $user_id )) {
				$mainframe->enqueueMessage('Username is allready in use. Username have to be unique', 'error');
				return false;
			}
		}
		
		
		//Validate email is not empty
		if ((strlen(trim($userData['email'])) > 0) == false) {
			$mainframe->enqueueMessage('Voter email can not be empty', 'error');
			return false;
		}
		else {
			//copyed from \libraries\joomla\database\table\user.php
			// check for existing email
			$query = 'SELECT id'
				. ' FROM #__users '
				. ' WHERE email = '. $this->_db->Quote($userData['email'])
				. ' AND id != '. (int) $user_id
				;
			$this->_db->setQuery( $query );
			$id_from_db = intval( $this->_db->loadResult() );
			if ($id_from_db && $id_from_db != intval( $user_id )) {
				$mainframe->enqueueMessage('Email is allready in use. Email has to be unique.', 'error');
				return false;
			}
		}
		
		return true;
	}


	
	
	function delete()
	{
		$cids 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$voter 	=& $this->getTable();

		if (count( $cids ))		{
			foreach($cids as $cid) {
				if (!$voter->delete((int)$cid)) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
				else {
					$user =& JUser::getInstance((int)$cid);
					$user->delete();
					
					$query = ' DELETE FROM #__joomelection_election_voter_status '
					. '  WHERE voter_id = '. (int) $cid;
					$this->_db->setQuery( $query );
					$this->_db->query();
				}
			}						
		}
		
		return true;
	}
	
	
	function deleteAll()
	{
		$query = ' SELECT v.voter_id '
		. ' FROM #__joomelection_voter AS v'
		. ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
		;
		$this->_db->setQuery( $query );
		$cids = $this->_db->loadResultArray();
		
		if (count( $cids ))		{
			foreach($cids as $cid) {
				$user =& JUser::getInstance($cid);
				$user->delete();
			}						
		}
		//Voters
		$query = ' DELETE FROM #__joomelection_voter';
		$this->_db->setQuery( $query );
		$this->_db->query();
		
		//Status
		$query = ' DELETE FROM #__joomelection_election_voter_status';
		$this->_db->setQuery( $query );
		$this->_db->query();
		
		return true;
	}
	
	
	function importVotersFromCsv() {
		//Import voters from CSV-file and store them to Users-table
		global $mainframe;
		jimport('joomla.filesystem.file');
		jimport('joomla.user.helper');
		
		$voter 				=& $this->getTable();
		$electionModel 		=& $this->getInstance('election', 'JoomElectionModel');
		$file 				= JRequest::getVar( 'fileUpload', '', 'files', 'array' );
		$generatePassword 	= JRequest::getVar( 'generatePassword', 0, 'post', 'int');
		$sendEmailToVoter 	= JRequest::getVar( 'sendEmailToVoter', 0, 'post', 'int');
		$election_id	 	= JRequest::getVar( 'election_id', 0, 'post', 'int');
		$separator		 	= JRequest::getVar( 'separator', ';', 'post', 'string');
		
		if($sendEmailToVoter == 1) {
			if(!$election_id > 0) {
				$mainframe->enqueueMessage(JText::_('Cannot send password to voter because you didnt select election that email is used'), 'message');
				return false;
			}
		}
		
		if (isset($file['name'])) {
			if(!empty($file['name'])) {
				
				$format = JFile::getExt($file['name']);
				if($format == "csv") {		
					$handle 		= fopen($file['tmp_name'], "r");
					$user_data 		= array();
					$created_users 	= array();
					$import_success = true;
					
					while (($data = $this->fgetcsv_ex($handle, $separator)) !== null && $import_success == true) {
						if($this->is_utf8($data[0]) && $this->is_utf8($data[1]) && $this->is_utf8($data[2]) && $this->is_utf8($data[3])) {
							//CSV is allready UTF-8. No need to encode
							$user_data['name'] 		= trim($data[0]);
							$user_data['username'] 	= trim($data[1]);
							$user_data['password']	= trim($data[2]);
							$user_data['password2'] = $user_data['password'];
							$user_data['email'] 	= trim($data[3]);
						}
						else {
							//CSV is not UTF-8. Convert.
							$user_data['name'] 		= trim(utf8_encode($data[0]));
							$user_data['username'] 	= trim(utf8_encode($data[1]));
							$user_data['password'] 	= trim(utf8_encode($data[2]));
							$user_data['password2'] = $user_data['password'];
							$user_data['email'] 	= trim(utf8_encode($data[3]));
						}

						$user_data['gid'] 		= 18; //Registered-usergroup
						
						//Generates random password ifrequired
						if($generatePassword == true) {
							$user_data['password']  = JUserHelper::genRandomPassword();
							$user_data['password2'] = $user_data['password'];
						}
						
						//Validate data
						if($this->validateUser($user_data, 0) == false) {
							$mainframe->enqueueMessage(JText::_('Invalid CSV data. Are columns and csv data separator correct? Usernames and emails have to be unique!'), 'error');
							$import_success = false;
						}
						else {
							//Create backupp user-object to be used in email sending
							$created_user = new stdClass();
							$created_user->name = $user_data['name'];
							$created_user->username = $user_data['username'];
							$created_user->password = $user_data['password'];
							$created_user->email = $user_data['email'];
							
							//Create Joomla userobject
							$user = new JUser();
							
							//Bind data to user object
							if(!$user->bind($user_data)) {
								$import_success = false;
							}
							
							//Save user to database
							if (!$user->save()) {
								$mainframe->enqueueMessage(JText::_('Invalid CSV data. Are columns and csv data separator correct?  Username that failed: ') . $user_data['username'], 'error');
								$mainframe->enqueueMessage($user->getError(), 'error');
								$import_success = false;
							}
							else {
								$created_user->id = $user->id;
								$created_users[] = $created_user;
								
								//Manual insert because voter->store tryes to update existing voter
								$query = "INSERT INTO #__joomelection_voter (voter_id, email_sent) "
								. "\n VALUES ('" .(int) $user->id . "', '" . 0 . "')"
								;
								$this->_db->setQuery( $query );
								if (!$this->_db->query()) {
									$mainframe->enqueueMessage(JText::_('Cannot save the user information for user ') . $user_data['username'], 'message');
									$mainframe->enqueueMessage($user->getError(), 'error');
									$import_success = false;
								}
							}
						}
					}
					//Close file connection
					fclose($handle);
					
					if($import_success == true) {
						//All users created succesfully. Send email to user if required
						if($sendEmailToVoter == 1) {
							if (count( $created_users )) {
								$election = $electionModel->getElection($election_id);
								foreach($created_users as $created_user) {
									$this->sendPasswordEmail($created_user, $created_user->password, $election);
									$voter->load($created_user->id);
									$voter->email_sent = 1;
									$voter->store();
								}
							}
						}
					}
					else {
						//Import was failure --> delete all created users
						if (count( $created_users )) {
							foreach($created_users as $created_user_to_delete) {
								$voter->delete($created_user_to_delete->id);
								$user = new JUser($created_user_to_delete->id);
								$user->delete();
							}
						}
					}
					
					return $import_success;
				}
				else {
					$mainframe->enqueueMessage('Tiedosto ei ole CSV-tiedosto', 'message');
					return false;
				}
			}
			else {
				$mainframe->enqueueMessage('Epäkelpo tiedosto', 'message');
				return false;
			}
			
		}
		else {
			$mainframe->enqueueMessage('Valitse tiedosto ladataksesi sen', 'message');
			return false;
		}
		
	}

	
	
	
	
	function sendPasswordEmail($user, $password, &$election) {
		global $mainframe;

		$name 			= $user->name;
		$email 			= $user->email;
		$username 		= $user->username;
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		
		$markers 	= array("[name]", "[username]", "[password]", "[election_name]", "[www]");
		$data   	= array($name, $username, $password, $election->election_name, $mainframe->getSiteURL());
		
		$subject	= str_replace($markers, $data, $election->election_voter_email_header);
		$subject 	= html_entity_decode($subject, ENT_QUOTES);
		
		$message 	= str_replace($markers, $data, $election->election_voter_email_text);
		$message 	= html_entity_decode($message, ENT_QUOTES);
		
		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);	
	}
	
	
	
	
	
	function generatePasswordAndSendEmail() {
		global $mainframe;
		jimport('joomla.user.helper');
		
		$selectedGenerationGroup 	= JRequest::getVar( 'selectedGenerationGroup', 3, 'post', 'int' );
		$selectedVotersIdsArray 	= array();
		$user_data					= array();
		$updated_users 				= array();
		
		//1 = generate and send email to selected
		if($selectedGenerationGroup == 1) {
			$selectedVotersIdsString 	= JRequest::getVar( 'selectedVoters', '', 'post', 'string' );
			$selectedVotersIdsArray = explode(",", $selectedVotersIdsString);
		}
		else if($selectedGenerationGroup == 0) { //Generate password and sen email to all voters
			$query = ' SELECT v.voter_id '
			. ' FROM #__joomelection_voter AS v'
			. ' LEFT JOIN #__users AS u ON u.id = v.voter_id';
			$this->_db->setQuery( $query );
			$selectedVotersIdsArray = $this->_db->loadResultArray();
		}
		
		if (count( $selectedVotersIdsArray )) {
			foreach($selectedVotersIdsArray as $selectedVoterId) {
				//Load existing user with id
				$user = new JUser($selectedVoterId);
				
				//Generate new password
				$user_data['password']  = JUserHelper::genRandomPassword();
				$user_data['password2'] = $user_data['password'];
				
				//Create backupp user-object to be used in email sending
				$updated_user 			= new stdClass();
				$updated_user->id 		= $user->id;
				$updated_user->name 	= $user->name;
				$updated_user->username = $user->username;
				$updated_user->password = $user_data['password'];
				$updated_user->email 	= $user->email;
				$updated_users[] 		= $updated_user;
		
				//Bind new password to user object
				$user->bind($user_data);
				
				//Update user data to database
				if (!$user->save()) {
					$mainframe->enqueueMessage(JText::_('Cannot update the user information for user ') . $user->username, 'message');
					$mainframe->enqueueMessage($user->getError(), 'error');
					return false;
				}
			}
		}
		
		
		$election_id = JRequest::getVar('election_id', 0, 'post', 'int');
		if($election_id > 0) {
			$electionModel 	=& $this->getInstance('election', 'JoomElectionModel');
			$election		=& $electionModel->getElection($election_id);
			$voter 			=& $this->getTable();
			
			if (count( $updated_users )) {
				foreach($updated_users as $updatedUser) {
					$this->sendPasswordEmail($updatedUser, $updatedUser->password, $election);
					$voter->load($updatedUser->id);
					$voter->email_sent = 1;
					$voter->store();
				}
			}
			
			return true;
		}
		else {
			$mainframe->enqueueMessage('No election created. You need to create at least one election to send emails.', 'message');
			return false;
		}
	}
	
	
	
	
	
	/**
	* Returns true if $string is valid UTF-8 and false otherwise.
	*/
    function is_utf8($string) {
      
        // From http://w3.org/International/questions/qa-forms-utf-8.html
        return preg_match('%^(?:
              [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $string);
      
    }
	
	

	/*
	   From: http://fi.php.net/manual/en/function.fgetcsv.php#82637
	
	    Modified function from user comment by Marcos Boyington / 06-Mar-2008 03:08
	   
	    This is a pretty useful update/modification to the fgetcsv function, which allows for:
	    * Multiple-character/multibyte delim/enclosure/escape
	    * Multibyte values
	    * Escape character specification in < PHP5
	    * Escape character = delim character
	    * Direct reading from files without bloating memory too much
	*/
	function fgetcsv_ex($file_handle, $delim = ',', $enclosure = '"', $escape = '"') {
	    $fields = null;
	    $fldCount = 0;
	    $inQuotes = false;

	    $complete = false;
	    $search_chars_list = array('\r\n', '\n', '\r');
	    if ($delim && ($delim != ''))
	        $search_chars_list[] = $delim;
	    if ($enclosure && ($enclosure != '')) {
	        $search_chars_list[] = $enclosure;
	        $enclosure_len = strlen($enclosure);
	    } else
	        $enclosure_len = 0;

	    if ($escape && ($escape != '')) {
	        $search_chars_list[] = $escape;
	        $escape_len = strlen($escape);
	    } else
	        $escape_len = 0;
	    $search_regex = '/' . implode('|', $search_chars_list) . '/';

	    $cur_pos = 0;
	    $line = '';
	    $cur_value = '';
	    $in_value = false;
	    $last_value = 0;
	    while (! $complete) {
	        $read_result = fread($file_handle, BUFFER_READ_LEN);
	        if ($read_result) {
	            $line .= $read_result;
	        } else if (strlen($line) == 0) {
	            return null;
	        } else {
	            $line .= "\n";
	        }

	        $line_len = strlen($line);

	        while (true) {
	            if (! preg_match($search_regex, $line, $matches, PREG_OFFSET_CAPTURE, $cur_pos)) {
	                if ($read_result) {
	                    // need more chars
	                    break;
	                } else {
	                    // Incomplete file
	                    return null;
	                }
	            } else {
	                $non_escape = false;
	                $cur_char = $matches[0][0];
	                $cur_len = strlen($cur_char);
	                $new_pos = $matches[0][1];
	                if (($enclosure == $escape) && $in_value && ($cur_char == $escape)) {
	                    // Escape char = enclosure char special handling
	                    if (($new_pos + $cur_len + $enclosure_len) >= $line_len) {
	                        // We need the next char
	                        break;
	                    }

	                    $next_char = substr($line, $new_pos + $cur_len, $enclosure_len);
	                    if ((! $enclosure) || ($next_char != $enclosure)) {
	                        $non_escape = true;
	                    }
	                }

	                $cur_pos = $new_pos;
	                if ($in_value && (! $non_escape)) {
	                    $cur_value .= mb_substr($line, $last_value, $cur_pos - $last_value);
	                    if ($cur_char == $escape) {
	                        // Skip escape char
	                        $cur_pos += $escape_len;
	                    }
	                    $last_value = $cur_pos;
	                } else if (($cur_char == "\n") || ($cur_char == "\r") || ($cur_char == "\r\n")) {
	                    $blank_start_lines = ($cur_pos == 0);
	                    ++$cur_pos;
	                    $cur_pos = $cur_pos + strspn($line, "\n\r", $cur_pos);
	                    if (! $blank_start_lines) {
	                        $complete = true;
	                    } else {
	                        $last_value = $cur_pos;
	                        continue;
	                    }
	                }
	                if ($cur_char == $delim || $complete) {
	                    if (is_null($fields)) {
	                        $fields = array();
	                    }
	                    $fields[] = $cur_value . trim(mb_substr($line, $last_value, $cur_pos - $last_value));
	                    $last_value = $cur_pos + $cur_len;
	                    $cur_value = '';
	                } else if ($cur_char == $enclosure) {
	                    if ($in_value) {
	                        $cur_value .= mb_substr($line, $last_value, $cur_pos - $last_value);
	                    }
	                    $last_value = $cur_pos + $cur_len;
	                    $in_value = ! $in_value;
	                }
	                if ($complete) {
	                    break;
	                }
	                $cur_pos += $cur_len;
	            }
	        }
	    }

	    fseek($file_handle, $cur_pos - strlen($line), SEEK_CUR);
	    return $fields;
	} 
}
?>
