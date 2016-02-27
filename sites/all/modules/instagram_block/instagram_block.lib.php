<?php

/**
 * @file
 * Instagram classes to integrate with the Instagram API.
 */

class InstagramRequest {
  /**
   * @var array $response.
   */
  protected $response;

  /**
   * @var array $config
   */
  protected $config;


  /**
   * @var array $values
   */
  protected $values;

  /**
   * Constructs the request object.
   *
   * @param array $config
   * @param array $values
   */
  public function __construct(array $config, array $values) {
    $this->config = $config;
    $this->values = $values;
  }

  /**
   * Builds a request for {user} media.
   */
  public function requestUserMedia() {
    $url = 'https://api.instagram.com/v1/users/' . $this->config['user_id'] . '/media/recent/';
    $params = array(
      'access_token' => $this->config['access_token'],
      'count' => $this->values['count'],
    );

    $this->response = $this->request($url, $params, 'GET');
  }

  /**
   * Builds a request for {tag} media
   */
  public function requestTagMedia() {
    $url = "https://api.instagram.com/v1/tags/" . $this->values['tag'] . "/media/recent/";
    $params = array(
      'access_token' => $this->config['access_token'],
      'count' => $this->values['count'],
    );

    $this->response = $this->request($url, $params, 'GET');
  }

  /**
   * Performs a request.
   *
   * @param string $url
   * @param array $params
   * @param string $method
   *
   * @throws \Exception
   */
  protected function request($url, $params = array(), $method = 'GET') {
    $data = '';
    if (count($params) > 0) {
      if ($method == 'GET') {
        $url .= '?'. http_build_query($params, '', '&');
      }
      else {
        $data = http_build_query($params, '', '&');
      }
    }

    $headers = array();

    $headers['Authorization'] = 'Oauth';
    $headers['Content-type'] = 'application/x-www-form-urlencoded';

    $response = $this->doRequest($url, $headers, $method, $data);
    if (!isset($response->error)) {
      return $response->data;
    }
    else {
      $error = $response->error;
      if (!empty($response->data)) {
        $data = $this->parse_response($response->data);
        if (isset($data->error)) {
          $error = $data->error;
        }
        elseif (isset($data->meta)) {
          $error = new Exception($data->meta->error_type . ': ' . $data->meta->error_message, $data->meta->code);
        }
      }
      watchdog('instagram_block', $error);
    }
  }

  /**
   * Actually performs a request.
   *
   * This method can be easily overriden through inheritance.
   *
   * @param string $url
   *   The url of the endpoint.
   * @param array $headers
   *   Array of headers.
   * @param string $method
   *   The HTTP method to use (normally POST or GET).
   * @param array $data
   *   An array of parameters
   *
   * @return
   *   stdClass response object.
   */
  protected function doRequest($url, $headers, $method, $data) {
    return drupal_http_request($url, array('headers' => $headers, 'method' => $method, 'data' => $data));
  }

  /**
   * Parses the response.
   */
  protected function parse_response($response) {
    // http://drupal.org/node/985544 - json_decode large integer issue
    $length = strlen(PHP_INT_MAX);
    $response = preg_replace('/"(id|in_reply_to_status_id)":(\d{' . $length . ',})/', '"\1":"\2"', $response);
    return json_decode($response);
  }

  /**
   * Get an array of InstagramPosts objects.
   */
  public function get_instagram_posts() {
    $response = $this->parse_response($this->response);
    // Check on successfull call
    if ($response) {
      $posts = array();
      foreach ($response->data as $post) {
        $posts[] = new InstagramPost($post);
      }
      return $posts;
    }
    else {
      return array();
    }
  }
}

/**
 * InstagramPost implementation class
 */
class InstagramPost {

  /**
   * @var string $id.
   */
  protected $id;

  /**
   * @var InstagramUser $user.
   */
  public $user;

  /**
   * @var string $created.
   */
  public $created;

  /**
   * @var string $type.
   */
  public $type;

  /**
   * @var string $link.
   */
  public $link;

  /**
   * @var InstagramLocation $location.
   */
  public $location;

  /**
   * @var InstagramComment $caption.
   */
  public $caption;

  /**
   * @var array $comments
   *   Array of InstagramComments.
   */
  public $comments;

  /**
   * @var integer $comment_count.
   */
  public $comment_count;

  /**
   * @var array $likes
   *   Array of InstagramLikes.
   */
  public $likes;

  /**
   * @var array $images
   *   Array of Image links.
   */
  public $images;

  /**
   * @var integer $like_count.
   */
  public $like_count;

  /**
   * Constructor for the InstagramPost class
   */
  public function __construct($instagram_post) {
    $this->id = $instagram_post->id;
    $this->user = new InstagramUser($instagram_post->user);
    $this->created = $instagram_post->created_time;
    $this->type = $instagram_post->type;
    $this->link = $instagram_post->link;
    $this->images = $instagram_post->images;

    if (!empty($instagram_post->location)) {
      $this->location = new InstagramLocation($instagram_post->location);
    }

    if (!empty($instagram_post->caption)) {
      $this->caption = new InstagramComment($instagram_post->caption);
    }

    if ($instagram_post->comments->count) {
      $this->comment_count = $instagram_post->comments->count;
      //$this->comments = $this->get_comments($instagram_post->comments->data);
    }

    /*if ($instagram_post->likes->count) {
      $this->like_count = $instagram_post->likes->count;
      $this->likes = $this->get_likes($instagram_post->likes->data);
    }*/
  }

  /**
   * Builds an InstagramComment.
   */
  protected function get_comments($data) {
    $comments = array();

    foreach ($data as $comment) {
      $comments[] = new InstagramComment($comment);
    }

    return $comments;
  }

  /**
   * Builds an InstagramLike.
   */
  protected function get_likes($data) {
    $likes = array();

    //foreach ($data as $like) {
    //  $likes[] = new InstagramLike($like);
    //}

    return $likes;
  }
}

/**
 * InstagramUser class.
 */
class InstagramUser {
  /**
   * @var string $id.
   */
  protected $id;

  /**
   * @var string $username.
   */
  public $username;

  /**
   * @var string $website.
   */
  public $website;

  /**
   * @var string $full_name.
   */
  public $full_name;

  /**
   * @var string $bio.
   */
  public $bio;

  /**
   * @var string $profile_picture
   *   URL to the image src.
   */
  public $profile_picture;

  /**
   * Constructor for the InstagramUser class.
   */
  public function __construct($user) {
    $this->profile_picture = $user->profile_picture;
    $this->id = $user->id;
    $this->full_name = $user->full_name;
    $this->username = $user->username;

    if (!empty($user->website)) {
      $this->website = $user->website;
    }

    if (!empty($user->bio)) {
      $this->bio = $user->bio;
    }
  }
}

/**
 * InstagramLocation class.
 */
class InstagramLocation {

  /**
   * @var string $id.
   */
  protected $id;

  /**
   * @var string $name.
   */
  public $name;

  /**
   * @var string $latitude.
   */
  public $latitude;

  /**
   * @var string $longitude.
   */
  public $longitude;

  /**
   * Constructor for the InstagramLocation class.
   */
  public function __construct($location) {
    if (isset($this->id)) {
      $this->id = $location->id;
    }

    if (isset($this->name)) {
      $this->name = $location->name;
    }

    $this->latitude = $location->latitude;
    $this->longitude = $location->longitude;
  }
}

/**
 * InstagramComment class.
 */
class InstagramComment {
  /**
   * @var string $id.
   */
  protected $id;

  /**
   * @var string $text.
   */
  public $text;

  /**
   * @var InstagramUser $from.
   */
  public $from;

  /**
   * @var string $created.
   */
  public $created;

  /**
   * Constructor for the InstagramComment class.
   */
  public function __construct($comment) {
    $this->id = $comment->id;
    $this->text = $comment->text;
    $this->from = new InstagramUser($comment->from);
    $this->created = $comment->created_time;
  }
}

/**
 * InstagramLike class.
 */
class InstagramLike {
  /**
   * @var string $id.
   */
  protected $id;

  /**
   * @var string $username.
   */
  public $username;

  /**
   * @var string $full_name.
   */
  public $full_name;

  /**
   * @var string $profile_picture.
   *   URL to the image src.
   */
  public $profile_picture;

  /**
   * Constructor for the InstagramLike class.
   */
  public function __construct($like) {
    $this->profile_picture = $like->profile_picture;
    $this->id = $like->id;
    $this->username = $like->username;
    $this->full_name = $like->full_name;
  }
}
