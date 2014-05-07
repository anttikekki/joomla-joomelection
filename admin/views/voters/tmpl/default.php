<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">

	<table>
		<tr>
			<td align="right" width="100%">
				<?php echo JText::_( 'Search by name' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->search;?>" class="text_area" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Search' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset search' ); ?></button>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
		<tr>
			<td align="right" width="100%">
				<?php echo JText::_( 'Show voters voted information by election' ); ?>:
				<?php echo $this->electionList; ?>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
	</table>

	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( '#' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->voters ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'Name' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Username' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Email' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Username and password sent to user email?' ); ?>
			</th>
			<th width="40">
				<?php echo JText::_( 'Voted?' ); ?>
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
		$checked 	= JHTML::_('grid.id',   $i, $row->voter_id );
		$link 		= JRoute::_( 'index.php?option=com_joomelection&controller=voter&task=edit&cid[]='. $row->voter_id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->limitstart + $i+1; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
			<td align="center">
				<?php echo $row->username; ?>
			</td>
			<td align="center">
				<?php echo $row->email; ?>
			</td>
			<td align="center">
				<?php
					if($row->email_sent) { ?>
						<img src="images/tick.png" border="0" alt="Sähköposti lähetetty" />	
				<?php }
					else { ?>
						<img src="images/publish_x.png" border="0" alt="Sähköpostia ei ole lähetetty" />	
					<?php } ?>
			</td>
			<td align="center">
				<?php
					if($row->voted) { ?>
						<img src="images/tick.png" border="0" alt="Äänestänyt" />	
				<?php }
					else { ?>
						<img src="images/publish_x.png" border="0" alt="Ei vielä äänestänyt" />	
					<?php } ?>
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
<input type="hidden" name="controller" value="voter" />
</form>
