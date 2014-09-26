<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');


class JoomElectionModelTranslation extends JModelLegacy {

  function getLanguageFileString($lang, $string) {
    $language = JLanguage::getInstance($lang);
    $reload = true;
    if(!$language->load('com_joomelection', JPATH_BASE, $lang, $reload)) {
      JFactory::getApplication()->enqueueMessage('Language file loading failed for language '.$lang);
    }
    return $language->_($string);
  }

  function &getTranslations($entity_type, $entity_ids) {
    $db = $this->_db;
    $entity_ids = array_filter($entity_ids, 'is_numeric');


    //JFactory::getApplication()->enqueueMessage('$entity_ids = ' . var_export($entity_ids, true), 'error');
    
    if(count($entity_ids) == 0) {
      return [];
    }

    $query = " 
      SELECT language, entity_field, entity_id, translationText
      FROM #__joomelection_translation 
      WHERE entity_type = " . $db->quote($entity_type) . "
        AND entity_id IN(" . implode(',', $entity_ids) . ")";

    $db->setQuery( $query );
    return $db->loadObjectList();
  }

  function loadTranslationsToObject($object, $entity_type, $entity_id) {
    $translations = $this->getTranslations($entity_type, [$entity_id]);

    foreach($translations as $translation) {
      $field = $translation->entity_field . '_' . $translation->language;
      $object->$field = $translation->translationText;
    }
  }

  function loadTranslationsToObjects($objects, $entity_type, $entity_id_field) {
    $ids = [];
    $objectMap = [];
    foreach($objects as $object) {
      $id = (int) $object->$entity_id_field;
      $ids[] = $id;
      $objectMap[$id] = $object;
    }

    $translations = $this->getTranslations($entity_type, $ids);
    foreach($translations as $translation) {
      $field = $translation->entity_field . '_' . $translation->language;
      $object = $objectMap[$translation->entity_id];
      $object->$field = $translation->translationText;
    }
  }

  function saveTranslation($language, $entity_type, $entity_id, $entity_field, $translationText) {
    $db = $this->_db;
    $query = " 
      REPLACE INTO #__joomelection_translation (
        language, 
        entity_type, 
        entity_id, 
        entity_field,
        translationText
      )
      VALUES (
        " . $db->quote($language) . ",
        " . $db->quote($entity_type) . ",
        " . (int) $entity_id . ",
        " . $db->quote($entity_field) . ",
        " . $db->quote($translationText) . "
      )
    ";

    $db->setQuery( $query );
    $db->query();
  }

  function deleteTranslation($entity_type, $entity_id) {
    $db = $this->_db;
    $query = " 
      DELETE FROM #__joomelection_translation 
      WHERE entity_type = " . $db->quote($entity_type) . "
        AND entity_id = " . (int) $entity_id . "
    ";

    $db->setQuery( $query );
    $db->query();
  }
}