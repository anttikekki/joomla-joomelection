<?php defined('_JEXEC') or die('Restricted access'); 


?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Voter' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->voter->name;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="username">
					<?php echo JText::_( 'Username' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="username" id="username" size="50" maxlength="100" value="<?php echo $this->voter->username;?>" />
			</td>
		</tr>
			
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
					<?php echo JText::_( 'Email' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="email" id="email" size="50" maxlength="100" value="<?php echo $this->voter->email;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="password">
					<?php 
					if($this->voter->voter_id > 0) { 
						echo JText::_( 'New Password' ) . ":";
					}
					else {
						echo JText::_( 'Password' ) . ":";
					} ?>
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="password" id="password" size="50" maxlength="100" value="<?php echo $this->voter->password;?>" />
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
<input type="hidden" name="id" value="<?php echo $this->voter->voter_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="voter" />
<input type="hidden" name="limit" value="<?php echo $this->stored_limit; ?>" />
<input type="hidden" name="limitstart" value="<?php echo $this->stored_limitstart; ?>" />
<input type="hidden" name="search" value="<?php echo $this->stored_search; ?>" />
</form>
