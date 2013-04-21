<?php

/**
 * @file
 * "NOT" implementation for mappings. Returns TRUE if it's one child returns
 * FALSE.
 */
class CqMappingNot extends CqAbstractMapping {

  /**
   * Implements CqAbstractMapping::evaluate()
   */
  function evaluate() {
    if (isset($this->children[0])) {
      return (!$this->children[0]->evaluate());
    }
    return FALSE;
  }

  /**
   * Overrides CqAbstractMapping::getAllText()
   */
  public function getAllText() {
    $retval = array();
    $retval['logic']['#markup'] = 'NOT';
    $retval += parent::getAllText();
    return $retval;
  }

}
