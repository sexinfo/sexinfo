<?php
/**
 * @file upgrade path from the D6 nodewords module
 * @author maxiorel <http://drupal.org/user/49016>
 * @author valthebald <http://drupal.org/user/239562>
 */

function metatags_quick_upgrade() {
  if (db_table_exists('nodewords')) {
    module_load_include('inc', 'metatags_quick', 'known_tags');
    $form['description'] = array(
      '#type' => 'markup',
      '#markup' => '<p>' . t('This utility will convert existing nodewords to the Drupal 7 fields.') . '</p>'
    );
    $form['create_fields'] = array(
      '#title' => t('Create fields'),
      '#description' => t('Create fields if they do not exist.'),
      '#type' => 'checkbox',
      '#return_value' => '1',
      '#default_value' => '1',
    );
    $fields = variable_get('nodewords_edit', array());
    $options = drupal_map_assoc(array_keys(_metatags_quick_known_fields()));
    $form['fields_select'] = array(
      '#title' => t('Fields to import'),
      '#description' => t('Field list is taken from nodewords settings.'),
      '#type' => 'checkboxes',
      '#required' => TRUE,
      '#options' => $options,
      '#default_value' => $fields,
    );
    $form['batch_size'] = array(
      '#type' => 'textfield',
      '#default_value' => '100',
      '#description' => t('How many nodes to process during one iteration'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Convert'),
    );
  }
  else {
    $form['description'] = array(
      '#markup' => t('Nodewords table not found. Please check your installation'),
    );
  }
  return $form;
}

function metatags_quick_upgrade_submit($form, &$form_state) {
  // Step 1. Create fields if they don't exist.
  if ($form_state['values']['create_fields']) {
    // Note: we upgrade only node data!
    $node_bundles = field_info_bundles('node');
    if (empty($node_bundles)) {
      form_set_error(t('No content types found. !define them first', array('!define' => l(t('define'), 'admin/structure/types'))));
      return;
    }
    
    module_load_include('inc', 'metatags_quick', 'metatags_quick.admin');
    foreach($form_state['values']['fields_select'] as $field_name => $value) {
      if (!$value) {
        continue;
      }
      foreach ($node_bundles as $bundle_name => $bundle) {
        _metatags_quick_field_attach('node', $bundle_name, $field_name);
      }
    }
  }
  
  $fields_to_import = array();
  foreach($form_state['values']['fields_select'] as $field_name => $value) {
    if ($value) {
      $fields_to_import[] = $field_name;
    }
  }
  $batch = metatags_quick_upgrade_batch($form_state['values']['batch_size'], $fields_to_import);
  $_SESSION['nodewords_iterations'] = count($batch['operations']);
  batch_set($batch);
}

function metatags_quick_upgrade_finished($success, $results, $operations) {
  if ($success) {
    drupal_set_message(t('@node_count results processed in @requests iterations.', array('@node_count' => $_SESSION['nodewords_upgrade_total'], '@requests' => $_SESSION['nodewords_iterations'])));
  }
  else {
    $error_operation = reset($operations);
    drupal_set_message(t('An error occurred while processing @operation with arguments : @args', array('@operation' => $error_operation[0], '@args' => print_r($error_operation[0], TRUE))));
  }
  unset($_SESSION['nodewords_iterations'], $_SESSION['nodewords_upgrade_total']);
}


function metatags_quick_upgrade_batch($batch_size, $fields_to_import) {
  if ($batch_size < 2) {
    $batch_size = 1;
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
    if (count($nids) >= $batch_size) {
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
  return $batch;
}

function metatags_quick_convert_metatags($nids, $fields_to_import) {
  $processed_nodes = node_load_multiple($nids);
  $select = db_select('nodewords', 'n')
    ->fields('n', array('id', 'name', 'content'))
    ->condition('n.name', $fields_to_import, 'IN')
    ->condition('n.id', $nids, 'IN')
    ->execute();

  foreach ($select as $row) {
    $nodewords_data[$row->id][$row->name] = unserialize($row->content);
  }
  
  foreach ($processed_nodes as $node) {
    $_SESSION['nodewords_upgrade_total']++;
    $node_changed = FALSE;
    foreach ($fields_to_import as $field) {
      if (!empty($nodewords_data[$node->nid][$field])
        && !empty($nodewords_data[$node->nid][$field]['value'])) {
        $node_changed = TRUE;
        $meta_field_name = "meta_$field";
        $langcode = empty($node->language) ? LANGUAGE_NONE : $node->language;
        if (is_array($nodewords_data[$node->nid][$field]['value'])) {
          $meta_field_value = implode(',', array_filter($nodewords_data[$node->nid][$field]['value']));
        }
        else {
          $meta_field_value = $nodewords_data[$node->nid][$field]['value'];
        }
        $node->{$meta_field_name}[$langcode][0]['metatags_quick'] = substr($meta_field_value, 0, 255);
      }
    }
    if ($node_changed) {
      field_attach_presave('node', $node);
      field_attach_update('node', $node);
    }
  }
}

function metatags_quick_get_node_count() {
  $query = 'SELECT count(*) as count FROM {node}';
  $result = db_query($query);
  foreach ($result as $record) {
    $pocet = $record->count;
  }
  return $pocet;
}