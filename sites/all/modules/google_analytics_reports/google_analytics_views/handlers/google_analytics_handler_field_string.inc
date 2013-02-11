<?php
/**
 * Render a field as a string value
 *
 * @ingroup views_field_handlers
 */
class google_analytics_handler_field_string extends views_handler_field {
  function option_definition() {
    $options = parent::option_definition();
    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
  }

}
