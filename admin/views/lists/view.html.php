<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();


class JoomElectionViewLists extends JViewLegacy
{

  function display($tpl = null)
  {
    JToolBarHelper::title(   JText::_( 'COM_JOOMELECTION_CANDIDATE_LISTS' ), 'list' );
    JToolBarHelper::addNew('list.add');
    JToolBarHelper::editList('list.edit');
    JToolBarHelper::deleteList('', 'list.remove');
    
    $listModel        = &$this->getModel('list');
    $this->lists      = &$listModel->getPaginatedElectionLists();
    $this->pagination = &$listModel->getPagination();
    
    //Sidebar
    require_once (JPATH_COMPONENT_ADMINISTRATOR .'/helpers/JoomElectionAdminSidebarHelper.php');
    $this->sidebar = JoomElectionAdminSidebarHelper::render('list');
    
    //Pass table sort parameters from last request
    $input = JFactory::getApplication()->input;
    $this->sortColumn = $input->getString('filter_order', '');
    $this->sortDirection = $input->getString('filter_order_Dir', '');

    parent::display($tpl);
  }
}