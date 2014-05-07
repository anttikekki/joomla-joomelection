<?php
/**
 * JoomElection component for Joomla 3
 *
 * @author      Antti Kekki
 * @copyright   Antti Kekki, http://code.google.com/p/joomelection/
 * @version     2.0.0
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Get an instance of the controller prefixed by JoomElectionController
$controller = JControllerLegacy::getInstance('JoomElection');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();