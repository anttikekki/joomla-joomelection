<?php 

defined('_JEXEC') or die('Restricted access');

?>

<form method="post" name="adminForm" id="adminForm">
  <div class="row-fluid">
    <div class="span9">
    
      <!-- Name -->
      <div class="control-group ">
        <div class="control-label">
          <label id="name-lbl" for="name" class="required" title="">
            <?php echo JText::_( 'Name' ); ?>
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
            <?php echo JText::_( 'Username' ); ?>
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
            <?php echo JText::_( 'Email' ); ?>
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
                echo JText::_( 'New Password' ) . ":";
              }
              else {
                echo JText::_( 'Password' ) . ":";
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
            <?php echo JText::_( 'Send username and password to users email' ); ?>?
          </label>
        </div>
        <div class="controls">
          <?php echo JHTML::_('select.booleanlist', 'sendEmailToVoter', null, 0); ?>
        
          <?php 
            if(count($this->elections) == 0) {
            ?><div class="alert alert-error"><?php
              echo JText::_( 'No elections available, impossible to send email. Create at least one election  first.' );
            ?></div><?php
            }
          ?>
        </div>
      </div>
    
      <!-- Select election thats email message is used -->
      <div class="control-group ">
        <div class="control-label">
          <label id="election_id-lbl" for="election_id" class="required" title="">
            <?php echo JText::_( 'Select election thats email message is used' ); ?>
            <span class="star">&nbsp;*</span>
          </label>
        </div>
        <div class="controls">
          <?php echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name' ); ?>
        </div>
      </div>

    </div>
  </div>
  
  <input type="hidden" name="id" value="<?php echo $this->voter->voter_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="limit" value="<?php echo $this->stored_limit; ?>" /> <!-- limit and limitstart is used when returning to listing -->
  <input type="hidden" name="limitstart" value="<?php echo $this->stored_limitstart; ?>" />
  <input type="hidden" name="search" value="<?php echo $this->stored_search; ?>" />
</form>
