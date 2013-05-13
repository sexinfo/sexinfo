<?php
/**
 * @file
 *   Implements migrate api for metatags_quick field.
 */

/**
 * Migrate field handler for metatags_quick field.
 */
class MigrateMetatagsquickFieldHandler extends MigrateFieldHandler {
  public function __construct() {
    $this->registerTypes(array('metatags_quick'));
  }

  public function prepare(stdClass $entity, array $field_info, array $instance, array $values) {
    $arguments = array();
    if (isset($values['arguments'])) {
      $arguments = array_filter($values['arguments']);
      unset($values['arguments']);
    }
    $language = $this->getFieldLanguage($entity, $field_info, $arguments);

    // Setup the standard Field API array for saving.
    $delta = 0;
    foreach ($values as $value) {
      $return[$language][$delta] = array(
        'metatags_quick' => $value,
      );
      $delta++;
    }

    return isset($return) ? $return : NULL;
  }
}