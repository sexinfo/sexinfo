<?php
// $Id$

/**
 * @file
 * The functions used to configure the module over the admin interface.
 *
 */

/**
 * Implements hook_perm().
 *
 * @return (array) permissions
 */
function seo_checker_permission() {
    return array(
      'administer seo_checker configuration' => array(
        'title' => t('Administer SEO Checker Configuration'),
        'description' => t('Gives access to the configuration panel of the SEO Checker module.'),
      ),
      'skip seo checks' => array(
        'title' => t('Skip SEO checks'),
        'description' => t('Checks will be skipped completely for users with this permission.'),
      ),
      'allow seo check failures' => array(
        'title' => t('Allow check failures'),
        'description' => t('Lets publishers publish their content even if they fail some tests.'),
      ),
    );
}


/**
 * Builds the settings form for the SEO checker using system_settings_form()
 * @return (array) settings form
 */
function seo_checker_settings() {
  /* load the required js and css files */
  _seo_checker_load_slider();

  /* get the rules and create the fieldset with the sliders */
  $rules = module_invoke_all('register_seo_rules');
  $form = array();

  $form['general'] = array(
    '#type' => 'fieldset',
    '#title' => t('General settings.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['general']['seo_checker_allow_failures'] = array(
    '#type' => 'radios',
    '#title' => t('Check result display settings'),
    '#default_value' => variable_get('seo_checker_allow_failures', 'show-preview-only'),
    '#options' => array(
      'show-preview-only' => t("Only show the check results on node previews."),
      'show-always' => t("Show the results on node previews AND when nodes are saved."),
    ),
  );

  $form['thresholds'] = array(
    '#type' => 'fieldset',
    '#title' => t('Thresholds for the SEO rules.'),
    '#collapsible' => TRUE,
    '#description' => t('For the following rules, set the threshold in % where the test should be considered as passed. Depending on the type of the rule, you can either choose 0 or 100 sometimes a value in between. <b>A threshold of 0% disables a test.</b> Results of disabled tests will not be displayed.'),
  );
  foreach ($rules as $rid => $rule) {
    $form['thresholds']['seo_threshold_' . $rid] = array(
      '#type' => 'seo_slider_' . $rule['threshold type'],
      '#title' => check_plain($rule['name']),
      '#default' => seo_checker_get_rule_threshold($rule, $rid),
      '#description' => check_plain($rule['description']),
    );
    if ($rule['threshold type'] == 'bool') {
      $form['thresholds']['seo_threshold_' . $rid]['#steps'] = 1;
    }
  }

  /* content types */
  $form['content_types'] = array(
    '#type' => 'fieldset',
    '#title' => t('SEO Checker per Content Type'),
    '#collapsible' => TRUE,
    '#description' => t('Enable the SEO Checker for at least one content type. Otherwise you will not see any effect.'),
  );
  foreach (_node_types_build()->types as $type) {
    $form['content_types']['seo_checker_' . $type->type] = array(
      '#type' => 'checkbox',
      '#title' => t($type->name),
      '#default_value' => variable_get('seo_checker_' . $type->type, 0),
    );
  }

  return system_settings_form($form);
}


/**
 * The slider requires some java scripts and css to be loaded
 */
function _seo_checker_load_slider() {
  drupal_add_library('system', 'ui.slider');
  drupal_add_js(drupal_get_path('module', 'seo_checker') . '/js/slider.js');
  drupal_add_css(drupal_get_path('module', 'seo_checker') . '/css/slider.css');
}