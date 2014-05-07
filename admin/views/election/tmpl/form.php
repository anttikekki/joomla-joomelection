<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor();
JHTML::_('behavior.calendar');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="election_name">
					<?php echo JText::_( 'Election name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="election_name" id="election_name" size="32" maxlength="250" value="<?php echo $this->election->election_name;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="election_type_id">
					<?php echo JText::_( 'Election type' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->election->election_type_id; ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="election_description">
					<?php echo JText::_( 'Election description' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $editor->display( 'election_description', $this->election->election_description, '100%', '300', '60', '35' ); ?>
			</td>
		</tr>
			
			
		<tr>	
			<td width="100" align="right" class="key">
				<label for="date_to_open">
					<?php echo JText::_( 'Date to open' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->calendars['date_to_open']; ?>
			</td>
		</tr>	
		
		<tr>	
			<td width="100" align="right" class="key">
				<label for="time_to_open">
					<?php echo JText::_( 'Time to open' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="time_to_open" id="time_to_open" size="10" maxlength="8" value="<?php echo JHTML::_('date',  $this->election->date_to_open, '%H:%M:%S') ;?>" />
			</td>
		</tr>
			
		<tr>
			<td width="100" align="right" class="key">
				<label for="date_to_close">
					<?php echo JText::_( 'Date to close' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->calendars['date_to_close']; ?>
			</td>
		</tr>
		
		<tr>	
			<td width="100" align="right" class="key">
				<label for="time_to_close">
					<?php echo JText::_( 'Time to close' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="time_to_close" id="time_to_close" size="10" maxlength="8" value="<?php echo JHTML::_('date',  $this->election->date_to_close, '%H:%M:%S') ;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="result_published">
					<?php echo JText::_( 'Published' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->election->published; ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="confirm_vote">
					<?php echo JText::_( 'Voters has to confirm selection' ); ?>?
				</label>
			</td>
			<td>
				<?php echo $this->election->confirm_vote; ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="confirm_vote_by_sign">
					<?php echo JText::_( 'Voters has to confirm selection by signing it' ); ?>?
				</label>
			</td>
			<td>
				<?php echo $this->election->confirm_vote_by_sign; ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="confirm_vote_by_sign_description">
					<?php echo JText::_( 'Confirm message' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" cols="70" rows="10" name="confirm_vote_by_sign_description"><?php echo $this->election->confirm_vote_by_sign_description;?></textarea>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="confirm_vote_by_sign_error">
					<?php echo JText::_( 'Confirm error message' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="inputbox" cols="70" rows="10" name="confirm_vote_by_sign_error"><?php echo $this->election->confirm_vote_by_sign_error;?></textarea>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="vote_success_description">
					<?php echo JText::_( 'Vote success message' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $editor->display( 'vote_success_description', $this->election->vote_success_description, '100%', '170', '60', '35' ); ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="election_voter_email_text">
					<?php echo JText::_( 'Voter email message that includes username and password' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_( 'Email subject' ); ?>:<br />
				<input class="inputbox" type="text" name="election_voter_email_header" size="150" maxlength="450" value="<?php echo $this->election->election_voter_email_header;?>" />
				<br /><br />
				<?php echo JText::_( 'Email message' ); ?>:<br />
				<textarea class="inputbox" cols="70" rows="15" name="election_voter_email_text"><?php echo $this->election->election_voter_email_text;?></textarea>
				<br /><br />
				<?php echo JText::_( 'VOTER_EMAIL_FIELD_LEGEND' ); ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_joomelection" />
<input type="hidden" name="election_id" value="<?php echo $this->election->election_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="election" />
<input type="hidden" name="opener_task" value="<?php echo $this->task; ?>" />
</form>
