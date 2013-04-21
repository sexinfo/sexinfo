<?php

/**
 * @file
 * Textlabels are used to turn a math number or a string into a text string or a
 * piece of html.
 */
class TextLabel {

  /**
   * The unique id of this TextLabel.
   *
   * @var String
   */
  public $id;

  /**
   * The list of triggers used to set the content of this TextLabel.
   *
   * @var array of Trigger
   */
  public $triggers = array();

  /**
   * Get the id of this text label.
   *
   * @return String
   *   The id of this TextLabel.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Check the triggers of this Label against the given input and return the
   * text of the first trigger that matches.
   *
   * @param mixed $input
   *   The input to check the triggers against.
   *
   * @return String
   *   The text of the matching trigger (if any).
   */
  public function getValue($input) {
    foreach ($this->triggers AS $trigger) {
      if ($trigger->matches($input)) {
        return $trigger->getText();
      }
    }
  }

  /**
   * Initialise this TextLabel by using values from the given XML node.
   *
   * @param $node
   *   DOMElement The node to use for initialisation.
   * @param $context
   *   CqQuestionInterface The question or other object that the mapping can query for
   *   things like the current answer, draggables, hotspots and the parsing of
   *   html.
   */
  public function initFromNode($node, $context) {
    $attribs = $node->attributes;
    $item = $attribs->getNamedItem('id');
    if ($item !== NULL) {
      $this->id = $item->value;
    }

    foreach ($node->childNodes as $child) {
      switch (strtolower($child->nodeName)) {
        case 'triggernumber':
          $trigger = new TriggerNumber();
          $trigger->initFromNode($child, $context);
          $this->triggers[] = $trigger;
          break;
      }
    }
  }

}
