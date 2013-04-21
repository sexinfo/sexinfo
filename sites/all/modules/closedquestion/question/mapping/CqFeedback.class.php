<?php

/**
 * @file
 * Class for handling feedback.
 * An instance of CqFeedback holds one feedback "item".
 */
class CqFeedback {

  /**
   * The question or other object that this item can query for things like the
   * current answer, draggables and hotspots.
   *
   * @var CqQuestion
   */
  private $context;
  /**
   * The top-branch of this mapping tree. Usually a CqMapping object.
   *
   * @var CqMapping
   */
  public $topParent;
  /**
   * The minimum number of tries before this feedback should be shown.
   *
   * @var int
   */
  private $minTries = 0;
  /**
   * The maximum number of tries for which this feedback should be shown.
   *
   * @var int
   */
  private $maxTries = 9999;
  /**
   * The html content of the feedback.
   *
   * @var string
   */
  private $text = '';
  /**
   * The identifier of the inline feedback block to use for this feedback item,
   * or FALSE to put the item in the common feedback area.
   *
   * @var String or FALSE
   */
  private $block = FALSE;

  /**
   * Initialises an existing CqFeedback from an XML node.
   *
   * @param DOMElement $node
   *   The node to use for initalisation
   * @param CqQuestionInterface $context
   *   The question or other object that the item can query for things like the
   *   current answer, draggables, hotspots and html processing.
   */
  public function initFromElement(DOMElement $node, $context, $topParent = NULL) {
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');

    $this->context = & $context;
    $this->topParent = & $topParent;

    $this->text .= cq_get_text_content($node, $context, TRUE, TRUE);
    $attribs = $node->attributes;
    $itemMin = $attribs->getNamedItem('mintries');
    if ($itemMin !== NULL) {
      $this->minTries = (int) $itemMin->value;
    }
    $itemMax = $attribs->getNamedItem('maxtries');
    if ($itemMax !== NULL) {
      $this->maxTries = (int) $itemMax->value;
    }
    $itemBlock = $attribs->getNamedItem('block');
    if ($itemBlock !== NULL) {
      $this->block = $itemBlock->value;
    }
  }

  /**
   * Initialises an existing CqFeedback with exact values for text, mintries
   * and maxtries
   *
   * @param string $text
   *   The feedback text.
   * @param int $mintries
   *   The minimum number of tries needed before this feedback is shown.
   * @param int $maxtries
   *   The maximum number of tries after which this feedback is no longer shown.
   */
  public function initWithValues($text, $mintries, $maxtries, $block=FALSE) {
    $this->text = $text;
    $this->minTries = $mintries;
    $this->maxTries = $maxtries;
    $this->block = $block;
  }

  /**
   * Getter for the minimum number of tries needed.
   *
   * @return int
   *   The minimum number of tries.
   */
  public function getMinTries() {
    return $this->minTries;
  }

  /**
   * Getter for the maximum number of tries allowed.
   *
   * @return int
   *   The maximum number of tries.
   */
  public function getMaxTries() {
    return $this->maxTries;
  }

  /**
   * Getter for the feedback text.
   *
   * @return String
   *   The text to show as feedback.
   */
  public function getText() {
    module_load_include('inc.php', 'closedquestion', 'lib/XmlLib');
    if ($this->block) {
      $block = $this->block;
      if (drupal_strtolower($block) == 'lastmatchedid') {
        $block = $this->topParent->lastMatchedId;
      }
      return '<div class="cqFbItem block-' . $block . '">' . cq_replace_tags($this->text, $this->context) . '</div>';
    }
    else {
      return cq_replace_tags($this->text, $this->context);
    }
  }

  /**
   * Checks if the given number of tries is between the min and max tries
   * (inclusive).
   *
   * @param int $tries
   *   The number to check.
   *
   * @return boolean
   *   TRUE if $tries is in range.
   */
  public function inRange($tries) {
    return ($tries >= $this->minTries && $tries <= $this->maxTries);
  }

  /**
   * Returns the id of the feedback block this feedback item is assiciated with,
   * or FALSE if not associated with a feedback block.
   *
   * @return string or boolean
   *   The feedback-block id, or FALSE if no block.
   */
  public function getBlock() {
    return $this->block;
  }

  /**
   * Associate this feedback item with the feedback block with the given id, or
   * remove any association by passing FALSE
   *
   * @param mixed $block
   *   string: the id of the feedback block to associate this item with.
   *   boolean FALSE: unset any association with a feedback block.
   */
  public function setBlock($block) {
    $this->block = $block;
  }

  /**
   * Creates a new CqFeedback and initialises it from an XML node.
   *
   * @param DOMElement $node
   *   The node to use for initalisation
   * @param CqQuestionInterface $context
   *   The question or other object that the item can query for
   *   things like the current answer, draggables and hotspots.
   *
   * @return CqFeedback
   *   A new CqFeedback instance.
   */
  public static function newCqFeedback(DOMElement $node, $context) {
    $fbItem = new CqFeedback();
    $fbItem->initFromElement($node, $context);
    return $fbItem;
  }

  /**
   * Get all the text in the item, for easier reviewing for spelling, etc.
   *
   * @return array
   *   Themable form array.
   */
  public function getAllText() {
    $retval = array();
    $retval['#theme'] = 'closedquestion_feedback_item';
    $retval['mintries'] = $this->minTries;
    $retval['maxtries'] = $this->maxTries;
    $retval['text'] = $this->text;
    return $retval;
  }

}
