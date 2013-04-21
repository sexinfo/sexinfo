<?php

/**
 * @file
 * A trigger can set a TextLabel to a certain value depeding on the input.
 * It checks against a minimum and maximum value.
 */
class TriggerNumber {

  /**
   * The minimum value to check against. If NULL, there is no minimum.
   *
   * @var float
   */
  public $min;

  /**
   * The maximum value to check against. If NULL, there is no maximum.
   *
   * @var float
   */
  public $max;

  /**
   * The text to return when this Trigger matches.
   *
   * @var String
   */
  public $text;

  /**
   * The question or other object that this item can query for things like the
   * current answer, draggables and hotspots.
   *
   * @var CqQuestion
   */
  public $context;

  /**
   * Initialise this Trigger by using values from the given XML node.
   *
   * @param $node
   *   DOMElement The node to use for initialisation.
   * @param $context
   *   CqQuestionInterface The question or other object that the mapping can query for
   *   things like the current answer, draggables, hotspots and the parsing of
   *   html.
   */
  public function initFromNode($node, $context) {
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');
    $this->context = $context;

    $this->text .= cq_get_text_content($node, $context, TRUE, TRUE);

    $attribs = $node->attributes;
    $itemMin = $attribs->getNamedItem('min');
    if ($itemMin !== NULL) {
      $this->min = (float) $itemMin->value;
    }
    $itemMax = $attribs->getNamedItem('max');
    if ($itemMax !== NULL) {
      $this->max = (float) $itemMax->value;
    }
  }

  /**
   * Check if the given input triggers this Trigger.
   *
   * @param (float) $input
   *   The input to check against. It it first cast to float.
   *
   * @return boolean
   *   TRUE if this trigger matches, FALSE otherwise.
   */
  public function matches($input) {
    $input = (float) $input;
    if (isset($this->min)) {
      if ($input < $this->min) {
        return FALSE;
      }
    }
    if (isset($this->max)) {
      if ($input > $this->max) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Get the parsed text of this Trigger.
   *
   * @return String
   *   The text to show if this Trigger matches.
   */
  public function getText() {
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');
    return cq_replace_tags($this->text, $this->context);
  }

}
