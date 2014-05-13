<?php defined('_JEXEC') or die('Restricted access'); ?>



<h2><?php echo JText::_( 'Result for' ) ." ". $this->statistics->election_name; ?></h2>

<br><br>

<div class="row-fluid">
  <div class="span3">
    <table class="table">
      <tr>
        <td><b><?php echo JText::_( 'Total number of voters' ); ?>:</b></td>
        <td><?php echo $this->statistics->voter_total; ?></td>
      </tr>
      <tr>
        <td><b><?php echo JText::_( 'Voters who voted' ); ?>:</b></td>
        <td><?php echo $this->statistics->voters_who_voted; ?></td>
      </tr>
      <tr>
        <td><b><?php echo JText::_( 'Voter percentage' ); ?>:</b></td>
        <td><?php echo $this->statistics->voted_percentage; ?> %</td>
      </tr>
    </table>
  </div>
</div>

<br><br>

<div class="row-fluid">
  <div class="span5">
    <table class="table">
      <thead>
        <tr>
          <th><?php echo JText::_( 'Option number' ); ?></th>
          <th><?php echo JText::_( 'Option name' ); ?></th>
          <th><?php echo JText::_( 'Option votes' ); ?></th>
          
          <?php 
          if($this->election_type_id == 2) {
            ?><th><?php echo JText::_( 'Election list name' ); ?></th>
              <th><?php echo JText::_( 'Election list votes' ); ?></th><?php
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php 
        for ($i=0, $n=count( $this->results ); $i < $n; $i++) {		
          $row = &$this->results[$i]; ?>
          
          <tr>
            <td><?php echo $row->option_number; ?></td>
            <td><?php echo $row->name; ?></td>
            <td><?php echo $row->votes; ?></td>
            
            <?php 
            if($this->election_type_id == 2) {
              ?><td><?php echo $row->list_name; ?></td>
                <td><?php echo $row->list_votes; ?></td><?php
            }
            ?>
            
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>



<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_joomelection" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="election" />
<input type="hidden" name="election_id" value="<?php echo $this->statistics->election_id; ?>" />
<input type="hidden" name="task_opened_from" value="<?php echo $this->task_opened_from; ?>" />
</form>
