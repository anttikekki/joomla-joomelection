<?php defined('_JEXEC') or die('Restricted access'); 


?>

<form method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

  <!-- File -->
  <div class="control-group ">
    <div class="control-label">
      <label for="fileUpload" class="required" title="">
        <?php echo JText::_( 'Filename' ); ?>
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
        <?php echo JText::_( 'CSV file data separator' ); ?>
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

  <div class="alert alert-info">
    <?php echo JText::_('Import info text'); ?>
    <br /><br />
    <a href="<?php echo JRoute::_('components/com_joomelection/importExample/test_user_import.csv'); ?>"><?php echo JText::_('Example import file'); ?></a>
  </div>

  <!-- Generate random passwords -->
  <div class="control-group ">
    <div class="control-label">
      <label for="generatePassword" class="required" title="">
        <?php echo JText::_( 'Generate random passwords' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <?php echo JHTML::_('select.booleanlist', 'generatePassword', null, 0); ?>
    </div>
  </div>

  <!-- Send username and password to users email -->
  <div class="control-group ">
    <div class="control-label">
      <label for="sendEmailToVoter" class="required" title="">
        <?php echo JText::_( 'Send username and password to users email' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <?php echo JHTML::_('select.booleanlist', 'sendEmailToVoter', null, 0); ?>
    </div>
  </div>

  <!-- Select election thats email message is used -->
  <div class="control-group ">
    <div class="control-label">
      <label for="sendEmailToVoter" class="required" title="">
        <?php echo JText::_( 'Select election thats email message is used' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <?php 
        if(count($this->elections) > 0) {
          echo JHTML::_('select.genericlist', $this->elections, 'election_id', 'class="inputbox" ', 'election_id', 'election_name' );
        }
        else {
          ?>
          <div class="alert alert-error">
           <?php echo JText::_( 'No elections available, impossible to send email. Create at least one election  first.' ); ?>
          </div>
          <?php
        }
      ?>
    </div>
  </div>

  <input type="hidden" name="task" value="" />
</form>
