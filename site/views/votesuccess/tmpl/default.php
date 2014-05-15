<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<h1>
<?php echo JText::_( 'COM_JOOMELECTION_THANK_YOU_FOR_VOTING' ); ?>
</h1>

<p>
<?php echo $this->election->vote_success_description; ?>.
</p>