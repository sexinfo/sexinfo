<?php
/**
 * @file
 * Provide views data and handlers for Google Analytics API
 */

/**
 * Implements hook_views_data()
 */
function google_analytics_views_views_data() {
  $data['google_analytics']['table']['group']  = t('Google Analytics');

  $data['google_analytics']['table']['base'] = array(
    'title' => t('Google Analytics'),
    'query class' => 'google_analytics_query',
    'help' => t('Views Google Analytics query builder'),
  );

  $data['google_analytics']['start_end'] = array(
    'title' => t('Start/End date of report (required)'),
    'help' => t('Start/End date of report (required)'),
    'filter' => array(
      'handler' => 'google_analytics_handler_filter_startend',
    ),
  );

  $data['google_analytics']['profile_id'] = array(
    'title' => t('Profile ID'),
    'help' => t('Profile ID'),
    'argument' => array(
      'handler' => 'google_analytics_handler_argument_string',
    ),
    'filter' => array(
      'handler' => 'google_analytics_handler_filter_string',
    ),
  );

  $fields = google_analytics_views_get_fields();
    
  foreach($fields as $field_name => $field) {
    $data['google_analytics'][$field_name] = array(
      'title' => $field['name'], 
      'help' => $field['description'],
      'group' => sprintf('%s %ss', $field['group'], ucfirst($field['type'])),
      'field' => array(
        'handler' => 'google_analytics_handler_field_string',
        'click sortable' => TRUE,
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
      'argument' => array(
        'handler' => ($field['type'] == 'metric') ? 'google_analytics_handler_argument_string' : 'google_analytics_handler_argument_string',
      ),
      'filter' => array(
        'handler' => ($field['type'] == 'metric') ? 'google_analytics_handler_filter_numeric' : 'google_analytics_handler_filter_string',
      ),
    );
  }

  return $data;
}

/**
 * Implements hook_views_plugins().
 */
function google_analytics_views_views_plugins() {
  return array(
    'query' => array(
      'google_analytics_query' => array(
        'title' => t('Google Analytics Query'), 
        'handler' => 'google_analytics_plugin_query_google_analytics'
      ),
    ),
  );
}

