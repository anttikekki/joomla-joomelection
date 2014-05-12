<?php defined('_JEXEC') or die('Restricted access'); 


?>

<form class="form-horizontal" method="post" name="adminForm" id="adminForm">

  <div class="alert alert-info">
    <?php echo JText::_( 'First select to whom you want to generate new password. Then select election that email template you want to use  in email.' ); ?>
  </div>

  <!-- Select voters -->
  <div class="control-group ">
    <div class="control-label">
      <label id="name-lbl" for="name" class="required" title="">
        <?php echo JText::_( 'Select voters' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <input type="radio" name="selectedGenerationGroup" id="selectedGenerationGroupSelected" value="1"  
        <?php if($this->selectedVoutersCount == 0) { ?>disabled="disabled"<?php } ?>
      />
      <label for="selectedGenerationGroupSelected">
        <?php echo JText::_( 'Selected voters' ); ?>  (<?php echo $this->selectedVoutersCount .' '. JText::_( 'selected' );?>)
      </label>
      
      <input type="radio" name="selectedGenerationGroup" id="selectedGenerationGroupAll" value="0" checked="checked" />
      <label for="selectedGenerationGroupAll"><?php echo JText::_( 'All Voters' ); ?></label>
    </div>
  </div>
        
  <!-- Election -->
  <div class="control-group ">
    <div class="control-label">
      <label id="name-lbl" for="name" class="required" title="">
        <?php echo JText::_( 'Election' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <?php
        if(count($this->elections) > 0) {
          echo JHTML::_('select.genericlist', $this->elections, 'election_id', 'class="inputbox" ', 'election_id', 'election_name' );
        } else {
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
  <input type="hidden" name="selectedVoters" value="<?php echo $this->selectedVotersStringList; ?>" />
</form>
