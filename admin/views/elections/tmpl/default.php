<?php 

defined('_JEXEC') or die('Restricted access'); 

$sortCallbackTask = 'election.showList';
?>

<form method="post" name="adminForm" id="adminForm">
	<table class="table table-striped">
    <thead>
      <tr>
        <th width="2%">
          <?php echo JText::_( '#' ); ?>
        </th>
        <th width="2%">
          <?php echo JHtml::_('grid.checkall');?>
        </th>			
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_ELECTION_NAME', 'election_name', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
        </th>
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_ELECTION_DATE_TO_OPEN', 'date_to_open', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
        </th>
        <th>
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_TIME_TO_OPEN' ); ?>
        </th>
        <th>
          <?php echo JHtml::_('grid.sort', 'COM_JOOMELECTION_ELECTION_DATE_TO_CLOSE', 'date_to_close', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
        </th>
        <th>
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_TIME_TO_CLOSE' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'COM_JOOMELECTION_ELECTION_STATUS' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'COM_JOOMELECTION_ELETCION_RESULT' ); ?>
        </th>
        <th width="5%">
          <?php echo JText::_( 'COM_JOOMELECTION_PUBLISHED' ); ?>
        </th>
      </tr>			
    </thead>
    <tfoot>
      <tr>
        <td colspan="10">
          <?php echo $this->pagination->getListFooter(); ?>
        </td>
      </tr>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->elections ); $i < $n; $i++)
    {		
      $row = &$this->elections[$i];
      $link 		= JRoute::_( 'index.php?option=com_joomelection&task=election.edit&cid[]='. $row->election_id );

      ?>
      <tr class="<?php echo "row$k"; ?>">
        <td>
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>
        <td>
          <?php echo JHTML::_('grid.id',   $i, $row->election_id ); ?>
        </td>
        <td>
          <a href="<?php echo $link; ?>"><?php echo $row->election_name; ?></a>
        </td>
        <td align="center">
          <?php echo JHTML::_('date',  $row->date_to_open, 'd.m.Y'); ?>
        </td>
        <td align="center">
          <?php echo JHTML::_('date',  $row->date_to_open, 'H:i:s'); ?>
        </td>
        <td align="center">
          <?php echo JHTML::_('date',  $row->date_to_close, 'd.m.Y'); ?>
        </td>
        <td align="center">
          <?php echo JHTML::_('date',  $row->date_to_close, 'H:i:s'); ?>
        </td>
        <td align="center">
          <?php
            if($row->election_open) {
              echo JHtml::_('image', 'admin/tick.png', JText::_( 'COM_JOOMELECTION_ELECTION_IS_OPEN' ), null, true);
            }
            else {
              echo JHtml::_('image', 'admin/publish_x.png', JText::_( 'COM_JOOMELECTION_ELECTION_IS_CLOSED' ), null, true);
            } ?>
        </td>
        <td align="center">
          <?php echo "<a href='index.php?option=com_joomelection&task=election.showResult&election_id=" .$row->election_id. "'>" .JText::_( 'COM_JOOMELECTION_ELETCION_RESULT' ). "</a>"; ?>
        </td>
        <td align="center">
          <?php echo JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'election.' ); ?>
        </td>
      </tr>
      <?php
      $k = 1 - $k;
    }
    ?>
    </tbody>
	</table>
  
  <input type="hidden" name="task" value=""/>
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="opener_task" value="<?php echo $this->task; ?>" /> <!-- For results page so that Back button returns to right page-->
  <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>
