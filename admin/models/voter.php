<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
define('BUFFER_READ_LEN', 4096);

jimport('joomla.application.component.model');


class JoomElectionModelVoter extends JModelLegacy
{  
  var $_list = null;
  var $_page = null;
  
  
  function __construct()
  {
    $this->_sendEmailToVoter = 0;
    $this->_election_id = 0;
    parent::__construct();
  }
  

  function &getPaginatedVoters()
  {
    $electionModel = $this->getInstance('election', 'JoomElectionModel');
    
    $input = JFactory::getApplication()->input;
    $limit = $input->getInt('limit', JFactory::getApplication()->getCfg('list_limit')); 
    $limitstart = $input->getInt('limitstart', 0);
    $search   = $input->getString('search', '');
    $orderByColumn = $this->_db->escape($input->getString('filter_order', 'u.name'));
    $orderByDirection = $this->_db->escape($input->getString('filter_order_Dir', 'ASC'));
    
    $electionList  =& $electionModel->getAllElections();
    $default_election_id = 0;
    if(count($electionList) > 0) {
      $default_election_id = $electionList[0]->election_id;
    }
    $election_id= $input->getInt('election_id', $default_election_id);
    
    
    // Get the total number of voters
    $query = 'SELECT COUNT(v.voter_id)'
    . ' FROM #__joomelection_voter AS v'
    . ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
    . ' LEFT JOIN #__joomelection_election_voter_status AS evs ON evs.voter_id = v.voter_id AND evs.election_id = '. (int) $election_id
    ;
    if ($search) {
      $query = $query . ' WHERE LOWER(u.name) LIKE "%'.$this->_db->escape($search).'%"';
    }
    
    $this->_db->setQuery($query);
    $total = $this->_db->loadResult();
    
    // Create the pagination object
    $this->_page = new JPagination($total, $limitstart, $limit);
    
    
    
    //Get list data
    $query = ' SELECT v.voter_id, u.name, u.username, u.email, evs.voted, v.email_sent '
    . ' FROM #__joomelection_voter AS v'
    . ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
    . ' LEFT JOIN #__joomelection_election_voter_status AS evs ON evs.voter_id = v.voter_id AND evs.election_id = '. (int) $election_id
    ;
    if ($search) {
      $query .= ' WHERE LOWER(u.name) LIKE "%'.$this->_db->escape($search).'%"';
    }
    $query .= ' ORDER BY ' .$orderByColumn. ' ' . $orderByDirection;
    
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
    $input = JFactory::getApplication()->input;
    $array = $input->get('cid', array(), 'array');
    $voter_id = (int)$array[0];
    
    $query = 'SELECT v.voter_id, v.email_sent, u.name, u.username, u.email '
    . ' FROM #__joomelection_voter AS v'
    . ' LEFT JOIN #__users AS u ON u.id = v.voter_id'
    . ' WHERE v.voter_id = '.$voter_id;
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

      //Set voter lang from JUser parameters
      $voter->voter_language = JFactory::getUser($voter_id)->getParam('language');
    }
    
    return $voter;
  }
  
  
  
  
  function getVoterFromRequest() {
    $input = JFactory::getApplication()->input;
  
    $voter = new stdClass();
    $voter->voter_id = $input->getInt( 'id', 0);
    $voter->name = $input->getString('name', '');
    $voter->username = $input->getString('username', '');
    $voter->password = $input->getRaw('password', '');
    $voter->email = $input->getString('email', '');
    
    return $voter;
  }

  
  

  function store()  {
    $input = JFactory::getApplication()->input;
    $electionModel =& $this->getInstance('election', 'JoomElectionModel');
    $voter         =& $this->getTable();
    $siteDefaultLanguage = JFactory::getLanguage()->getTag();
  
    $user_id_from_post   = $input->getInt( 'id', 0);
    $sendEmailToVoter   = $input->getInt( 'sendEmailToVoter', 0);
    $election_id     = $input->getInt( 'election_id', 0);
  
    $user           = new JUser($user_id_from_post);
    $userData         = array();
    $userData['name']    = $input->getString('name', '');
    $userData['username']  = $input->getString('username', '');
    $userData['password']  = $input->getRaw('password', '');
    $userData['password2']  = $userData['password'];
    $userData['email']    = $input->getString('email', '');
    $userData['params']    = ['language' => $input->getString('voter_language', $siteDefaultLanguage)];
    $clearPassword       = $userData['password'];
    
    /*
    * Registered - This group allows the user to login to the Frontend interface. Registered users can't contribute content, but this may allow them access to other areas, like a 
    * forum or download section if your site has one. 
    */
    $userData['groups'] = array('Registered' => 2);
    
    if($user->id > 0 && $sendEmailToVoter && empty($clearPassword)) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_LOGIN_EMAIL_SEND_ERROR_NO_NEW_PASSWORD'), 'error');
      return false;
    }
    
    if (!$user->bind($userData)) {
      JFactory::getApplication()->enqueueMessage($user->getError(), 'error');
      return false;
    }
    
    /*
    * User validation and creation needs to done with UserTable instead of JUser. 
    * JUser save() sends onUserAfterSave event. This causes Joomla! to send welcome email on default User - Joomla! plugin settings.
    */
    $userTable =& JUser::getTable();
    $userTable->bind($user->getProperties());
    if(!$userTable->check()) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_CREATE_USER_ERROR') . $userData['username'], 'message');
      JFactory::getApplication()->enqueueMessage($userTable->getError(), 'error');
      return false;
    }
    
    if(!$userTable->store()) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_CREATE_USER_ERROR') . $userData['username'], 'message');
      JFactory::getApplication()->enqueueMessage($userTable->getError(), 'error');
      return false;
    }
    
    $email_sent = 0;
    if($sendEmailToVoter) {
      if($election_id > 0) {
        $election = $electionModel->getElection($election_id);
        $this->sendPasswordEmail($userTable, $clearPassword, $election, $user->getParam('language', $siteDefaultLanguage));
        $email_sent = 1;
      }
      else {
        JFactory::getApplication()->enqueueMessage(JText::_( 'COM_JOOMELECTION_VOTER_LOGIN_EMAIL_SEND_ERROR_NO_ELECTION' ), 'message');
        return false;
      }
    }
    
    //Update or create voter
    $voter->voter_id = $userTable->id;
    $voter->email_sent = $email_sent;
    
    if (!$voter->store()) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_CREATE_ERROR') . $userData['username'], 'message');
      JFactory::getApplication()->enqueueMessage($this->_db->getErrorMsg(), 'error');
      return false;
    }
    
    return true;
  }


  
  
  function delete()
  {
    $input = JFactory::getApplication()->input;
    $cids   = $input->get( 'cid', array(), 'array' );
    $voter   =& $this->getTable();

    if (count( $cids ))    {
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
    $query = "
      SELECT v.voter_id
      FROM #__joomelection_voter AS v
    ";
    $this->_db->setQuery( $query );
    $voterIds = $this->_db->loadColumn();
    
    if (count( $voterIds ) > 0)    {
      foreach($voterIds as $voterId) {
        $user =& JUser::getInstance($voterId);
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
    jimport('joomla.filesystem.file');
    jimport('joomla.user.helper');
    $input = JFactory::getApplication()->input;
    
    $voter              =& $this->getTable();
    $electionModel      =& $this->getInstance('election', 'JoomElectionModel');
    $file               = $input->files->get( 'fileUpload');
    $generatePassword   = $input->getInt( 'generatePassword', 0);
    $sendEmailToVoter   = $input->getInt( 'sendEmailToVoter', 0);
    $election_id        = $input->getInt( 'election_id', 0);
    $separator          = $input->getString( 'separator', ';');

    $siteDefaultLanguage = JFactory::getLanguage()->getTag();
    $language = $input->getString('email_language', $siteDefaultLanguage);
    $all_installed_languages = JLanguageHelper::getLanguages();
    
    if($sendEmailToVoter == 1) {
      if(!$election_id > 0) {
        JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_LOGIN_EMAIL_SEND_ERROR_NO_ELECTION'), 'message');
        return false;
      }
    }
    
    if (isset($file['name'])) {
      if(!empty($file['name'])) {
        
        $format = JFile::getExt($file['name']);
        if($format == "csv") {    
          $handle     = fopen($file['tmp_name'], "r");
          $user_data     = array();
          $created_users   = array();
          $import_success = true;

          /*
          * When turned on, PHP will examine the data read by fgets() and file() to see if it is using Unix, MS-Dos or Macintosh line-ending conventions. 
          * This enables PHP to interoperate with Macintosh systems, but defaults to Off, as there is a very small performance penalty when detecting the EOL conventions for the first line
          */
          ini_set("auto_detect_line_endings", true);

          /*
          * Must be greater than the longest line (in characters) to be found in the CSV file (allowing for trailing line-end characters). It became optional in PHP 5. 
          * Omitting this parameter (or setting it to 0 in PHP 5.1.0 and later) the maximum line length is not limited, which is slightly slower. 
          */
          $length = 0;
          
          while (($data = fgetcsv($handle, $length, $separator)) !== false && $import_success == true) {
            
            //Get data from columns
            $user_data['name']     = trim($data[0]);
            $user_data['username']   = trim($data[1]);
            $user_data['password']  = trim($data[2]);
            $user_data['password2'] = $user_data['password'];
            $user_data['email']   = trim($data[3]);
            $user_data['language'] = array_key_exists(4, $data) ? trim($data[4]) : '';

            /*
            * Registered - This group allows the user to login to the Frontend interface. Registered users can't contribute content, but this may allow them access to other areas, like a 
            * forum or download section if your site has one. 
            */
            $user_data['groups']     = array('Registered' => 2);
            
            //Generates random password ifrequired
            if($generatePassword == true) {
              $user_data['password']  = JUserHelper::genRandomPassword();
              $user_data['password2'] = $user_data['password'];
            }

            //Validate user language
            $user_language = $user_data['language'];
            $valid_user_language = null;

            foreach($all_installed_languages as $installed_lang) { 
              if($user_language == $installed_lang->lang_code) {
                $valid_user_language = $installed_lang->lang_code;
              }
            }

            if(!isset($valid_user_language)) {
              $user_language = $siteDefaultLanguage;
            }
            $user_data['params'] = ['language' => $user_language];

            //Email language solving
            $selected_email_language = $language;
            if($language == 'user') {
              $selected_email_language = $user_language;
            }
            
            //Create backupp user-object to be used in email sending
            $created_user = new stdClass();
            $created_user->name = $user_data['name'];
            $created_user->username = $user_data['username'];
            $created_user->password = $user_data['password'];
            $created_user->email = $user_data['email'];
            $created_user->email_language = $selected_email_language;
            
            //Create Joomla userobject
            $user = new JUser();
            
            //Bind data to user object
            if(!$user->bind($user_data)) {
              $import_success = false;
              continue;
            }
            
            /*
            * User validation and creation needs to done with UserTable instead of JUser. 
            * JUser save() sends onUserAfterSave event. This causes Joomla! to send welcome email on default User - Joomla! plugin settings.
            */
            $userTable =& JUser::getTable();
            $userTable->bind($user->getProperties());
            if(!$userTable->check()) {
              JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_IMPORT_CREATE_USER_ERROR') . $user_data['username'], 'error');
              JFactory::getApplication()->enqueueMessage($userTable->getError(), 'error');
              $import_success = false;
              continue;
            }
            
            //Save user to database
            if (!$userTable->store()) {
              JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_IMPORT_CREATE_USER_ERROR') . $user_data['username'], 'error');
              JFactory::getApplication()->enqueueMessage($userTable->getError(), 'error');
              $import_success = false;
              continue;
            }
            
            //Store new Joomla user id
            $created_user->id = $userTable->id;
            $created_users[] = $created_user;
            
            //Create voter
            $voter->reset();
            $voter->voter_id = $userTable->id;
            $voter->email_sent = 0;
            
            if (!$voter->store()) {
              JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_CREATE_ERROR') . $user_data['username'], 'error');
              JFactory::getApplication()->enqueueMessage($voter->getError(), 'error');
              $import_success = false;
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
                  $this->sendPasswordEmail($created_user, $created_user->password, $election, $created_user->email_language);
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
          JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_IMPORT_FILE_IS_NOT_CSV'), 'error');
          return false;
        }
      }
      else {
        JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_IMPORT_FILE_INVALID'), 'error');
        return false;
      }
      
    }
    else {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_IMPORT_FILE_MISSING'), 'error');
      return false;
    }
    
  }

  
  
  
  
  function sendPasswordEmail($user, $password, &$election, $language) {
    $name       = $user->name;
    $email       = $user->email;
    $username     = $user->username;
    $sitename     = JFactory::getApplication()->getCfg( 'sitename' );
    $mailfrom     = JFactory::getApplication()->getCfg( 'mailfrom' );
    $fromname     = JFactory::getApplication()->getCfg( 'fromname' );

    $language = (!isset($language) || trim($language) === '') ? JFactory::getLanguage()->getTag() : $language;
    $electionNameFieldName = 'election_name_' . $language;
    $emailHeaderFieldName = 'election_voter_email_header_' . $language;
    $emailTextFieldName = 'election_voter_email_text_' . $language;
    
    $markers  = array("[name]", "[username]", "[password]", "[election_name]", "[www]");
    $data     = array($name, $username, $password, $election->$electionNameFieldName, JURI::root());
    
    $subject  = str_replace($markers, $data, $election->$emailHeaderFieldName);
    $subject   = html_entity_decode($subject, ENT_QUOTES);
    
    $message   = str_replace($markers, $data, $election->$emailTextFieldName);
    $message   = html_entity_decode($message, ENT_QUOTES);
    
    JMail::getInstance()->sendMail($mailfrom, $fromname, $email, $subject, $message);  
  }
  
  
  
  
  
  function generatePasswordAndSendEmail() {
    jimport('joomla.user.helper');
    $input = JFactory::getApplication()->input;
    
    $selectedGenerationGroup   = $input->getInt( 'selectedGenerationGroup', 3);
    $selectedVotersIdsArray   = array();
    $user_data          = array();
    $updated_users         = array();
    $siteDefaultLanguage = JFactory::getLanguage()->getTag();
    $language = $input->getString('email_language', $siteDefaultLanguage);
    
    if($selectedGenerationGroup == 1) {
      //1 = generate and send email to selected
      $selectedVotersIdsString   = $input->getString( 'selectedVoters', '');
      $selectedVotersIdsArray = explode(",", $selectedVotersIdsString);
    }
    else if($selectedGenerationGroup == 0) {
      //0 = generate password and send email to all voters
      $query = ' SELECT v.voter_id '
      . ' FROM #__joomelection_voter AS v';
      $this->_db->setQuery( $query );
      $selectedVotersIdsArray = $this->_db->loadColumn();
    }
    
    if (count( $selectedVotersIdsArray )) {
      foreach($selectedVotersIdsArray as $selectedVoterId) {
        //Load existing user with id
        $user = new JUser($selectedVoterId);
        
        //Generate new password
        $user_data['password']  = JUserHelper::genRandomPassword();
        $user_data['password2'] = $user_data['password'];

        //User language solver
        $selected_language = $language;
        if($language == 'user') {
          $selected_language = $user->getParam('language', $siteDefaultLanguage);
        }
        
        //Create backupp user-object to be used in email sending
        $updated_user       = new stdClass();
        $updated_user->id     = $user->id;
        $updated_user->name   = $user->name;
        $updated_user->username = $user->username;
        $updated_user->password = $user_data['password'];
        $updated_user->email   = $user->email;
        $updated_user->email_language = $selected_language;
        $updated_users[]     = $updated_user;
    
        //Bind new password to user object
        $user->bind($user_data);
        
        //Update user data to database
        if (!$user->save()) {
          JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_USER_SAVE_ERROR') . $user->username, 'error');
          JFactory::getApplication()->enqueueMessage($user->getError(), 'error');
          return false;
        }
      }
    }
    
    
    $election_id = $input->getInt('election_id', 0);
    if($election_id > 0) {
      $electionModel   =& $this->getInstance('election', 'JoomElectionModel');
      $election    =& $electionModel->getElection($election_id);
      $voter       =& $this->getTable();
      
      if (count( $updated_users )) {
        foreach($updated_users as $updatedUser) {
          $this->sendPasswordEmail($updatedUser, $updatedUser->password, $election, $updatedUser->email_language);
          $voter->load($updatedUser->id);
          $voter->email_sent = 1;
          $voter->store();
        }
      }
      
      return true;
    }
    else {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_JOOMELECTION_VOTER_LOGIN_EMAIL_SEND_ERROR_NO_ELECTION'), 'error');
      return false;
    }
  }
  
}
?>
