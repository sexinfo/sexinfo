<?php

/**
 * @file
 * An option to choose from in a Fillblanks question.
 */
class CqInlineOption {

  /**
   * The identifier of the option
   *
   * @var string
   */
  private $identifier;
  /**
   * The (html) text of the option
   *
   * @var string
   */
  private $text;
  /**
   * The group-identifier of the group that this option belongs to.
   *
   * @var string
   */
  private $group = 'default';

  /**
   * Creates a new CqInlineOption
   *
   * @param DOMElement $node
   *   The XML node to use to initialise the option.
   * @param CqQuestionInterface $context
   *   The question or other object that the item can query for things like the
   *   current answer, draggables, hotspots and html processing.
   */
  public function __construct(DOMElement $node, $context) {

    $this->text .= cq_get_text_content($node, $context);

    $attribs = $node->attributes;

    $item = $attribs->getNamedItem('identifier');
    if ($item === NULL) {
      $item = $attribs->getNamedItem('id');
    }
    if ($item === NULL) {
      $item = $attribs->getNamedItem('name');
    }
    if ($item !== NULL) {
      $this->identifier = $item->nodeValue;
    }

    $item = $attribs->getNamedItem('group');
    if ($item !== NULL && !empty($item->nodeValue)) {
      $this->group = $item->nodeValue;
    }
  }

  /**
   * Get the text of this option.
   *
   * @return string
   */
  public function getText() {
    return $this->text;
  }

  /**
   * Get the identifier of this option.
   *
   * @return string
   */
  public function getIdentifier() {
    return $this->identifier;
  }

  /**
   * Get the group identifier of this option.
   *
   * @return string
   */
  public function getGroup() {
    return $this->group;
  }

  /**
   * Get the text of this option, for text-review purposes.
   *
   * @return string
   */
  public function getAllText() {
    $retval = $this->identifier . ': ' . $this->text;
    return $retval;
  }

}
