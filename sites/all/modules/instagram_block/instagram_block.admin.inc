<?php

/**
 * @file
 * Contains forms and pages for instagram admin pages.
 */

/**
 * Form for authenticating user with Instagram API.
 */
function instagram_block_admin_settings($form, &$form_state) {
  $form = array();

  $content = t('To configure your instagram account you need to authorise your account. To do this, click ');
  $path = 'https://instagram.com/oauth/authorize/';
  $options = array(
    'query' => array(
      'client_id' => '759ec610e0c1416baa8a8a6b41552087',
      'redirect_uri' => 'http://instagram.yanniboi.com/configure/instagram',
      'response_type' => 'code',
    ),
    'attributes' => array(
      'target' => '_blank',
    ),
  );

  $content .= l(t('here'), $path, $options);
  $content .= '.';

  $form['authorise'] = array(
    '#markup' => $content,
  );

  // Create a keyed array of blank defaults for the storage variable.
  $empty = array(
    'user_id' => '',
    'access_token' => '',
  );

  // Store data from variable in $form for now.
  $form['#data'] = variable_get('instagram_block_admin_settings', $empty);

  $form['user_id'] = array(
    '#type' => 'textfield',
    '#title' => t('User Id'),
    '#description' => t('Your unique Instagram user id. Eg. 460786510'),
    '#default_value' => isset($form['#data']['user_id']) ? $form['#data']['user_id'] : '',
  );

  $form['access_token'] = array(
    '#type' => 'textfield',
    '#title' => t('Access Token'),
    '#description' => t('Your Instagram access token. Eg. 460786509.ab103e5.a54b6834494643588d4217ee986384a8'),
    '#default_value' => isset($form['#data']['access_token']) ? $form['#data']['access_token'] : '',
  );

  $form['#validate'][] = 'instagram_block_admin_settings_validate';
  $form['#submit'][] = 'instagram_block_admin_settings_submit';

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}


/**
 * Form validation handler.
 */
function instagram_block_admin_settings_validate(&$form, &$form_state) {
  // @todo: Do some validation.
}

/**
 * Form submission handler.
 */
function instagram_block_admin_settings_submit(&$form, &$form_state) {
  if (isset($form_state['values'])) {
    // Strip any spaces that may have resulted from a copy/paste of tokens
    $form_state['values']['access_token'] = trim($form_state['values']['access_token']);
    $form_state['values']['user_id'] = trim($form_state['values']['user_id']);

    variable_set('instagram_block_admin_settings', $form_state['values']);
    drupal_set_message(t('Instagram block admin configuration has been saved.'), 'status');
  }
}
