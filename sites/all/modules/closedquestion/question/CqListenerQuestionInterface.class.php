<?php

/**
 * @file
 * Defines the events a question listener can listen for.
 */
interface CqListenerQuestionInterface {

  /**
   * Fired when the question is solved for the first time.
   *
   * @param int $tries
   *   Number of tries needed.
   */
  public function FirstSolutionFound($tries);

  /**
   * Fired when the question is generating it's feedback items, in case
   * the listener wants to add items of it's own.
   *
   * @param CqQuestionInterface $caller
   *   The quetsion that wants to know if there is extra feedback to display.
   * @param int $tries
   *   Number of tries needed.
   */
  public function getExtraFeedbackItems($caller, $tries);
}

