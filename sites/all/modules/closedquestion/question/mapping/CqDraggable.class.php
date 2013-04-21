<?php

/**
 * @file
 * CqDraggable describes a draggable in Drag&Drop and Hotspot questions.
 */
class CqDraggable {

  /**
   * The identifier of this draggable
   *
   * @var string
   */
  private $identifier = 'no_identifier';
  /**
   * The location of this draggable.
   *
   * @var array
   *   - 0: x coordinate
   *   - 1: y coordinate
   */
  private $location;
  /**
   * If this draggable is of the image kind, the source url of the image.
   *
   * @var string
   */
  private $imgSrc;
  /**
   * The (html) content of the draggable.
   *
   * @var string
   */
  private $text;
  /**
   * The css class to use for the draggable.
   *
   * @var string
   */
  private $cssClass;

  /**
   * Initialises this item using data from an XML node.
   * @param DOMElement $node The node to use for initialisation.
   * @param CqQuestionInterface $context The question or other object that the mapping
   * can query for things like the current answer, draggables and hotspots.
   */
  public function __construct(DOMElement $node, $context) {
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');

    if ($node) {
      $attribs = $node->attributes;
      if ($attribs) {
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

        $item = $attribs->getNamedItem('src');
        if ($item !== NULL) {
          $this->imgSrc = $item->nodeValue;
        }

        $item = $attribs->getNamedItem('class');
        if ($item !== NULL) {
          $this->cssClass = $item->nodeValue;
        }
      }
      $this->text = cq_get_text_content($node, $context);
    }
  }

  /**
   * Gets the identifier for this draggable
   * @return String the identifier
   */
  public function getIdentifier() {
    return $this->identifier;
  }

  /**
   * Sets the location of this draggable from an Array($x, $y)
   *
   * @param array $location
   *   The array containing the X and Y coordinates.
   */
  public function setLocation(Array $location) {
    $this->location = $location;
  }

  /**
   * Sets the location of this draggable from an individual x and y coordinate.
   *
   * @param Number $x
   *   The X coordinate of the draggable.
   * @param Number $y
   *   The Y coordinate of the draggable.
   */
  public function setLocationXY($x, $y) {
    $this->location = array($x, $y);
  }

  /**
   * Gets the location of this draggable as an Array($x, $y)
   *
   * @return Array
   *   The location of this draggable.
   */
  public function getLocation() {
    return $this->location;
  }

  /**
   * @return String
   *   The (html) content of this draggable.
   */
  public function getText() {
    if ($this->imgSrc != NULL) {
      return '<img src="' . $this->imgSrc . '">';
    }
    else {
      return $this->text;
    }
  }

  /**
   * The CSS class name to use for this draggable.
   *
   * @return String
   *   The CSS class name.
   */
  public function getClass() {
    return $this->cssClass;
  }

  /**
   * @return Boolean
   *   True if the draggable only contains an image and no other html.
   */
  public function imageOnly() {
    return ($this->imgSrc != NULL);
  }

}

