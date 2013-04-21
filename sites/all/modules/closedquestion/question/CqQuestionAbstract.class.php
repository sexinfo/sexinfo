<?php

/**
 * @file
 * Abstract base implementation of the CqQuestionInterface interface
 */
abstract class CqQuestionAbstract implements CqQuestionInterface {

  /**
   * The CqUserAnswerInterface to use for storing the student's answer.
   *
   * @var CqUserAnswer
   */
  protected $userAnswer;

  /**
   * The drupal node object that contains this question.
   *
   * @var Object
   */
  protected $node;

  /**
   * The list of CqListenerQuestionInterface listeners.
   *
   * @var array of CqListenerQuestionInterface
   */
  protected $listeners = array();

  /**
   * The html to add before the generated html of the question
   *
   * @var string
   */
  protected $prefix = '';

  /**
   * The html to add after the generated html of the question
   *
   * @var string
   */
  protected $postfix = '';

  /**
   * The path that was used to load this question. We need to store it because
   * we can't trust the path if the object is stored in a cached form and later
   * used from a json call.
   *
   * @var string
   */
  public $usedPath = '';

  /**
   * List of node names or tags in the text that are handled by this question
   * when it is used as $context parameter in cq_get_text_content().
   * Tags should be in the form of [tagName|tagData]
   *
   * @var array of string
   *
   * @see cq_get_text_content()
   * @see handleNode()
   * @see handleTag()
   */
  private $handledTags = array(
    'mathresult',
    'feedbackblock',
    'textlabelresult',
  );

  /**
   * A list of XML elements that are known by the Question. If an element is
   * found that is not in this list the user probably made a typo and should be
   * warned.
   *
   * @var array of string
   */
  protected $knownElements = array(
    '#text',
    '#comment',
    'prefix',
    'postfix',
    'matheval',
    'textlabel',
  );

  /**
   * The EvalMath object used to evaluate math expressions used to generate the
   * question.
   *
   * @var EvalMath
   */
  private $evalMath;

  /**
   * The list of expressions found in this question.
   *
   * @var array of array
   *   Each item is an array with the fields:
   *   - expression: string containing the mathematical expression.
   *   - store: boolean indicating that after this expression is executed the
   *     state of the variables should be stored.
   */
  private $mathExpressions = array();

  /**
   * The text labels used in this question.
   *
   * @var associative array of key-TextLabel pairs.
   */
  private $textLabels = array();

  /**
   * Has the question been fully initialised?
   * This has to happen during node_view, to give other modules the time to do
   * their thing.
   *
   * @var boolean
   */
  private $initialised = FALSE;

  /**
   * Default constructor.
   */
  function __construct() {
    $this->usedPath = isset($_GET['q']) ? $_GET['q'] : '';
  }

  /**
   * Initialises this question from the given DOMElement.
   *
   * @param DOMElement $dom
   *   The XML DOMElement to use to initialise this question.
   */
  public function loadXml(DOMElement $dom) {
    // In case loadXml is called by another means than initialise(), we don't
    // want initialise() to run.
    $this->initialised = TRUE;
    foreach ($dom->childNodes as $node) {
      switch ($node->nodeName) {
        case 'prefix':
          $this->prefix = cq_get_text_content($node, $this);
          break;

        case 'postfix':
          $this->postfix = cq_get_text_content($node, $this);
          break;

        case 'matheval':
          $expression = array();
          $expression['expression'] = '';
          $expression['store'] = FALSE;

          $attribs = $node->attributes;
          $item = $attribs->getNamedItem('expression');
          if ($item === NULL) {
            $item = $attribs->getNamedItem('e');
          }
          if ($item !== NULL) {
            $expression['expression'] = $item->value;
          }
          $item = $attribs->getNamedItem('store');
          if ($item !== NULL) {
            $expression['store'] = (boolean) $item->value;
          }
          $this->mathExpressions[] = $expression;
          break;

        case 'textlabel':
          $textlabel = new TextLabel();
          $textlabel->initFromNode($node, $this);
          $labelId = $textlabel->getId();
          if (!is_null($labelId) && strlen($labelId) > 0) {
            if (isset($this->textLabels[$labelId])) {
              drupal_set_message(t('Warning, TextLabels with duplicate ID: %id', array('%id' => $labelId)));
            }
            else {
              $this->textLabels[$labelId] = $textlabel;
            }
          }
          break;
      }
    }

    if (count($this->mathExpressions) > 0) {
      $this->evalMath = new EvalMath();
      $needs_store = FALSE;
      $debugOutput = '';

      $vars = $this->userAnswer->getData('emv');
      $oldVars = FALSE;
      if (!empty($vars)) {
        $oldVars = TRUE;
        $this->evalMath->setVars($vars);
      }

      foreach ($this->mathExpressions as $e) {
        if (!$e['store'] || !$oldVars) {
          $result = $this->evalMath->evaluate($e['expression']);
          if (user_access(CLOSEDQUESTION_RIGHT_CREATE)) {
            $debugOutput .= t('Expression: %e result: %r', array('%e' => $e['expression'], '%r' => $result)) . '<br />';
          }
          if ($e['store']) {
            $vars = $this->evalMath->getVars();
            $this->userAnswer->setData('emv', $vars);
            $needs_store = TRUE;
          }
        }
      }
      if ($needs_store) {
        $this->userAnswer->store();
      }
      if (user_access(CLOSEDQUESTION_RIGHT_CREATE)) {
        $message = closedquestion_make_fieldset('Teacher only debug output', '<p>' . $debugOutput . '</p>', TRUE, TRUE);
        drupal_set_message($message);
      }
    }
  }

  /**
   * Do final initialisation of the question.
   * This has to happen during node_view, to give other modules the time to do
   * their thing.
   */
  public function initialise() {
    if (!$this->initialised) {
      $this->initialised = TRUE;

      // The module inline calls this function too late for us, so we call it now.
      if (module_exists('inline')) {
        // Only nodes with Inline filter in the format may be processed.
        foreach (filter_list_format($this->node->format) as $filter) {
          if ($filter->module == 'inline') {
            $xml = _inline_substitute_tags($this->node, 'body');
            break;
          }
        }
      }
      $firstChild = null;

      if (isset($this->node->body)) {
        $lang = $this->node->language;
        if (!isset($this->node->body[$lang])) {
          $lang = 'und';
          if (!isset($this->node->body[$lang])) {
            $langs = array_keys($this->node->body);
            $lang = $lang[0];
          }
        }
        if (!isset($xml) || empty($xml)) {
          $xml = $this->node->body[$lang][0]['value'];
        }
        $xml = closedquestion_filter_content($this->node, $xml);

        $dom = new DomDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $questions = $xpath->query('/question');
        $firstChild = $questions->item(0);
      }

      if ($firstChild) {
        $this->loadXML($firstChild);
      }
      else {
        drupal_set_message(t('No question found in XML of node %nid.', array('%nid' => $this->node->nid)));
      }
    }
  }

  /**
   * Check if the question is already initialised
   *
   * @return boolean
   */
  public function isInitialised() {
    return $this->initialised;
  }

  /**
   * Get the form/html output of this question.
   *
   * @return string
   *   Themed html.
   */
  abstract public function getOutput();

  /**
   * Get all the text in the question, for easier reviewing for spelling, etc.
   *
   * @return array
   *   Drupal form-api compatible array.
   */
  abstract public function getAllText();

  /**
   * Get the last answer that the user gave to this quetsion. The type of the
   * returned data depends on the question implementation.
   *
   * @return mixed
   *   The last answer.
   */
  public function getAnswer() {
    return $this->userAnswer->getAnswer();
  }

  /**
   * Get the list of hotspots defined in this question.
   * To be overridden by question types that have hotspots.
   *
   * @return array of CqHotspot
   */
  public function getHotspots() {
    return array();
  }

  /**
   * Get the list of draggables defined in this question.
   * To be overridden by question types that have draggables.
   *
   * @return array of CqDraggable
   */
  public function getDraggables() {
    return array();
  }

  /**
   * Form builder for the question-form of this question.
   *
   * Since drupal_get_form can not take a method of an object instance as
   * parameter, the wrapper closedquestion_get_form_for() is used, which will call
   * this method.
   *
   * @see closedquestion_get_form_for()
   * @see submitAnswer()
   * @ingroup forms
   */
  public abstract function getForm($formState);

  /**
   * Form submission handler for getForm()
   * Since the form submit handler can not be the method of an object instance,
   * ClosedQuesion will receive the form_submit, fetch the object from the form
   * cache and forward the submit to the object.
   *
   * @see hook_form_submit()
   */
  public abstract function submitAnswer($form, &$form_state);

  /**
   * Run any checks to see if the last given answer is correct. This method is
   * called by isCorrect() and the result is cached.
   *
   * @return boolean
   *   TRUE if the user answered correctly.
   */
  public abstract function checkCorrect();

  /**
   * Implements CqQuestionInterface::isCorrect()
   */
  public function isCorrect($force = FALSE) {
    if ($force || $this->userAnswer->isCorrect() == -1) {
      $wasCorrect = $this->userAnswer->onceCorrect();
      $this->userAnswer->setCorrect($this->checkCorrect());
      if (!$wasCorrect && $this->userAnswer->isCorrect()) {
        $this->fireFirstSolutionFound();
      }
    }
    return ($this->userAnswer->isCorrect() > 0);
  }

  /**
   * Implements CqQuestionInterface::onceCorrect()
   */
  public function onceCorrect() {
    return $this->userAnswer->onceCorrect();
  }

  /**
   * Implements CqQuestionInterface::getTries()
   */
  public function getTries() {
    return $this->userAnswer->getTries();
  }

  /**
   * Implements CqQuestionInterface::reset()
   */
  public function reset() {
    $this->userAnswer->reset();
  }

  /**
   * Add a tag to the list of tags handled by this object.
   *
   * @param string $tag
   *   The tag to add.
   *
   * @see cq_get_text_content()
   * @see handled_tags()
   */
  public function registerTag($tag) {
    $this->handledTags[] = $tag;
  }

  /**
   * Get the list of tags handled by this object.
   *
   * @return array of string
   *   The list of tags.
   *
   * @see cq_get_text_content()
   * @see handled_tags()
   */
  public function getHandledTags() {
    return $this->handledTags;
  }

  /**
   * Get the html required for implementing the given XML node.
   * cq_get_text_content() will call this method when this object is the
   * $context and it finds an XML node that is listed in $this->handled_tags.
   *
   * @param DOMNone $node
   *   The XML node to handle.
   * @param boolean $delay
   *   If true, some XML nodes are replaced with a [] tag so they can be
   *   processed later. This can be used when not all data needed for full
   *   processing is available yet.
   *
   * @return string
   *   The html that implements the needed functionality.
   *
   * @see cq_get_text_content()
   * @see handled_tags()
   */
  public function handleNode($node, $delay = FALSE) {
    $retval = '';
    switch (drupal_strtolower($node->nodeName)) {
      case 'mathresult':
        $attribs = $node->attributes;
        $item = $attribs->getNamedItem('expression');
        if ($item === NULL) {
          $item = $attribs->getNamedItem('e');
        }
        if ($item !== NULL) {
          $expression = $item->value;
          if (is_null($this->evalMath)) {
            $this->evalMath = new EvalMath();
          }
          if ($delay) {
            $retval .= '[mathresult|' . $expression . ']';
          }
          else {
            $retval .= $this->evalMath->e($expression);
          }
        }
        break;

      case 'feedbackblock':
        $attribs = $node->attributes;
        $item = $attribs->getNamedItem('identifier');
        if ($item === NULL) {
          $item = $attribs->getNamedItem('id');
        }
        if ($item === NULL) {
          drupal_set_message(t('FeedbackBlock without id'));
        }
        else {
          $id = $item->value;
          $retval .= '<span class="cqFbBlock cqFb-' . $id . '" ></span>';
        }
        break;

      case 'textlabelresult':
        $attribs = $node->attributes;
        $item = $attribs->getNamedItem('identifier');
        if ($item === NULL) {
          $item = $attribs->getNamedItem('id');
        }
        if ($item === NULL) {
          drupal_set_message(t('TextLabelResult requested without id.'));
        }
        else {
          $labelId = $item->value;
          $item = $attribs->getNamedItem('mathvariable');
          if ($item === NULL) {
            $item = $attribs->getNamedItem('variable');
          }
          if ($item === NULL) {
            drupal_set_message(t('TextLabelResult requested without variable name.'));
          }
          else {
            $variable = $item->value;
            if ($delay) {
              $retval .= '[textlabelresult|' . $labelId . '|' . $variable . ']';
            }
            else {
              $retval .= $this->handleTag('textlabelresult', $labelId . '|' . $variable);
            }
          }
        }
        break;
    }
    return $retval;
  }

  /**
   * Get the html required for implementing the given tag.
   * cq_replace_tags() will call this method when this object is the $context
   * and it finds a tag that is listed in $this->handled_tags.
   *
   * Tags have to be in the form [tagName|tagData]
   *
   * @param string $tagName
   *   The name of the tag that was found.
   * @param string $tagData
   *   The data of the tag that was found.
   */
  public function handleTag($tagName, $tagData) {
    $retval = '';
    switch ($tagName) {
      case 'mathresult':
        if (is_null($this->evalMath)) {
          $this->evalMath = new EvalMath();
        }
        $retval .= $this->evalMath->e($tagData);
        break;

      case 'textlabelresult':
        $data = explode('|', $tagData);
        $id = $data[0];
        $variable = $data[1];
        if (isset($this->textLabels[$id])) {
          $value = $this->evaluateMath($variable);
          $retval .= $this->textLabels[$id]->getValue($value);
        }
        else {
          $retval .= t('Unknown TextLabel: %s.', array('%s' => $data[0]));
        }
        break;

      case 'feedbackblock':
        $retval .= '<span class="cqFbBlock cqFb-' . $tagData . '" ></span>';
        break;
    }
    return $retval;
  }

  /**
   * Evaluate the given expression in the current evalMath context.
   *
   * @param string $expression
   *   The expression to evaluate
   *
   * @return string
   *   The result of the evaluateion of the expression.
   */
  public function evaluateMath($expression) {
    if (is_null($this->evalMath)) {
      $this->evalMath = new EvalMath();
    }
    if (!empty($expression)) {
      return $this->evalMath->e($expression);
    }
  }

  /**
   * Check if this question has any math associated with it so far.
   *
   * @return boolean
   *   TRUE if there is any math in the question so far, FALSE otherwise.
   */
  public function hasMath() {
    return (!is_null($this->evalMath));
  }

  /**
   * Implements CqQuestionInterface::addListener()
   */
  public function addListener(CqListenerQuestionInterface &$listener) {
    $this->listeners[] = & $listener;
  }

  /**
   * Return an array with all the feedback items that should be active.
   *
   * @return array of CqFeedback
   */
  public abstract function getFeedbackItems();

  /**
   * Inserts the form item that shows the feedback, into the given form.
   *
   * @param array $form
   *   The form into which the feedback should be inserted.
   * @param string $wrapper_id
   *   Array key and HTML id to give the feedback wrapper item. The same id
   *   must be passed to insertSubmit() for the standard feedback-replacement
   *   AHAH functionality.
   *
   * @see insertSubmit()
   */
  public function insertFeedback(&$form, $wrapper_id) {
    // This wrapper will be used for AHAH replacement upon form submit.
    $form[$wrapper_id] = array(
      '#theme_wrappers' => array('container'),
      '#attributes' => array(
        'id' => $wrapper_id,
        'class' => array('cq-feedback-wrapper'),
      ),
    );

    $feedbackItems = $this->getFeedbackItems();
    if (count($feedbackItems) > 0) {
      $attribs = array();
      if (!$this->isCorrect()) {
        $attribs['class'] = array('error');
      }

      $form[$wrapper_id]['feedback'] = array(
        '#type' => 'fieldset',
        '#title' => t('Feedback'),
        '#attributes' => $attribs,
      );
      foreach ($feedbackItems as $nr => $fb) {
        $form[$wrapper_id]['feedback']['cq-feedback-' . $nr] = array(
          '#type' => 'item',
          '#markup' => $fb->getText(),
          '#weight' => $nr,
        );
      }
    }
  }

  /**
   * Inserts the standard form submit element that uses Drupal AHAH to replace
   * the feedback item built by insertFeedback().
   *
   * @param array $form
   *   The form into which the submit should be inserted.
   * @param string $feedback_wrapper_id
   *   The HTML id of the feedback's wrapper element.
   *
   * @see insertFeedback()
   */
  public function insertSubmit(&$form, $feedback_wrapper_id) {
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit Answer'),
      '#ajax' => array(
        'callback' => 'closedquestion_submit_answer_js',
        'wrapper' => $feedback_wrapper_id,
        'method' => 'replace',
        'progress' => array('type' => 'throbber', 'message' => t('Please wait...')),
      ),
    );
  }

  /**
   * Returns the node of this question
   *
   * @return object
   *   Drupal node object.
   */
  public function getNode() {
    return $this->node;
  }

  /**
   * Inform any listeners that the student found the correct solution for the
   * first time.
   */
  public function fireFirstSolutionFound() {
    foreach ($this->listeners as $listener) {
      $listener->FirstSolutionFound($this->userAnswer->getTries());
    }
  }

  /**
   * Ask any listeners if they want to add additional feedback items to the
   * question.
   *
   * @param CqQestion $caller
   *   The question that requests extra feedback.
   * @param int $tries
   *  The number of incorrect tries the student did.
   *
   * @return array of CqFeedback
   */
  public function fireGetExtraFeedbackItems($caller, $tries) {
    $feedback = array();
    foreach ($this->listeners as $listener) {
      $feedback = array_merge($feedback, $listener->getExtraFeedbackItems($caller, $tries));
    }
    return $feedback;
  }

}

