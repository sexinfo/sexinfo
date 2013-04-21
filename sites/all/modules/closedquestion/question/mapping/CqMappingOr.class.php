<?php

/**
 * @file
 * "OR" implementation for mappings. Returns TRUE if at least one of the
 * children return TRUE.
 */
class CqMappingOr extends CqAbstractMapping {

  /**
   * Implements CqAbstractMapping::evaluate()
   */
  function evaluate() {
    foreach ($this->children AS $id => $tempExpression) {
      if ($tempExpression->evaluate()) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Overrides CqAbstractMapping::getAllText()
   */
  public function getAllText() {
    $retval = array();
    $retval['logic']['#markup'] = 'OR';
    $retval += parent::getAllText();
    return $retval;
  }
}
