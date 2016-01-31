<?php
/**
 * @file
 * File to implement menu callbacks and other helper functions.
 *
 */

/**
 * Implements menu callback to autocomplete search.
 */
function _autocomplete_searchbox_search_portal($input) {
  $result_set = array();

  if (!empty($input)) {

    // Search for nodes.
    $nodes = variable_get('autocomplete_searchbox_content_types', array());
    $nodes = _autocomplete_search_filter_result($nodes);
    $result_set += _autocomplete_search_entity($nodes, 'node', $input);

    // Search for terms.
    $terms = variable_get('autocomplete_searchbox_terms', array());
    $terms = _autocomplete_search_filter_result($terms);
    $result_set += _autocomplete_search_entity($terms, 'term', $input);

    // Search for users.
    if (variable_get('autocomplete_searchbox_search_user', '0') == 1) {
      $users = array();
      $result_set += _autocomplete_search_entity($users, 'user', $input);
    }
  }

  // Return the result set.
  if (empty($result_set)) {
    $result_set = theme('search_dropdown', array(
      'type' => 'not_found',
    ));
  }
  return drupal_json_output($result_set);
}

/**
 * Function to return searched entities.
 */
function _autocomplete_search_entity($entity, $type, $input) {
  $result_set = array();

  switch ($type) {
    case 'node' :

      foreach ($entity as $name) {
        $nodes = db_select('node', 'n')
        ->fields('n', array('title'))
        ->condition('n.title', '%' . db_like($input) . '%', 'like')
        ->condition('n.type', $name, '=')
        ->addTag('node_access')
        ->execute()
        ->fetchCol();

        $result_set += theme('search_dropdown', array(
          'type' => 'node',
          'db_result' => $nodes,
          'label' => $name,
          'fcolor' => 'black',
          'bcolor' => '#FFFC04',
        ));
      }
    break;

    case 'term' :
      $term = array();
      $terms = db_select('taxonomy_term_data', 'ttd')
      ->fields('ttd', array('name'))
      ->condition('ttd.name', '%' . db_like($input) . '%', 'like')
      ->execute()
      ->fetchCol();

      // Remove the restricted terms from result.
      foreach ($terms as $name) {
        if (in_array($name, $entity)) {
          $term[] = $name;
        }
      }

      $result_set += theme('search_dropdown', array(
        'type' => 'term',
        'db_result' => $term,
        'label' => t('Category'),
        'fcolor' => 'white',
        'bcolor' => '#3CB6FC',
      ));
     break;

    case 'user' :
      global $user;

      if ($user->uid == 1 || in_array('administrator', $user->roles)) {
        $users = db_select('users', 'u')
        ->fields('u', array('name'))
        ->condition('u.name', '%' . db_like($input) . '%', 'like')
        ->execute()
        ->fetchCol();

        $result_set += theme('search_dropdown', array(
          'type' => 'user',
          'db_result' => $users,
          'label' => 'user',
          'fcolor' => 'white',
          'bcolor' => 'crimson',
        ));
      }
     break;
  }
  return $result_set;
}

/**
 * Function to filter only selected values.
 */
function _autocomplete_search_filter_result($variable) {
  $filtered_variable = array();
  if (is_array($variable)) {
    foreach ($variable as $value => $selection) {
      if ($selection) {
        $filtered_variable[] = $value;
      }
    }
  }
  return $filtered_variable;
}

/**
 * Function to filter only unselected values.
 */
function _autocomplete_search_filter_unselected_result($variable) {
  $filtered_variable = array();
  if (is_array($variable)) {
    foreach ($variable as $value => $selection) {
      if (!$selection) {
        $filtered_variable[] = $value;
      }
    }
  }
  return $filtered_variable;
}

/**
 * Implements theme callback.
 */
function theme_search_dropdown($variables) {
  $result_set = array();
  $result = $variables['db_result'];
  $type = $variables['type'];
  $fcolor = $variables['fcolor'];
  $bcolor = $variables['bcolor'];
  $separator = '';
  $label = '';

  // Check whether to use a separator or not.
  if (variable_get('autocomplete_searchbox_use_separator', '0')) {
    $separator = variable_get('autocomplete_searchbox_separator', '::');
  }

  // Check whether to use label or not.
  if (variable_get('autocomplete_searchbox_use_label', '0')) {
    $label = $variables['label'];
  }

  // Return result set accordingly.
  if (!empty($result)) {
    foreach ($result as $result_value) {

      if ($type == 'node') {
        $fcolor = variable_get('autocomplete_searchbox_fcolor_' . $label, '');
        $bcolor = variable_get('autocomplete_searchbox_bgcolor_' . $label, '');
      }

      if ($type == 'term') {
        $value = preg_replace('/\s/', '_', $result_value);
        $fcolor = variable_get('autocomplete_searchbox_fcolor_' . $value, '');
        $bcolor = variable_get('autocomplete_searchbox_bgcolor_' . $value, '');
      }

      if ($type == 'user') {
        $fcolor = variable_get('autocomplete_searchbox_user_fcolor', '');
        $bcolor = variable_get('autocomplete_searchbox_user_bgcolor', '');
      }

      // Output.
      $result_set[$result_value] = '<span style="color:' . $fcolor . ';background-color:' . $bcolor . ';">' . ucfirst($label) . ' </span> ' . $separator . ' ' . $result_value;
    }
  }
  else {
    if ($type == 'not_found') {
      $result_set[''] = '<span style="color:#8d2300;">' . variable_get('autocomplete_searchbox_no_results', t('Found nothing..')) . '</span>';
    }
  }
  return $result_set;
}
