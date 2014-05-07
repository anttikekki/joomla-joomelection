<?php defined('_JEXEC') or die('Restricted access'); 


?>
			
			

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Generate passwords' ); ?></legend>

		<table class="admintable">
		<tr>
			<td></td>
			<td><?php echo JText::_( 'First select to whom you want to generate new password. Then select election that email template you want to use  in email.' ); ?></td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="generatePassword">
					<?php echo JText::_( 'Select voters' ); ?>
				</label>
			</td>
			<td>
				<input type="radio" name="selectedGenerationGroup" id="selectedGenerationGroupSelected" value="1" class="inputbox" 
					<?php if($this->selectedVoutersCount == 0) { ?>disabled="disabled"<?php } ?> />
				<label for="selectedGenerationGroupSelected">
					<?php echo JText::_( 'Selected voters' ); ?>  (<?php echo $this->selectedVoutersCount .' '. JText::_( 'selected' );?>)
				</label>
				
				<input type="radio" name="selectedGenerationGroup" id="selectedGenerationGroupAll" value="0" checked="checked" class="inputbox" />
				<label for="selectedGenerationGroupAll"><?php echo JText::_( 'All Voters' ); ?></label>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="sendEmailToVoter">
					<?php echo JText::_( 'Select election thats email message is used' ); ?>
				</label>
			</td>
			<td>
				<?php if($this->elections_count > 0) { ?>
					<?php echo $this->elections_list; ?>
				<?php } else { ?>
					<?php echo JText::_( 'No elections available, impossible to send email. Create at least one election  first.' ); ?>
				<?php } ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_joomelection" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="voter" />
<input type="hidden" name="selectedVoters" value="<?php echo $this->selectedVotersStringList; ?>" />
</form>
