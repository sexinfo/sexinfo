<?php
/**
 * @file
 * Admin functions.
 */

/**
 * Generates the settings form.
 */
function taxonomy_tooltip_settings_form($form, &$form_state) {
  $vocabs = array();

  foreach (taxonomy_get_vocabularies() as $vocab) {
    $vocabs[$vocab->vid] = $vocab->name;
  }

  $form['taxonomy_tooltip_vocabs'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Enabled vocabularies'),
    '#description' => t('Select the vocabularies which you want to use with the module.'),
    '#options' => $vocabs,
    '#default_value' => variable_get('taxonomy_tooltip_vocabs', array()),
  );

  return system_settings_form($form);
}
