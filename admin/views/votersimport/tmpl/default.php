<?php defined('_JEXEC') or die('Restricted access'); 


?>
			
			

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Import Voters' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="fileUpload">
					<?php echo JText::_( 'Filename' ); ?>:
				</label>
			</td>
			<td>
				<input type="file" id="fileUpload" name="fileUpload" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="separator">
					<?php echo JText::_( 'CSV file data separator' ); ?>:
				</label>
			</td>
			<td>
				<select id="separator" class="inputbox" name="separator">
					<option value=","> , </option>
					<option value=";"> ; </option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="importType">
					<?php echo JText::_( 'Import info' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_('Import info text'); ?>
				<br /><br />
				<?php $link = JRoute::_('components/com_joomelection/importExample/test_user_import.csv'); ?>
				<a href="<?php echo $link; ?>"><?php echo JText::_('Example import file'); ?></a>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="generatePassword">
					<?php echo JText::_( 'Generate random passwords' ); ?>?
				</label>
			</td>
			<td>
				<input type="radio" name="generatePassword" id="generatePasswordyes" value="1" class="inputbox" />
				<label for="generatePasswordyes"><?php echo JText::_( 'Yes' ); ?></label>
				
				<input type="radio" name="generatePassword" id="generatePasswordno" value="0" checked="checked" class="inputbox" />
				<label for="generatePasswordno"><?php echo JText::_( 'No' ); ?></label>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="sendEmailToVoter">
					<?php echo JText::_( 'Send username and password to users email' ); ?>?
				</label>
			</td>
			<td>
				<?php if($this->elections_count > 0) { ?>
					<input type="radio" name="sendEmailToVoter" id="sendEmailToVoteryes" value="1" class="inputbox" />
					<label for="sendEmailToVoteryes"><?php echo JText::_( 'Yes' ); ?></label>
					
					<input type="radio" name="sendEmailToVoter" id="sendEmailToVoterno" value="0" checked="checked" class="inputbox" />
					<label for="sendEmailToVoterno"><?php echo JText::_( 'No' ); ?></label>
					<br /><br />
					<?php echo JText::_( 'Select election thats email message is used' ); ?>:
					<br />
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
</form>
