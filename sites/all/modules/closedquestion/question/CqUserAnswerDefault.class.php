<?php

/**
 * @file
 * CqUserAnswerDefault is the default implementation of CqUserAnswerInterface.
 *
 * It stores data for authenticated users in the database and data for anonymous
 * users in the session of the user.
 */
class CqUserAnswerDefault implements CqUserAnswerInterface {

  /**
   * The answer set by the user.
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
   * The number of times the student gave a wrong answer.
   *
   * @var int
   */
  private $tries = 0;
  /**
   * Is the current answer the correct answer?
   * 1: yes
   * 0: no
   * -1: correctness not determined yet.
   *
   * @var int
   */
  private $isCorrect = -1;
  /**
   * Has the student ever answered this question correct, even though his
   * current answer might be incorrect?
   *
   * @var int
   */
  private $onceCorrect = 0;
  /**
   * The serialised version of the previous answer. Used to see if anything
   * changed.
   *
   * @var string
   */
  private $origAnswerSerialised;
  /**
   * The serialised version of the previous data. Used to see if anything
   * changed.
   *
   * @var string
   */
  private $origDataSerialised;
  /**
   * The previous version of the tries parameter. Used to see if anything
   * changed.
   *
   * @var int
   */
  private $origTries;
  /**
   * The previous version of the onceCorrect parameter. Used to see if anything
   * changed.
   *
   * @var int
   */
  private $origCorrect;
  /**
   * The node id of the Drupal node this UserAnswer belongs to.
   *
   * @var int
   */
  private $nodeId;
  /**
   * The user id of the Drupal user.
   *
   * @var int
   */
  private $userId;
  /**
   * Is there already an answer in the database? If so, we need to UPDATE and
   * not INSERT. Default=FALSE
   *
   * @var boolean
   */
  private $inDatabase = FALSE;

  /**
   * Constructs a new CqUserAnswerDefault
   *
   * @param int $nid
   *   The node id this answer belongs to.
   * @param int $uid
   *   The user id of the user this answer belongs to.
   */
  public function __construct($nid, $uid) {
    $this->nodeId = (int) $nid;
    $this->userId = (int) $uid;
    $row = FALSE;

    if ($this->userId > 0 && $this->nodeId > 0) {
      $row = $this->fetchFromDatabase();
    }
    elseif ($this->nodeId > 0) {
      if (isset($_SESSION['cq']['answers'][$nid]) && variable_get('closedquestion_anonymous_user_save_answer')) {
        $row = $_SESSION['cq']['answers'][$nid];
      }
    }
    if ($row) {
      $this->origAnswerSerialised = $row['answer'];
      $this->origDataSerialised = $row['data'];
      $this->onceCorrect = $row['once_correct'];
      $this->origCorrect = $row['once_correct'];
      $this->origTries = $row['tries'];
      $this->answer = unserialize($this->origAnswerSerialised);
      $this->data = unserialize($this->origDataSerialised);
      $this->tries = $this->origTries;
      $this->inDatabase = TRUE;
    }
  }

  /**
   * Implements CqUserAnser::store()
   */
  public function store() {
    $serdata = serialize($this->data);
    $seranswer = serialize($this->answer);
    if ($serdata != $this->origDataSerialised ||
      $seranswer != $this->origAnswerSerialised ||
      $this->origTries != $this->tries ||
      $this->origCorrect != $this->onceCorrect) {

      if ($this->userId > 0 && $this->nodeId > 0) {
        if ($this->inDatabase) {
          $this->update($seranswer, $serdata, $this->onceCorrect, $this->tries, $this->userId, $this->nodeId);
        }
        else {
          // Sometimes there is a record in the database not inserted by this instance of CqUserAnswerDefault (simultanious requests?)
          $row = $this->fetchFromDatabase();
          if ($row) {
            $this->update($seranswer, $serdata, $this->onceCorrect, $this->tries, $this->userId, $this->nodeId);
          }
          else {
            $this->insert($seranswer, $serdata, $this->onceCorrect, $this->tries, $this->userId, $this->nodeId);
          }
          $this->inDatabase = TRUE;
        }
        db_insert('cq_user_answer_log')
          ->fields(array(
            'answer' => $seranswer,
            'once_correct' => $this->onceCorrect,
            'tries' => $this->tries,
            'uid' => $this->userId,
            'nid' => $this->nodeId,
            'unixtime' => REQUEST_TIME,
          ))
          ->execute();
      }
      elseif ($this->nodeId > 0 && variable_get('closedquestion_anonymous_user_save_answer')) {
        $row = array(
          'answer' => $seranswer,
          'data' => $serdata,
          'once_correct' => $this->onceCorrect,
          'tries' => $this->tries
        );
        $_SESSION['cq']['answers'][$this->nodeId] = $row;
      }
      $this->origAnswerSerialised = $seranswer;
      $this->origDataSerialised = $serdata;
      $this->origTries = $this->tries;
      $this->origCorrect = $this->onceCorrect;
    }
  }

  /**
   * Fetch the answer for this UserAnswer from the database.
   *
   * @return array
   *   An array containing the db results.
   */
  private function fetchFromDatabase() {
    $result = db_query('SELECT answer, data, once_correct, tries, unixtime FROM {cq_user_answer} WHERE uid = :uid AND nid = :nid', array(
        ':uid' => $this->userId,
        ':nid' => $this->nodeId,
      ));
    return $result->fetchAssoc();
  }

  /**
   * Insert a new answer into the database.
   *
   * @param string $seranswer
   *   The serialised version of the answer.
   * @param string $serdata
   *   The serialised version of the additional data.
   * @param int $onceCorrect
   *   The onceCorrect status of the answer.
   * @param int $tries
   *   The number of tries.
   * @param int $userId
   *   The user id of the owning user.
   * @param int $nodeId
   *   The node id of the owning node.
   *
   * @return mixed
   *   Status result of the db_insert call.
   */
  private function insert($seranswer, $serdata, $onceCorrect, $tries, $userId, $nodeId) {
    return db_insert('cq_user_answer')
      ->fields(array(
        'answer' => $seranswer,
        'data' => $serdata,
        'once_correct' => $onceCorrect,
        'tries' => $tries,
        'uid' => $userId,
        'nid' => $nodeId,
        'unixtime' => REQUEST_TIME,
      ))
      ->execute();
  }

  /**
   * Update an answer in the database.
   *
   * @param string $seranswer
   *   The serialised version of the answer.
   * @param string $serdata
   *   The serialised version of the additional data.
   * @param int $onceCorrect
   *   The onceCorrect status of the answer.
   * @param int $tries
   *   The number of tries.
   * @param int $userId
   *   The user id of the owning user.
   * @param int $nodeId
   *   The node id of the owning node.
   *
   * @return mixed
   *   Status result of the db_update call.
   */
  private function update($seranswer, $serdata, $onceCorrect, $tries, $userId, $nodeId) {
    return db_update('cq_user_answer')
      ->fields(array(
        'answer' => $seranswer,
        'data' => $serdata,
        'once_correct' => $onceCorrect,
        'tries' => $tries,
        'unixtime' => REQUEST_TIME,
      ))
      ->condition('uid', $userId)
      ->condition('nid', $nodeId)
      ->execute();
  }

  /**
   * Implements CqUserAnser::getAnser()
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
    if ($this->userId > 0 && $this->nodeId > 0) {

      db_delete('cq_user_answer')
        ->condition('uid', $this->userId)
        ->condition('nid', $this->nodeId)
        ->execute();
      db_insert('cq_user_answer_log')
        ->fields(array(
          'answer' => 'Reset',
          'once_correct' => 0,
          'tries' => 0,
          'uid' => $this->userId,
          'nid' => $this->nodeId,
          'unixtime' => REQUEST_TIME,
        ))
        ->execute();

      $this->inDatabase = FALSE;
      $this->answer = NULL;
      $this->tries = 0;
      $this->isCorrect = -1;
      $this->onceCorrect = 0;
      $this->origDataSerialised = '';
      $this->origTries = -1;
    }
    elseif ($this->nodeId > 0) {
      if (isset($_SESSION['cq']['answers'][$this->nodeId])) {
        unset($_SESSION['cq']['answers'][$this->nodeId]);
      }
    }
  }

  /**
   * Implements CqUserAnser::answerHasChanged()
   */
  public function answerHasChanged() {
    $seranswer = serialize($this->answer);
    return ($seranswer != $this->origAnswerSerialised);
  }

  /**
   * Implements CqUserAnser::getTries()
   */
  public function getTries() {
    return $this->tries;
  }

  /**
   * Implements CqUserAnser::setTries()
   */
  public function setTries($tries) {
    $this->tries = $tries;
  }

  /**
   * Implements CqUserAnser::increaseTries()
   */
  public function increaseTries() {
    $this->tries++;
  }

  /**
   * Implements CqUserAnser::isCorrect()
   */
  public function isCorrect() {
    return $this->isCorrect;
  }

  /**
   * Implements CqUserAnser::isEmpty()
   */
  public function isEmpty() {
    return drupal_strlen((string) $this->answer) <= 0;
  }

  /**
   * Implements CqUserAnser::onceCorrect()
   */
  public function onceCorrect() {
    return $this->onceCorrect;
  }

  /**
   * Implements CqUserAnser::setCorrect()
   */
  public function setCorrect($correct) {
    $this->isCorrect = (int) $correct;
    if ($correct && !$this->onceCorrect) {
      $this->onceCorrect = $this->tries + 1;
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
