<?php

/**
 * @file
 * CqMatchRange checks if one or more inlineChoice(s) contains a numeric value
 * in a certain range, or if a certain given value is in a certain range.
 */
class CqMatchRange extends CqAbstractMapping {

  /**
   * Implements CqAbstractMapping::evaluate()
   */
  function evaluate() {
    $choiceId = $this->getParam('inlinechoice');
    $matchAll = $this->getParam('matchall');
    $minval = $this->getParam('minval');
    if (!is_null($minval) && !is_numeric($minval)) {
      // Non numeric minval, probably a math expression.
      $minval = $this->context->evaluateMath($minval);
    }

    $maxval = $this->getParam('maxval');
    if (!is_null($maxval) && !is_numeric($maxval)) {
      // Non numeric minval, probably a math expression.
      $maxval = $this->context->evaluateMath($maxval);
    }

    $value = $this->getParam('value');
    if (!is_null($value) && !is_numeric($value)) {
      // Non numeric value, probably a math expression.
      $value = $this->context->evaluateMath($value);
    }

    if (is_null($choiceId) && is_null($value)) {
      drupal_set_message(t('Range without inlineChoice or value attribute found.'), 'warning');
    }
    if (!is_null($choiceId)) {
      $answer = $this->context->getAnswerForChoice($choiceId);
    }
    else {
      $answer = $value;
    }

    if (!is_null($answer)) {
      if (is_array($answer)) {
        foreach ($answer as $subChoice => $subAnswer) {
          $this->topParent->lastMatchedId = $subChoice;

          $subAnswer = closedquestion_fix_number($subAnswer);
          $matched = TRUE;
          if (!is_null($minval) && $subAnswer < $minval) {
            $matched = FALSE;
          }
          if (!is_null($maxval) && $subAnswer > $maxval) {
            $matched = FALSE;
          }
          if (!$matchAll && $matched) {
            // This sub-answer mached, and we need only one to match, so this
            // range matches, return TRUE.
            return TRUE;
          }
          if ($matchAll && !$matched) {
            // This sub-answer did not match, and we need all of 'em to match
            // for true, so the match failed, return FALSE.
            return FALSE;
          }
        }
        // If we did not return sooner then the result of the last one is final.
        return $matched;
      }
      else {
        $this->topParent->lastMatchedId = $choiceId;

        $answer = closedquestion_fix_number($answer);
        if (!is_null($minval) && $answer < $minval) {
          return FALSE;
        }
        if (!is_null($maxval) && $answer > $maxval) {
          return FALSE;
        }
        return TRUE;
      }
    }
    else {
      return FALSE;
    }
  }

  /**
   * Overrides CqAbstractMapping::getAllText()
   */
  public function getAllText() {
    $choiceId = $this->getParam('inlinechoice');
    $value = $this->getParam('value');

    $minval = $this->getParam('minval');
    if (is_null($minval)) {
      $minval = '-&infin;';
    }
    else {
      $minval = check_plain($minval);
    }

    $maxval = $this->getParam('maxval');
    if (is_null($maxval)) {
      $maxval = '&infin;';
    }
    else {
      $maxval = check_plain($maxval);
    }

    $retval = array();

    if (!is_null($choiceId)) {
      $retval['logic']['#markup'] = t('Range: choiceId=%id, minval=!minval, maxval=!maxval.',
          array(
            '%id' => $choiceId,
            '!minval' => $minval,
            '!maxval' => $maxval,
        ));
    }
    elseif (!is_null($value)) {
      $retval['logic']['#markup'] = t('Range: value=%id, minval=!minval, maxval=!maxval.',
          array(
            '%id' => $value,
            '!minval' => $minval,
            '!maxval' => $maxval,
        ));
    }
    else {
      $retval['logic']['#markup'] = t('Range: minval=!minval, maxval=!maxval.',
          array(
            '!minval' => $minval,
            '!maxval' => $maxval,
        ));
    }

    $retval += parent::getAllText();
    return $retval;
  }

}