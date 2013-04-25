<?php

/*
 * @file
 * Handles field user quiz state.
 */
class quiz_views_handler_field_user_quiz_state extends views_handler_field {

  function construct() {
    parent::construct();
    // TODO: what to do with $this->real_field in this situation?
    // $this->real_field = time() . 'BETWEEN '
  }
  // Note that the native field is time_start, and time_end is added as an
  // additional field in the handler declaration
  function query() {
    $this->ensure_my_table();
<<<<<<< HEAD
    $this->query->add_field($this->table_alias, "time_end", 'is_finished');
=======
    $this->query->add_field($this->table_alias, "time_end > 0", 'is_finished');
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    // $this->query->add_field($this->table_alias, time() . " BETWEEN $this->table_alias.time_start AND $this->table_alias.time_end", $this->field_alias);
  }

  function render($values) {
<<<<<<< HEAD
    if ($values->is_finished > 0) {
=======
    if (is_null($values->is_finished)) {
      return t('Not Started');
    }
    elseif (!empty($values->is_finished)) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
      return t('Finished');
    }
    else {
      return t('In Progress');
    }
  }
}
