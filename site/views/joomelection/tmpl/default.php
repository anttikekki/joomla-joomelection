<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/styles.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );


foreach ($this->elections as $election) { ?>
  <h1><?php echo $election->election_name; ?></h1>

  <div>
    <table cellspacing="10">
      <tr>
        <td nowrap="nowrap">
          <?php echo "<b>" .JText::_( 'COM_JOOMELECTION_ELECTION_OPEN_TIME') .":</b>"; ?>
        </td>
        <td>
          <?php 
            echo JHTML::_('date',  $election->date_to_open, 'd.m.Y') ." ". JText::_( 'COM_JOOMELECTION_TIME_AT') ." ". 
            JHTML::_('date',  $election->date_to_open, 'H.i') ." - ". 
            JHTML::_('date',  $election->date_to_close, 'd.m.Y') ." ". JText::_( 'COM_JOOMELECTION_TIME_AT') ." ". 
            JHTML::_('date',  $election->date_to_close, 'H.i'); 
          ?>
        </td>
      </tr>
      
      
      <tr>
        <td nowrap="nowrap">
          <?php echo "<b>" .JText::_( 'COM_JOOMELECTION_ELECTION_STATUS') .":</b>"; ?>
        </td>
        <td>
          <?php 
            if($election->valid_election) {
              echo "<b class='green'>" .JText::_( 'COM_JOOMELECTION_ELECTION_STATUS_OPEN') ."</b>";
            }
            else {
              echo "<b class='red'>" .JText::_( 'COM_JOOMELECTION_ELECTION_STATUS_CLOSED') ."</b>";
            }
           ?>
        </td>
      </tr>
      
      <tr>
        <td nowrap="nowrap">
          <?php echo "<b>" .JText::_( 'COM_JOOMELECTION_YOUR_ELECTION_STATUS'). ":</b>"; ?>
        </td>
        <td>
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
        </td>
      </tr>
      
      <tr>
        <td nowrap="nowrap" valign="top">
          <?php echo "<b>" .JText::_( 'COM_JOOMELECTION_ELECTION_DESCRIPTION') .":</b>"; ?>
        </td>
        <td>
          <?php echo $election->election_description; ?>
        </td>
      </tr>
    </table>
  </div>
  
  
  
  
  
  
  
  <?php if(count($election->election_lists) > 0) { ?>
  <div class="election_list_tabs">
    <ul class="tabnav">
      <li class="tabs_legend"><?php echo JText::_('COM_JOOMELECTION_SELECT_VIEW'); ?>:</li>
      <li <?php if($this->selectedViewTab == 'view_election_candidates') {?> class="selectedTab" <?php } ?>>
        <a href="index.php?option=com_joomelection&view=joomelection&orderBy=number&selectedViewTab=view_election_candidates">
          <?php echo JText::_('COM_JOOMELECTION_VIEW_CANDIDATES'); ?>
        </a>
      </li>
      
      <li <?php if($this->selectedViewTab == 'view_election_lists') {?> class="selectedTab" <?php } ?>>
        <a href="index.php?option=com_joomelection&view=joomelection&selectedViewTab=view_election_lists">
          <?php echo JText::_('COM_JOOMELECTION_VIEW_CANDIDATE_LISTS'); ?>
        </a>
      </li>
    </ul>
  </div>
  <?php } ?>
  
  
  
  
  <?php if($this->selectedViewTab == 'view_election_candidates') { ?>
  
    <div class="candidate_list_tabs<?php if(count($election->election_lists) == 0) { ?>_no_list_tabs<?php } ?>">
      <ul class="tabnav">
        <li class="tabs_legend"><?php echo JText::_('COM_JOOMELECTION_ORDER_BY'); ?>:</li>
        <li <?php if($this->orderBy == 'number') {?> class="selectedTab" <?php } ?>>
          <a href="index.php?option=com_joomelection&view=joomelection&orderBy=number">
            <?php echo JText::_('COM_JOOMELECTION_ORDER_BY_CANDIDATE_NUMBER'); ?>
          </a>
        </li>
        <li <?php if($this->orderBy == 'name') {?> class="selectedTab" <?php } ?> >
          <a href="index.php?option=com_joomelection&view=joomelection&orderBy=name">
            <?php echo JText::_('COM_JOOMELECTION_ORDER_BY_CANDIDATE_NAME'); ?>
          </a>
        </li>
        <?php 
          //If election type is list (list = 2)
          if($election->election_type_id == 2) {
             ?>
            <li <?php if($this->orderBy == 'listName') { ?> class="selectedTab" <?php } ?>>
               <a href="index.php?option=com_joomelection&view=joomelection&orderBy=listName">
                <?php echo JText::_('COM_JOOMELECTION_ORDER_BY_CANDIDATE_LIST_NAME'); ?>
               </a>
            </li><?php
          }
        ?>
      </ul>
    </div>
    
    <div class="candidate_list">
      <table cellpadding="20" cellspacing="0" width="100%" class="candidate_list_table">
      <?php
      
      foreach ($election->options as $option) { ?>
        <tr class="candidate_list_row">
          <td width="70%" class="candidate_list_cell">
            <div class="candidate_list_row_info">
              <span class="candidate_list_row_info_header"><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_NUMBER') .' '. $option->option_number; ?>:</span> <?php echo $option->name; ?><br />
              <?php 
                //If election type is list (list = 2)
                if($election->election_type_id == 2) {
                   ?><span class="candidate_list_row_info_header"><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST'); ?>:</span> <?php echo $option->list_name; ?><br /><?php
                }
              ?>
            </div>
            <div class="candidate_list_row_description">
              <?php echo $option->description; ?>
            </div>
          </td>
          <td nowrap="nowrap" align="center" width="30%" class="candidate_list_cell">
            <?php
            
            if($election->valid_voter) {
              $voteLinkText = JText::_( 'COM_JOOMELECTION_VOTE_CANDIDATE_NUMBER'). " " .$option->option_number;
              $imgUrl = JURI::root(true) . '/components/com_joomelection/img/ok.png';
              $img = "<img src='$imgUrl' />";
              echo "<a class='vote_logo' href='" .$option->vote_link. "'>$img</a><br /><b><a href='" .$option->vote_link. "'>$voteLinkText</a></b>";
            }
            else {
              echo "&nbsp;";
            }
            
             ?>
          </td>
        </tr>
            
        
      <?php 
      }
       ?>
      </table>
    </div>
    
    
  <?php } 
  else {?>
  
    <div class="candidate_list">
      <table cellpadding="20" cellspacing="0" width="100%" class="candidate_list_table">
      <?php
      
      foreach ($election->election_lists as $election_list) { ?>
        <tr class="candidate_list_row">
          <td width="70%" class="candidate_list_cell">
            <div class="candidate_list_row_info">
              <span class="candidate_list_row_info_header"><?php echo JText::_( 'COM_JOOMELECTION_CANDIDATE_LIST_NAME'); ?>:</span> <?php echo $election_list->name; ?><br />
            </div>
            <div class="candidate_list_row_description">
              <?php echo $election_list->description; ?>
            </div>
            <div class="election_lists_list_candidates">
              <ul>
                <?php
                foreach ($election_list->list_options as $list_option) { ?>
                  <li><?php echo $list_option->name .". ". JText::_( 'COM_JOOMELECTION_CANDIDATE_NUMBER'). " " .$list_option->option_number; ?></li>
                <?php } ?>
              </ul>
            </div>
          </td>
        </tr>
        
      <?php 
      }
       ?>
      </table>
    </div>
    
  <?php } ?>
<?php } ?>