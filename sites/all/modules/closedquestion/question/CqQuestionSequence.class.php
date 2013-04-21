<?php

/**
 * @file
 * Implementation of the Sequence question type. A Sequence is a set of questions
 * that behave as being one multi-part question.
 */
class CqQuestionSequence extends CqQuestionAbstract implements CqListenerQuestionInterface {

  /**
   * The list of questions in the sequence.
   *
   * @var array of CqQuestion
   */
  private $subQuestions = array();
  /**
   * The index of the question in the sequence that the student is currently
   * making.
   *
   * @var int
   */
  private $currentIndex = 0;
  /**
   * The bit of html with the links to the previous and next questions.
   *
   * @var string
   */
  private $backNext = '';
  /**
   * Toggle to see if the back and next links have been generated yet.
   *
   * @var boolean
   */
  private $backNextFixed = FALSE;

  /**
   * Constructs a Select&Order question object
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
  }

  /**
   * Implements CqQuestionAbstract::getFeedbackItems()
   */
  public function getFeedbackItems() {
    $feedback = $this->subQuestions[$this->currentIndex]->getFeedbackItems();
    $feedback = array_merge($feedback, $this->fireGetExtraFeedbackItems($this, $tries));
    return $feedback;
  }

  /**
   * Overrides CqQuestionAbstract::getFeedbackFormItem()
   */
  public function getFeedbackFormItem() {
    return $this->subQuestions[$this->currentIndex]->getFeedbackFormItem();
  }

  /**
   * Implements CqQuestionAbstract::submitAnswer()
   */
  public function submitAnswer($form, &$form_state) {
    $this->subQuestions[$this->currentIndex]->submitAnswer($form, $form_state);
    $this->isCorrect(TRUE);
    $this->userAnswer->store();
    $this->fixBackNextLinks();
  }

  /**
   * Implements CqQuestionAbstract::checkCorrect()
   */
  public function checkCorrect() {
    $retval = TRUE;
    $tries = 0;
    foreach ($this->subQuestions as $question) {
      $tries += max(0, $question->onceCorrect() - 1);
      if (!$question->isCorrect()) {
        $retval = FALSE;
      }
    }
    $this->userAnswer->setTries($tries);
    return $retval;
  }

  /**
   * Implements CqQuestionAbstract::getOutput()
   */
  public function getOutput() {
    $this->initialise();
    $retval = '';
    if (!$this->backNextFixed) {
      $this->fixBackNextLinks();
    }
    $form['backnext'] = array(
      '#type' => 'item',
      '#markup' => $this->backNext,
      '#prefix' => '<h2>',
      '#suffix' => '</h2>',
    );
    if (isset($this->subQuestions[$this->currentIndex])) {
      $form['question'] = array(
        'cur_question' => $this->subQuestions[$this->currentIndex]->getOutput(),
        '#prefix' => $this->prefix,
        '#suffix' => $this->postfix,
      );
    }
    return $form;
  }

  /**
   * Overrides CqQuestionAbstract::loadXml()
   */
  public function loadXml(DOMElement $dom) {
    parent::loadXml($dom);
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');

    $i = 0;
    foreach ($dom->childNodes as $child) {
      if (drupal_strtolower($child->nodeName) == 'question') {
        $uac = & new CqUserAnswerClient($this->userAnswer, $i);

        $question = & cq_question_from_dom_element($child, $uac, $this->node);
        if ($question) {
          $question->loadXML($child);
          $this->subQuestions[] = & $question;
          $question->addListener($this);
          unset($question);
          $i++;
        }
      }
    }

    $answer = $this->userAnswer->getAnswer();
    if (isset($_REQUEST['CqQS_' . $this->node->nid . '_Step'])) {
      $this->currentIndex = (int) $_REQUEST['CqQS_' . $this->node->nid . '_Step'];
      $answer['ci'] = $this->currentIndex;
      $this->userAnswer->setAnswer($answer);
      $this->userAnswer->store();
    }
    else {
      $this->currentIndex = (int) $answer['ci'];
    }
    if ($this->currentIndex < 0 || $this->currentIndex >= count($this->subQuestions)) {
      $this->currentIndex = 0;
    }
  }

  /**
   * Implements CqQuestionAbstract::getForm()
   */
  public function getForm($formState) {
    $retval = $this->subQuestions[$this->currentIndex]->getForm($formState);
    return $retval;
  }

  /**
   * Overrides CqQuestionAbstract::reset()
   */
  public function reset() {
    $this->userAnswer->reset();
  }

  /**
   * Implements CqQuestionAbstract::getAllText()
   */
  public function getAllText() {
    $this->initialise();
    $retval = array();
    $nr = 0;
    foreach ($this->subQuestions as $question) {
      $nr++;
      $retval['items'][] = array(
        'title' => t('Sub-Question @nr', array('@nr' => $nr)),
        'question' => $question->getAllText(),
      );
    }
    $retval['#theme'] = 'closedquestion_question_sequence_text';
    return $retval;
  }

  /**
   * Creates the back and next links to the previous and next question in the
   * sequence, if they exist.
   */
  private function fixBackNextLinks() {
    $path = $this->usedPath;
    $prevUrl = '';
    $nextUrl = '';
    if ($this->currentIndex > 0 && $this->subQuestions[$this->currentIndex - 1]->isCorrect()) {
      $prevUrl = url(
          $path,
          array(
            'query' => array(
              'CqQS_' . $this->node->nid . '_Step' => $this->currentIndex - 1,
            ),
            'fragment' => 'node-' . $this->node->nid,
          )
      );
    }
    if ($this->currentIndex + 1 < count($this->subQuestions) && $this->subQuestions[$this->currentIndex]->isCorrect()) {
      $nextUrl = url(
          $path,
          array(
            'query' => array(
              'CqQS_' . $this->node->nid . '_Step' => $this->currentIndex + 1,
            ),
            'fragment' => 'node-' . $this->node->nid,
          )
      );
    }
    $variables = array(
      'index' => $this->currentIndex,
      'total' => count($this->subQuestions),
      'prev_url' => $prevUrl,
      'next_url' => $nextUrl,
    );
    $this->backNext = theme('closedquestion_sequence_back_next', $variables);
    $this->backNextFixed = TRUE;
  }

  /**
   * Implements CqQuestionAbstract::FirstSolutionFound()
   */
  public function FirstSolutionFound($tries) {
    // A sub question is answered correctly, but we don't need that info.
  }

  /**
   * Implements CqQuestionAbstract::getExtraFeedbackItems()
   */
  public function getExtraFeedbackItems($caller, $tries) {
    $retval = array();
    if (!empty($this->backNext)) {
      $fbItem = new CqFeedback();
      $fbItem->initWithValues($this->backNext, 0, 9999);
      $retval[] = $fbItem;
    }
    $retval = array_merge($retval, $this->fireGetExtraFeedbackItems($this, $tries));
    return $retval;
  }

}
