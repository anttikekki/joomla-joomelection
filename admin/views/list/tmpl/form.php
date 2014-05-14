<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor(); 
JHTML::_('behavior.calendar');
?>

<form method="post" name="adminForm" id="adminForm">
  <div class="form-horizontal">
    <div class="row-fluid">
      <div class="span9">
        <fieldset class="form-vertical">
        
          <!-- Candidate List name -->
          <div class="control-group ">
            <div class="control-label">
              <label id="name-lbl" for="name" class="required" title="">
                <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_NAME' ); ?>
                <span class="star">&nbsp;*</span>
              </label>
            </div>
            <div class="controls">
              <input type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->electionList->name;?>" />
            </div>
          </div>
        
          <!-- Election -->
          <div class="control-group ">
            <div class="control-label">
              <label id="election_id-lbl" for="election_id" class="required" title="">
                <?php echo JText::_( 'COM_JOOMELECTION_ELECTION' ); ?>
                <span class="star">&nbsp;*</span>
              </label>
            </div>
            <div class="controls">
              <?php echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name', $this->electionList->election_id );?>
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
              <?php echo JHTML::_('select.booleanlist', 'published', null, $this->electionList->published); ?>
            </div>
          </div>
        
          <!-- Candidate List description -->
          <div class="control-group ">
            <div class="control-label">
              <label id="description-lbl" for="description" class="" title="">
                <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_DESCRIPTION' ); ?>
              </label>
            </div>
            <div class="controls">
              <?php echo $editor->display( 'description', $this->electionList->description, '100%', '150', '60', '35' ); ?>
            </div>
          </div>
          
        </fieldset>
      </div>
    </div>
  </div>
  
  <input type="hidden" name="list_id" value="<?php echo $this->electionList->list_id; ?>" />
  <input type="hidden" name="task" value="" />
</form>