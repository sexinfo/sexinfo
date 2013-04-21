<?php

/**
 * @file
 * Implementation of the Flash question type.
 *
 * This question type only handles storing of data for a flash movie or any
 * other client side technology like a Java applet. All question logic is done
 * on the client side.
 *
 * The page containing this question will respond to the following get/post
 * variables:
 *
 * Get and Post:
 * - action=getAnswer: returns the current answer, or an empty page when there
 *   is no answer.
 * - action=getData: returns the current data field, or an empty page when there
 *   is no data.
 * - action=getInfo: return the current user's data, formatting in the way
 *   specified in the format field. The data consists of the following:
 *   - username: The current user's username.
 *   - firstname: If there is a profile field "profile_firstname" then the data
 *     of this field is returned.
 *   - middlename: If there is a profile field "profile_middlename" then the
 *     data of this field is returned.
 *   - lastname: If there is a profile field "profile_lastname" then the data
 *     of this field is returned.
 *   - tries: (int) The number in the tries field.
 *   - onceCorrect: (int) The number of tries that was listed the first time the
 *     correct variable was nonzero.
 * - format: The format to return the data of getInfo in. Possible values:
 *   - flash: return as flashvars (& seprated key=value pairs)
 *   - xml: xml data formatted as: <data><key1>value1</key1>...</data>
 *   - json: {"key":"value"}
 *
 * Post only:
 * - action=store: Will read (and store) the following variables, if set.
 *   - answer
 *   - data
 *   - tries
 *   - correct
 *
 */
class CqQuestionFlash extends CqQuestionAbstract {

  /**
   * HTML containing the flash tag and any accompanying text.
   *
   * @var string
   */
  private $text;

  /**
   * Constructs an option question object
   *
   * @param CqUserAnswerInterface $userAnswer
   *   The CqUserAnswerInterface to use for storing the student's answer.
   * @param object $node
   *   Drupal node object that this question belongs to.
   */
  public function __construct(CqUserAnswerInterface &$userAnswer, &$node) {
    parent::__construct();
    $this->userAnswer = &$userAnswer;
    $this->node = &$node;

    if (isset($_REQUEST['action'])) {
      if (strtolower($_REQUEST['action']) == 'getanswer') {
        echo $this->userAnswer->getAnswer();
        die();
      }

      if (strtolower($_REQUEST['action']) == 'getdata') {
        echo $this->userAnswer->getData('data');
        die();
      }

      if (strtolower($_REQUEST['action']) == 'getinfo') {
        global $user;
        profile_load_profile(&$user);

        $info = array();
        $info['username'] = $user->name;
        if (isset($user->profile_firstname)) {
          $info['firstname'] = $user->profile_firstname;
        }
        if (isset($user->profile_middlename)) {
          $info['middlename'] = $user->profile_middlename;
        }
        if (isset($user->profile_lastname)) {
          $info['lastname'] = $user->profile_lastname;
        }
        $info['tries'] = $this->userAnswer->getTries();
        $info['onceCorrect'] = $this->userAnswer->onceCorrect();

        $format = '';
        if (isset($_REQUEST['format'])) {
          $format = strtolower($_REQUEST['format']);
        }
        switch ($format) {
          case 'xml':
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('data');
            $dom->appendChild($root);
            foreach ($info AS $key => $value) {
              $node = $dom->createElement($key, $value);
              $root->appendChild($node);
            }
            echo $dom->saveXML();
            die;
            break;

          case 'json':
            drupal_json($info);
            die();
            break;

          case 'flash':
          default:
            foreach ($info AS $key => $value) {
              echo '&' . $key . '=' . urlencode($value) . '&';
            }
            die;
            break;
        }
      }
    }

    if (isset($_POST['action'])) {
      if (strtolower($_POST['action']) == 'store') {
        if (isset($_POST['answer'])) {
          $this->userAnswer->setAnswer($_POST['answer']);
        }
        if (isset($_POST['data'])) {
          $this->userAnswer->setData('data', $_POST['data']);
        }
        if (isset($_POST['tries'])) {
          $this->userAnswer->setTries($_POST['tries']);
        }
        if (isset($_POST['correct'])) {
          $this->userAnswer->setCorrect($_POST['correct']);
          $this->userAnswer->setData('correct', $_POST['correct']);
        }
        switch ($format) {
          case 'xml':
            $dom = new DOMDocument('1.0', 'utf-8');
            $root = $dom->createElement('result');
            $dom->appendChild($root);
            $node = $dom->createElement('success', 'true');
            $root->appendChild($node);
            echo $dom->saveXML();
            die;
            break;

          case 'json':
            drupal_json(array('success' => TRUE));
            die();
            break;

          case 'flash':
          default:
            echo '&success=true&';
            die;
            break;
        }
      }
    }
  }

  /**
   * Implements CqQuestionAbstract::getOutput()
   */
  public function getOutput() {
    $this->initialise();
    $retval = drupal_get_form('closedquestion_get_form_for', $this->node);
    $retval['#prefix'] = $this->prefix;
    $retval['#suffix'] = $this->postfix;
    return $retval;
  }

  /**
   * Implements CqQuestionAbstract::getFeedbackItems()
   */
  public function getFeedbackItems() {
    $feedback = array();
    return $feedback;
  }

  /**
   * Overrides CqQuestionAbstract::loadXml()
   */
  public function loadXml(DOMElement $dom) {
    parent::loadXml($dom);
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');

    foreach ($dom->childNodes as $node) {
      $name = drupal_strtolower($node->nodeName);
      switch ($name) {
        case 'text':
          $this->text = cq_get_text_content($node, $this);
          break;

        default:
          if (!in_array($name, $this->knownElements)) {
            drupal_set_message(t('Unknown node: @nodename', array('@nodename' => $node->nodeName)));
          }
          break;
      }
    }
  }

  /**
   * Implements CqQuestionAbstract::getForm()
   */
  public function getForm($formState) {

    $form['questionText'] = array(
      '#type' => 'item',
      '#markup' => $this->text,
    );

    return $form;
  }

  /**
   * Implements CqQuestionAbstract::checkCorrect()
   */
  public function checkCorrect() {
    dpm('checkCorrect()');
    return 0;
  }

  /**
   * Implements CqQuestionAbstract::submitAnswer()
   */
  public function submitAnswer($form, &$form_state) {
    dpm('submitAnswer()');
    dpm($_REQUEST);
  }

  /**
   * Implements CqQuestionAbstract::getAllText()
   */
  public function getAllText() {
    $this->initialise();
    $retval = array();
    $retval['text']['#markup'] = $this->text;
    if ($this->correctFeeback) {
      $retval['correctFeeback']['#markup'] = $this->correctFeeback->getText();
    }
    return $retval;
  }

}
