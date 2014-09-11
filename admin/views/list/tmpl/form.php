<?php defined('_JEXEC') or die('Restricted access'); 

require_once (JPATH_COMPONENT_ADMINISTRATOR .'/helpers/JoomElectionAdminMultilangHelper.php');
$currentLang =& JFactory::getLanguage();
?>

<form class="form-horizontal" method="post" name="adminForm" id="adminForm">
        
  <!-- Candidate List name -->
  <div class="control-group ">
    <div class="control-label">
      <label id="name-lbl" for="name" class="required" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_NAME' ); ?>
        <span class="star">&nbsp;*</span>
      </label>
    </div>
    <div class="controls">
      <?php echo JoomElectionAdminMultilangHelper::getFieldHtml('text', $this->electionList, "name", ['maxlength' => 250]); ?>
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
      <?php echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name_'.$currentLang->getTag(), $this->electionList->election_id );?>
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
    <?php echo JHTML::_('select.booleanlist', 'published', null, $this->electionList->published); ?>
  </div>

  <!-- Candidate List description -->
  <div class="control-group ">
    <div class="control-label">
      <label id="description-lbl" for="description" class="" title="">
        <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_DESCRIPTION' ); ?>
      </label>
    </div>
    <div class="controls">
      <?php echo JoomElectionAdminMultilangHelper::getFieldHtml('editor', $this->electionList, "description"); ?>
    </div>
  </div>
  
  <input type="hidden" name="list_id" value="<?php echo $this->electionList->list_id; ?>" />
  <input type="hidden" name="task" value="" />
</form>