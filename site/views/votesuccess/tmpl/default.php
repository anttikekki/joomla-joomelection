<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::base() . 'components/com_joomelection/css/joomelection-bootstrap.css', 'text/css'); 
?>

<div class="joomelection-bootstrap-wrapper">
	<h3>
	<?php echo JText::_( 'COM_JOOMELECTION_THANK_YOU_FOR_VOTING' ); ?>
	</h3>

	<p>
	<?php echo $this->election->vote_success_description; ?>
	</p>
</div>