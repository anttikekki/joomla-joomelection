<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( '#' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->options ); ?>);" />
			</th>
			<th width="10%">
				<?php echo JText::_( 'Option number' ); ?>
			</th>		
			<th>
				<?php echo JText::_( 'Option name' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Option description' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Election' ); ?>
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
	for ($i=0, $n=count( $this->options ); $i < $n; $i++)
	{		
		$row = &$this->options[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->option_id );
		$published 	= JHTML::_('grid.published', $row, $i );
		$link 		= JRoute::_( 'index.php?option=com_joomelection&controller=option&task=edit&cid[]='. $row->option_id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $i+1; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td align="left">
				<?php echo $row->option_number; ?>
			</td>
			<td align="center">
				<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
			<td>
				<?php echo substr(strip_tags($row->description), 0, 100) ."..."; ?>
			</td>
			<td align="center">
				<?php echo $row->election_name; ?>
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
</div>

<input type="hidden" name="option" value="com_joomelection" />
<input type="hidden" name="task" value="showList" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="option" />
</form>
