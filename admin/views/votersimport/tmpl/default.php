<?php defined('_JEXEC') or die('Restricted access'); 

require_once (JPATH_COMPONENT_ADMINISTRATOR .'/helpers/JoomElectionAdminMultilangHelper.php');
$currentLang =& JFactory::getLanguage();

?>
<style type="text/css">

.form-horizontal .controls {
    margin-left: 300px;
}

</style>

<form class="form-horizontal"  method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
      
  <div class="alert alert-info">
    <?php echo JText::_('COM_JOOMELECTION_VOTER_IMPORT_INFO_TEXT'); ?>
    <br /><br />
    <a href="<?php echo JRoute::_('components/com_joomelection/importExample/test_user_import.csv'); ?>"><?php echo JText::_('COM_JOOMELECTION_VOTER_IMPORT_EXAMPLE_FILE'); ?></a>
  </div>

  <!-- File -->
  <div class="control-group ">
    <div class="control-label">
      <label for="fileUpload" class="required" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_VOTER_IMPORT_FILENAME' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <input type="file" id="fileUpload" name="fileUpload" />
    </div>
  </div>

  <!-- CSV file data separator -->
  <div class="control-group ">
    <div class="control-label">
      <label for="separator" class="required" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_VOTER_IMPORT_CSV_FILE_SEPARATOR' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <select id="separator" name="separator">
        <option value=","> , </option>
        <option value=";"> ; </option>
      </select>
    </div>
  </div>

  

  <!-- Generate random passwords -->
  <div class="control-group ">
    <div class="control-label">
      <label for="generatePassword" class="required" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_RANDOM' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <?php echo JHTML::_('select.booleanlist', 'generatePassword', null, 0); ?>
  </div>

  <!-- Send username and password to users email -->
  <div class="control-group ">
    <div class="control-label">
      <label for="sendEmailToVoter" class="required" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_VOTER_SEND_LOGINS_TO_EMAIL' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <?php echo JHTML::_('select.booleanlist', 'sendEmailToVoter', null, 0); ?>
  </div>

  <!-- Select election thats email message is used -->
  <div class="control-group ">
    <div class="control-label">
      <label for="sendEmailToVoter" class="required" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_VOTER_SELECT_ELECTION' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <?php 
        if(count($this->elections) > 0) {
          echo JHTML::_('select.genericlist', $this->elections, 'election_id', 'class="inputbox" ', 'election_id', 'election_name_'.$currentLang->getTag() );
        }
        else {
          ?>
          <div class="alert alert-error">
           <?php echo JText::_( 'COM_JOOMELECTION_VOTER_NO_ELECTIONS_FOR_EMAIL_ERROR' ); ?>
          </div>
          <?php
        }
      ?>
    </div>
  </div>

    <!-- Language -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_id-lbl" for="election_id" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_EMAIL_LANGUAGE' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <label class="radio">
          <input type="radio" value="user" name="email_language">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_EMAIL_LANGUAGE_USER' ); ?>
        </label>
        <?php echo JoomElectionAdminMultilangHelper::getLanguageRadioButtonsHtml('email_language', $currentLang->getTag()); ?>
      </div>
    </div>

  <input type="hidden" name="task" value="" />
</form>