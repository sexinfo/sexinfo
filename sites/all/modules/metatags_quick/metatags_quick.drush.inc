<?php

/**
 * @file metatags_quick.drush.inc
 * Drush commands for metatags_quick.
 */

/**
 * Implementation of hook_drush_help().
 */
function metatags_quick_drush_help($section) {
  switch ($section) {
    case 'metatags quick:metatags-quick-migrate':
      return dt("Migrate from nodewords to metatags-quick");
  }
}


/**
 * Implementation of hook_drush_command().
 */
function metatags_quick_drush_command() {
  $items['metatags-quick-migrate'] = array(
    'description' => 'Migrate from nodewords to metatags-quick.',
    'arguments' => array(
      'name' => 'An optional list of og fields. If not specified, all available fields will be migrated.',
    ),
  );

  return $items;
}

/**
 * Command callback.
 */
function drush_metatags_quick_migrate() {
  $field_names = drush_metatags_quick_migrate_get_fields(_convert_csv_to_array(func_get_args()));
  if (empty($field_names)) {
    return drush_set_error('METATAGS_QUICK_MIGRATE_NOTHING_TO_DO', dt('No fields to migrate.'));
  }
  drush_log(dt('The following fields will be migrated: !fields', array('!fields' => implode(', ', $field_names))), 'status');
  if(!drush_confirm(dt('Do you really want to continue?'))) {
    return drush_user_abort();
  }
  foreach ($field_names as $name) {
    $fields[$name] = $name;
  }
  drush_metatags_quick_migrate_fields($fields);

  _drush_log_drupal_messages();
  drush_log(dt('Migration complete'), 'status');
}

function drush_metatags_quick_migrate_fields($field_names) {  
  if (!$field_names) {
    return;
  }

  // Step 1. Create fields if they don't exist.
  // Note: we upgrade only node data!
  $node_bundles = field_info_bundles('node');
  if (empty($node_bundles)) {
    return drush_set_error('METATAGS_QUICK_MIGRATE_NOTHING_TO_DO', dt('No content types found. !define them first', array('!define' => l(t('define'), 'admin/structure/types'))));
  }

  module_load_include('inc', 'metatags_quick', 'metatags_quick.admin');
  module_load_include('inc', 'metatags_quick_import', 'metatags_quick_import.admin');
  
  foreach($field_names as $field_name => $value) {
    if (!$value) {
      continue;
    }
    foreach ($node_bundles as $bundle_name => $bundle) {
      _metatags_quick_field_attach('node', $bundle_name, $field_name);
    }
  }

  $fields_to_import = array();
  foreach($field_names as $field_name => $value) {
    if ($value) {
      $fields_to_import[] = $field_name;
    }
  }

  $num_operations = metatags_quick_get_node_count();
  $_SESSION['nodewords_upgrade_total'] = $num_operations;
  $_SESSION['nodewords_upgrade_processed'] = 0;
  drupal_set_message(t('Converting metatags for @num nodes', array('@num' => $num_operations)));
  $operations = array();
  $nid_result = db_select('node', 'n')
    ->fields('n', array('nid'))
    ->execute();
  $nids = array();
  foreach ($nid_result as $node) {
    $nids[] = $node->nid;
    if (count($nids) >= 1000) {
      $operations[] = array('metatags_quick_convert_metatags', array($nids, $fields_to_import));
      $nids = array();
    }
  }
  if (count($nids)) {
    $operations[] = array('metatags_quick_convert_metatags', array($nids, $fields_to_import));
  }
  $batch = array(
    'operations' => $operations,
    'progress_message' => t('Completed :nodes_completed of :nodes_total', array(
      ':nodes_completed' => $_SESSION['nodewords_upgrade_processed'], ':nodes_total' => $_SESSION['nodewords_upgrade_total'])),
    'finished' => 'metatags_quick_upgrade_finished',
    'file' => drupal_get_path('module', 'metatags_quick') . '/metatags_quick_import.admin.inc',
  );

  batch_set($batch);
  $batch =& batch_get();
  $batch['progressive'] = FALSE;
  drush_backend_batch_process();
}



function drush_metatags_quick_migrate_get_fields($requests = array()) {
  $fields = array(
    'abstract' => 'abstract',
    'copyright' => 'copyright',
    'description' => 'description',
    'geourl' => 'location',
    'keywords' => 'keywords',
    'Revisit-After' => 'revisit-after',
    'robots' => 'robots',
  );  
  $options = drupal_map_assoc(array_keys($fields));
  if (!empty($requests) && !empty($options)) {
    $not_found = array_diff($requests, $options);
    $options = array_intersect($options, $requests);
    if (!empty($not_found)) {
      return drush_set_error('METATAGS_QUICK_MIGRATE_PLUGIN_NOT_FOUND', dt('The following fields were not found: !fields', array('!fields' => implode(', ', $not_found))));
    }
  }
  return $options;  
}

