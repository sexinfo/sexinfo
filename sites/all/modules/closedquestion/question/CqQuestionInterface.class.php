<?php


/**
 * @file
 * Interface containing the set of functions external modules can expect from a
 * question.
 */
interface CqQuestionInterface {

  /**
   * Check if the user answered the quetsion correctly.
   *
   * @return boolean
   *   TRUE if the current answer of the user is the correct answer, FALSE
   *   otherwise.
   */
  public function isCorrect();

  /**
   * Get the number of tries the user needed to find the correct answer,
   * or 0 if the user has not found the correct answer yet.
   *
   * @return int
   *   The number of tries needed to find the correct answer.
   */
  public function onceCorrect();

  /**
   * Get the number of incorrect answers the user has given. This can be larger
   * than the number returned by onceCorrect, if the user, after finding the
   * correct answer, tries more incorrect answers just to see the feedback.
   *
   * @return int
   *   The number of incorrect answers given.
   */
  public function getTries();

  /**
   * Resets the answer and tries counts of the question.
   */
  public function reset();

  /**
   * Adds a listener to the question.
   *
   * @param CqListenerQuestionInterface $listener
   *   The listener to add.
   */
  public function addListener(CqListenerQuestionInterface &$listener);
}

