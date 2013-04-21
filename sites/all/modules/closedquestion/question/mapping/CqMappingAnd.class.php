<?php

/**
 * @file
 * "And" implementation for mappings. Returns TRUE only of all children return
 * TRUE.
 */
class CqMappingAnd extends CqAbstractMapping {

  /**
   * Implements CqAbstractMapping::evaluate()
   */
  function evaluate() {
    foreach ($this->children AS $id => $tempExpression) {
      if (!$tempExpression->evaluate()) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Overrides CqAbstractMapping::getAllText()
   */
  public function getAllText() {
    $retval = array();
    $retval['logic']['#markup'] = 'AND';
    $retval += parent::getAllText();
    return $retval;
  }

}
