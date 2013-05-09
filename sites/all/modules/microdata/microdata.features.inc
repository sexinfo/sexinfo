<?php

/**
 * @file
 * Provides Features integration for microdata.
 */

/**
 * Implements hook_features_api().
 */
function microdata_features_api() {
  return array(
    'microdata_mappings' => array(
      'name' => 'Microdata',
      'default_hook' => 'microdata_mappings',
      'base' => 'microdata_mappings',
      'file' => drupal_get_path('module', 'microdata') . '/microdata.features.inc',
    ),
  );
}

/**
 * Implements hook_features_export().
 */
function microdata_mappings_features_export($data, &$export, $module_name = '') {
  // A feature exporting microdata will need the microdata module.
  $export['dependencies']['microdata'] = 'microdata';

  foreach ($data as $key => $value) {
    $parts = explode('-', $key);
    $entity_type = $parts[0];
    $bundle_type = $parts[1];

    if ($microdata_mapping = _microdata_load_mapping($entity_type, $bundle_type)) {
      $export['features']['microdata_mappings'][$entity_type . '-' . $bundle_type] = $microdata_mapping;
    }
  }

  return array();
}

/**
 * Implements hook_features_export_options().
 */
function microdata_mappings_features_export_options() {
  $bundles = array();

  foreach (entity_get_info() as $entity_type => $entity) {
    foreach ($entity['bundles'] as $bundle_name => $bundle) {
      $bundles[$entity_type . '-' . $bundle_name] = $entity['label'] . ': ' . $bundle['label'];
    }
  }

  return $bundles;
}

/**
 * Implements hook_features_export_render().
 */
function microdata_mappings_features_export_render($module, $data, $export = NULL) {
  $code = array();
  $code[] = '  $microdata_mappings = array();';
  $code[] = '';

  foreach ($data as $key => $entity_type_bundle) {
    if (is_array($entity_type_bundle)) {
      $entity_type_bundle = $key;
    }
    $parts = explode('-', $entity_type_bundle);
    $entity_type = $parts[0];
    $bundle_type = $parts[1];
    if ($microdata_mapping = _microdata_load_mapping($entity_type, $bundle_type)) {
      $microdata_mapping_export = features_var_export($microdata_mapping, '  ');
      $microdata_bundle = features_var_export($bundle_type);
      $microdata_entity_type = features_var_export($entity_type);
      $code[] = "  // Exported Microdata mapping: {$bundle_type}";
      $code[] = "  \$microdata_mappings[$microdata_entity_type][$microdata_bundle] = $microdata_mapping_export;";
      $code[] = "";
    }
  }

  $code[] = '  return $microdata_mappings;';
  $code = implode("\n", $code);
  return array('microdata_mappings_defaults' => $code);
}

/**
 * Implements hook_features_revert().
 */
function microdata_mappings_features_revert($module) {
  return microdata_mappings_features_rebuild($module);
}

/**
 * Implements hook_features_rebuild().
 */
function microdata_mappings_features_rebuild($module) {
  if ($defaults = features_get_default('microdata_mappings', $module)) {
    foreach ($defaults as $entity_type => $bundles) {
      foreach ($bundles as $bundle_type => $mapping) {
        microdata_save_mapping($entity_type, $bundle_type, $mapping);
      }
    }
  }
}
