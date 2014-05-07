<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<h1>
<?php echo JText::_( 'Thank you for voting' ); ?>!
</h1>

<p>
<?php echo $this->election->vote_success_description; ?>.
</p>