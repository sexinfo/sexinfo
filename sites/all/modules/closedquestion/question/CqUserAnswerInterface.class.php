<?php

/**
 * @file
 * UserAnswer is an interface and implementing classes for storing a users
 * answer, the number of tries a user needed so far, and wether a user has given
 * a correct answer at least once. The "answer" can be any serializable data
 * type.
 *
 * Changes to the answer, tries or correct status is not written untill the
 * store() method is called.
 *
 * The number of tries should, in general, not be increased for "correct"
 * answers.
 *
 * If the "correctness" has been set to TRUE once then the value of onceCorrect
 * will be set to the current number of tries + 1. Further changes to the
 * correctness will not change this value any further. This is done so the
 * student can explore a question for feedback even after answering a question
 * correctly.
 *
 * UserAnswer can also store additional data key/value pairs for a question,
 * using the getData and setData methods.
 */
interface CqUserAnswerInterface {

  /**
   * Tell the implementation to write data to the backend.
   */
  public function store();

  /**
   * Fetch the answer.
   */
  public function getAnswer();

  /**
   * Set the answer.
   *
   * @param mixed $newAnswer
   *   The new answer
   */
  public function setAnswer($newAnswer);

  /**
   * Clear the answer, tries and correct status.
   */
  public function reset();

  /**
   * Has anything changed since the last time store() was called?
   */
  public function answerHasChanged();

  /**
   * Return the number of tries the user needed.
   */
  public function getTries();

  /**
   * Set the number of tries the user needed to a specific value.
   *
   * @param int $tries
   *   The value to set the number of tries to.
   */
  public function setTries($tries);

  /**
   * Increase the number of tries by 1.
   */
  public function increaseTries();

  /**
   * Is the current answer the correct aswer?
   *
   * @return boolean
   *   TRUE if the current answer was set to be correct.
   */
  public function isCorrect();

  /**
   * Check if an answer has been set.
   *
   * @return boolean
   *   TRUE if an answer was set, FALSE if no answer has been set.
   */
  public function isEmpty();

  /**
   * @return int
   *   The number of tries the user needed the first time he answered the
   *   question correctly, or 0 if the user has not yet answered the question
   *   correctly.
   */
  public function onceCorrect();

  /**
   * Set the correct value of the current answer.
   *
   * @param boolean $correct
   *   The correct value of the current answer.
   */
  public function setCorrect($correct);

  /**
   * Get additional data stored under the given key.
   *
   * @param string $key
   *   The key under which the requested data is stored.
   *
   * @return mixed
   *   The additional data that was stored for the given key.
   */
  public function getData($key);

  /**
   * Store additional data.
   *
   * @param string $key
   *   The key under which the requested data is stored.
   * @param string $value
   *   The data to store
   */
  public function setData($key, $value);
}
