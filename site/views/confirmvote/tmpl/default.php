<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/joomelection-bootstrap.css', 'text/css');
?>

<div class="joomelection-bootstrap-wrapper">

<h3><?php echo JText::_( 'COM_JOOMELECTION_PLEASE_CONFIRM_YOUR_SELECTION'); ?></h3>

<div class="panel panel-default">
  <div class="panel-body">

    <h4><?php echo $this->option->option_number; ?>. <?php echo $this->option->name; ?></h4>

    <form role="form">

      <?php if($this->election->election_type_id == 2) { //List election ?>
        <div class="form-group">
          <label><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST'); ?></label>
          <div>
            <?php echo $this->option->list_name; ?>
          </div>
        </div>
      <?php } ?>

      <div class="form-group">
        <label><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_DESCRIPTION'); ?></label>
        <div>
          <?php echo $this->option->description; ?>
        </div>
      </div>

    </form>

  </div>
</div>

    

<form action="index.php" method="post">

  <?php if($this->election->confirm_vote_by_sign) { ?>
    <div class="checkbox">
      <label>
        <input class="inputbox" type="checkbox" name="confirm_vote" value="1"> 
        <?php echo $this->election->confirm_vote_by_sign_description; ?>
      </label>
    </div>
  <?php } ?>
  
  <div class="form-group">
    <div>
      <button type="submit" class="btn btn-success btn-sm">
        <span class="glyphicon glyphicon-ok"></span> 
        <?php echo JText::_( 'COM_JOOMELECTION_VOTE'); ?>
      </button>

      <a href="index.php?option=com_joomelection&view=joomelection" class="btn btn-danger btn-sm" role="button">
        <span class="glyphicon glyphicon-remove"></span> 
        <?php echo JText::_( 'COM_JOOMELECTION_VOTE_CANCEL'); ?>
      </a>
    </div>
  </div>
  
  <input type="hidden" name="option" value="com_joomelection" />
  <input type="hidden" name="vote_option" value="<?php echo $this->option->vote_option; ?>" />
  <input type="hidden" name="task" value="vote" />
</form>

</div>