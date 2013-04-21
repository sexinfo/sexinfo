<?php

/**
 * @file
 * Implementation of the Drag&Drop question type.
 * A D&D question presents the student with an image and a set of draggables
 * and the assignment to drag the draggables to the relevant position on the
 * image.
 */
class CqQuestionDragDrop extends CqQuestionAbstract {

  /**
   * HTML containing the question-text.
   *
   * @var string
   */
  private $text;
  /**
   * The base-name used for form elements that need to be accessed by
   * javascript.
   *
   * @var string
   */
  private $formElementName;
  /**
   * The state of the draggables to use when the student has not yet changed
   * any thing.
   *
   * @var string
   */
  private $startstate;
  /**
   * The url of the image to use as background for the drag&drop question.
   *
   * @var string
   */
  private $matchImgUrl;
  /**
   * The width of the background image
   *
   * @var int
   */
  private $matchImgWidth;
  /**
   * The height of the background image
   *
   * @var int
   */
  private $matchImgHeight;
  /**
   * The list of hotspots in the image.
   *
   * @var array of CqHotspot
   */
  private $hotspots = array();
  /**
   * The list of draggables.
   *
   * @var array of CqDraggable
   */
  private $draggables = array();
  /**
   * The mappings to check the student's answer against.
   *
   * @var array of CqMapping
   */
  private $mappings = array();
  /**
   * The mappings that have the correct flag set and matched the current answer.
   *
   * @var array of CqMapping
   */
  private $matchedCorrectMappings = array();
  /**
   * The mappings that matched the current answer.
   *
   * @var array of CqMapping
   */
  private $matchedMappings = array();
  /**
   * List of feedback items to use as general hints.
   *
   * @var array of CqFeedback
   */
  private $hints = array();

  /**
   * Constructs a Drag&Drop question object
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
    $this->formElementName = 'cq_dd_' . $this->node->nid . '_';
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
    $tries = $this->userAnswer->getTries();
    $answer = $this->userAnswer->getAnswer();
    $feedback = array();
    if ($tries == 0 && $answer == $this->startstate) { // if there is no answer, don't check any further.
      return $feedback;
    }

    if ($this->isCorrect()) {
      foreach ($this->matchedCorrectMappings AS $mapping) {
        $feedback = array_merge($feedback, $mapping->getFeedbackItems($tries));
      }
    }
    else {
      foreach ($this->hints as $fb) {
        if ($fb->inRange($tries)) {
          $feedback[] = $fb;
        }
      }
      foreach ($this->matchedMappings AS $mapping) {
        $feedback = array_merge($feedback, $mapping->getFeedbackItems($tries));
      }
    }

    // Finally, ask external systems if they want to add extra feedback.
    $feedback = array_merge($feedback, $this->fireGetExtraFeedbackItems($this, $tries));
    return $feedback;
  }

  /**
   * Overrides CqQuestionAbstract::getDraggables()
   */
  public function getDraggables() {
    return $this->draggables;
  }

  /**
   * Implements CqQuestionAbstract::getHotspots()
   */
  public function getHotspots() {
    return $this->hotspots;
  }

  /**
   * Overrides CqQuestionAbstract::loadXml()
   */
  public function loadXml(DOMElement $dom) {
    parent::loadXml($dom);
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');
    module_load_include('php', 'closedquestion', 'question/mapping/FactoryHotspot');

    $this->hotspots = array();
    $this->draggables = array();
    $this->mappings = array();
    $this->hints = array();

    foreach ($dom->childNodes as $node) {
      $name = drupal_strtolower($node->nodeName);
      switch ($name) {
        case 'text':
          $this->text = cq_get_text_content($node, $this);
          break;

        case 'matchimg':
          $this->matchImgUrl = $node->getAttribute('src');
          $this->matchImgHeight = $node->getAttribute('height');
          $this->matchImgWidth = $node->getAttribute('width');
          foreach ($node->childNodes as $child) {
            switch (drupal_strtolower($child->nodeName)) {
              case 'hotspot':
                $hotspot = cq_Hotspot_from_xml($child, $this);
                if (is_object($hotspot)) {
                  if (isset($this->hotspots[$hotspot->getIdentifier()])) {
                    drupal_set_message(t('Hotspot identifier %identifier used more than once!', array('%identifier' => $hotspot->getIdentifier())), 'warning');
                  }
                  $this->hotspots[$hotspot->getIdentifier()] = $hotspot;
                }
                break;
              case 'draggable':
                $draggable = new CqDraggable($child, $this);
                if (isset($this->draggables[$draggable->getIdentifier()])) {
                  drupal_set_message(t('Draggable identifier %identifier used more than once!', array('%identifier' => $draggable->getIdentifier())), 'warning');
                }
                $this->draggables[$draggable->getIdentifier()] = $draggable;
                break;
            }
          }
          break;

        case 'mapping':
          $map = new CqMapping();
          $map->generateFromNode($node, $this);
          $this->mappings[] = $map;
          break;

        case 'hint':
          $this->hints[] = CqFeedback::newCqFeedback($node, $this);
          break;

        case 'default':
        case 'startstate':
          $this->startstate = $node->getAttribute('value');
          break;

        default:
          if (!in_array($name, $this->knownElements)) {
            drupal_set_message(t('Unknown node: @nodename', array('@nodename' => $node->nodeName)));
          }
          break;
      }
    }

    $attribs = $dom->attributes;

    $item = $attribs->getNamedItem('width');
    if ($item !== NULL && $this->matchImgWidth == NULL) {
      $this->matchImgWidth = (int) $item->value;
    }

    $item = $attribs->getNamedItem('height');
    if ($item !== NULL && $this->matchImgHeight == NULL) {
      $this->matchImgHeight = (int) $item->value;
    }

    if ($this->userAnswer->isEmpty()) {
      $this->userAnswer->setAnswer($this->startstate);
    }
    $this->parseAnswer();
  }

  /**
   * Parse the answer string and put the coordinates of the different draggables
   * into the corresponding draggable objects.
   */
  private function parseAnswer() {
    $answer = $this->userAnswer->getAnswer();
    //drupal_set_message("parsing answer: " . $answer);
    $parts = explode(';', $answer);
    foreach ($parts as $part) {
      $partArr = explode(',', $part);
      if (count($partArr) == 3) {
        if (isset($this->draggables[$partArr[0]])) {
          $draggable = $this->draggables[$partArr[0]];
          $draggable->setLocationXY($partArr[1], $partArr[2]);
        }
        else {
          drupal_set_message(t('unknown draggable %identifier in answer.', array('%identifier' => $partArr[0])), 'warning');
        }
      }
    }
  }

  /**
   * Implements CqQuestionAbstract::getForm()
   */
  public function getForm($formState) {
    $nextlink = '';
    $answer = $this->userAnswer->getAnswer();
    $mapName = $this->formElementName . 'map';


    // The question part is themed
    $form['question']['#theme'] = 'closedquestion_question_drag_drop';
    $form['question']['questionText'] = array(
      '#type' => 'item',
      '#markup' => $this->text,
    );

    // The data needed by the theme function.
    $data = array();
    $data['elementname'] = $this->formElementName;
    $data['mapname'] = $mapName;
    $data['image'] = array(
      "height" => (int) $this->matchImgHeight,
      "width" => (int) $this->matchImgWidth,
      "url" => $this->matchImgUrl,
    );

    // Handle the draggables
    foreach ($this->draggables as $draggable) {
      $dragClass = 'cqDdDraggable';
      if ($draggable->getClass()) {
        $dragClass = 'cqDdDraggable cqDdDraggable_' . $draggable->getClass();
      }
      elseif ($draggable->imageOnly()) {
        $dragClass = 'cqDdDraggable cqDdDraggable_minimal';
      }
      $loc = $draggable->getLocation();
      $data['draggables'][$draggable->getIdentifier()] = array(
        'class' => $dragClass,
        'text' => $draggable->getText(),
        'cqvalue' => $draggable->getIdentifier(),
        'x' => (int) $loc[0],
        'y' => (int) $loc[1],
      );
    }

    // Handle the hotspots, we only need the ones that have a description.
    $data['hotspots'] = array();
    foreach ($this->hotspots as $hotspot) {
      $description = $hotspot->getDescription();
      if (!empty($description)) {
        $termId = $this->formElementName . 'term_' . $hotspot->getIdentifier();
        $data['hotspots'][$hotspot->getIdentifier()] = array(
          'termid' => $termId,
          'maphtml' => $hotspot->getMapHtml(),
          'description' => $description,
        );
      }
    }
    $form['question']['data'] = array('#type' => 'value', '#value' => $data);


    // Other elements are not themed by default.
    // This element should be filled by the javascript so it holds the answer.
    $form[$this->formElementName . 'answer'] = array(
      '#type' => 'hidden',
      '#default_value' => $answer,
      '#input' => TRUE,
    );

    // Insert standard feedback and submit elements.
    $wrapper_id = 'cq-feedback-wrapper_' . $this->formElementName;
    $this->insertFeedback($form, $wrapper_id);
    $this->insertSubmit($form, $wrapper_id);
    return $form;
  }

  /**
   * Implements CqQuestionAbstract::checkCorrect()
   */
  public function checkCorrect() {
    $this->parseAnswer();
    $this->matchedCorrectMappings = array();
    $this->matchedMappings = array();
    $correct = FALSE;

    foreach ($this->mappings as $id => $mapping) {
      if ($mapping->evaluate()) {
        if ($mapping->getCorrect() != 0) {
          $correct = TRUE;
          $this->matchedCorrectMappings[] = $mapping;
        }
        else {
          $this->matchedMappings[] = $mapping;
        }
        if ($mapping->stopIfMatch()) {
          break;
        }
      }
      unset($mapping);
    }
    return $correct;
  }

  /**
   * Implements CqQuestionAbstract::submitAnswer()
   */
  public function submitAnswer($form, &$form_state) {
    $newAnswer = $form_state['values'][$this->formElementName . 'answer'];
    if (user_access(CLOSEDQUESTION_RIGHT_CREATE)) {
      drupal_set_message(t('Current answer=%a (Teacher only message)', array('%a' => str_replace('&', ';', $newAnswer))));
    }
    $this->userAnswer->setAnswer($newAnswer);
    $correct = $this->isCorrect(TRUE);
    if ($this->userAnswer->answerHasChanged()) {
      if (!$correct) {
        $this->userAnswer->increaseTries();
      }
      $this->userAnswer->store();
    }
  }

  /**
   * Implements CqQuestionAbstract::getAllText()
   */
  public function getAllText() {
    $this->initialise();
    $retval = array();
    $retval['text']['#markup'] = $this->text;

    // Hints
    $retval['hints'] = array(
      '#theme' => 'closedquestion_feedback_list',
      'extended' => TRUE,
    );
    foreach ($this->hints AS $fbitem) {
      $retval['hints']['items'][] = $fbitem->getAllText();
    }

    // Mappings
    $retval['mappings'] = array(
      '#theme' => 'closedquestion_mapping_list',
      'items' => array(),
    );
    foreach ($this->mappings AS $mapping) {
      $retval['mappings']['items'][] = $mapping->getAllText();
    }

    $retval['#theme'] = 'closedquestion_question_general_text';
    return $retval;
  }

}
