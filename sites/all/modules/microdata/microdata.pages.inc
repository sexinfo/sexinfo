<?php

/**
 * @file
 * Page callbacks for microdata module.
 */

/**
 * Autocomplete callback; Itemtype textfield.
 *
 * This attempts to autocomplete multiple, comma separated itemtypes. However,
 * the AJAX fails because of issue #93854 (RTBC for Drupal 8).
 */
function microdata_autocomplete_itemtype($input = '') {
  $matches = array();
  $input = drupal_explode_tags($input);
  $string = drupal_strtolower(array_pop($input));

  $types = microdata_get_types();
  if ($string) {
    $prefix = count($input) ? drupal_implode_tags($input) . ', ' : '';
    foreach ($types as $type) {
      if (preg_match("/$string/", drupal_strtolower($type['url']))) {
        $matches[$prefix . check_plain($type['url'])] = check_plain($type['vocabulary_label']) . ' - ' . check_plain($type['label']);
      }
    }
  }

  drupal_json_output($matches);
}

/**
 * Autocomplete callback; Itemprop textfield.
 */
function microdata_autocomplete_itemprop($itemtype, $input = '') {
  $matches = array();
  $input = drupal_explode_tags($input);
  $string = drupal_strtolower(array_pop($input));

  $types = microdata_get_types();
  if ($string) {
    $prefix = count($input) ? drupal_implode_tags($input) . ', ' : '';
    // Decode the itemtype arg, replace the '/'s, and explode it into an array.
    $itemtypes = explode(', ', str_replace(';', '/', urldecode($itemtype)));
    foreach ($itemtypes as $type) {
      foreach ($types[$type]['properties'] as $property) {
        if (preg_match("/$string/", $property)) {
          $matches[$prefix . check_plain($property)] = check_plain($property);
        }
      }
    }
  }

  drupal_json_output($matches);
}

/**
 * Helper function.
 *
 * @return array
 *   An array of type info, including properties that can be used in those
 *   types.
 */
function microdata_get_types() {
  $types = array();
  $vocabularies = microdata_get_vocabularies();
  foreach ($vocabularies as $vocabulary) {
    foreach ($vocabulary['types'] as $type) {
      // @todo Remove the array conversion once Vocabulary Parser is in place.
      $type = (array) $type;

      $types[$type['url']] = array(
        'label' => $type['label'],
        'url' => $type['url'],
        'vocabulary_label' => $vocabulary['label'],
        'properties' => $type['properties'],
      );
    }
  }

  return $types;
}

/**
 * Helper function.
 *
 * @return array
 *   An array of vocabulary info, keyed by vocabulary and then itemtype.
 */
function microdata_get_vocabularies() {
  $vocabulary_info = microdata_get_vocabulary_info();
  $enabled_vocabs = microdata_get_enabled_vocabularies();

  // If there are enabled vocabularies, compile their definitions into one
  // array.
  if (!empty($vocabulary_info) && count($enabled_vocabs)) {
    $result = db_select('cache_microdata_vocabulary')
      ->fields('cache_microdata_vocabulary', array('cid', 'data'))
      ->condition('cid', $enabled_vocabs, 'IN')
      ->execute();
    foreach ($result as $row) {
      $data = $row->data;
      $schema = unserialize($data);
      // @todo Remove the array conversion once Vocabulary Parser is in place.
      $schema->types = (array) $schema->types;
      $vocabulary_info[$row->cid]['types'] = $schema->types;
    }
  }

  return $vocabulary_info;
}
