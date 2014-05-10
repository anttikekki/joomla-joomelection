<?php 

defined('_JEXEC') or die('Restricted access'); 

$sortCallbackTask = 'list.showList';
?>
<form method="post" name="adminForm" id="adminForm">
  <table class="table table-striped">
    <thead>
      <tr>
        <th width="2%">
          <?php echo JText::_( '#' ); ?>
        </th>
        <th width="2%">
          <?php echo JHtml::_('grid.checkall'); ?>
        </th>  
        <th>
          <?php echo JHtml::_('grid.sort', 'Candidate List name', 'list.name', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
        </th>
        <th>
          <?php echo JText::_( 'Candidate List description' ); ?>
        </th>
        <th>
          <?php echo JHtml::_('grid.sort', 'Election', 'e.election_name', $this->sortDirection, $this->sortColumn, $sortCallbackTask);?>
        </th>
        <th width="5%">
          <?php echo JText::_( 'Published' ); ?>
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
    for ($i=0, $n=count( $this->lists ); $i < $n; $i++)
    {    
      $row              = &$this->lists[$i];
      $listEditLink     = JRoute::_( 'index.php?option=com_joomelection&task=list.edit&cid[]='. $row->list_id );
      $electionEditLink = JRoute::_( 'index.php?option=com_joomelection&task=election.edit&cid[]='. $row->election_id );

      ?>
      <tr class="<?php echo "row$k"; ?>">
        <td>
          <?php echo $this->pagination->getRowOffset($i); ?>
        </td>
        <td>
          <?php echo JHTML::_('grid.id', $i, $row->list_id ); ?>
        </td>
        <td align="center">
          <a href="<?php echo $listEditLink; ?>"><?php echo $row->name; ?></a>
        </td>
        <td>
          <?php echo substr(strip_tags($row->description), 0, 100) ."..."; ?>
        </td>
        <td align="center">
          <a href="<?php echo $electionEditLink; ?>"><?php echo $row->election_name; ?></a>
        </td>
        <td align="center">
          <?php echo JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'list.' ); ?>
        </td>
      </tr>
      <?php
      $k = 1 - $k;
    }
    ?>
    </tbody>
  </table>

  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />
</form>
