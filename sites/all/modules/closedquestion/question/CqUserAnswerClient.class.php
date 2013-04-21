<?php

/**
 * @file
 * CqUserAnswerClient is a CqUserAnswerInterface implementation that multiplexes
 * it's data into a parent CqUserAnswerInterface implementation. This way a node
 * can store the data of more than one question.
 *
 * Used by sequence questions, that are questions consisting of several
 * sub-questions.
 *
 * The data of the parent is assumed to be an associative array. Each client
 * puts it's data in that array with it's assigned key.
 *
 * @see CqQuestionSequence()
 */
class CqUserAnswerClient implements CqUserAnswerInterface {

  /**
   * The CqUserAnswerInterface that we use to store our answer.
   *
   * @var CqUserAnswer
   */
  private $parentUserAnswer;
  /**
   * The key to use when adding data to the parent CqUserAnswerInterface.
   *
   * @var string
   */
  private $key;
  /**
   * The answer-data as it is stored in the parent.
   *
   * @var array
   *   Associative array
   *   - sa: The serialised answer.
   *   - tries: The number of tries.
   *   - onceCorrect: The onceCorrect property.
   */
  private $parentAnswer;
  /**
   * The user's answer.
   *
   * @var mixed
   */
  private $answer;
  /**
   * Additional data, as key/value pairs.
   *
   * @var array
   */
  private $data;
  /**
   * The correct value of the current answer.
   * 1: correct
   * 0: incorrect
   * -1: (default) correctness not determined yet.
   *
   * @var int
   */
  private $isCorrect = -1;
  /**
   * The previous value of the nuber of tries.
   *
   * @var int
   */
  private $origTries;

  /**
   * Creates a new CqUserAnswerClient
   *
   * @param CqUserAnswerInterface $parentUserAnswer
   *   The parent CqUserAnswerInterface used to store data.
   * @param string $key
   *   The key used to store data in the parent UserAnswer
   */
  public function __construct(CqUserAnswerInterface &$parentUserAnswer, $key) {
    $this->parentUserAnswer = & $parentUserAnswer;
    $this->key = $key;

    $parentAnswer = $this->parentUserAnswer->getAnswer();
    if (isset($parentAnswer[$this->key])) {
      $this->parentAnswer = $parentAnswer[$this->key];
      $this->answer = unserialize($this->parentAnswer['sa']);
      $this->data = $this->parentUserAnswer->getData($this->key);
    }
    else {
      $this->parentAnswer['tries'] = 0;
      $this->parentAnswer['onceCorrect'] = 0;
    }
    $this->origTries = $this->parentAnswer['tries'];
  }

  /**
   * Implements CqUserAnser::store()
   */
  public function store() {
    $seranswer = serialize($this->answer);
    if ($seranswer != $this->parentAnswer['sa'] || $this->origTries != $this->parentAnswer['tries']) {
      $this->parentAnswer['sa'] = $seranswer;
      $parentAnswer = $this->parentUserAnswer->getAnswer();
      $parentAnswer[$this->key] = $this->parentAnswer;
      $this->parentUserAnswer->setAnswer($parentAnswer);
    }
    $this->parentUserAnswer->setData($this->key, $this->data);
    $this->parentUserAnswer->store();
  }

  /**
   * Implements CqUserAnser::getAnswer()
   */
  public function getAnswer() {
    return $this->answer;
  }

  /**
   * Implements CqUserAnser::setAnswer()
   */
  public function setAnswer($newAnswer) {
    $this->answer = $newAnswer;
  }

  /**
   * Implements CqUserAnser::reset()
   */
  public function reset() {
    $this->parentAnswer = array();
    $this->parentAnswer['tries'] = 0;
    $this->isCorrect = -1;
    $this->parentAnswer['onceCorrect'] = 0;
    $this->answer = '';
    $this->parentAnswer['sa'] = serialize($this->answer);
    $this->store();
  }

  /**
   * Implements CqUserAnser::answerHasChanged()
   */
  public function answerHasChanged() {
    $seranswer = serialize($this->answer);
    return ($seranswer != $this->parentAnswer['sa']);
  }

  /**
   * Implements CqUserAnser::getTries()
   */
  public function getTries() {
    return $this->parentAnswer['tries'];
  }

  /**
   * Implements CqUserAnser::setTries()
   */
  public function setTries($tries) {
    $this->parentAnswer['tries'] = $tries;
  }

  /**
   * Implements CqUserAnser::increaseTries()
   */
  public function increaseTries() {
    $this->parentAnswer['tries']++;
  }

  /**
   * Implements CqUserAnser::isEmpty()
   */
  public function isEmpty() {
    return empty($this->answer);
  }

  /**
   * Implements CqUserAnser::isCorrect()
   */
  public function isCorrect() {
    return $this->isCorrect;
  }

  /**
   * Implements CqUserAnser::onceCorrect()
   */
  public function onceCorrect() {
    return $this->parentAnswer['onceCorrect'];
  }

  /**
   * Implements CqUserAnser::setCorrect()
   */
  public function setCorrect($correct) {
    $this->isCorrect = (int) $correct;
    if ($correct && !$this->parentAnswer['onceCorrect']) {
      $this->parentAnswer['onceCorrect'] = $this->parentAnswer['tries'] + 1;
    }
  }

  /**
   * Implements CqUserAnser::getData()
   */
  public function getData($key) {
    return $this->data[$key];
  }

  /**
   * Implements CqUserAnser::setData()
   */
  public function setData($key, $value) {
    $this->data[$key] = $value;
  }

}
