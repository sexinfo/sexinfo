<?php

/**
 * Basic argument handler to implement string arguments that may have length
 * limits.
 *
 * @ingroup views_argument_handlers
 */
class google_analytics_handler_argument_string extends views_handler_argument {
  function init(&$view, &$options) {
    parent::init($view, $options);
  }

  /**
   * Build the query 
   */
  function query($group_by = FALSE) {
    $this->operator = '=@';
    $this->query->add_where(1, $this->real_field, $this->argument, $this->operator);
  }

}
