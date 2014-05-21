<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor();
JHTML::_('behavior.calendar');
?>

<form class="form-horizontal" method="post" name="adminForm" id="adminForm">
  <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
  
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_JOOMELECTION_GENERAL')); ?>
  
    <!-- Election name -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_name-lbl" for="election_name" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_NAME' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="election_name" id="election_name" size="32" maxlength="250" value="<?php echo $this->election->election_name;?>" />
      </div>
    </div>
    
    <!-- Election type -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_type_id-lbl" for="election_type_id" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_TYPE' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JHTML::_('select.genericlist', $this->electionTypes, 'election_type_id', null, 'election_type_id', 'type_name', $this->election->election_type_id ); ?>
      </div>
    </div>
    
    <!-- Date to open -->
    <div class="control-group ">
      <div class="control-label">
        <label id="date_to_open-lbl" for="date_to_open" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_DATE_TO_OPEN' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JHTML::_('calendar', JHTML::_('date',  $this->election->date_to_open, 'Y-m-d'), 'date_to_open', 'date_to_open', '%Y-%m-%d', array(' READONLY ')); ?>
      </div>
    </div>
    
    <!-- Time to open -->
    <div class="control-group ">
      <div class="control-label">
        <label id="time_to_open-lbl" for="time_to_open" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_TIME_TO_OPEN' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="time_to_open" id="time_to_open" size="10" maxlength="8" value="<?php echo JHTML::_('date',  $this->election->date_to_open, 'H:i:s') ;?>" />
      </div>
    </div>
    
    <!-- Date to close -->
    <div class="control-group ">
      <div class="control-label">
        <label id="date_to_close-lbl" for="date_to_close" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_DATE_TO_CLOSE' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JHTML::_('calendar', JHTML::_('date',  $this->election->date_to_close, 'Y-m-d'), 'date_to_close', 'date_to_close', '%Y-%m-%d', array(' READONLY ')); ?>
      </div>
    </div>
    
    <!-- Time to close -->
    <div class="control-group ">
      <div class="control-label">
        <label id="time_to_close-lbl" for="time_to_close" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_TIME_TO_CLOSE' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
       <input type="text" name="time_to_close" id="time_to_close" size="10" maxlength="8" value="<?php echo JHTML::_('date',  $this->election->date_to_close, 'H:i:s') ;?>" />
      </div>
    </div>
    
    <!-- Published -->
    <div class="control-group ">
      <div class="control-label">
        <label id="published-lbl" for="published" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_PUBLISHED' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
       <?php echo JHTML::_('select.booleanlist', 'published', null, $this->election->published); ?>
      </div>
    </div>
    
      <!-- Election description -->
      <div class="control-group ">
        <div class="control-label">
          <label id="election_name-lbl" for="election_description" class="required" title="">
            <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_DESCRIPTION' ); ?>
            <span class="star">&nbsp;*</span>
          </label>
        </div>
        <div class="controls">
          <?php echo $editor->display( 'election_description', $this->election->election_description, '100%', '300', '60', '35' ); ?>
        </div>
      </div>
    
  <?php echo JHtml::_('bootstrap.endTab'); ?>
  
  
  
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'vote_confirmation', JText::_('COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_HEADER')); ?>
  
    <!-- Vote confirmation -->
    <div class="control-group ">
      <div class="control-label">
        <label id="confirm_vote-lbl" for="confirm_vote" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
       <?php echo JHTML::_('select.booleanlist', 'confirm_vote', null, $this->election->confirm_vote); ?>
      </div>
    </div>
    
    <!-- Vote confirmation by signing-->
    <div class="control-group ">
      <div class="control-label">
        <label id="confirm_vote-lbl" for="confirm_vote" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
       <?php echo JHTML::_('select.booleanlist', 'confirm_vote_by_sign', null, $this->election->confirm_vote_by_sign); ?>
      </div>
    </div>
    
    <!-- Vote confirmation message -->
    <div class="control-label">
      <label id="confirm_vote_by_sign_description-lbl" for="confirm_vote_by_sign_description" class="" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN_DESCRIPTION_TITLE' ); ?>
      </label>
    </div>
    <div class="controls">
      <?php echo $editor->display( 'confirm_vote_by_sign_description', $this->election->confirm_vote_by_sign_description, '100%', '200', '60', '35' ); ?>
    </div>
  
    <!-- Vote confirmation message -->
    <div class="control-label">
      <label id="confirm_vote_by_sign_error-lbl" for="confirm_vote_by_sign_error" class="" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_CONFIRM_VOTE_BY_SIGN_ERROR_TITLE' ); ?>
      </label>
    </div>
    <div class="controls">
      <?php echo $editor->display( 'confirm_vote_by_sign_error', $this->election->confirm_vote_by_sign_error, '100%', '200', '60', '35' ); ?>
    </div>
          
  <?php echo JHtml::_('bootstrap.endTab'); ?>
  
  
  
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'vote_success', JText::_('COM_JOOMELECTION_ELECTION_VOTE_SUCCESS')); ?>
        
    <!-- Vote confirmation message -->
    <div class="control-label">
      <label id="vote_success_description-lbl" for="vote_success_description" class="" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_VOTE_SUCCESS_DESCRIPTION_TITLE' ); ?>
      </label>
    </div>
    <div class="controls">
      <?php echo $editor->display( 'vote_success_description', $this->election->vote_success_description, '100%', '200', '60', '35' ); ?>
    </div>
          
  <?php echo JHtml::_('bootstrap.endTab'); ?>
  
  
  
  <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'email', JText::_('COM_JOOMELECTION_EMAIL')); ?>
    
    <h3> <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_EMAIL' ); ?></h3>
      
    <!-- Email subject -->
    <div class="control-label">
      <label id="vote_success_description-lbl" for="vote_success_description" class="" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_EMAIL_SUBJECT' ); ?>
      </label>
    </div>
    <div class="controls">
      <input type="text" name="election_voter_email_header" size="150" maxlength="450" value="<?php echo $this->election->election_voter_email_header;?>" />
    </div>
  
    <!-- Email message -->
    <div class="control-label">
      <label id="vote_success_description-lbl" for="vote_success_description" class="" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_EMAIL_MESSAGE' ); ?>
      </label>
    </div>
    <div class="controls">
      <textarea cols="120" rows="15" name="election_voter_email_text"><?php echo $this->election->election_voter_email_text;?></textarea>
      <p><?php echo JText::_( 'COM_JOOMELECTION_ELECTION_VOTER_EMAIL_FIELD_LEGEND' ); ?></p>
    </div>
      
  <?php echo JHtml::_('bootstrap.endTab'); ?>
    
  <?php echo JHtml::_('bootstrap.endTabSet'); ?>
  
  <input type="hidden" name="election_id" value="<?php echo $this->election->election_id; ?>" />
  <input type="hidden" name="opener_task" value="<?php echo $this->task; ?>" /> <!-- For results page so that Back button returns to right page-->
  <input type="hidden" name="task" value="" />
</form>
