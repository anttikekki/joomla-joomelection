<?php 

defined('_JEXEC') or die('Restricted access');

require_once (JPATH_COMPONENT_ADMINISTRATOR .'/helpers/JoomElectionAdminMultilangHelper.php');
$currentLang =& JFactory::getLanguage();

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_joomelection/css/joomelection_admin.css');

?>

<div id="voter_form_container">
  <form class="form-horizontal" method="post" name="adminForm" id="adminForm">
      
    <!-- Name -->
    <div class="control-group ">
      <div class="control-label">
        <label id="name-lbl" for="name" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_NAME' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->voter->name;?>" />
      </div>
    </div>

    <!-- Username -->
    <div class="control-group ">
      <div class="control-label">
        <label id="username-lbl" for="username" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_USERNAME' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="username" id="username" size="50" maxlength="100" value="<?php echo $this->voter->username;?>" />
      </div>
    </div>

    <!-- Email -->
    <div class="control-group ">
      <div class="control-label">
        <label id="email-lbl" for="email" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_EMAIL' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="email" id="email" size="50" maxlength="100" value="<?php echo $this->voter->email;?>" />
      </div>
    </div>

    <!-- Password -->
    <div class="control-group ">
      <div class="control-label">
        <label id="password-lbl" for="password" class="required" title="">
          <?php 
            if($this->voter->voter_id > 0) { 
              echo JText::_( 'COM_JOOMELECTION_VOTER_NEW_PASSWORD' );
            }
            else {
              echo JText::_( 'COM_JOOMELECTION_VOTER_PASSWORD' );
            }
          ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="password" id="password" size="50" maxlength="100" value="<?php echo $this->voter->password;?>" />
      </div>
    </div>

    <!-- Send password to user with email -->
    <div class="control-group ">
      <div class="control-label">
        <label id="email-lbl" for="email" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_SEND_LOGINS_TO_EMAIL' ); ?>?
        </label>
      </div>
      
      <?php echo JHTML::_('select.booleanlist', 'sendEmailToVoter', null, 0); ?>
    
      <?php 
        if(count($this->elections) == 0) {?>
          <div class="controls">
            <div class="alert alert-error">
              <?php echo JText::_( 'COM_JOOMELECTION_VOTER_NO_ELECTIONS_FOR_EMAIL_ERROR' );?>
            </div>
          </div>
        <?php
        }
      ?>
    </div>

    <!-- Select election thats email message is used -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_id-lbl" for="election_id" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_SELECT_ELECTION' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name_'.$currentLang->getTag() ); ?>
        <div>
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_ELECTION_EMAIL_LANGUAGE_INFO' );?>
        </div>
      </div>
    </div>

    <!-- Language -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_id-lbl" for="election_id" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_LANGUAGE' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JoomElectionAdminMultilangHelper::getLanguageRadioButtonsHtml('voter_language', $this->voter->voter_language); ?>
      </div>
    </div>
    
    <input type="hidden" name="id" value="<?php echo $this->voter->voter_id; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="limit" value="<?php echo $this->stored_limit; ?>" /> <!-- limit, limitstart and search is used when returning to voter listing -->
    <input type="hidden" name="limitstart" value="<?php echo $this->stored_limitstart; ?>" />
    <input type="hidden" name="search" value="<?php echo $this->stored_search; ?>" />
  </form>
</div>
