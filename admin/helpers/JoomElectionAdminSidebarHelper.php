<?php

defined('_JEXEC') or die;

class JoomElectionAdminSidebarHelper {
  public static function render($activeController) {
    $linkHtml =   static::renderLink('index.php?option=com_joomelection&task=election.showList',  'COM_JOOMELECTION_ELECTIONS',        $activeController == 'election');
    $linkHtml .=  static::renderLink('index.php?option=com_joomelection&task=list.showList',      'COM_JOOMELECTION_CANDIDATE_LISTS',  $activeController == 'list');
    $linkHtml .=  static::renderLink('index.php?option=com_joomelection&task=option.showList',    'COM_JOOMELECTION_CANDIDATES',       $activeController == 'option');
    $linkHtml .=  static::renderLink('index.php?option=com_joomelection&task=voter.showList',     'COM_JOOMELECTION_VOTERS',           $activeController == 'voter');
  
    $html = "
      <div id='sidebar'>
        <div class='sidebar-nav'>
          <ul id='submenu' class='nav nav-list'>$linkHtml</ul>
        </div>
      </div>
    ";
    return $html;
  }
  
  protected static function renderLink($link, $nameTranslationKey, $isActive) {
    $linkText = JText::_($nameTranslationKey);
    $liClass = $isActive ? 'active' : '';
  
    return "
      <li class='$liClass'>
        <a href='$link'>$linkText</a>
      </li>
    ";
  }
}