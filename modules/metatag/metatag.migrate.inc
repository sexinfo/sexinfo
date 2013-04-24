<?php

/**
 * @file
 * Metatag support for migrate.
 */

/**
 * Implements hook_migrate_api().
 */
function metatag_migrate_api() {
  $api = array(
    'api' => 2,
    'destination handlers' => array(
      'MigrateMetatagHandler',
    ),
  );

  return $api;
}

/**
 * Metatags destination handler.
 */
class MigrateMetatagHandler extends MigrateDestinationHandler {

  public function __construct() {
    $types = array();
    foreach (entity_get_info() as $entity_type => $entity_info) {
      if (isset($entity_info['metatags']) && $entity_info['metatags']) {
        $types[] = $entity_type;
      }
    }

    $this->registerTypes($types);
  }

  /**
   * Implements MigrateDestinationHandler::fields().
   */
  public function fields() {
    $fields = array();
    $elements = metatag_get_info();

    foreach ($elements['tags'] as $value) {
      $metatag_field = 'metatag_' . $value['name'];
      $fields[$metatag_field] = $value['description'];
    }

    return $fields;
  }

  /**
   * Implements MigrateDestinationHandler::prepare().
   */
  public function prepare($entity, stdClass $row) {
    $elements = metatag_get_info();

    foreach ($elements['tags'] as $value) {
      $metatag_field = 'metatag_' . $value['name'];
      if (isset($entity->$metatag_field)) {
        $entity->metatags[$value['name']]['value'] = $entity->$metatag_field;
        unset($entity->$metatag_field);
      }
    }
  }
}
