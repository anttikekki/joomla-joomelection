<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/joomelection-bootstrap.css', 'text/css');
$langTag = JFactory::getLanguage()->getTag();

?>

<div class="joomelection-bootstrap-wrapper">

<?php foreach ($this->elections as $election) { ?>

  <h3><?php echo $election->election_name; ?></h3>

  <form role="form">

    <div class="form-group">
      <label><?php echo JText::_( 'COM_JOOMELECTION_ELECTION_OPEN_TIME'); ?></label>
      <div>
      <?php 
        echo JHTML::_('date',  $election->date_to_open, 'd.m.Y') ." ". 
        JText::_( 'COM_JOOMELECTION_TIME_AT') ." ". 
        JHTML::_('date',  $election->date_to_open, 'H.i') ." - ". 
        JHTML::_('date',  $election->date_to_close, 'd.m.Y') ." ". 
        JText::_( 'COM_JOOMELECTION_TIME_AT') ." ". 
        JHTML::_('date',  $election->date_to_close, 'H.i'); 
      ?>
      </div>
    </div>
  
    <div class="form-group">
      <label><?php echo JText::_( 'COM_JOOMELECTION_ELECTION_STATUS'); ?></label>
      <div>
      <?php 
        if($election->valid_election) { 
          ?>
          <span class="label label-success">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_STATUS_OPEN'); ?>
          </span>
          <?php
        }
        else {
          ?>
          <span class="label label-danger">
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_STATUS_CLOSED'); ?>
          </span>
          <?php
        }
       ?>
       </div>
    </div>

    <div class="form-group">
      <label><?php echo JText::_( 'COM_JOOMELECTION_YOUR_ELECTION_STATUS'); ?></label>
      <div>
      <?php 
        if($this->user_logged_in) {
          if($election->valid_voter) {
            echo JText::_( 'COM_JOOMELECTION_WELCOME_TO_VOTE' ) . ", " .$this->voter_name. ". " .JText::_( 'COM_JOOMELECTION_YOU_CAN_VOTE_IN_THIS_ELECTION');
          }
          else {
            if($election->valid_election) {
              echo JText::_( 'COM_JOOMELECTION_ALLREADY_VOTED_ERROR');
            }
            else {
              echo JText::_( 'COM_JOOMELECTION_ELECTION_CLOSED_ERROR' );
            }
          }
        }
        else {
          if($election->valid_election) {
            echo JText::_( 'COM_JOOMELECTION_NOT_LOGGED_IN_ERROR' );
          }
          else {
            echo JText::_( 'COM_JOOMELECTION_ELECTION_CLOSED_ERROR' );
          }
        } 
      ?>
      </div>
    </div>
  
    <div class="form-group">
      <label><?php echo JText::_( 'COM_JOOMELECTION_ELECTION_DESCRIPTION'); ?></label>
      <div>
        <?php echo $election->election_description; ?>
       </div>
    </div>

  </form>


  <hr>

  <a name="candidates_section"></a> 

  <?php if(count($election->election_lists) > 0) { 
    $candidatesButtonActiveClass = $this->selectedViewTab == 'view_election_candidates' ? 'active' : '';
    $listsButtonActiveClass = $this->selectedViewTab == 'view_election_lists' ? 'active' : '';
    $orderByNumberActiveClass = $this->orderBy == 'number' ? 'active' : '';
    $orderByNameActiveClass = $this->orderBy == 'name' ? 'active' : '';
    $orderByListNameActiveClass = $this->orderBy == 'listName' ? 'active' : '';
    ?>
    <form>
      <div class="row">

        <div class="col-xs-7">
          <div class="form-group">
            <label><?php echo JText::_('COM_JOOMELECTION_ORDER_BY'); ?>:</label>
            <div>
              <a href="index.php?option=com_joomelection&view=joomelection&orderBy=number#candidates_section" 
                  class="btn btn-default btn-sm <?php echo $orderByNumberActiveClass; ?>" 
                  role="button">
                <span class="glyphicon glyphicon-sort-by-order"></span> <?php echo JText::_('COM_JOOMELECTION_ORDER_BY_CANDIDATE_NUMBER'); ?>
              </a>

              <a href="index.php?option=com_joomelection&view=joomelection&orderBy=name#candidates_section" 
                  class="btn btn-default btn-sm <?php echo $orderByNameActiveClass; ?>" 
                  role="button">
                <span class="glyphicon glyphicon-sort-by-alphabet"></span> <?php echo JText::_('COM_JOOMELECTION_ORDER_BY_CANDIDATE_NAME'); ?>
              </a>

              <?php if($election->election_type_id == 2) { //List election ?>
                <a href="index.php?option=com_joomelection&view=joomelection&orderBy=listName#candidates_section" 
                    class="btn btn-default btn-sm <?php echo $orderByListNameActiveClass; ?>" 
                    role="button">
                  <span class="glyphicon glyphicon-sort-by-attributes"></span> <?php echo JText::_('COM_JOOMELECTION_ORDER_BY_CANDIDATE_LIST_NAME'); ?>
                </a>
              <?php } ?>
            </div>
          </div>

        </div>

        <div class="col-xs-5 text-right">

          <div class="form-group">
            <label><?php echo JText::_('COM_JOOMELECTION_SELECT_VIEW'); ?>:</label>
            <div>
              <a href="index.php?option=com_joomelection&view=joomelection&orderBy=number&selectedViewTab=view_election_candidates#candidates_section" 
                  class="btn btn-default btn-sm <?php echo $candidatesButtonActiveClass; ?>" 
                  role="button">
                <span class="glyphicon glyphicon-user"></span> <?php echo JText::_('COM_JOOMELECTION_VIEW_CANDIDATES'); ?>
              </a>

              <a href="index.php?option=com_joomelection&view=joomelection&selectedViewTab=view_election_lists#candidates_section" 
                  class="btn btn-default btn-sm <?php echo $listsButtonActiveClass; ?>" 
                  role="button">
                <span class="glyphicon glyphicon-list"></span> <?php echo JText::_('COM_JOOMELECTION_VIEW_CANDIDATE_LISTS'); ?>
              </a>
            </div>
          </div> <!-- form group end -->

        </div>
      </div>
    </form>
  <?php } ?>


  
  <?php if($this->selectedViewTab == 'view_election_candidates') {
    foreach ($election->options as $option) { ?>
      <a name="candidate_<?php echo $option->option_id; ?>"></a> 

      <div class="panel panel-default">
        <div class="panel-body">

          <h4><?php echo $option->option_number; ?>. <?php echo $option->name; ?></h4>

          <form role="form">

            <div class="form-group">
              <div>
                <?php if($election->valid_voter) { ?>
                  <a href="<?php echo $option->vote_link; ?>" class="btn btn-success btn-sm" role="button">
                    <span class="glyphicon glyphicon-ok"></span> 
                    <?php echo JText::_( 'COM_JOOMELECTION_VOTE'); ?>
                  </a>
                <?php } ?>
              </div>
            </div>

            <?php if($election->election_type_id == 2) { //List election ?>
              <div class="form-group">
                <label><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST'); ?></label>
                <div>
                  <a href="index.php?option=com_joomelection&view=joomelection&selectedViewTab=view_election_lists#candidate_list_<?php echo $option->list_id; ?>">
                    <?php echo $option->list_name; ?>
                  </a>
                </div>
              </div>
            <?php } ?>

            <div class="form-group">
              <label><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_DESCRIPTION'); ?></label>
              <div>
                <?php echo $option->description; ?>
              </div>
            </div>

          </form>

        </div> <!-- panel-body end -->
      </div> <!-- panel end -->
            
    <?php }
   }
  else { //View candidate lists
    foreach ($election->election_lists as $election_list) { ?>
      <a name="candidate_list_<?php echo $election_list->list_id; ?>"></a> 

      <div class="panel panel-default">
        <div class="panel-body">

          <h4><?php echo $election_list->name; ?></h4>

          <form role="form">

            <div class="form-group">
              <div>
                <?php echo $election_list->description; ?>
              </div>
            </div>

            <div class="form-group">
              <label><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_CANDIDATES'); ?></label>
              <div>
                <?php foreach ($election_list->list_options as $list_option) { ?>
                    <div>
                      <a href="index.php?option=com_joomelection&view=joomelection&orderBy=number&selectedViewTab=view_election_candidates#candidate_<?php echo $list_option->option_id; ?>">
                        <?php echo $list_option->option_number; ?>. <?php echo $list_option->name; ?>
                      </a>
                    </div>
                <?php } ?>
              </div>
            </div>

          </form>

        </div> <!-- panel-body end -->
      </div> <!-- panel end -->
      
    <?php } ?>
    
  <?php } ?>
<?php } ?>

</div> <!-- joomelection-bootstrap-wrapper -->