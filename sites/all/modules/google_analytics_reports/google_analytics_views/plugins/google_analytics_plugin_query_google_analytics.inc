<?php
/**
 * @file google_analytics_plugin_query_default.inc
 * Defines the default query object which builds queries for the
 * Google Analytics Reports API.
 */

/**
 * Object used to create a Google Analaytics API query.
 */
class google_analytics_plugin_query_google_analytics extends views_plugin_query {

  /**
   * Constructor; Create the basic query object and fill with default values.
   */
  function init($base_table, $base_field, $options) {
    parent::init($base_table, $base_field, $options);
    $this->unpack_options($this->options, $options);
  }

  /**
   * Add a metric or dimension to the query.
   *
   * @param $table
   *   NULL in most cases, we could probably remove this altogether.
   * @param $field
   *   The name of the metric/dimension/field to add.
   * @param $alias
   *   Probably could get rid of this too.
   * @param $params
   *   Probably could get rid of this too.
   *
   * @return $name
   *   The name that this field can be referred to as.
   */
  function add_field($table, $field, $alias = '', $params = array()) {
    // We check for this specifically because it gets a special alias.
    if ($table == $this->base_table && $field == $this->base_field && empty($alias)) {
      $alias = $this->base_field;
    }

    if ($table && empty($this->table_queue[$table])) {
      $this->ensure_table($table);
    }

    if (!$alias && $table) {
      $alias = $table . '_' . $field;
    }

    // Make sure an alias is assigned
    $alias = $alias ? $alias : $field;

    // We limit the length of the original alias up to 60 characters
    // to get a unique alias later if its have duplicates
    $alias = substr($alias, 0, 60);

    // Create a field info array.
    $field_info = array(
      'field' => $field,
      'table' => $table,
      'alias' => $alias,
    ) + $params;

    // Test to see if the field is actually the same or not. Due to
    // differing parameters changing the aggregation function, we need
    // to do some automatic alias collision detection:
    $base = $alias;
    $counter = 0;
    while (!empty($this->fields[$alias]) && $this->fields[$alias] != $field_info) {
      $field_info['alias'] = $alias = $base . '_' . ++$counter;
    }

    if (empty($this->fields[$alias])) {
      $this->fields[$alias] = $field_info;
    }

    // Keep track of all aliases used.
    $this->field_aliases[$table][$field] = $alias;

    return $alias;
  }

  /**
   * Add a filter string to the query. 
   *
   * @param $group
   *   The filter group to add these to; groups are used to create AND/OR
   *   sections of the Google Analytics query. Groups cannot be nested. 
   *   Use 0 as the default group.  If the group does not yet exist it will 
   *   be created as an AND group.
   * @param $field
   *   The name of the metric/dimension/field to check.
   * @param $value
   *   The value to test the field against. In most cases, this is a scalar. 
   * @param $operator
   *   The comparison operator, such as =, <, or >=. 
   */
  function add_where($group, $field, $value = NULL, $operator = NULL) {
    // Ensure all variants of 0 are actually 0. Thus '', 0 and NULL are all
    // the default group.
    if (empty($group)) {
      $group = 0;
    }

    // Check for a group.
    if (!isset($this->where[$group])) {
      $this->set_where_group('AND', $group);
    }

    $this->where[$group]['conditions'][] = array(
      'field' => $field,
      'value' => $value,
      'operator' => $operator,
    );
  }

  /**
   * Add SORT attribute to the query.
   *
   * @param $table
   *   NULL, don't use this.
   * @param $field
   *   The metric/dimensions/field
   * @param $order
   *   Either '' for ascending or '-' for descending.
   * @param $alias
   *   Don't use this yet (at all?).
   * @param $params
   *   Don't use this yet (at all?).
   */
  function add_orderby($table, $field = NULL, $order = 'ASC', $alias = '', $params = array()) {
    $this->orderby[] = array(
      'field' => $field,
      'direction' => (strtoupper($order) == 'DESC') ? '-' : '',
    );
  }

  /**
   * Generate a query and a countquery from all of the information supplied
   * to the object.
   *
   * @param $get_count
   *   Provide a countquery if this is true, otherwise provide a normal query.
   */
  function query($get_count = FALSE) {
    $available_fields = google_analytics_views_get_fields();
    $query = array();

    foreach ($this->fields as $field) {
      if ($available_fields[$field['field']]) {
        $type = $available_fields[$field['field']]['type'];
        $type = ($type == 'dimension') ? 'dimensions' : 'metrics';
        $query[$type][] = 'ga:' . $field['field'];
      }
    }

    $filters = array();

    foreach ($this->where as $where_group => $where) {
      foreach ($where['conditions'] as $condition) {
        if ($condition['field'] == 'start_date') {
          $query['start_date'] = $condition['value']; 
          continue;
        }
        if ($condition['field'] == 'end_date') {
          $query['end_date'] = $condition['value']; 
          continue;
        }
        if ($condition['field'] == 'profile_id') {
          $query['profile_id'] = $condition['value']; 
          continue;
        }

        if ($available_fields[$condition['field']]) {
          $filters[$where_group][] = 'ga:' . $condition['field'] . $condition['operator'] . $condition['value'];
        }
      }
      $glue = ($where['type'] == 'AND') ? ';' : ',';
      $filters[$where_group] = implode($glue, $filters[$where_group]);
    }
    
    if (!empty($filters)) {
      $glue = ($this->group_operator == 'AND') ? ';' : ',';
      $query['filters'] = implode($glue, $filters);
    }

    foreach ($this->orderby as $field) {
      $query['sort_metric'][] = $field['direction'] . 'ga:' . $field['field'];
    }

    return $query;
  }

  /**
   * Builds the necessary info to execute the query.
   */
  function build(&$view) {
    // Store the view in the object to be able to use it later.
    $this->view = $view;

    $view->init_pager();

    // Let the pager modify the query to add limits.
    $this->pager->query();

    $view->build_info['query'] = $this->query();
    $view->build_info['count_query'] = $this->query(TRUE);
  }

  /**
   * Executes the query and fills the associated view object with according
   * values.
   *
   * Values to set: $view->result, $view->total_rows, $view->execute_time,
   * $view->current_page.
   */
  function execute(&$view) {  
    $query = $view->build_info['query'];
    $count_query = $view->build_info['count_query'];
    $start = microtime(TRUE);

    /* Query for total number of items */
    $count_query['max_results'] = 9999;
    $count_query['start_index'] = 1;
    $count_feed = google_analytics_api_report_data($count_query);
    $this->pager->total_items = count($count_feed->results->rows);
    $this->pager->update_page_info();

    /* Adjust based on the pager's modifications to limit and offset */
    if (!empty($this->limit) || !empty($this->offset)) {
      $query['max_results'] = intval(!empty($this->limit) ? $this->limit : 1000);
      $query['start_index'] = intval(!empty($this->offset) ? $this->offset : 0) + 1;
    }

    $feed = google_analytics_api_report_data($query);

    $rows = $feed->results->rows;
    foreach ($rows as $row) {
      $views_result[] = (object) $row;
    }

    $view->result = $views_result;
    $view->execute_time = microtime(TRUE) - $start;
    if ($this->pager->use_pager()) {
      $view->total_rows = $this->pager->get_total_items();
    }
    
    /* Add to build_info['query'] to render query in Views UI query summary area. */
    $view->build_info['query'] = print_r($feed->results->query, TRUE);
  }

}
