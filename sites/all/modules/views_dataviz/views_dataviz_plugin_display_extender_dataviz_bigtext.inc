<?php
class views_dataviz_plugin_display_extender_dataviz_bigtext extends views_plugin_display_extender {
  function options_definition_alter(&$options) {
    $options['dataviz_load_bigtext'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  /**
   * Load the jQuery plugin
   */
  function pre_execute() {
    // Call the parent setup function so we do not lose data.
    parent::pre_execute();
    if (isset($this->display->options['dataviz_load_bigtext']) && $this->display->options['dataviz_load_bigtext']) {
      drupal_add_js(drupal_get_path('module', 'views_dataviz') . '/js/dataviz-bigtext.js');
    }
  }

  /**
   * Provide the form
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    if ($form_state['section'] == 'dataviz_load_bigtext') {
      $form['#title'] .= t('Load Dataviz BigText');
      $form['description'] = array(
        '#markup' => '<div class="description form-item">' . t('If set, this display will load the Dataviz BigText jQuery plugin. You can invoke this plugin by adding the bigtext-apply class to a row, block, view, list, table field or any HTML element.') . '</div>',
      );
      $form['dataviz_load_bigtext'] = array(
        '#type' => 'radios',
        '#options' => array(1 => t('Yes'), 0 => t('No')),
        '#default_value' => $this->display->get_option('dataviz_load_bigtext') ? 1 : 0,
      );
    }
  }

  /**
   * Save option to display
   */
  function options_submit(&$form, &$form_state) {
    parent::options_submit($form, $form_state);
    $this->display->set_option('dataviz_load_bigtext', $form_state['values']['dataviz_load_bigtext']);
  }

  /**
   * Summary
   */
  function options_summary(&$categories, &$options) {
    parent::options_summary($categories, $options);
    $options['dataviz_load_bigtext'] = array(
      'category' => 'other',
      'title' => t('Load Dataviz BigText'),
      'value' => $this->display->get_option('dataviz_load_bigtext') ? t('Yes') : t('No'),
      'desc' => t('Load jQuery file for BigText support.'),
    );
  }
}
