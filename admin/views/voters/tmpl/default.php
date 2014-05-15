<?php 

defined('_JEXEC') or die('Restricted access');

// Load search tools
JHtml::_('searchtools.form');

$sortCallbackTask = 'voter.showList';
$document =& Jfactory::getDocument();
$document->addScriptDeclaration("
  jQuery(document).ready(function() {
    jQuery('#clearSearchButton').on('click', function() {
      jQuery('#searchInput').val('');
      jQuery('#adminForm').submit();
    });
    
    jQuery('#election_id').on('change', function() {
      jQuery('#adminForm').submit();
    });
  });
");
?>

<form action="index.php?option=com_joomelection&task=voter.showList" method="post" name="adminForm" id="adminForm">
  <div class="row-fluid">
    <div id="j-sidebar-container" class="span2">
      <?php echo $this->sidebar; ?>
    </div>
    <div class="span10">
      <div class="row-fluid">
        <div class="span6">
          <div class="js-stools clearfix">
            <div class="clearfix">
              <div class="js-stools-container-bar">
                <div class="btn-wrapper input-append">
                  <input id="searchInput" class="js-stools-search-string" type="text" placeholder="<?php echo JText::_('COM_JOOMELECTION_VOTER_SEARCH_LABEL'); ?>" value="<?php echo $this->search;?>" name="search">
                  <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
                    <i class="icon-search"></i>
                  </button>
                </div>
                <div class="btn-wrapper">
                  <button id="clearSearchButton" type="button" class="btn hasTooltip js-stools-btn-clear" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>">
                    <?php echo JText::_('JSEARCH_FILTER_CLEAR');?>
                  </button>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="span6">
          <div class="pull-right">
            <?php echo JText::_('COM_JOOMELECTION_VOTER_SHOW_VOTED_INFO'); ?>
            <?php echo JHTML::_('select.genericlist', $this->elections, 'election_id', null, 'election_id', 'election_name', $this->election_id ); ?>
          </div>
        </div>
      </div>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th width="2%">
              <?php echo JText::_( '#' ); ?>
            </th>
            <th width="2%">
              <?php echo JHtml::_('grid.checkall');?>
            </th>      
            <th>
              <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_VOTER_NAME', 'u.name', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
            </th>
            <th>
              <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_VOTER_USERNAME', 'u.username', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
            </th>
            <th>
              <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_VOTER_EMAIL', 'u.email', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
            </th>
            <th width="20%">
              <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_VOTER_LOGINS_EMAIL_SENT', 'v.email_sent', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
            </th>
            <th width="3%">
              <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_VOTER_VOTED', 'evs.voted', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
            </th>
          </tr>      
        </thead>
        <tfoot>
          <tr>
            <td colspan="7">
              <?php echo $this->pagination->getListFooter(); ?>
            </td>
          </tr>
        </tfoot>
        <tbody>
        <?php
        $k = 0;
        for ($i=0, $n=count( $this->voters ); $i < $n; $i++)
        {    
          $row = &$this->voters[$i];
          $voterEditLink = JRoute::_( 'index.php?option=com_joomelection&task=voter.edit&cid[]='. $row->voter_id );

          ?>
          <tr class="<?php echo "row$k"; ?>">
            <td>
              <?php echo $this->pagination->getRowOffset($i); ?>
            </td>
            <td>
              <?php echo JHTML::_('grid.id',   $i, $row->voter_id ); ?>
            </td>
            <td>
              <a href="<?php echo $voterEditLink; ?>"><?php echo $row->name; ?></a>
            </td>
            <td align="center">
              <?php echo $row->username; ?>
            </td>
            <td align="center">
              <?php echo $row->email; ?>
            </td>
            <td align="center">
              <?php
                if($row->email_sent) { 
                  echo JHtml::_('image', 'admin/tick.png', JText::_( 'Email is sent' ), null, true);
                }
                else { 
                  echo JHtml::_('image', 'admin/publish_x.png', JText::_( 'Email is not sent' ), null, true);
                }
              ?>
            </td>
            <td align="center">
              <?php
                if($row->voted) { 
                  echo JHtml::_('image', 'admin/tick.png', JText::_( 'Voted' ), null, true);
                }
                else { 
                  echo JHtml::_('image', 'admin/publish_x.png', JText::_( 'Has not voted' ), null, true);
                }
              ?>
            </td>
          </tr>
          <?php
          $k = 1 - $k;
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <input type="hidden" name="task" value="voter.showList" /> <!-- default value is required because election selection select does submit -->
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>
