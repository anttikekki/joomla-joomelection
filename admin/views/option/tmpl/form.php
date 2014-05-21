<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor(); 
JHTML::_('behavior.calendar');

$document =& Jfactory::getDocument();  
$optionJSON = json_encode($this->option);
$electionsJSON = json_encode($this->elections);
$electionListsJSON = json_encode($this->electionLists);
$electionListNotNeededMsg = JText::_( 'COM_JOOMELECTION_CANDIDATE_CANDIDATE_LIST_NOT_NEEDED' );
$noElectionListsForElectionMsg = JText::_( 'COM_JOOMELECTION_CANDIDATE_NO_LISTS_EXIST' );

$document->addScriptDeclaration("
  var JoomElection = {};
  JoomElection.option = $optionJSON;
  JoomElection.elections = $electionsJSON;
  JoomElection.electionLists = $electionListsJSON;
  JoomElection.electionListNotNeededMsg = '$electionListNotNeededMsg';
  JoomElection.noElectionListsForElectionMsg = '$noElectionListsForElectionMsg';
  
  JoomElection.isListElection = function(electionId) {
    for(var i=0; i<JoomElection.elections.length; i++) {
      var election = JoomElection.elections[i];
      if(election.election_id == electionId) {
        return election.election_type_id == 2;
      }
    }
    throw 'No election for given id ' + electionId;
  };
  
  JoomElection.getElectionListsForElection = function(electionId) {
    var lists = [];
    for(var i=0; i<JoomElection.electionLists.length; i++) {
      var list = JoomElection.electionLists[i];
      if(list.election_id == electionId) {
        lists.push(list);
      }
    }
    return lists;
  };
  
  JoomElection.populateElectionListSelect = function(electionLists) {
    var html = '';
    for(var i=0; i<electionLists.length; i++) {
      var list = electionLists[i];
      var selected = (JoomElection.option.list_id == list.list_id) ? 'selected' : '';
      html += '<option value=\"' + list.list_id + '\" ' + selected + '>' + list.name + '</option>';
    }
    jQuery('#list_id').html(html);
  };
  
  jQuery(document).ready(function() {
    jQuery('#election_id').on('change', function() {
      var electionId = this.value;
      var listSelect = jQuery('#list_id');
      var listSelectMessageElement = jQuery('#list_id_message');
      
      if(JoomElection.isListElection(electionId)) {
        var electionLists = JoomElection.getElectionListsForElection(electionId);
        JoomElection.populateElectionListSelect(electionLists);
        
        if(electionLists.length > 0) {
          listSelect.show();
          listSelectMessageElement.hide();
        }
        else {
          listSelectMessageElement.html(JoomElection.noElectionListsForElectionMsg);
          listSelect.hide();
          listSelectMessageElement.show();
        }
      }
      else {
        listSelectMessageElement.html(JoomElection.electionListNotNeededMsg);
        listSelect.hide();
        listSelectMessageElement.show();
      }
    });
    jQuery('#election_id').change();
  });
");
?>

<form class="form-horizontal" method="post" name="adminForm" id="adminForm">
        
    <!-- Candidate name -->
    <div class="control-group ">
      <div class="control-label">
        <label id="name-lbl" for="name" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_NAME' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->option->name;?>" />
      </div>
    </div>
  
    <!-- Option number -->
    <div class="control-group ">
      <div class="control-label">
        <label id="option_number-lbl" for="option_number" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_NUMBER' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <input type="text" name="option_number" id="option_number" size="11" maxlength="11" value="<?php echo $this->option->option_number;?>" />
      </div>
    </div>
  
    <!-- Option number -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_id-lbl" for="election_id" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name', $this->option->election_id );?>
      </div>
    </div>
  
    <!-- Election candidate list -->
    <div class="control-group ">
      <div class="control-label">
        <label id="election_id-lbl" for="election_id" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo JHTML::_('select.genericlist', array(), 'list_id', null, 'list_id', 'name', $this->option->list_id );?>
        <div class="alert alert-info" id="list_id_message"></div>
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
        <?php echo JHTML::_('select.booleanlist', 'published', null, $this->option->published); ?>
      </div>
    </div>
  
    <!-- Option description -->
    <div class="control-group ">
      <div class="control-label">
        <label id="description-lbl" for="description" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_DESCRIPTION' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php echo $editor->display( 'description', $this->option->description, '100%', '350', '60', '35' ); ?>
      </div>
    </div>
  
  <input type="hidden" name="option_id" value="<?php echo $this->option->option_id; ?>" />
  <input type="hidden" name="task" value="" />
</form>