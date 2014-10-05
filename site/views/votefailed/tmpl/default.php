<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 


$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/joomelection-bootstrap.css', 'text/css');
?>

<div class="joomelection-bootstrap-wrapper">
	<h3><?php echo JText::_( 'COM_JOOMELECTION_VOTE_FAILED'); ?></h3>

	<p class="text-danger"><?php echo $this->election->confirm_vote_by_sign_error; ?></p>

	<a href="index.php?option=com_joomelection&view=joomelection" class="btn btn-danger btn-sm" role="button">
		<span class="glyphicon glyphicon-remove"></span> 
		<?php echo JText::_( 'COM_JOOMELECTION_VOTE_CANCEL'); ?>
	</a>
</div>