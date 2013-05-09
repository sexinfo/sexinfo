<?php
/**
 * @file
 * Rules integration for metatags_quick
 */

/**
 * Implements hook_rules_action_info().
 *
 * Use this rule to add meta tags on non entity pages.
 */
function metatags_quick_rules_action_info() {
  $actions = array();

  $actions['metatags_quick_add_meta_tag'] = array(
    'label' => t('Set a meta tag on the page'),
    'parameter' => array(
      'name' => array(
        'type' => 'text',
        'label' => t('Meta name'),
        'optional' => FALSE,
      ),
      'value' => array(
        'type' => 'text',
        'label' => t('Meta value'),
        'optional' => FALSE,
      ),
    ),
    'group' => t('Metatags'),
  );
  return $actions;
}

/**
 * Rules integraiton function for _metatags_quick_add_head().
 *
 * @see _metatags_quick_add_head()
 *
 * @param string $name
 * @param string $value
 */
function metatags_quick_add_meta_tag($name, $value) {
  _metatags_quick_add_head(array('name' => $name, 'content' => $value));
}