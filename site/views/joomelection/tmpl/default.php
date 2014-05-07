<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
 
<?php
$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/styles.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );




for ($i=0, $n=count( $this->elections ); $i < $n; $i++) {		
	$election = &$this->elections[$i];
	?>
	
		
	<h1><?php echo $election->election_name; ?></h1>

	<div>
		<table cellspacing="10">
			<tr>
				<td nowrap="nowrap">
					<?php echo "<b>" .JText::_( 'Election open') .":</b>"; ?>
				</td>
				<td>
					<?php 
						echo JHTML::_('date',  $election->date_to_open, '%d.%m.%Y') ." ". JText::_( 'at') ." ". 
						JHTML::_('date',  $election->date_to_open, '%H.%M') ." - ". 
						JHTML::_('date',  $election->date_to_close, '%d.%m.%Y') ." ". JText::_( 'at') ." ". 
						JHTML::_('date',  $election->date_to_close, '%H.%M'); 
					?>
				</td>
			</tr>
			
			
			<tr>
				<td nowrap="nowrap">
					<?php echo "<b>" .JText::_( 'Election status') .":</b>"; ?>
				</td>
				<td>
					<?php 
						if($election->valid_election) {
							echo "<b class='green'>" .JText::_( 'Open') ."</b>";
						}
						else {
							echo "<b class='red'>" .JText::_( 'Closed') ."</b>";
						}
					 ?>
				</td>
			</tr>
			
			<tr>
				<td nowrap="nowrap">
					<?php echo "<b>" .JText::_( 'Your status'). ":</b>"; ?>
				</td>
				<td>
					<?php 
						if($this->user_logged_in > 0) {
							if($election->valid_voter) {
								echo JText::_( 'Welcome to vote' ) . ", " .$this->voter_name. ". " .JText::_( 'You can vote in this election');
							}
							else {
								if($election->valid_election) {
									echo JText::_( 'You have allready voted in this election');
								}
								else {
									echo JText::_( 'This election have been closed. Voting is not possible anymore' );
								}
							}
						}
						else {
							if($election->valid_election) {
								echo JText::_( 'You have not logged in. You have to log-in to vote' );
							}
							else {
								echo JText::_( 'This election have been closed. Voting is not possible anymore' );
							}
						} 
					?>
				</td>
			</tr>
			
			<tr>
				<td nowrap="nowrap" valign="top">
					<?php echo "<b>" .JText::_( 'Election description') .":</b>"; ?>
				</td>
				<td>
					<?php echo $election->election_description; ?>
				</td>
			</tr>
		</table>
	</div>
	
	
	
	
	
	
	
	<?php if(count($election->election_lists) > 0) { ?>
	<div class="election_list_tabs">
		<ul class="tabnav">
			<li class="tabs_legend"><?php echo JText::_('Select view'); ?>:</li>
			<li <?php if($this->selectedViewTab == 'view_election_candidates') {?> class="selectedTab" <?php } ?>>
				<a href="index.php?option=com_joomelection&view=joomelection&orderBy=number&selectedViewTab=view_election_candidates">
					<?php echo JText::_('Candidates'); ?>
				</a>
			</li>
			
			<li <?php if($this->selectedViewTab == 'view_election_lists') {?> class="selectedTab" <?php } ?>>
				<a href="index.php?option=com_joomelection&view=joomelection&selectedViewTab=view_election_lists">
					<?php echo JText::_('Election lists'); ?>
				</a>
			</li>
		</ul>
	</div>
	<?php } ?>
	
	
	
	
	<?php if($this->selectedViewTab == 'view_election_candidates') { ?>
	
		<div class="candidate_list_tabs<?php if(count($election->election_lists) == 0) { ?>_no_list_tabs<?php } ?>">
			<ul class="tabnav">
				<li class="tabs_legend"><?php echo JText::_('Order by'); ?>:</li>
				<li <?php if($this->orderBy == 'number') {?> class="selectedTab" <?php } ?>>
					<a href="index.php?option=com_joomelection&view=joomelection&orderBy=number">
						<?php echo JText::_('Order by candidate number'); ?>
					</a>
				</li>
				<li <?php if($this->orderBy == 'name') {?> class="selectedTab" <?php } ?> >
					<a href="index.php?option=com_joomelection&view=joomelection&orderBy=name">
						<?php echo JText::_('Order by name'); ?>
					</a>
				</li>
				<?php 
					//If election type is list (list = 2)
					if($election->election_type_id == 2) {
						 ?>
						<li <?php if($this->orderBy == 'listName') { ?> class="selectedTab" <?php } ?>>
							 <a href="index.php?option=com_joomelection&view=joomelection&orderBy=listName">
								<?php echo JText::_('Order by election list name'); ?>
							 </a>
						</li><?php
					}
				?>
			</ul>
		</div>
		
		<div class="candidate_list">
			<table cellpadding="20" cellspacing="0" width="100%" class="candidate_list_table">
			<?php
			
			for ($i2=0, $n2=count( $election->options ); $i2 < $n2; $i2++) {		
				$option = $election->options[$i2]; ?>
		
					
				<tr class="candidate_list_row">
					<td width="70%" class="candidate_list_cell">
						<div class="candidate_list_row_info">
							<span class="candidate_list_row_info_header"><?php echo JText::_( 'No.') . $option->option_number; ?>:</span> <?php echo $option->name; ?><br />
							<?php 
								//If election type is list (list = 2)
								if($election->election_type_id == 2) {
									 ?><span class="candidate_list_row_info_header"><?php echo JText::_( 'Election list'); ?>:</span> <?php echo $option->list_name; ?><br /><?php
								}
							?>
						</div>
						<div class="candidate_list_row_description">
							<?php echo $option->description; ?>
						</div>
					</td>
					<td nowrap="nowrap" align="center" width="30%" class="candidate_list_cell">
						<?php
						
						if($election->valid_voter) {
							echo "<a class='vote_logo' href='" .$option->vote_link. "'><img src='administrator/images/checkin.png' border='0' alt='" .JText::_( 'Vote number'). " " .$option->option_number. "' /></a><br />"
							. "<b><a href='" .$option->vote_link. "'>" .JText::_( 'Vote number'). " " .$option->option_number. "</a></b>";
						}
						else {
							echo "&nbsp;";
						}
						
						 ?>
					</td>
				</tr>
						
				
			<?php 
			}
			 ?>
			</table>
		</div>
		
		
	<?php } 
	else {?>
	
		
		<div class="candidate_list">
			<table cellpadding="20" cellspacing="0" width="100%" class="candidate_list_table">
			<?php
			
			for ($i2=0, $n2=count( $election->election_lists ); $i2 < $n2; $i2++) {		
				$election_list = $election->election_lists[$i2]; ?>
		
					
				<tr class="candidate_list_row">
					<td width="70%" class="candidate_list_cell">
						<div class="candidate_list_row_info">
							<span class="candidate_list_row_info_header"><?php echo JText::_( 'Election list name'); ?>:</span> <?php echo $election_list->name; ?><br />
						</div>
						<div class="candidate_list_row_description">
							<?php echo $election_list->description; ?>
						</div>
						<div class="election_lists_list_candidates">
							<ul>
								<?php
								for ($i3=0, $n3=count( $election_list->list_options ); $i3 < $n3; $i3++) {	
									$list_option = $election_list->list_options[$i3]; ?>
									<li><?php echo $list_option->name .", ". JText::_( 'candidate number'). " " .$list_option->option_number; ?></li>
								<?php } ?>
							</ul>
						</div>
					</td>
				</tr>
						
				
			<?php 
			}
			 ?>
			</table>
		</div>
		
	<?php } ?>
<?php } ?>