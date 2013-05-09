<?php

/**
 * @file
 * Hooks provided by Microdata module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Enable microdata for a field type.
 *
 * This is a placeholder for describing further keys for hook_field_info(),
 * which are introduced by Microdata module. It also documents Entity API keys
 * which are required for Microdata.
 *
 * If a field has a simple value (string, number, etc) or if it is an item
 * value (entities, addressfields), enable microdata on the field itself. Some
 * fields, such as Link, are just containers for properties (ie, the link url
 * and the linked text). These have a struct data type and microdata output
 * should not be enabled for the field, but on the properties themselves.
 *
 * @see entity_hook_field_info()
 */
function microdata_hook_field_info() {
  return array(
    'addressfield' => array(
      'label' => t('Postal address'),
      // property_type and property_callbacks come from Entity API.
      'property_type' => 'addressfield',
      'property_callbacks' => array('addressfield_property_info_callback'),
      // ...
      // Enable microdata for this field.
      'microdata' => TRUE,
    ),
  );
}

/**
 * Change a field type's microdata value type.
 *
 * The value type determines how microdata is placed for a field.
 */
function hook_microdata_value_types_alter(&$types) {
  $types['addressfield'] = 'item_option';
}

/**
 * Suggest microdata terms to use for a field type and its properties.
 *
 * This hook should only be used by the module that defines the field type.
 * Other modules can use hook_microdata_suggestions_alter() to add or change
 * suggested mappings for a field.
 *
 * @return array
 *   An array of suggested mappings, keyed as following:
 *   $suggested_mappings[group][field_name][mapping scheme]
 *
 * @see hook_microdata_suggestions_alter()
 */
function hook_microdata_suggestions() {
  $suggestions = array();

  // Suggested Schema.org mapping for the Fivestar field.
  $suggestions['fields']['fivestar']['schema.org'] = array(
    '#itemprop' => array('aggregateRating'),
    '#itemtype' => array('http://schema.org/AggregateRating'),
    'average_rating' => array(
      '#itemprop' => array('ratingValue'),
    ),
  );

  return $suggestions;
}

/**
 * Alter the suggested microdata terms provided in hook_microdata_suggestions.
 *
 * @param array $suggestions
 *   The array of suggestions.
 *
 * @see hook_microdata_suggestions()
 */
function hook_microdata_suggestions_alter(&$suggestions) {
  $suggestions['fields']['fivestar']['example.org'] = array(
    '#itemprop' => array('ratingExample'),
    '#itemtype' => array('http://example.org/RatingExample'),
    'average_rating' => array(
      '#itemprop' => array('exampleValue'),
    ),
  );
}


/**
 * Declare microdata vocabularies.
 */
function hook_microdata_vocabulary_info() {
  return array(
    'schema_org' => array(
      'label' => 'Schema.org',
      'description' => t("Google, Bing, and Yahoo! supported terms for Rich Snippets, documented at !link.", array('!link' => l('Schema.org', 'http://schema.org'))),
      'import_url' => 'http://schema.rdfs.org/all.json',
    ),
  );
}

/**
 * @} End of "addtogroup hooks".
 */
