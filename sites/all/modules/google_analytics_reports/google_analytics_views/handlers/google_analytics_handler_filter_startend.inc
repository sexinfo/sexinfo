<?php

/**
 * Filter to handle Google Analytics API start/end dates.
 *
 * @ingroup views_filter_handlers
 */
class google_analytics_handler_filter_startend extends views_handler_filter_date {
  function op_between($field) {
    $a = REQUEST_TIME + intval(strtotime($this->value['min'], 0));
    $b = REQUEST_TIME + intval(strtotime($this->value['max'], 0));
    $operator = strtoupper($this->operator);
    $this->query->add_where($this->options['group'], 'end_date', $a);
    $this->query->add_where($this->options['group'], 'start_date', $b);
  }

  function op_simple($field) {
    $value = REQUEST_TIME + intval(strtotime($this->value['value'], 0));
    $this->query->add_where($this->options['group'], 'start_date', $value);
    $this->query->add_where($this->options['group'], 'end_date', $value);
  }
}
