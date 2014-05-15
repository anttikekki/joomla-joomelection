<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 


$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/styles.css', 'text/css', null, array( 'id' => 'StyleSheet' ) );
?>

<h1><?php echo JText::_( 'COM_JOOMELECTION_VOTE_FAILED'); ?></h1>

<p><?php echo $this->election->confirm_vote_by_sign_error; ?></p>

<p>
  <a class='vote_logo' href='javascript: history.back();'><img src='<?php echo JURI::root(true); ?>/components/com_joomelection/img/cancel.png' border='0' alt='<?php echo JText::_( 'COM_JOOMELECTION_VOTE_CANCEL'); ?>' /></a><br />
  <b><a href='javascript: history.back();'><?php echo JText::_( 'COM_JOOMELECTION_VOTE_CANCEL'); ?></a></b>
</p>