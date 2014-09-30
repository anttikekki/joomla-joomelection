<?php defined('_JEXEC') or die('Restricted access'); 


require_once (JPATH_COMPONENT_ADMINISTRATOR .'/helpers/JoomElectionAdminMultilangHelper.php');
$currentLang =& JFactory::getLanguage();

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_joomelection/css/joomelection_admin.css');

?>

<div id="voter_form_container">
  <form class="form-horizontal" method="post" name="adminForm" id="adminForm">

    <div class="alert alert-info">
      <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_INFO_TEXT' ); ?>
    </div>

    <!-- Select voters -->
    <div class="control-group ">
      <div class="control-label">
        <label id="name-lbl" for="name" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_SELECT_VOTERS' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        
        <label for="selectedGenerationGroupSelected">
          <input type="radio" name="selectedGenerationGroup" id="selectedGenerationGroupSelected" value="1"  
            <?php if($this->selectedVoutersCount == 0) { ?>disabled="disabled"<?php } ?>
          />
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_SELECTED_VOTERS' ); ?>  (<?php echo $this->selectedVoutersCount .' '. JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_SELECTED' );?>)
        </label>
        
        <label for="selectedGenerationGroupAll">
          <input type="radio" name="selectedGenerationGroup" id="selectedGenerationGroupAll" value="0" checked="checked" />
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_GENERATE_PASSWORDS_ALL_VOTERS' ); ?>
        </label>
        
      </div>
    </div>
          
    <!-- Election -->
    <div class="control-group ">
      <div class="control-label">
        <label id="name-lbl" for="name" class="required" title="">
          <?php echo JText::_( 'COM_JOOMELECTION_VOTER_SELECT_ELECTION' ); ?>
          <span class="star">&nbsp;*</span>
        </label>
      </div>
      <div class="controls">
        <?php
          if(count($this->elections) > 0) {
            echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name_'.$currentLang->getTag() );
          } else {
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
    <input type="hidden" name="selectedVoters" value="<?php echo $this->selectedVotersStringList; ?>" />
  </form>
</div>