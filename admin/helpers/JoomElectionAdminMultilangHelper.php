<?php

defined('_JEXEC') or die;

class JoomElectionAdminMultilangHelper {

  public static function getValue($dataObject, $fieldName) {
    $currentLang =& JFactory::getLanguage();
    $fullFieldName = $fieldName . '_' . $currentLang->getTag();

    return $dataObject->$fullFieldName;
  }

  public static function getFieldHtml($type, $dataObject, $fieldName, $options=[]) {
    $languages = JLanguageHelper::getLanguages();
    $currentLang =& JFactory::getLanguage();
    $html = '';

    //Input
    foreach($languages as $language) { 
      $isCurrentLang = $currentLang->getTag() == $language->lang_code;
      $hideStyle = $isCurrentLang ? '' : 'display: none;';
      $fullFieldName = $fieldName . '_' . $language->lang_code;

      $html .= '<div id="'.$fullFieldName.'_container" style="'.$hideStyle.'" class="'.$fieldName.'_input_container joomelection_'.$type.'_input_container">';
      $html .= self::getInputElement($type, $fullFieldName, $dataObject, $options);
      $html .= '</div>';
    }

    //Language chooser radio buttons
    $html .= '<div class="joomelection_field_language_chooser_container '.$fieldName.'_language_chooser">';
    foreach($languages as $language) { 
      $isCurrentLang = $currentLang->getTag() == $language->lang_code;
      $checked = $isCurrentLang ? 'checked' : '';
      
      $html .= '<label class="radio">';
      $html .= '  <input type="radio" '.$checked.' value="'.$language->lang_code.'" name="'.$fieldName.'_language">';
      $html .= $language->title;
      $html .= '</label>';
    }

    //Language chooser javascript
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

  private function getInputElement($type, $fullFieldName, $dataObject, $options) {
    $html = '';
    $class = array_key_exists('class', $options) ? $options['class'] : '';

    if($type == 'text') {
      $html .= '<input type="text" name="'.$fullFieldName.'" id="'.$fullFieldName.'" class="'.$class.'" size="100" maxlength="'.$options['maxlength'].'" value="'.$dataObject->$fullFieldName.'" />';
    }
    else if($type == 'editor') {
      $editor =& JFactory::getEditor();
      $html .= $editor->display( $fullFieldName, $dataObject->$fullFieldName, '100%', '100px', '60', '20' );
    }
    else if($type == 'textarea') {
      $html .= '<textarea cols="120" rows="'.$options['rows'].'" name="'.$fullFieldName.'" class="'.$class.'">'.$dataObject->$fullFieldName.'</textarea>';
    }

    return $html;
  }
}