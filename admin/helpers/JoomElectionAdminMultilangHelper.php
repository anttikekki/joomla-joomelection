<?php

defined('_JEXEC') or die;

class JoomElectionAdminMultilangHelper {
  
  public static function getFieldHtml($dataObject, $fieldName) {
    $languages = JLanguageHelper::getLanguages();
    $currentLang =& JFactory::getLanguage();
    $html = '';

    foreach($languages as $language) { 
      $isCurrentLang = $currentLang->getTag() == $language->lang_code;
      $hideStyle = $isCurrentLang ? '' : 'display: none;';
      $fullFieldName = $fieldName . '_' . $language->lang_code;

      $html .= '<div id="'.$fullFieldName.'_container" style="'.$hideStyle.'" class="'.$fieldName.'_input_container">';
      $html .= '  <input type="text" name="'.$fullFieldName.'" id="'.$fullFieldName.'" size="32" maxlength="250" value="'.$dataObject->$fullFieldName.'" />';
      $html .= '</div>';
    }

    $html .= '<div class="joomelection_field_language_chooser_container '.$fieldName.'_language_chooser">';
      foreach($languages as $language) { 
        $isCurrentLang = $currentLang->getTag() == $language->lang_code;
        $checked = $isCurrentLang ? 'checked' : '';
        
        $html .= '<label class="radio">';
        $html .= '  <input type="radio" '.$checked.' value="'.$language->lang_code.'" name="'.$fieldName.'_language">';
        $html .= $language->title;
        $html .= '</label>';
      }

      $html .= "<script type=\"text/javascript\">
        jQuery(document).ready(function() {
          jQuery('.".$fieldName."_language_chooser input').on('change', function(event) {
            jQuery('.".$fieldName."_input_container').hide();

            var selectedLang = jQuery(event.target).val();
            jQuery('#".$fieldName."_' + selectedLang + '_container').show();
          });
        });
      </script>";
    $html .= '</div>';

    return $html;
  }
}