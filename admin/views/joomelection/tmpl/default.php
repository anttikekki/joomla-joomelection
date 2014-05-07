<?php defined('_JEXEC') or die('Restricted access'); ?>

<div>
		<a href="<?php echo JRoute::_( 'index.php?option=com_joomelection&controller=election&task=showList' ); ?>"><?php echo JText::_( 'Elections' ); ?></a><br />
		<a href="<?php echo JRoute::_( 'index.php?option=com_joomelection&controller=list&task=showList' ); ?>"><?php echo JText::_( 'Candidate Lists' ); ?></a><br />
		<a href="<?php echo JRoute::_( 'index.php?option=com_joomelection&controller=option&task=showList' ); ?>"><?php echo JText::_( 'Options' ); ?></a><br />
		<a href="<?php echo JRoute::_( 'index.php?option=com_joomelection&controller=voter&task=showList' ); ?>"><?php echo JText::_( 'Voters' ); ?></a>

</div>


