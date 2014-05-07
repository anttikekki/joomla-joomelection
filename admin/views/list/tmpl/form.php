<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor(); 
JHTML::_('behavior.calendar');
?>

<style type="text/css">
  span.error { 
	  color: red;
  }
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_( 'Candidate List name' ); ?>:
					</label>
				</td>
				<td>
					<input class="inputbox" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->electionList->name;?>" />
				</td>
			</tr>
			
			
			<tr>
				<td width="100" align="right" class="key">
					<label for="election_id">
						<?php echo JText::_( 'Election' ); ?>:
					</label>
				</td>
				<td>
						<?php 
							if($this->electionListEmpty == true) {
								?><span class="error"><?php
								echo JText::_( 'You have to create at least one election first before you can create a list. You can not save list with no election.' );
								?></span><?php
							}
							else {
								echo $this->electionComboBox;
							}
						?>
				</td>
			</tr>
				
				
			<tr>	
				<td width="100" align="right" class="key">
					<label for="description">
						<?php echo JText::_( 'Candidate List description' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $editor->display( 'description', $this->option->description, '100%', '350', '60', '35' ); ?>
				</td>
			</tr>	
				
			
			<tr>
				<td width="100" align="right" class="key">
					<label for="result_published">
						<?php echo JText::_( 'Published' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->electionList->published; ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_joomelection" />
<input type="hidden" name="list_id" value="<?php echo $this->electionList->list_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="list" />
</form>
