<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor(); 
JHTML::_('behavior.calendar');

$document =& Jfactory::getDocument();  
$optionJSON = json_encode($this->option);
$electionLists = json_encode($this->electionLists);
$document->addScriptDeclaration("
  var JoomElection = {};
  JoomElection.option = $optionJSON;
  JoomElection.electionLists = $electionLists;
  
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
      var electionLists = JoomElection.getElectionListsForElection(electionId);
      JoomElection.populateElectionListSelect(electionLists);
    });
    jQuery('#election_id').change();
  });
");
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
                <?php echo JText::_( 'Option name' ); ?>
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
                <?php echo JText::_( 'Option number' ); ?>
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
                <?php echo JText::_( 'Election' ); ?>
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
                <?php echo JText::_( 'Election list' ); ?>
                <span class="star">&nbsp;*</span>
              </label>
            </div>
            <div class="controls">
              <?php echo JHTML::_('select.genericlist', array(), 'list_id', null, 'list_id', 'name', $this->option->list_id );?>
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
              <?php echo JHTML::_('select.booleanlist', 'published', null, $this->option->published); ?>
            </div>
          </div>
        
          <!-- Option description -->
          <div class="control-group ">
            <div class="control-label">
              <label id="description-lbl" for="description" class="required" title="">
                <?php echo JText::_( 'Option description' ); ?>
                <span class="star">&nbsp;*</span>
              </label>
            </div>
            <div class="controls">
              <?php echo $editor->display( 'description', $this->option->description, '100%', '350', '60', '35' ); ?>
            </div>
          </div>
        
          
        </fieldset>
      </div>
    </div>
  </div>

  <input type="hidden" name="option" value="com_joomelection" />
  <input type="hidden" name="option_id" value="<?php echo $this->option->option_id; ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="controller" value="option" />
</form>