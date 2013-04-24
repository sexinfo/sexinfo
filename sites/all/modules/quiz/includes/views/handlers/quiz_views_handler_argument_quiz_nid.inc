<?php

/*
 * @file
 * Handles argument quiz nid.
 */

class quiz_views_handler_argument_quiz_nid extends views_handler_argument_numeric {
  /**
   * An array that the subselect will fill with all the corresponding quiz node,
   * according to the configuration on the argument settings form. These vids
   * will then be the actual ones used while generating the real query.
   *
   * @var array
   */
  var $corresponding_vids = array();
  var $phrase_broken = FALSE;

  function construct() {
    parent::construct();
    $this->nid_field = $this->definition['nid field'];
    $this->vid_field = isset($this->definition['vid field']) ? $this->definition['vid field'] : 'vid';
  }

  function option_definition() {
    $options = parent::option_definition();

    $options['which_vid'] = array('default' => 'latest');

    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    // Set weighting so that our element can sanely ordered
    $form['title']['#weight'] = -2;
//    $form['default_action']['#weight'] = -3;
//    $form['wildcard']['#weight'] = -4;
//    $form['wildcard_substitution']['#weight'] = -4;
    $form['which_vid'] = array(
      '#type' => 'select',
      '#title' => t('Quiz revision(s) to use'),
      '#options' => array(
        'latest' => t('Latest: most recent version of the quiz ONLY.'),
        'initial' => t('Initial: original version of the quiz ONLY.'),
        'all' => t('All: ALL versions of the quiz.')
      ),
      '#description' => t('The validator will transform the incoming quiz node id(s) into one or more quiz node revisions, depending on your selection.'),
      '#default_value' => $this->options['which_vid'],
      '#weight' => -1,
    );
  }

  function title_query() {
    $titles = array();
    $placeholders = implode(', ', array_fill(0, count($this->corresponding_vids), '%d'));

    $result = db_query("SELECT n.title FROM {node_revisions} n WHERE n.vid IN ($placeholders)", $this->corresponding_vids);
    while ($term = db_fetch_object($result)) {
      $titles[] = check_plain($term->title);
    }
    return $titles;
  }

  /**
   * Override the default behavior of query() to introduce the medial step of
   * retrieving vids from the provided nids.
   */
  function query() {
    $this->ensure_my_table();
    $this->break_phrase();

    // Do subselect to get relevant vids, then add where clause
    if ($this->subselect_vids()) {
      $placeholders = implode(', ', array_fill(0, count($this->corresponding_vids), '%d'));
      $this->query->add_where(0, "$this->table_alias.$this->nid_field IN ($placeholders)", $this->corresponding_vids);
    }
    else {
      $this->query->add_where(0, "$this->table_alias.$this->nid_field = %d", $this->corresponding_vids);
    }
  }

  /**
   * Set up the argument with the vids extracted from nids.
   *
   * Needs to be done here, because this is the earliest stage at which we can
   * guarantee the contents of $this->argument to be available.
   *
   * @param string $arg
   *   The argument, as delivered in the URL.
   */
  function set_argument($arg) {
    $this->argument = $arg;
    if ($this->validate_arg($arg)) {
      $this->break_phrase();
      $this->subselect_vids();
      return TRUE;
    }
    return FALSE;
  }

  function break_phrase() {
    if (!$this->phrase_broken) {
      // Handle multiple argument inputs
      if (!empty($this->options['break_phrase'])) {
        views_break_phrase($this->argument, $this);
      }
      else {
        $this->value = array($this->argument);
      }
      $this->phrase_broken = TRUE;
    }
  }

  /**
   * Helper method to retrieve the vid(s) the final view query should actually
   * be run against.
   *
   * Would be done in pre_query(), but $this->argument is not yet available at
   * that time. So, called from set_argument().
   */
  function subselect_vids() {
    $from = 'FROM {quiz_node_properties} qnp';
    $field = 'qnp.vid';
    if (count($this->value) > 1) {
      // Guaranteed to produce multiple values; therefore may need the group by
      $use_group_by = TRUE;

      $operator = empty($this->options['not']) ? 'IN' : 'NOT IN';
      $placeholders = implode(', ', array_fill(0, count($this->value), '%d'));
      $where = "WHERE qnp.nid $operator ($placeholders)";
    }
    else {
      // Multiple values only possible with a NOT; only then do we need group by
      $use_group_by = !empty($this->options['not']);

      $operator = empty($this->options['not']) ? '=' : '!=';
      $where = "WHERE qnp.nid $operator %d";
    }

    switch ($this->options['which_vid']) {
      case 'initial':
      case 'latest':
        // SQL operation for getting a single vids based on view config settings
        $operation = ($this->options['which_vid'] == 'initial' ? 'MIN' : 'MAX');
        // If we need a group by clause, tag it onto the end.
        $where .= ($use_group_by) ? ' GROUP BY qnp.nid' : '';

        $result = db_query("SELECT $operation(qnp.vid) AS vid $from $where", $this->value);
        break;

      case 'all':
        $result = db_query("SELECT qnp.vid AS vid $from $where", $this->value);
        break;
    }

    $this->nid_field = $this->vid_field;

    $this->corresponding_vids = array();
    while ($item = db_fetch_object($result)) {
      $this->corresponding_vids[] = $item->vid;
    }
    return (count($this->corresponding_vids) > 1);
  }
}
