<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<style type="text/css">
  
  a.vote_logo:hover {
	text-decoration:none;
  }
</style>

<h1><?php echo JText::_( 'Vote failed'); ?>!</h1>

<p><?php echo $this->election->confirm_vote_by_sign_error; ?></p>

<p>
<a class='vote_logo' href='javascript: history.back();'><img src='<?php echo JURI::root(true); ?>/components/com_joomelection/img/cancel.png' border='0' alt='<?php echo JText::_( 'Cancel'); ?>' /></a><br />
<b><a href='javascript: history.back();'><?php echo JText::_( 'Cancel'); ?></a></b>
</p>