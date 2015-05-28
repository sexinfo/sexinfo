<?php

/**
 * @file
 * Admin and OAuth callbacks for Google Analytics Reports API module.
 */

/**
 * Menu callback - admin form for OAuth and other settings.
 */
function google_analytics_reports_api_admin() {

  $form = array();
  $account = google_analytics_reports_api_new_gafeed();

  if ($account && $account->isAuthenticated()) {
    $webprops = $account->queryWebProperties()->results->items;
    $profiles = $account->queryProfiles()->results->items;
    $options = array();
    $profile_id = variable_get('google_analytics_reports_api_profile_id', 0);
    $set_default = FALSE;

    // Add optgroups for each web property.
    if(!empty($profiles)){
      foreach ($profiles as $profile) {
        $webprop = NULL;
        foreach ($webprops as $webprop_value) {
          if ($webprop_value->id == $profile->webPropertyId) {
            $webprop = $webprop_value;
            break;
          }
        }

        $options[$webprop->name][$profile->id] = theme('google_analytics_reports_api_profile_label', array('profile' => $profile));
        // Rough attempt to see if the current site is in the account list.
        if (empty($profile_id) && (parse_url($webprop->websiteUrl, PHP_URL_PATH) == $_SERVER['HTTP_HOST'])) {
          $profile_id = $profile->id;
          $set_default = TRUE;
        }
      }
    }
    
    // If no profile ID is set yet, set the first profile in the list.
    if (empty($profile_id)) {
      $profile_id = key($options[key($options)]);
      $set_default = TRUE;
    }

    if ($set_default) {
      variable_set('google_analytics_reports_api_profile_id', $profile_id);
    }

    $current_profile = NULL;

    // Load current profile object.
    foreach ($profiles as $profile) {
      if ($profile->id == $profile_id) {
        $current_profile = $profile;
        variable_set('google_analytics_reports_api_default_page', isset($current_profile->defaultPage) ? '/' . $current_profile->defaultPage : '/');
        break;
      }
    }

    $form['ga'] = array(
      '#type' => 'fieldset',
      '#title' => t('Settings'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );


    $form['ga']['google_analytics_reports_api_profile_id'] = array(
      '#type' => 'select',
      '#title' => t('Reports profile'),
      '#options' => $options,
      '#default_value' => $profile_id,
      '#description' => t("Choose your Google Analytics profile.  The currently active profile is: %profile", array(
        '%profile' => theme('google_analytics_reports_api_profile_label',
          array('profile' => $current_profile)),
      )),
    );

    if(!empty($options) || $options[0]){
      $form['ga']['google_analytics_reports_api_profile_id']['#required'] = TRUE;
    }

    // Default cache periods.
    $times = array();
    // 1-6 days.
    for ($days = 1; $days <= 6; $days++) {
      $times[] = $days * 60 * 60 * 24;
    }
    // 1-4 weeks.
    for ($weeks = 1; $weeks <= 4; $weeks++) {
      $times[] = $weeks * 60 * 60 * 24 * 7;
    }
    $form['ga']['google_analytics_reports_api_cache_length'] = array(
      '#type' => 'select',
      '#title' => t('Query cache'),
      '#description' => t('The <a href="!link">Google Analytics Quota Policy</a> restricts the number of queries made per day. This limits the creation of new reports on your site.  We recommend setting this cache option to at least three days.', array(
        '!link' => url('https://developers.google.com/analytics/devguides/reporting/core/v3/limits-quotas', array(
          'fragment' => 'core_reporting',
        )),
      )),
      '#options' => drupal_map_assoc($times, 'format_interval'),
      '#default_value' => variable_get('google_analytics_reports_api_cache_length', 259200),
      '#required' => TRUE,
    );
    $form['ga']['settings_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save settings'),
    );
    $form['revoke'] = array(
      '#type' => 'fieldset',
      '#title' => t('Revoke access and logout'),
      '#description' => t('Revoke your access token to Google Analytics. This action will log you out of your Google Analytics account and stop all reports from displaying on your site.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $form['revoke']['revoke_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Revoke access token'),
    );
  }
  // Else, there are no profiles, and we should just leave it at setup.
  else {
    $form['setup'] = array(
      '#type' => 'fieldset',
      '#title' => t('Initial setup'),
      '#description' => t("When you submit this form, you will be redirected to Google for authentication. Login with the account that has credentials to the Google Analytics profile you'd like to use."),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    $form['setup']['client_id'] = array(
     '#type' => 'textfield',
     '#title' => t('Client ID'),
     '#default_value' => variable_get('google_analytics_reports_client_id', ''),
     '#size' => 30,
     '#description' => t('Client ID created for the app in the access tab of the ') . l('Google API Console', 'http://code.google.com/apis/console', array('attributes' => array('target' => '_blank'))),
     '#weight' => -9,
    );

    $form['setup']['client_secret'] = array(
     '#type' => 'textfield',
     '#title' => t('Client Secret'),
     '#default_value' => variable_get('google_analytics_reports_client_secret', ''),
     '#size' => 30,
     '#description' => t('Client Secret created for the app in the Google API Console'),
     '#weight' => -8,
    );
    $form['setup']['setup_submit'] = array(
      '#type' => 'submit',
      '#value' => t('Start setup and authorize account'),
    );
  }
  return $form;
}

/**
 * Submit handler.
 *
 * Steps through the OAuth process, revokes tokens, saves profiles.
 */
function google_analytics_reports_api_admin_submit($form, &$form_state) {
  $op = isset($form_state['values']['op']) ? $form_state['values']['op'] : '';
  cache_clear_all('GoogleAnalyticsReportsApiFeed', 'cache', '*');
  switch ($op) {
    case t('Start setup and authorize account'):
      $client_id = $form_state['values']['client_id'];
      $client_secret = $form_state['values']['client_secret'];
      $redirect_uri = GoogleAnalyticsReportsApiFeed::currentUrl();
      variable_set('google_analytics_reports_client_id', $client_id);
      variable_set('google_analytics_reports_client_secret', $client_secret);
      variable_set('google_analytics_reports_redirect_uri', $redirect_uri);

      module_load_include('inc', 'google_analytics_api', 'GAFeed.lib'); 
      $GAFeed = new GoogleAnalyticsReportsApiFeed();
      $GAFeed -> beginAuthentication($client_id, $redirect_uri);
      break;

    case t('Save settings'):
      variable_set('google_analytics_reports_profile_id', $form_state['values']['google_analytics_reports_profile_id']);
      variable_set('google_analytics_reports_cache_length', $form_state['values']['google_analytics_reports_cache_length']);
      drupal_set_message(t('Settings have been saved successfully.'));
      break;

    case t('Revoke access token'):
      google_analytics_reports_api_revoke();
      drupal_set_message(t('Access token has been successfully revoked.'));
      break;
  }
}