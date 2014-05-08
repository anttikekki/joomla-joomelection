<?php defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php?option=com_joomelection" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped">
    <thead>
      <tr>
        <th width="5">
          <?php echo JText::_( '#' ); ?>
        </th>
        <th width="20">
          <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->elections ); ?>);" />
        </th>			
        <th>
          <?php echo JText::_( 'Election name' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'Date to open' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'Time to open' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'Date to close' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'Time to close' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'Election open' ); ?>
        </th>
        <th>
          <?php echo JText::_( 'Result' ); ?>
        </th>
        <th width="5%">
          <?php echo JText::_( 'Published' ); ?>
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
      $checked 	= JHTML::_('grid.id',   $i, $row->election_id );
      $published 	= JHTML::_('grid.published', $row, $i );
      $link 		= JRoute::_( 'index.php?option=com_joomelection&task=election.edit&cid[]='. $row->election_id );

      ?>
      <tr class="<?php echo "row$k"; ?>">
        <td>
          <?php echo $i+1; ?>
        </td>
        <td>
          <?php echo $checked; ?>
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
            if($row->election_open) { ?>
              <img src="images/tick.png" border="0" alt="<?php echo JText::_( 'Election is open' ); ?>" />	
          <?php }
            else { ?>
              <img src="images/publish_x.png" border="0" alt="<?php echo JText::_( 'Election is closed' ); ?>" />	
            <?php } ?>
        </td>
        <td align="center">
          <?php echo "<a href='index.php?option=com_joomelection&task=election.showResult&election_id=" .$row->election_id. "'>" .JText::_( 'Result' ). "</a>"; ?>
        </td>
        <td align="center">
          <?php echo $published; ?>
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
  <input type="hidden" name="opener_task" value="<?php echo $this->task; ?>" />
</form>
