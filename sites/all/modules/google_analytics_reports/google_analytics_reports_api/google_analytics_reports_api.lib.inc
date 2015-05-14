<?php

/**
 * @file
 * Provides the Google Analytics Reports API Feed object type
 * and associated methods.
 */

/**
 * GoogleAnalyticsReportsApiFeed class to authorize access to and request data
 * from the Google Analytics Core Reporting API.
 */
class GoogleAnalyticsReportsApiFeed {

  const OAUTH2_REVOKE_URI = 'https://accounts.google.com/o/oauth2/revoke';
  const OAUTH2_TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';
  const OAUTH2_AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
  const SCOPE = 'https://www.googleapis.com/auth/analytics.readonly https://www.google.com/analytics/feeds/';

  // Response object.
  public $response;

  // Formatted array of request results.
  public $results;

  // URL to Google Analytics Core Reporting API.
  public $queryPath;

  // Translated error message.
  public $error;

  // Boolean TRUE if data is from the cache tables.
  public $fromCache = FALSE;

  // OAuth access token.
  public $access_token;

  // OAuth refresh token.
  public $refresh_token;

  // OAuth expiration time.
  public $expires_at;

  // Host and endpoint of Google Analytics API.
  protected $host = 'www.googleapis.com/analytics/v3';

  // Request header source.
  protected $source = 'drupal';

  // Google authorize callback verifier string.
  protected $verifier;

  // OAuth host.
  protected $oAuthHost = 'www.google.com';

  /**
   * Check if object is authenticated with Google.
   */
  public function isAuthenticated() {
    return !empty($this->access_token);
  }

  public function __construct($token = NULL) {
    $this->access_token = $token;
  }

  /**
   * Get the current page url
   *
   * @return String
   */
  public static function currentUrl() {
    $https = $_SERVER['HTTPS'] == 'on';
    $url = $https ? 'https://' : 'http://';
    $url .= $_SERVER['SERVER_NAME'];
    if ((!$https && $_SERVER['SERVER_PORT'] != '80') ||
      ($https && $_SERVER['SERVER_PORT'] != '443')) {
      $url .= ':' . $_SERVER['SERVER_PORT'];
     }

    return $url . $_SERVER['REQUEST_URI'];
  }

  /**
   * Create a URL to obtain user authorization.
   * The authorization endpoint allows the user to first
   * authenticate, and then grant/deny the access request.
   * @param string $client_id
   * @return string
   */
  public function createAuthUrl($client_id, $redirect_uri) {
    $params = array(
      'response_type=code',
      'redirect_uri=' . $redirect_uri,
      'client_id=' . urlencode($client_id),
      'scope=' . self::SCOPE,
      'access_type=offline',
      'approval_prompt=force' 
    );

    $params = implode('&', $params);
    return self::OAUTH2_AUTH_URL . "?$params";
  }
 
 /**
  * Authenticate with the Google API
  *
  * @param String $client_id
  * @param String $client_secret
  * @param String $refresh_token
  * @return GAFeed
  */
  protected function fetchToken($client_id, $client_secret, $redirect_uri, $refresh_token=NULL) {
    if ($refresh_token) {
     $params = array(
        'client_id=' . $client_id,
        'client_secret=' . $client_secret,
        'refresh_token=' . $refresh_token,
        'grant_type=refresh_token',
      );
    }
    else {
      $params = array(
        'code=' . $_GET['code'],
        'grant_type=authorization_code',
        'redirect_uri=' . $redirect_uri,
        'client_id=' . $client_id,
        'client_secret=' . $client_secret,
      );
    }
     
  $data = implode('&', $params);

  $this->response = drupal_http_request(self::OAUTH2_TOKEN_URI, array('headers' => array('Content-Type' => 'application/x-www-form-urlencoded'), 'method' => 'POST', 'data' => $data));

  if (substr($this->response->code, 0, 1) == '2') {
    $decoded_response = json_decode($this->response->data, true);
    $this->access_token = $decoded_response['access_token'];
    $this->expires_at = time() + $decoded_response['expires_in'];
    if (!$refresh_token) {
      $this->refresh_token = $decoded_response['refresh_token'];
    }
  } 
  else {
    $error_msg = 'Code: !code - Error: !message - Message: !details';
    $error_vars = array('!code' => $this->response->code, '!message' => $this->response->error, '!details' => strip_tags($this->response->data));
    $this->error =  t($error_msg, $error_vars);
    watchdog('google analytics reports', $error_msg, $error_vars, WATCHDOG_ERROR);
  }
}

  /**
   * Complete the authentication process.
   * We got here after being redirected from a successful authorization grant.
   * Fetch the access token
   *
   * @param String $client_id
   * @param String $client_secret
   */
  public function finishAuthentication($client_id, $client_secret, $redirect_uri) {
    $this->fetchToken($client_id, $client_secret, $redirect_uri);
  }

  /**
   * Begin authentication by allowing the user to grant/deny access to the Google account
   *
   * @param String $client_id
   */
  public function beginAuthentication($client_id, $redirect_uri) {
    drupal_goto($this->createAuthUrl($client_id, $redirect_uri));
  }

  /**
   * Fetches a fresh access token with the given refresh token.
   * @param String $client_id
   * @param String $client_secret
   * @param string $refresh_token
   */
  public function refreshToken($client_id, $client_secret, $refresh_token) {
    $this->refresh_token = $refresh_token;
    $this->fetchToken($client_id, $client_secret, '', $refresh_token);
  }

  /**
   * OAuth step #1: Fetch request token.
   * Revoke an OAuth2 access token or refresh token. This method will revoke the current access
   * token, if a token isn't provided.
   * @param string|NULL $token The token (access token or a refresh token) that should be revoked.
   * @return boolean Returns True if the revocation was successful, otherwise False.
   */
  public function revokeToken($token = NULL) {
    if (!$token) {
      $token = $this->refresh_token ? $this->refresh_token : $this->access_token;
    }

    $this->response = drupal_http_request(self::OAUTH2_REVOKE_URI, array('headers' => array('Content-Type' => 'application/x-www-form-urlencoded'), 'method' => 'POST', 'data' => "token=$token"));

    if ($this->response->code == 200) {
      $this->access_token = NULL;
      return true;
    }

    return false;
  }

  /**
   * OAuth step #2: Authorize request token.
   * Generate authorization token header for all requests
   *
   * @return Array
   */
  public function generateAuthHeader($token=NULL) {
    if ($token == NULL) {
      $token = $this->access_token;
     }
    return array('Authorization' => 'Bearer ' . $token);
  }

  /**
   * OAuth step #3: Fetch access token.
   * Set the verifier property
   */
  public function setVerifier($verifier) {
    $this->verifier = $verifier;
  }

  /**
   * Set the host property.
   */
  public function setHost($host) {
    $this->host = $host;
  }

  /**
   * Set the queryPath property.
   */
  protected function setQueryPath($path) {
    $this->queryPath = 'https://' . $this->host . '/' . $path;
  }

  /**
   * Public query method for all Core Reporting API features.
   */
  public function query($url, $params = array(), $method = 'GET', $headers, $cache_options = array()) {
    $params_defaults = array(
      'start-index' => 1,
      'max-results' => 1000,
    );
    $params += $params_defaults;

    // Provide cache defaults if a developer did not override them.
    $cache_defaults = array(
      'cid' => NULL,
      'expire' => google_analytics_reports_api_cache_time(),
      'refresh' => FALSE,
    );
    $cache_options += $cache_defaults;

    // Provide a query MD5 for the cid if the developer did not provide one.
    if (empty($cache_options['cid'])) {
      $cache_options['cid'] = 'GoogleAnalyticsReportsApiFeed:' . md5(serialize(array_merge($params, array($url, $method))));
    }

    $cache = FALSE;

    if (!$cache_options['refresh'] && isset($cache) && !empty($cache->data)) {
      $this->response = $cache->data;
      $this->results = json_decode($this->response->data);
      $this->fromCache = TRUE;
    }
    else {
      $this->request($url, $params, $headers);
    }

    if (empty($this->error)) {
      cache_set($cache_options['cid'], $this->response, 'cache', $cache_options['expire']);
    }

    return (empty($this->error));
  }

  /**
   * Execute a query.
   */
  protected function request($url, $params = array(), $headers = array(), $method = 'GET') {
    $options = array(
      'method' => $method,
      'headers' => $headers,
    );

    if (count($params) > 0) {
      if ($method == 'GET') {
        $url .= '?' . drupal_http_build_query($params);
      }
      else {
        $options['data'] = drupal_http_build_query($params);
      }
    }

    $this->response = drupal_http_request($url, $options);

    if ($this->response->code == '200') {
      $this->results = json_decode($this->response->data);
    }
    else {
      // Data is undefined if the connection failed.
      if (!isset($this->response->data)) {
        $this->response->data = '';
      }
      $error_vars = array(
        '@code' => $this->response->code,
        '@message' => $this->response->error,
        '@details' => strip_tags($this->response->data),
      );
      $this->error = t('Code: @code, Error: @message, Message: @details', $error_vars);
      watchdog('google analytics reports api', 'Code: @code, Error: @message, Message: @details', $error_vars, WATCHDOG_ERROR);
    }
  }

  /**
   * Query Management API - Accounts.
   */
  public function queryAccounts($params = array(), $cache_options = array()) {
    $this->setQueryPath('management/accounts');
    $this->query($this->queryPath, $params, 'GET', $this->generateAuthHeader(), $cache_options);
    return $this;
  }

  /**
   * Query Management API - WebProperties.
   */
  public function queryWebProperties($params = array(), $cache_options = array()) {
    $params += array(
      'account-id' => '~all',
    );
    $this->setQueryPath('management/accounts/' . $params['account-id'] . '/webproperties');
    $this->query($this->queryPath, $params, 'GET', $this->generateAuthHeader(), $cache_options);
    return $this;
  }

  /**
   * Query Management API - Profiles.
   */
  public function queryProfiles($params = array(), $cache_options = array()) {
    $params += array(
      'account-id' => '~all',
      'web-property-id' => '~all',
    );
    $this->setQueryPath('management/accounts/' . $params['account-id'] . '/webproperties/' . $params['web-property-id'] . '/profiles');
    $this->query($this->queryPath, $params, 'GET', $this->generateAuthHeader(), $cache_options);

    return $this;
  }

  /**
   * Query Management API - Segments.
   */
  public function querySegments($params = array(), $cache_options = array()) {
    $this->setQueryPath('management/segments');
    $this->query($this->queryPath, $params, 'GET', $this->generateAuthHeader(), $cache_options);
    return $this;
  }

  /**
   * Query Management API - Goals.
   */
  public function queryGoals($params = array(), $cache_options = array()) {
    $params += array(
      'account-id' => '~all',
      'web-property-id' => '~all',
      'profile-id' => '~all',
    );
    $this->setQueryPath('management/accounts/' . $params['account-id'] . '/webproperties/' . $params['web-property-id'] . '/profiles/' . $params['profile-id'] . '/goals');
    $this->query($this->queryPath, $params, 'GET', $cache_options);
    return $this;
  }

  /**
   * Query and sanitize report data.
   */
  public function queryReportFeed($params = array(), $cache_options = array()) {

    // Provide defaults if the developer did not override them.
    $params += array(
      'profile_id' => 0,
      'dimensions' => NULL,
      'metrics' => 'ga:sessions',
      'sort_metric' => NULL,
      'filters' => NULL,
      'segment' => NULL,
      'start_date' => NULL,
      'end_date' => NULL,
      'start_index' => 1,
      'max_results' => 10000,
    );

    $parameters = array('ids' => $params['profile_id']);

    if (is_array($params['dimensions'])) {
      $parameters['dimensions'] = implode(',', $params['dimensions']);
    }
    elseif ($params['dimensions'] !== NULL) {
      $parameters['dimensions'] = $params['dimensions'];
    }

    if (is_array($params['metrics'])) {
      $parameters['metrics'] = implode(',', $params['metrics']);
    }
    else {
      $parameters['metrics'] = $params['metrics'];
    }

    if ($params['sort_metric'] == NULL && isset($parameters['metrics'])) {
      $parameters['sort'] = $parameters['metrics'];
    }
    elseif (is_array($params['sort_metric'])) {
      $parameters['sort'] = implode(',', $params['sort_metric']);
    }
    else {
      $parameters['sort'] = $params['sort_metric'];
    }

    if (empty($params['start_date']) || !is_int($params['start_date'])) {
      // Use the day that Google Analytics was released (1 Jan 2005).
      $start_date = '2005-01-01';
    }
    elseif (is_int($params['start_date'])) {
      // Assume a Unix timestamp.
      $start_date = date('Y-m-d', $params['start_date']);
    }

    $parameters['start-date'] = $start_date;

    if (empty($params['end_date']) || !is_int($params['end_date'])) {
      $end_date = date('Y-m-d');
    }
    elseif (is_int($params['end_date'])) {
      // Assume a Unix timestamp.
      $end_date = date('Y-m-d', $params['end_date']);
    }

    $parameters['end-date'] = $end_date;

    // Accept only strings, not arrays, for the following parameters.
    if (!empty($params['filters'])) {
      $parameters['filters'] = $params['filters'];
    }
    if (!empty($params['segment'])) {
      $parameters['segment'] = $params['segment'];
    }
    $parameters['start-index'] = $params['start_index'];
    $parameters['max-results'] = $params['max_results'];

    $this->setQueryPath('data/ga');
    if ($this->query($this->queryPath, $parameters, 'GET', $this->generateAuthHeader(), $cache_options)) {
      $this->sanitizeReport();
    }
    return $this;
  }

  /**
   * Sanitize report data.
   */
  protected function sanitizeReport() {
    // Get all Google Analytics fields.
    $fields = google_analytics_reports_views_get_fields();

    // Named keys for report values.
    $this->results->rawRows = isset($this->results->rows) ? $this->results->rows : array();
    $this->results->rows = array();
    foreach ($this->results->rawRows as $row_key => $row_value) {
      foreach ($row_value as $item_key => $item_value) {
        $field_without_ga = str_replace('ga:', '', $this->results->columnHeaders[$item_key]->name);
        $item_value = $this->replaceData($field_without_ga, $item_value);
        // Date and time datatypes should not have the digits after the zero.
        if (in_array($fields[$field_without_ga]['datatype'], array('date', 'time'))) {
          $item_value = round($item_value);
        }
        $this->results->rows[$row_key][$field_without_ga] = $item_value;
      }
    }
    unset($this->results->rawRows);

    // Named keys for report totals.
    $this->results->rawTotals = $this->results->totalsForAllResults;
    $this->results->totalsForAllResults = array();
    foreach ($this->results->rawTotals as $row_key => $row_value) {
      $this->results->totalsForAllResults[str_replace('ga:', '', $row_key)] = $row_value;
    }
    unset($this->results->rawTotals);
  }

  /**
   * Addition replacements.
   */
  public function replaceData($type, $value) {

    switch ($type) {
      case 'userType':
        return ($value == 'New Visitor') ? t('New Visitor') : t('Returning Visitor');

      case 'date':
        return strtotime($value);

      case 'yearMonth':
        return strtotime($value . '01');

      case 'userGender':
        return ($value == 'male') ? t('Male') : t('Female');

      default:
        return $value;
    }
  }
}
