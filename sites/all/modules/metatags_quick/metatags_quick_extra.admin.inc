<?php
/**
 * Optional automatic settings configuration
 * 
 * @author valthebald <http://drupal.org/user/239562>
 */

/**
 * Admin page for inputting auto-settings for meta tags
 * @param $form
 * @param $form_state
 */

function metatags_quick_admin_auto_settings($form,$form_state) {
  $current_settings = variable_get('metatags_quick_auto_settings', '');
  
  // construct the options array for all content types
  $type_names = node_type_get_names();
  
  // facility to select content types for auto-tagging. Has AJAX behavior.
  $form['applicable_content_types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Applicable content types'),
    '#options' => $type_names,
    '#default_value' => isset($current_settings['applicable_content_types'])?$current_settings['applicable_content_types']:array(),
    '#description' => t('Select the content types for which auto settings should be applied'),
    '#ajax' => array(
      'callback' => 'metatags_quick_extra_ajax_callback',
      'wrapper' => 'metatags-quick-auto-default-keywords',
      'method' => 'replace'
    )  
  );
  
  // Common keywords that would be applied for all chosen content types 
  $form['global_keywords'] = array(
    '#type' => 'textfield',
    '#title' => t('Global keywords'),
    '#maxlength' => variable_get('metatags_quick_default_field_length', 255),
    '#default_value' => isset($current_settings['global_keywords'])?$current_settings['global_keywords']:'',
    '#description' => t('Enter a comma separated list of keywords. Global keywords will be always added to applicable content types.')
  );
  
  // Content-type-wise special keywords. Only those content types chosen for auto-tagging would be displayed
  $form['default_keywords'] = array(
    '#type' => 'fieldset',
    '#title' => t('Default keywords'),
    '#collapsible' => true,
    '#description' => t('Enter a comma separated list of keywords for the content types below, if you want them to be included automatifcally by default.'),
    '#tree' => true,
    '#prefix' => '<div id="metatags-quick-auto-default-keywords">',
    '#suffix' => '</div>'
  );
  
  $applicable_content_types = array();
  $available_content_types = array();
  
  if (!empty($form_state['values']['applicable_content_types'])) {
    $applicable_content_types = array_values($form_state['values']['applicable_content_types']);
  }
  elseif (isset($current_settings['applicable_content_types'])) {    
    $applicable_content_types = array_values($current_settings['applicable_content_types']);
  }  
  
  foreach ($type_names as $machine_name => $type_name) {
    $exists = in_array($machine_name, $applicable_content_types, true); 
    if ($exists) {
      $available_content_types[$machine_name] = $type_name;
    }
  }
  
  foreach ($available_content_types as $machine_name => $type_name) {
    if (isset($current_settings['default_keywords']) && isset($current_settings['default_keywords'][$machine_name])) {
      $default_value = $current_settings['default_keywords'][$machine_name];
    }
    else {
      $default_value = '';
    }
    
    $form['default_keywords'][$machine_name] = array(
      '#type' => 'textfield',
      '#title' => $type_name,
      '#maxlength' => variable_get('metatags_quick_default_field_length', 255),
      '#default_value' => $default_value
    );  
  }
  
  
  // taxonomy vocabularies that would supply keywords
  $vocabularies = taxonomy_vocabulary_get_names();
  $options = array();
  foreach ($vocabularies as $machine_name => $vocabulary) {
    $options[$machine_name] = $vocabulary->name;
  }
  
  $form['auto_keywords_vocabularies'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Auto-keywords vocabularies'),
    '#options' => $options,
    '#default_value' => isset($current_settings['auto_keywords_vocabularies'])?$current_settings['auto_keywords_vocabularies']:array(),
    '#description' => t('Select the vocabularies which contain terms you want to add to the keywords meta tag for nodes. The terms of these vocabularies are added before the global and default keywords but after the page-specific keywords.')
  );
  
  
  // auto settings for meta description
  $form['meta_description'] = array(
    '#type' => 'fieldset',
    '#title' => t('Meta Description'),
    '#collapsible' => true,
    '#description' => t('These options specify how meta description is to be generated from the node content.'),
    '#tree' => true    
  );  
  
  if (isset($current_settings['meta_description']) && isset($current_settings['meta_description']['auto_generate'])) {
    $default_value = $current_settings['meta_description']['auto_generate'];
  }
  else {
    $default_value = '';
  }
  
  // Is auto-generation needed?
  $form['meta_description']['auto_generate'] = array(
    '#type' => 'checkbox',
    '#title' => t('Auto generate meta description'),
    '#default_value' => $default_value
  );
  
  // if yes, from where?
  if (isset($current_settings['meta_description']) && isset($current_settings['meta_description']['source'])) {
    $default_value = $current_settings['meta_description']['source'];
  }
  else {
    $default_value = '';
  }
  
  $options = array(
    'body' => t('Generate meta tags content from the node body'),
    'teaser' => t('Generate meta tags content from the node teaser'),
    'either' => t('Generate meta tags content from the node teaser, or the node body when the node teaser is empty')
  );
  
  $form['meta_description']['source'] = array(
    '#type' => 'radios',
    '#title' => t('Generation source'),
    '#options' => $options,
    '#default_value' => $default_value
  );
  
  $form['op'] = array(
    '#value' => t('Submit'),
    '#type' => 'submit', 
  );
  return $form;
}


/***
 * Submit function for the admin settings form.
 */

function metatags_quick_admin_auto_settings_submit($form,$form_state) {
  $new_settings = array(
    'applicable_content_types' => $form_state['values']['applicable_content_types'],
    'global_keywords' => $form_state['values']['global_keywords'],
    'auto_keywords_vocabularies' => $form_state['values']['auto_keywords_vocabularies']
  );
  
  foreach ($form_state['values']['default_keywords'] as $machine_name => $value) {
    $new_settings['default_keywords'][$machine_name] = $value;
  }
  
  foreach ($form_state['values']['meta_description'] as $key => $value) {
    $new_settings['meta_description'][$key] = $value;
  }  
  
  variable_set('metatags_quick_auto_settings', $new_settings);
  drupal_set_message(t('Auto settings saved for Meta tags (quick)'), 'status');   
}

/**
 * Auto Settings form - AJAX Callback to display/remove textfields for entering content-type-wise keywords
 * @param $form
 * @param $form_state
 */
function metatags_quick_ajax_callback($form,$form_state) {
  return $form['default_keywords'];
}
