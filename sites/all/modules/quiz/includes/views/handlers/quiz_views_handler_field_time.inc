<?php
/**
 * @file A handler to provide proper displays for dates.
 *
 * @ingroup views_field_handlers
 */
class quiz_views_handler_field_time extends views_handler_field {
  function render($values) {
    $value = $values->{$this->field_alias};
    return _quiz_format_duration($value);
  }
<<<<<<< HEAD
}
=======
}
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
