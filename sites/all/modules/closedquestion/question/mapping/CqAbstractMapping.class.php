<?php
/*
 * @file
 * A mapping maps a certain answer into a certain feedback.
 * This is currently used by Check, Drag&Drop, Hotspot and Fillblanks questions.
 */

/**
 * Functions as base for all mapping classes, and as a factory to create mapping
 * classes from XML DOMElements.
 */
abstract class CqAbstractMapping {

  /**
   * The question or other object that the mapping can query for things like the
   * current answer, draggables and hotspots.
   *
   * @var CqQuestion
   */
  public $context;

  /**
   * The top-branch of this mapping tree. Usually a CqMapping object.
   *
   * @var CqMapping
   */
  public $topParent;

  /**
   * The parameters of the mapping, in the xml these are the attributes.
   *
   * @var associative array of key/value pairs.
   */
  public $params;

  /**
   * Child-mappings of this mapping.
   *
   * @var array of CqAbstractMapping
   */
  public $children;

  /**
   * Feedback items contained in this mapping.
   *
   * @var array of CqFeedback
   */
  public $feedback;

  /**
   * Evaluate the mapping.
   * Runs any checks in this mapping and returns the result of the checks:
   * TRUE if the mapping maches, FALSE if it does not match.
   *
   * @return boolean
   */
  abstract public function evaluate();

  /**
   * Initialises a mapping item using data from an XML node.
   *
   * @param $node
   *   DOMElement The node to use for initialisation.
   * @param $context
   *   CqQuestionInterface The question or other object that the mapping can query for
   *   things like the current answer, draggables, hotspots and the parsing of
   *   html.
   * @param $topParent
   *   The highest parent in this mapping tree.
   */
  public function generateFromNode($node, &$context, $topParent = NULL) {
    $this->params = array();
    $this->children = array();
    $this->feedback = array();
    $this->context = & $context;
    $this->topParent = & $topParent;
    if (is_null($topParent)) {
      $topParent = &$this;
    }

    foreach ($node->attributes as $attrib) {
      $this->params[strtolower($attrib->nodeName)] = $attrib->nodeValue;
    }

    foreach ($node->childNodes as $child) {
      switch ($child->nodeName) {
        case 'feedback':
          $newchild = CqFeedback::newCqFeedback($child, $context);
          $newchild->topParent = & $topParent;
          $this->feedback[] = $newchild;
          continue 2; // continue to the next child.

        case '#text':
        case '#comment':
          continue 2; // continue to the next child.

        case 'pattern': // Old style patterns are now handled by CqMatch
          $newchild = new CqMatch();
          $newchild->context = & $context;
          $newchild->params['pattern'] = $child->nodeValue;
          $this->children[] = $newchild;
          continue 2; // continue to the next child.

        case 'mapping':
          $newchild = new CqMapping();
          break;

        case 'and':
          $newchild = new CqMappingAnd();
          break;

        case 'or':
          $newchild = new CqMappingOr();
          break;

        case 'not':
          $newchild = new CqMappingNot();
          break;

        case 'combination':
        case 'match':
          $newchild = new CqMatch();
          break;

        case 'range':
          $newchild = new CqMatchRange();
          break;

        default:
          drupal_set_message(t('Unknown node type: @nodename', array('@nodename' => $child->nodeName)));
          continue 2; // continue to the next child.
      }
      $newchild->generateFromNode($child, $context, $topParent);
      $this->children[] = $newchild;
    }
  }

  /**
   * Returns a html representation of this mapping, and it's children, for text
   * review.
   *
   * @return string
   *   The html representation.
   */
  public function getAllText() {
    $retval = array();

    if (count($this->feedback) > 0) {
      $retval['feedback'] = array(
        '#theme' => 'closedquestion_feedback_list',
        'extended' => TRUE,
      );
      foreach ($this->feedback AS $fbitem) {
        $retval['feedback']['items'][] = $fbitem->getAllText();
      }
    }

    $retval['children'] = array(
      '#theme' => 'closedquestion_mapping_list',
      'items' => array(),
      'extended' => TRUE,
    );
    if (isset($this->children)) {
      foreach ($this->children AS $child) {
        $retval['children']['items'][] = $child->getAllText();
      }
    }

    $retval['#theme'] = 'closedquestion_mapping_item';
    return $retval;
  }

  /**
   * Get the parameter value of the given parameter, if it is set, or the given
   * default value if the parameter is not set.
   *
   * @param string $paramName
   *   The name of the parameter to fetch.
   * @param string $default
   *   The default value to return if the parameter is not set.
   *
   * @return string
   *   The value of the parameter.
   */
  public function getParam($paramName, $default=NULL) {
    $retval = isset($this->params[$paramName]) ? $this->params[$paramName] : $default;
    return $retval;
  }

}
