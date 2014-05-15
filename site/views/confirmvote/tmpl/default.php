<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/styles.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );
?>

<h1><?php echo JText::_( 'COM_JOOMELECTION_PLEASE_CONFIRM_YOUR_SELECTION'); ?>:</h1>


<div class="candidate_list_row_info">
  <span class="candidate_list_row_info_header"><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_NUMBER') . $this->option->option_number; ?>:</span> <?php echo $this->option->name; ?><br />
  <?php 
    //If election type is list (list = 2)
    if($this->election->election_type_id == 2) {
       ?><span class="candidate_list_row_info_header"><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST'); ?>:</span> <?php echo $this->option->list_name; ?><br /><?php
    }
  ?>
</div>
    

<form action="index.php" method="post" name="adminForm" id="adminForm">
  
  <?php
  if($this->election->confirm_vote_by_sign) { ?>
    <div>
      <table cellspacing="5">
        <tr>
          <td>
            <input class="inputbox" type="checkbox" name="confirm_vote">
          </td>
          <td>
            <?php echo $this->election->confirm_vote_by_sign_description; ?>
          </td>
        </tr>
      </table>
    </div>
  <?php } ?>
  
  <div>
    <table cellspacing="10">
      <tr>
        <td>
          <a class='vote_logo' href='javascript: document.adminForm.submit();'><img src='<?php echo JURI::root(true); ?>/components/com_joomelection/img/ok.png' border='0' alt='<?php echo JText::_( 'COM_JOOMELECTION_VOTE'); ?>' /></a><br />
          <b><a href='javascript: document.adminForm.submit();'><?php echo JText::_( 'COM_JOOMELECTION_VOTE'); ?></a></b>
        </td>
        <td>
          <a class='vote_logo' href='javascript: history.back();'><img src='<?php echo JURI::root(true); ?>/components/com_joomelection/img/cancel.png' border='0' alt='<?php echo JText::_( 'COM_JOOMELECTION_VOTE_CANCEL'); ?>' /></a><br />
          <b><a href='javascript: history.back();'><?php echo JText::_( 'COM_JOOMELECTION_VOTE_CANCEL'); ?></a></b>
        </td>
      </tr>
    </table>
  </div>
  
  
  <input type="hidden" name="option" value="com_joomelection" />
  <input type="hidden" name="vote_option" value="<?php echo $this->option->vote_option; ?>" />
  <input type="hidden" name="task" value="vote" />
</form>