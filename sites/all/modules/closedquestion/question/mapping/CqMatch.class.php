<?php

/**
 * @file
 * CqMatch checks if a hotspot contains a draggable or if a choice has been
 * filled with a certain option.
 * Can also check if one of a set of hotspots contains a draggable, or if one of
 * a set of draggables is in a hotspot or of one of a set of draggables is in
 * one of a set of hotspots.
 */
class CqMatch extends CqAbstractMapping {

  /**
   * Implements CqAbstractMapping::evaluate()
   */
  public function evaluate() {
    $inlinechoice = $this->getParam('inlinechoice');
    $optionId = $this->getParam('inlineoption');
    $pattern = $this->getParam('pattern');
    $matchAll = $this->getParam('matchall');
    $hotspotId = $this->getParam('hotspot');
    $hotspotPattern = $this->getParam('hotspotpattern');
    $draggableId = $this->getParam('draggable');
    $draggablePattern = $this->getParam('draggablepattern');

    if (!is_null($hotspotId) && !is_null($hotspotPattern)) {
      drupal_set_message(t('Match with both hotspot and hotspotpattern set!'), 'warning');
    }
    if (!is_null($hotspotId) && is_null($hotspotPattern)) {
      $hotspotPattern = $hotspotId;
    }
    if (!is_null($draggableId) && !is_null($draggablePattern)) {
      drupal_set_message(t('Match with both draggable and draggablepattern set!'), 'warning');
    }
    if (!is_null($draggableId) && is_null($draggablePattern)) {
      $draggablePattern = $draggableId;
    }

    if ($inlinechoice != NULL && $optionId != NULL) { // FillBlanks-selectbox-type
      return ($this->context->getAnswerForChoice($inlinechoice) == $optionId);
    }
    elseif (!is_null($pattern)) { // FillBlanks-freeform-type
      return $this->evaluatePattern($inlinechoice, (boolean) $matchAll, $pattern);
    }
    elseif (!is_null($hotspotPattern)) {
      // Matching hotspots and draggables.
      $matchAll = drupal_strtolower(drupal_substr($matchAll, 0, 1));
      return $this->evaluateHotspots($hotspotPattern, $draggablePattern, $matchAll);
    }
    else {
      drupal_set_message(t('Warning: Match with a strange combination of attributes found.'), 'warning');
    }
    return FALSE;
  }

  /**
   * Do matching of the answer(s) against a pattern.
   *
   * @param string $inlinechoice
   *   A regular expression to find which inline choices (answers) to match
   *   against the regular expression.
   * @param boolean $matchAll
   *   If there is more than one answer matched by $inlinechoice, do all of them
   *   have to match the pattern, or only one?
   * @param string $pattern
   *   The regular expression pattern to match the answer(s) on.
   *
   * @return boolean
   *   TRUE if the matching was successfull, FALSE otherwise.
   */
  private function evaluatePattern($inlinechoice, $matchAll, $pattern) {
    if (drupal_substr($pattern, 0, 1) != '/') {
      $pattern = '/' . $pattern . '/';
    }
    $answer = $this->context->getAnswerForChoice($inlinechoice);
    if (is_array($answer)) {
      if (count($answer) == 0) {
        return FALSE;
      }
      foreach ($answer as $choiceId => $subAnswer) {
        $this->topParent->lastMatchedId = $choiceId;
        $match = preg_match($pattern, trim($subAnswer));
        if ($match && !$matchAll) {
          // This sub-answer mached, and we need only one to match, so this
          // range matches, return TRUE.
          return TRUE;
        }
        if (!$match && $matchAll) {
          // This sub-answer did not match, and we need all of 'em to match
          // for true, so the match failed, return FALSE.
          return FALSE;
        }
      }
      if ($matchAll) {
        // We needed all to match, since we got here, none failed to match,
        // return TRUE
        return TRUE;
      }
      else {
        // We needed only one to match, but since we got here none matched,
        // return FALSE
        return FALSE;
      }
    }
    else {
      $this->topParent->lastMatchedId = $inlinechoice;
      return preg_match($pattern, trim($answer));
    }

    return FALSE; // Should be unreachable.
  }

  /**
   * Match hotspots with draggables.
   *
   * @param string $hotspotPattern
   *   The regular expression used to select which hotspots to match.
   * @param string $draggablePattern
   *   The regular expression used to select with draggables to match.
   * @param string $matchAll
   *   A string of length 1, indicating if:
   *   (h)otspots: All selected hotspots should have one of the selected
   *     draggable.
   *   (d)raggable: All selected draggables should be on one of the selected
   *     hotspots.
   *   (b)oth: All selected hotspots should have one of the selected draggables
   *     and all selected draggables should be on one of the selected hotspots.
   *
   * @return boolean
   *   TRUE if any of the hotspots mached any of the draggables.
   */
  private function evaluateHotspots($hotspotPattern, $draggablePattern, $matchAll=FALSE) {
    $allHotspots = $this->context->getHotspots();
    $allDraggables = $this->context->getDraggables();
    $hotspots = array();
    $draggables = array();

    // Find the hotspots to operate on
    if ($hotspotPattern != NULL) {
      // First we check if the pattern is an exact match.
      if (isset($allHotspots[$hotspotPattern])) {
        $hotspots[] = $allHotspots[$hotspotPattern];
      }
      else {
        // Not an exact match, do regex matching.
        if (drupal_substr($hotspotPattern, 0, 1) != '/') {
          $hotspotPattern = '/' . $hotspotPattern . '/';
        }
        foreach ($allHotspots as $hotspot) {
          if (preg_match($hotspotPattern, $hotspot->getIdentifier())) {
            $hotspots[] = $hotspot;
          }
        }
      }
    }
    if (count($hotspots) == 0) {
      drupal_set_message(t('Warning: no hotspots found with: %id', array('%id' => $hotspotPattern)), 'warning');
      return FALSE;
    }

    // Find the draggables to operate on
    if ($draggablePattern != NULL) {
      // First check if the pattern is an exact match.
      if (isset($allDraggables[$draggablePattern])) {
        $draggables[] = $allDraggables[$draggablePattern];
      }
      else {
        // Not an exact match, do regex matching.
        if (drupal_substr($draggablePattern, 0, 1) != '/') {
          $draggablePattern = '/' . $draggablePattern . '/';
        }
        foreach ($allDraggables as $draggable) {
          if (preg_match($draggablePattern, $draggable->getIdentifier())) {
            $draggables[] = $draggable;
          }
        }
      }
    }
    if (is_null($draggablePattern)) {
      $draggables = $allDraggables;
    }
    if (count($draggables) == 0 && !is_null($draggablePattern)) {
      drupal_set_message(t('Warning: no draggables found with %id', array('%id' => $draggablePattern)), 'warning');
      return FALSE;
    }

    // Do the matching
    if ($matchAll == 'd' || $matchAll == 'b') {
      foreach ($draggables as $draggable) {
        foreach ($hotspots as $hotspot) {
          if ($hotspot->doMatch($draggable->getLocation())) {
            // This draggable has a match, skip all further hotspots and do the
            // next draggable.
            continue 2;
          }
        }
        // This draggable did not have any match. Return FALSE.
        return FALSE;
      }
      if ($matchAll == 'd') {
        // All draggables had a match. We only needed to check all draggables.
        return TRUE;
      }
    }

    if ($matchAll == 'h' || $matchAll == 'b') {
      foreach ($hotspots as $hotspot) {
        foreach ($draggables as $draggable) {
          if ($hotspot->doMatch($draggable->getLocation())) {
            // This hotspot has a draggable, skip to the next hotspot.
            continue 2;
          }
        }
        // This hotspot did not have any draggables. Return FALSE.
        return FALSE;
      }
      // All hotspots had a match. If matchAll was 'b' then all draggables also
      // had a match, so we can return TRUE.
      return TRUE;
    }

    if ($matchAll) {
      drupal_set_message(t('Unknown matchAll value: %matchall, expected one of h(otspot),d(raggable) or b(oth)', array('%matchall' => $matchAll)));
    }
    foreach ($hotspots as $hotspot) {
      foreach ($draggables as $draggable) {
        if ($hotspot->doMatch($draggable->getLocation())) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * Overrides CqAbstractMapping::getAllText()
   */
  public function getAllText() {
    $inlinechoice = $this->getParam('inlinechoice');
    $optionId = $this->getParam('inlineoption');
    $pattern = $this->getParam('pattern');
    $matchAll = $this->getParam('matchall');
    $hotspotId = $this->getParam('hotspot');
    $hotspotPattern = $this->getParam('hotspotpattern');
    $draggableId = $this->getParam('draggable');
    $draggablePattern = $this->getParam('draggablepattern');

    if (!is_null($hotspotId) && !is_null($hotspotPattern)) {
      drupal_set_message(t('Match with both hotspot and hotspotpattern set!'), 'warning');
    }
    if (!is_null($hotspotId) && is_null($hotspotPattern)) {
      $hotspotPattern = $hotspotId;
    }

    if (!is_null($draggableId) && !is_null($draggablePattern)) {
      drupal_set_message(t('Match with both draggable and draggablepattern set!'), 'warning');
    }
    if (!is_null($draggableId) && is_null($draggablePattern)) {
      $draggablePattern = $draggableId;
    }

    $retval = array();
    $retval['logic']['#markup'] = t('Match %choice %hotspot against %option %draggable %pattern',
        array(
          '%choice' => $inlinechoice,
          '%hotspot' => $hotspotPattern,
          '%option' => $optionId,
          '%pattern' => $pattern,
          '%draggable' => $draggablePattern,
      ));
    if (!is_null($matchAll)) {
      $retval['logic']['#markup'] .= t('<br/>Match all = %matchall', array('%matchall' => $matchAll));
    }

    $retval += parent::getAllText();
    return $retval;
  }

}