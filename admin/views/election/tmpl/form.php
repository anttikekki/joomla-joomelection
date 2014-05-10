<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor();
JHTML::_('behavior.calendar');
?>

<form method="post" name="adminForm" id="adminForm">
  <div class="form-horizontal">
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
    
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('General')); ?>
		<div class="row-fluid">
			<div class="span9">
				<fieldset class="adminform">
					<?php echo $editor->display( 'election_description', $this->election->election_description, '100%', '300', '60', '35' ); ?>
				</fieldset>
			</div>
			<div class="span3">
				<fieldset class="form-vertical">
        
          <!-- Election name -->
          <div class="control-group ">
            <div class="control-label">
              <label id="election_name-lbl" for="election_name" class="required" title="">
                <?php echo JText::_( 'Election name' ); ?>
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
                <?php echo JText::_( 'Election type' ); ?>
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
                <?php echo JText::_( 'Date to open' ); ?>
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
                <?php echo JText::_( 'Time to open' ); ?>
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
                <?php echo JText::_( 'Date to close' ); ?>
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
                <?php echo JText::_( 'Time to close' ); ?>
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
                <?php echo JText::_( 'Published' ); ?>
                <span class="star">&nbsp;*</span>
              </label>
            </div>
            <div class="controls">
             <?php echo JHTML::_('select.booleanlist', 'published', null, $this->election->published); ?>
            </div>
          </div>
        
        </fieldset> <!-- fieldset form-vertical -->
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
    
    
    
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'vote_confirmation', JText::_('Vote confirmation')); ?>
		<div class="row-fluid">
			<div class="span9">
				<fieldset class="form-vertical">
          <div class="control-group ">
          
            <!-- Vote confirmation message -->
            <div class="control-label">
              <label id="confirm_vote_by_sign_description-lbl" for="confirm_vote_by_sign_description" class="" title="">
                <?php echo JText::_( 'Confirm message' ); ?>
              </label>
            </div>
            <div class="controls">
              <?php echo $editor->display( 'confirm_vote_by_sign_description', $this->election->confirm_vote_by_sign_description, '100%', '200', '60', '35' ); ?>
            </div>
          
            <!-- Vote confirmation message -->
            <div class="control-label">
              <label id="confirm_vote_by_sign_error-lbl" for="confirm_vote_by_sign_error" class="" title="">
                <?php echo JText::_( 'Confirm error message' ); ?>
              </label>
            </div>
            <div class="controls">
              <?php echo $editor->display( 'confirm_vote_by_sign_error', $this->election->confirm_vote_by_sign_error, '100%', '200', '60', '35' ); ?>
            </div>
            
          </div>
				</fieldset>
			</div>
			<div class="span3">
				<fieldset class="form-vertical">
  
          <!-- Vote confirmation -->
          <div class="control-group ">
            <div class="control-label">
              <label id="confirm_vote-lbl" for="confirm_vote" class="required" title="">
                <?php echo JText::_( 'Voters has to confirm selection' ); ?>
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
                <?php echo JText::_( 'Voters has to confirm selection by signing it' ); ?>
                <span class="star">&nbsp;*</span>
              </label>
            </div>
            <div class="controls">
             <?php echo JHTML::_('select.booleanlist', 'confirm_vote_by_sign', null, $this->election->confirm_vote_by_sign); ?>
            </div>
          </div>

        </fieldset> <!-- fieldset form-vertical -->
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
    
    
    
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'vote_success', JText::_('Vote success')); ?>
    <div class="row-fluid">
			<div class="span9">
				<fieldset class="form-vertical">
          <div class="control-group ">
          
            <!-- Vote confirmation message -->
            <div class="control-label">
              <label id="vote_success_description-lbl" for="vote_success_description" class="" title="">
                <?php echo JText::_( 'Vote success message' ); ?>
              </label>
            </div>
            <div class="controls">
              <?php echo $editor->display( 'vote_success_description', $this->election->vote_success_description, '100%', '200', '60', '35' ); ?>
            </div>
          
          </div>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
    
    
    
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'email', JText::_('Email')); ?>
    <div class="row-fluid">
			<div class="span9">
      
        <h3> <?php echo JText::_( 'Voter email message that includes username and password' ); ?></h3>
      
				<fieldset class="form-vertical">
          <div class="control-group ">
          
            <!-- Email subject -->
            <div class="control-label">
              <label id="vote_success_description-lbl" for="vote_success_description" class="" title="">
                <?php echo JText::_( 'Email subject' ); ?>
              </label>
            </div>
            <div class="controls">
              <input class="inputbox" type="text" name="election_voter_email_header" size="150" maxlength="450" value="<?php echo $this->election->election_voter_email_header;?>" />
            </div>
          
            <!-- Email message -->
            <div class="control-label">
              <label id="vote_success_description-lbl" for="vote_success_description" class="" title="">
                <?php echo JText::_( 'Email message' ); ?>
              </label>
            </div>
            <div class="controls">
              <textarea class="inputbox" cols="120" rows="25" name="election_voter_email_text"><?php echo $this->election->election_voter_email_text;?></textarea>
            </div>
          
          </div>
				</fieldset>
        
        <p><?php echo JText::_( 'VOTER_EMAIL_FIELD_LEGEND' ); ?></p>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
      
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>
   
  </div> <!-- form-horizontal -->
  
  <input type="hidden" name="election_id" value="<?php echo $this->election->election_id; ?>" />
  <input type="hidden" name="opener_task" value="<?php echo $this->task; ?>" /> <!-- For results page so that Back button returns to right page-->
  <input type="hidden" name="task" value="" />
</form>
