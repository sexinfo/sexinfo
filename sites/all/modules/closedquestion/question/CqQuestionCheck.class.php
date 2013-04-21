<?php

/**
 * @file
 * Implementation of a Multiple answer question (multiple choice with more than
 * one selectable)
 */
class CqQuestionCheck extends CqQuestionAbstract {

  /**
   * HTML containing the question-text.
   *
   * @var string
   */
  protected $text;
  /**
   * The base-name used for form elements that need to be accessed by
   * javascript.
   *
   * @var string
   */
  protected $formElementName;
  /**
   * Prompt do display directly above the options.
   *
   * @var string
   */
  protected $prompt = '';
  /**
   * The list of options for the student to choose from.
   *
   * @var array of CqOption
   */
  protected $options = array();
  /**
   * List of feedback items to use as general hints.
   *
   * @var array of CqFeedback
   */
  protected $hints = array();
  /**
   * Feedback mappings.
   *
   * @var array of CqMapping
   */
  protected $mappings = array();
  /**
   * Mappings that have the "correct" flag set and mached the current answer.
   *
   * @var array of CqMapping
   */
  protected $matchedCorrectMappings = array();
  /**
   * Mappings that mached the current answer.
   *
   * @var array of CqMapping
   */
  protected $matchedMappings = array();
  /**
   * The feedback to show when the answer is answered correctly.
   *
   * @var CqFeedback
   */
  protected $correctFeeback;
  /**
   * Show feedback in-line?
   * 1: yes, any other value: no;
   *
   * @var int
   */
  protected $inlineFeedback = 1;
  /**
   * The answer in string-format.
   *
   * @var string
   */
  protected $answerString = NULL;

  /**
   * Constructs a check question object.
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
    $this->formElementName = 'xq_check_question' . $this->node->nid . '_';
    $this->prompt = t('Pick any number');
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
   * Get the current options (CqQuestionOption objects) for this question.
   *
   * @return array
   *   Array of CqQuestionOption objects.
   */
  public function getOptions() {
    return $this->options;
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

    // The direct option-related feedback
    if (!$this->isCorrect()) {
      foreach ($this->hints as $fb) {
        if ($fb->inRange($tries)) {
          $feedback[] = $fb;
        }
      }
    }
    // The option-related feedback. Shown always if feedback is inline, or
    // only when the answer is not correct if the feedback is not inline.
    if (!$this->isCorrect() || $this->inlineFeedback) {
      if ($answer !== NULL) {
        foreach ($this->options as $optionNr => $option) {
          $optionName = 'o' . $optionNr;
          $feedbacks = $option->getFeedback($tries, $answer[$optionName]);
          foreach ($feedbacks as $fb) {
            if ($this->inlineFeedback) {
              $fb->setBlock($optionName);
            }
            $feedback[] = $fb;
          }
        }
      }
    }

    // The feedback if the answer is correct.
    if ($this->isCorrect()) {
      if ($this->correctFeeback != NULL) {
        $feedback[] = $this->correctFeeback;
      }
    }

    // The new style mappings.
    if ($this->isCorrect()) {
      foreach ($this->matchedCorrectMappings AS $mapping) {
        $feedback = array_merge($feedback, $mapping->getFeedbackItems($tries));
      }
    }
    else {
      foreach ($this->matchedMappings AS $mapping) {
        $feedback = array_merge($feedback, $mapping->getFeedbackItems($tries));
      }
    }

    // Finally, ask external systems if they want to add extra feedback.
    $feedback = array_merge($feedback, $this->fireGetExtraFeedbackItems($this, $tries));
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
        case 'option':
          $this->options[] = new CqOption($node, $this);
          break;

        case 'text':
          $this->text = cq_get_text_content($node, $this);
          break;

        case 'prompt':
          $this->prompt = cq_get_text_content($node, $this);
          break;

        case 'hint':
          $this->hints[] = CqFeedback::newCqFeedback($node, $this);
          break;

        case 'correct':
          $this->correctFeeback = CqFeedback::newCqFeedback($node, $this);
          break;

        case 'mapping': // New style mapping, continues at match by default.
          $map = new CqMapping();
          $map->generateFromNode($node, $this);

          if ($node->nodeName == 'sequence') {
            $map->setStopIfMatch(TRUE);
          }

          $this->mappings[] = $map;
          break;

        default:
          if (!in_array($name, $this->knownElements)) {
            drupal_set_message(t('Unknown node: @nodename', array('@nodename' => $node->nodeName)));
          }
          break;
      }
    }
    $attribs = $dom->attributes;
    $item = $attribs->getNamedItem('inlinefeedback');
    if ($item !== NULL) {
      $this->inlineFeedback = (int) $item->value;
    }
  }

  /**
   * Implements CqQuestionAbstract::getForm()
   */
  public function getForm($formState) {
    $answer = $this->userAnswer->getAnswer();
    $tries = $this->userAnswer->getTries();
    $answered = TRUE;
    if ($answer === NULL) {
      $answer = array();
      $answered = FALSE;
    }

    $formPos = strpos($this->text, '<formblock/>');
    if ($formPos !== FALSE) {
      // not using drupal_substr since we use a strpos generated index.
      $preForm = substr($this->text, 0, $formPos);
      $postForm = substr($this->text, $formPos + 12);
    }
    else {
      $form['questionText'] = array(
        '#type' => 'item',
        '#markup' => $this->text,
      );
    }

    $optionsFinal = array();
    foreach ($this->options as $optionNr => $option) {
      $optionName = 'o' . $optionNr;
      $optionsFinal[$optionName] = $option->getText();
      if ($this->inlineFeedback) {
        $optionsFinal[$optionName] .= '<p><span class="cqFbBlock cqFb-' . $optionName . '" ></span></p>';
      }
    }

    $form['options'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->prompt,
      '#options' => $optionsFinal,
      '#default_value' => $answer,
    );
    if ($formPos !== FALSE) {
      $form['options']['#prefix'] = $preForm;
      $form['options']['#suffix'] = $postForm;
    }

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
    $answer = $this->userAnswer->getAnswer();
    $this->matchedMappings = array();
    $this->matchedCorrectMappings = array();

    if (count($this->mappings) > 0) { // There are mappings, use those.
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
    else { // No mappings, use direct option-related checks.
      foreach ($this->options as $optionNr => $option) {
        $optionName = 'o' . $optionNr;
        if ($option->getCorrect() >= 0) {
          if ((!isset($answer[$optionName]) || $answer[$optionName] === 0) && $option->getCorrect() > 0) {
            return FALSE;
          }
          if ($answer[$optionName] === $optionName && $option->getCorrect() == 0) {
            return FALSE;
          }
        }
      }
      return TRUE;
    }
  }

  /**
   * Implements CqQuestionAbstract::submitAnswer()
   */
  public function submitAnswer($form, &$form_state) {
    $this->answerString = NULL;
    $this->userAnswer->setAnswer($form_state['values']['options']);
    $correct = $this->isCorrect(TRUE);
    if ($this->userAnswer->answerHasChanged()) {
      if (!$correct) {
        $this->userAnswer->increaseTries();
      }
      $this->userAnswer->store();
    }
  }

  /**
   * Returns the the answer in string form.
   *
   * @param string $identifier
   *   unused in this question type.
   *
   * @return String
   *   the answer in string form.
   */
  public function getAnswerForChoice($identifier) {
    $answer = $this->userAnswer->getAnswer();
    if ($this->answerString === NULL) {
      $this->answerString = '';
      foreach ($this->options as $optionNr => $option) {
        $optionName = 'o' . $optionNr;
        if ($answer[$optionName] === $optionName) {
          $this->answerString .= $option->getIdentifier();
        }
      }
      if (user_access(CLOSEDQUESTION_RIGHT_CREATE)) {
        drupal_set_message(t('Current answer=%a (Teacher only message)', array('%a' => $this->answerString)));
      }
    }
    return $this->answerString;
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

    // Options
    $retval['options'] = array(
      '#theme' => 'closedquestion_option_list',
      'items' => array(),
      'extended' => TRUE,
    );
    foreach ($this->options AS $option) {
      $retval['options']['items'][] = $option->getAllText();
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
