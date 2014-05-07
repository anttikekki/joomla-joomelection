<?php defined('_JEXEC') or die('Restricted access'); 

$editor =& JFactory::getEditor(); 
JHTML::_('behavior.calendar');

$document =& Jfactory::getDocument();
$document->addScriptDeclaration(
	"window.addEvent('domready',function(){"
		."$('election_id').addEvent('change',function(){"
			."var url='index.php?option=com_joomelection&controller=option&format=raw&task=listElectionListsForElection&election_id='+this.getValue();"
			."var a=new Ajax(url,{ method:'get', update:$('electionLists-container')}).request();"
		."});"
	."});");
?>


<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_( 'Option name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->option->name;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="option_number">
					<?php echo JText::_( 'Option number' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="option_number" id="option_number" size="11" maxlength="11" value="<?php echo $this->option->option_number;?>" />
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="election_id">
					<?php echo JText::_( 'Election' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->electionList; ?>
			</td>
		</tr>
		
		<tr>
			<td width="100" align="right" class="key">
				<label for="election_id">
					<?php echo JText::_( 'Election list' ); ?>:
				</label>
			</td>
			<td id="electionLists-container">
				<?php echo $this->electionListsComboBox; ?>
			</td>
		</tr>
			
		<tr>	
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'Option description' ); ?>:
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
				<?php echo $this->option->published; ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_joomelection" />
<input type="hidden" name="option_id" value="<?php echo $this->option->option_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="option" />
</form>
