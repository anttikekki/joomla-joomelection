<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewOptions extends JViewLegacy
{

  function display($tpl = null)
  {
    JToolBarHelper::title( JText::_( 'Options' ), 'generic.png' );
    JToolBarHelper::addNew('option.add');
    JToolBarHelper::editList('option.edit');
    JToolBarHelper::deleteList('', 'option.remove');
    
    $optionModel      = $this->getModel('option');
    $this->options     = &$optionModel->getOptions();
    $this->pagination = &$optionModel->getPagination();
    
    //Pass table sort parameters from last request
    $input = JFactory::getApplication()->input;
    $this->sortColumn = $input->getString('filter_order', '');
    $this->sortDirection = $input->getString('filter_order_Dir', '');

    parent::display($tpl);
  }
}