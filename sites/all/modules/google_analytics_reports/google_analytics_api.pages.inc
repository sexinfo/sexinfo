<?php

/**
 * @file
 * Admin and OAuth callbacks.
 */

/**
 * Menu callback - admin form for OAuth and other settings.
 */
function google_analytics_api_admin() {

  $form = array();
  $account = google_analytics_api_account_data();

  /* If there is at least one profile */
  if (!empty($account->results)) {
    $options = array();
    $profile_id = variable_get('google_analytics_reports_profile_id', 0);
    $set_default = FALSE;

    foreach ($account->results as $profile_key => $profile) {
      $options[$profile_key] = theme('google_analytics_api_profile_label', $profile);
      /* Rough attempt to see if the current site is in the account list */
      if (empty($profile_id) && (parse_url($profile['title'], PHP_URL_PATH) == $_SERVER['HTTP_HOST'])) {
        $profile_id = $profile_key;
        $set_default = TRUE;
      }
    }

    /* If no profile ID is set yet, set the first profile in the list */
    if (empty($profile_id)) {
      $profile_id = key($options);
      $set_default = TRUE;
    }

    if ($set_default) {
      variable_set('google_analytics_reports_profile_id', $profile_id);
    }

    $form['ga'] = array(
      '#type' => 'fieldset',
      '#title' => t('Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#weight' => 1,
    );
    $form['ga']['google_analytics_reports_profile_id'] = array(
      '#type' => 'select',
      '#title' => t('Reports profile'),
      '#options' => $options,
      '#default_value' => $profile_id,
      '#description' => t("Choose your Google Analytics profile.  The currently active profile is: %profile", array('%profile' => theme('google_analytics_api_profile_label', $account->results[$profile_id]))),
      '#required' => TRUE,
    );
    /* Seven days of cache options */
    $times = array();
    for ($days = 1; $days <= 6; $days++) {
      $times[] = $days * GOOGLE_ANALYTICS_REPORTS_DAY;
    }
    for ($weeks = 1; $weeks <= 4; $weeks++) {
      $times[] = $weeks * GOOGLE_ANALYTICS_REPORTS_WEEK;
    }
    $form['ga']['google_analytics_reports_cache_length'] = array(
      '#type' => 'select',
      '#title' => t('Query cache'),
      '#description' => t('The <a href="!link">Google Analytics Quota Policy</a> restricts the number of queries made per day.  This limits the creation of new reports on your site.  We recommend setting this cache option to at least three days.', array('!link' => url('http://code.google.com/apis/analytics/docs/gdata/gdataDeveloperGuide.html', array('absolute' => TRUE, 'fragment' => 'quota')))),
      '#options' => drupal_map_assoc($times, 'format_interval'),
      '#default_value' => variable_get('google_analytics_reports_cache_length', 259200),
      '#required' => TRUE,
    );
    $form['ga']['settings_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save settings'),
    );
    $form['revoke'] = array(
      '#type' => 'fieldset',
      '#title' => t('Revoke access and logout'),
      '#description' => t('Revoke your access token to Google Analytics.  This action will log you out of your Google Analytics account and stop all reports from displaying on your site.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#weight' => 5,
    );
    $form['revoke']['revoke_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Revoke access token'),
    );
  }
  /* Else, there are no profiles, and we should just leave it at setup */
  else {
    $form['setup'] = array(
      '#type' => 'fieldset',
      '#title' => t('Initial setup'),
      '#description' => t("When you submit this form, you will be redirected to Google for authentication.  Login with the account that has credentials to the Google Analytics profile you'd like to use."),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    $form['setup']['google_analytics_reports_hd'] = array(
      '#type' => 'textfield',
      '#title' => t('Google Apps for Business Domain (optional)'),
      '#description' => t('Provide the domain name (example.com) if your domain is registered with Google Apps for Business.  Otherwise, leave blank.'),
      '#default_value' => variable_get('google_analytics_reports_hd', ''),
    );
   $form['setup']['setup_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Start setup and authorize account'),
    );
  }
  return $form;
}

/**
 * Submit handler.  Steps throuh the OAuth process, revokes tokens, saves profiles.
 */
function google_analytics_api_admin_submit($form, &$form_state) {
  $op = isset($form_state['values']['op']) ? $form_state['values']['op'] : '';
  cache_clear_all('GAFeed', 'cache', '*');
  switch ($op) {
    case t('Start setup and authorize account'):
        variable_set('google_analytics_reports_hd', $form_state['values']['google_analytics_reports_hd']);
        /* Anonymous keys are a Google default */
        $key = variable_get('google_analytics_reports_consumer_key', 'anonymous');
        $secret = variable_get('google_analytics_reports_consumer_secret', 'anonymous');
        module_load_include('inc', 'google_analytics_api', 'GAFeed.lib');
        $GAFeed = new GAFeed($key, $secret);

        /* Step #1 of OAuth */
        $token = $GAFeed->getRequestToken();
        $_SESSION['google_analytics_reports_oauth']['token'] = $token;
        $_SESSION['google_analytics_reports_oauth']['destination'] = $_GET['q'];

        /* Step #2 of OAuth */
        $GAFeed->obtainAuthorization($token);
      break;
    case t('Save settings'):
        variable_set('google_analytics_reports_profile_id', $form_state['values']['google_analytics_reports_profile_id']);
        variable_set('google_analytics_reports_cache_length', $form_state['values']['google_analytics_reports_cache_length']);
        drupal_set_message(t('Settings have been saved successfully.'));
      break;
    case t('Revoke access token'):
        google_analytics_api_revoke();
        drupal_set_message(t('Access token has been successfully revoked.'));
      break;
  }
}

/**
 * Page callback - Provided for Google to call back during the OAuth process.
 */
function google_analytics_reports_oauth_callback() {
  $key = variable_get('google_analytics_reports_consumer_key', 'anonymous');
  $secret = variable_get('google_analytics_reports_consumer_secret', 'anonymous');

  $session_data = $_SESSION['google_analytics_reports_oauth'];
  unset($_SESSION['google_analytics_reports_oauth']);

  $token = $session_data['token'];
  if (!is_array($token) || !$key || !$secret) {
    drupal_set_message(t('Invalid Google Analytics OAuth request'), 'error');
    return ' ';
  }

  if ($token['oauth_token'] != $_GET['oauth_token']) {
    drupal_set_message(t('Invalid OAuth token.'), 'error');
    return ' ';
  }

  module_load_include('inc', 'google_analytics_api', 'GAFeed.lib');
  $GAFeed = new GAFeed($key, $secret, $token['oauth_token'], $token['oauth_token_secret']);

  /* Google required the verifier */
  $GAFeed->setVerifier($_GET['oauth_verifier']);
  $response = $GAFeed->getAccessToken();

  variable_set('google_analytics_reports_oauth_token', $response['oauth_token']);
  variable_set('google_analytics_reports_oauth_token_secret', $response['oauth_token_secret']);

  drupal_goto('admin/config/system/google-analytics-reports');
}