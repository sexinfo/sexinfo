<?php
/**
 * @file
 * Code is effectively a port of the Feeds code from the References module.
 *
 */

/**
 * Implements hook_feeds_parser_sources_alter().
 */
function metatags_quick_feeds_parser_sources_alter(&$sources, $content_type) {
  if (!empty($content_type)) {
    $fields = field_info_fields();
    foreach ($fields as $field) {
      if ($field['type'] == 'metatags_quick' && isset($field['bundles']['node']) && in_array($content_type, $field['bundles']['node'])) {
        $sources['parent:metatags_quick:'. $field['meta_name']] = array(
          'name' => t('Feed node: Metatag: @meta_name', array('@meta_name' => $field['meta_name'])),
          'description' => t('Metatags the parent feed node.'),
          'callback' => 'metatags_quick_feeds_get_source',
        );
      }
    }
  }
  return $sources;
}

/**
 * Callback for mapping parent metatags to child metatag values.
 *
 * @param $source
 *   A FeedsSource object.
 * @param $result
 *   FeedsParserResult object.
 * @param $key
 *   The key as defined in the _feeds_parser_sources_alter() hook defined above.
 * @return array
 *   The metatag value.
 */
function metatags_quick_feeds_get_source(FeedsSource $source, FeedsParserResult $result, $key) {
  if ($node = node_load($source->feed_nid)) {
    $results = array();
    $field = substr($key, 23);
    if (!empty($node->{$field}['und'])) {
      foreach ($node->{$field}['und'] as $delta => $value) {
        $results[] = $value['metatags_quick'];
      }
    }
    return $results;
  }
}

/**
 * Implements hook_feeds_processor_targets_alter() for metatags_quick fields.
 *
 * @see FeedsNodeProcessor::getMappingTargets().
 */
function metatags_quick_feeds_processor_targets_alter(&$targets, $entity_type, $bundle_name) {
  foreach (field_info_instances($entity_type, $bundle_name) as $name => $instance) {
    $info = field_info_field($name);
    if ($info['type'] == 'metatags_quick') {

      $targets[$name] = array(
        'name' => $instance['label'],
        'callback' => 'metatags_quick_feeds_set_target',
        'description' => t('The @label metatag.', array('@label' => $instance['label'])),
        'real_target' => $name);
    }
  }
}

/**
 * Callback for mapping metatag fields
 *
 * Implementation of hook_feeds_set_target().
 *
 * @param $source
 *   A FeedsSource object.
 * @param $entity
 *   The entity to map to.
 * @param $target
 *   The target key on $entity to map to.
 * @param $value
 *   The value to map. Can be an array or a string.
 */
function metatags_quick_feeds_set_target($source, $entity, $target, $value) {
  if (empty($value)) {
    return;
  }

  if (strstr($target, 'meta')) {
    $field['und'][0]['meta_name'] = substr($target, 11); /* strip off field_meta_ */
    $field['und'][0]['metatags_quick'] = $value;
  }
  $entity->{$target} = $field;
}
