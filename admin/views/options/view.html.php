<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class JoomElectionViewOptions extends JViewLegacy
{

  function display($tpl = null)
  {
    JToolBarHelper::title( JText::_( 'COM_JOOMELECTION_CANDIDATES' ), 'users' );
    JToolBarHelper::addNew('option.add');
    JToolBarHelper::editList('option.edit');
    JToolBarHelper::deleteList('', 'option.remove');
    
    $optionModel      = $this->getModel('option');
    $this->options    = &$optionModel->getPaginatedOptions();
    $this->pagination = &$optionModel->getPagination();
    
    //Sidebar
    require_once (JPATH_COMPONENT_ADMINISTRATOR .'/helpers/JoomElectionAdminSidebarHelper.php');
    $this->sidebar = JoomElectionAdminSidebarHelper::render('option');
    
    //Pass table sort parameters from last request
    $input = JFactory::getApplication()->input;
    $this->sortColumn = $input->getString('filter_order', '');
    $this->sortDirection = $input->getString('filter_order_Dir', '');

    parent::display($tpl);
  }
}