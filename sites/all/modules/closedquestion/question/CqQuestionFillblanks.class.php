<?php

/**
 * @file
 * Implementation of the Fillblanks question type.
 */
class CqQuestionFillblanks extends CqQuestionAbstract {

  /**
   * HTML containing the question-text.
   *
   * @var string
   */
  private $text;
  /**
   * The base-name used for form elements.
   *
   * @var string
   */
  private $formElementName = '';
  /**
   * List of options to use in select boxes.
   *
   * @var array of CqInlineOption
   */
  private $inlineOptions = array();
  /**
   * List of boxes for the user to fill in.
   *
   * @var array of numbers
   */
  private $inlineChoices = array();
  /**
   * List containing lists of options to use in select boxes, with the group
   * name as key.
   *
   * @var array
   *   Associative array with the group name as key and array of CqInlineOption
   *   as value.
   */
  private $inlineOptionsByGroup = array();
  /**
   * List of feedback items to use as general hints.
   *
   * @var array of CqFeedback
   */
  private $hints = array();
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
   * Keep track of wether the answer has been parsed into variables yet.
   * This will only be true if createVariables() is called with a math
   * environment present.
   *
   * @var boolean
   */
  private $variablesParsed = FALSE;

  /**
   * Constructs a Fill-blanks question object
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
    $this->formElementName = 'xq_fillblanks_question' . $this->node->nid . '_';
    parent::registerTag('inlinechoice');
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
   * Get the answer(s) the student has given for the given hotspot(s).
   *
   * @param string $pattern
   *   A regular expression that is mached against all hotspot id's.
   *
   * @return mixed
   *   array: array of strings of the answers of the hotspots that matched.
   *   string: the answer if only one hotspot mached, or an empty string
   *   if no id matched.
   */
  public function getAnswerForChoice($pattern) {
    if (drupal_substr($pattern, 0, 1) != '/') {
      $pattern = '/' . $pattern . '/';
    }
    $answer = $this->userAnswer->getAnswer();
    $retarr = array();
    //dpm($answer);
    if (is_array($answer)) {
      foreach ($answer as $id => $value) {
        if (preg_match($pattern, $id)) {
          $retarr[$id] = $value;
        }
      }
    }
    if (count($retarr) > 1) {
      return $retarr;
    }
    elseif (count($retarr) == 1) {
      return reset($retarr);
    }
    return '';
  }

  /**
   * Overrides CqQuestionAbstract::loadXml()
   */
  public function loadXml(DOMElement $dom) {
    parent::loadXml($dom);
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');

    $textNode = NULL;
    foreach ($dom->childNodes as $node) {
      $name = drupal_strtolower($node->nodeName);
      switch ($name) {
        case 'inlineoption':
          $option = new CqInlineOption($node, $this);
          if (isset($this->inlineOptions[$option->getIdentifier()])) {
            drupal_set_message(t('Inlineoption identifier %identifier used more than once!', array('%identifier' => $option->getIdentifier())), 'warning');
          }
          $this->inlineOptions[$option->getIdentifier()] = $option;
          $this->inlineOptionsByGroup[$option->getGroup()][$option->getIdentifier()] = $option;
          break;

        case 'mapping':
          $map = new CqMapping();
          $map->generateFromNode($node, $this);
          $this->mappings[] = $map;
          break;

        case 'text':
          $textNode = $node;
          break;

        case 'hint':
          $this->hints[] = CqFeedback::newCqFeedback($node, $this);
          break;

        default:
          if (!in_array($name, $this->knownElements)) {
            drupal_set_message(t('Unknown node: @nodename', array('@nodename' => $node->nodeName)));
          }
          break;
      }
    }
    if ($textNode != NULL) {
      $this->text = cq_get_text_content($textNode, $this);
    }
  }

  /**
   * Implements CqQuestionAbstract::checkCorrect()
   */
  public function checkCorrect() {
    $this->matchedCorrectMappings = array();
    $this->matchedMappings = array();
    $correct = FALSE;

    $this->createVariables();

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
   * Overrides CqQuestionAbstract::evaluateMath()
   *
   * Checks if the answer(s) have been parsed into variables yet, and does so
   * if not.
   */
  public function evaluateMath($expression) {
    if (!$this->variablesParsed) {
      if (!$this->hasMath()) {
        // There is no math environment yet, make parent parse an empty
        // expression so it makes a math environment.
        parent::evaluateMath("");
      }
      $this->createVariables();
    }
    return parent::evaluateMath($expression);
  }

  /**
   * Put all numeric answers into MathExpression variables, but only for those
   * answers that have an id that is a valid MathExpression variable name.
   */
  private function createVariables() {
    if ($this->hasMath()) {
      $this->variablesParsed = TRUE;
      $answer = $this->userAnswer->getAnswer();
      foreach ($this->inlineChoices as $id => $option) {
        if (preg_match("/^[a-zA-Z]\w*$/", $id)) {
          if (preg_match("/^e|pi$/i", $id)) {
            if (user_access(CLOSEDQUESTION_RIGHT_CREATE)) {
              drupal_set_message(t('When using Math in your question you can not use the identifiers "e" or "pi".'), 'warning');
            }
          }
          else {
            if (is_array($answer) && isset($answer[$id]) && is_numeric($answer[$id])) {
              $expression = $id . '=' . $answer[$id];
              $result = $this->evaluateMath($expression);
            }
            else {
              $expression = $id . '=0';
              $result = $this->evaluateMath($expression);
            }
          }
        }
      }
    }
  }

  /**
   * Implements CqQuestionAbstract::getFeedbackItems()
   */
  public function getFeedbackItems() {
    $tries = $this->userAnswer->getTries();
    $answer = $this->userAnswer->getAnswer();
    $feedback = array();
    if ($answer == NULL) { // if there is no answer, don't check any further.
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
   * Implements CqQuestionAbstract::submitAnswer()
   */
  public function submitAnswer($form, &$form_state) {
    $this->userAnswer->setAnswer($form_state['values'][$this->formElementName]);
    $correct = $this->isCorrect(TRUE);
    if ($this->userAnswer->answerHasChanged()) {
      if (!$correct) {
        $this->userAnswer->increaseTries();
      }
      $this->userAnswer->store();
    }
  }

  /**
   * Implements CqQuestionAbstract::getOutput()
   *
   * The form of the Fillblanks question is too simple to need custom theming,
   * there is no additional html needed.
   */
  public function getForm($formState) {
    $form['questionText'] = array(
      '#type' => 'item',
      '#markup' => $this->text,
    );

    // We have to tell the form about our custom content.
    $form[$this->formElementName] = array(
      '#type' => 'item',
      '#input' => TRUE,
    );

    // Insert standard feedback and submit elements.
    $wrapper_id = 'cq-feedback-wrapper_' . $this->formElementName;
    $this->insertFeedback($form, $wrapper_id);
    $this->insertSubmit($form, $wrapper_id);
    return $form;
  }

  /**
   * Overrides CqQuestionAbstract::handleNode()
   */
  public function handleNode($node, $delay = FALSE) {
    $answer = $this->userAnswer->getAnswer();
    $retval = '';
    if (drupal_strtolower($node->nodeName) == 'inlinechoice') {
      $choice = array();

      $attribs = $node->attributes;
      $item = $attribs->getNamedItem('identifier');
      if ($item === NULL) {
        $item = $attribs->getNamedItem('id');
      }
      if ($item !== NULL) {
        $choiceid = $item->nodeValue;
      }
      else {
        if (user_access(CLOSEDQUESTION_RIGHT_CREATE)) {
          drupal_set_message(t('Warning: inlinechoice without identifier.'), 'warning');
        }
        $choiceid = 'noId';
      }
      $choice['id'] = $choiceid;
      $freeform = $node->attributes->getNamedItem('freeform');
      $style = $node->attributes->getNamedItem('style');
      $groupItem = $node->attributes->getNamedItem('group');
      if ($groupItem == NULL || empty($groupItem->nodeValue)) {
        $choice['group'] = 'default';
      }
      else {
        $choice['group'] = $groupItem->nodeValue;
      }
      if (!isset($this->inlineOptionsByGroup[$choice['group']])) {
        $choice['group'] = 'default';
      }

      if ($style != NULL) {
        $choice['style'] = $style->nodeValue;
      }

      $class = $node->attributes->getNamedItem('class');
      if ($class != NULL) {
        $choice['class'] = $class->nodeValue;
      }
      $choice['name'] = '' . $this->formElementName . '[' . $choiceid . ']';

      $choice['size'] = 8;
      $sizeAtt = $node->attributes->getNamedItem('size');
      if ($sizeAtt != NULL) {
        $choice['size'] = $sizeAtt->nodeValue;
      }

      $choice['value'] = $answer[$choiceid];

      $choice['freeform'] = 0;
      if ($freeform != NULL && $freeform->nodeValue) {
        $choice['freeform'] = $freeform->nodeValue;
      }

      if (!$choice['freeform']) {
        $choice['options'] = $this->inlineOptionsByGroup[$choice['group']];
      }

      $this->inlineChoices[$choiceid] = $choiceid;
      $retval = theme('closedquestion_inline_choice', $choice);
    }
    else {
      $retval = parent::handleNode($node, $delay);
    }

    return $retval;
  }

  /**
   * Implements CqQuestionAbstract::getAllText()
   */
  public function getAllText() {
    $this->initialise();
    $retval = array();

    $retval['text']['#markup'] = $this->text;

    // Inline options
    $retval['options'] = array(
      '#theme' => 'closedquestion_inline_option_list',
      'items' => array(),
      'extended' => TRUE,
    );
    foreach ($this->inlineOptions AS $option) {
      $retval['options']['items'][] = $option;
    }

    // Hints
    if (count($this->hints) > 0) {
      $retval['hints'] = array(
        '#theme' => 'closedquestion_feedback_list',
        'extended' => TRUE,
      );
      foreach ($this->hints AS $fbitem) {
        $retval['hints']['items'][] = $fbitem->getAllText();
      }
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
