<?php

/**
 * @file
 * Collection of XML methods for handling text nodes.
 */

/**
 * Copy all the attributes of $node into a string as it would be used in html,
 * and return that string.
 *
 * @param DOMNode $node
 *   XML node to copy all the attributes from.
 *
 * @return string containing the attributes.
 */
function cq_copy_attributes($node) {
  $retval = '';
  $attribs = $node->attributes;
  foreach ($node->attributes as $attr_name => $attr_node) {
    $retval .= ' ' . $attr_name . '="' . $attr_node->nodeValue . '"';
  }
  return $retval;
}

/**
 * Recursively parse the children of an XML node and return the parts that are
 * valid html.  The context CqQuestionInterface object can provide a list of node names
 * that are not html, but can be turned into html by the context object.
 *
 * If fullFilter is set, the final html will be filtered through the Drupal
 * filter set of the context CqQuestionInterface.
 *
 * @param DOMNode $parent
 *   The XML node to parse the children of.
 * @param CqQuestionInterface $context
 *   The question that can supply extra node names to handle, and drupal
 *   filters.
 * @param boolean $fullFilter
 *   Filter the result also through the drupal content filters?
 * @param boolean $delay
 *   If true, some XML nodes are replaced with a [] tag so they can be
 *   processed later. This can be used when not all data needed for full
 *   processing is available yet.
 *   Important: If delay is TRUE then the text is NOT filtered through the
 *   drupal content filters untill after a call to cq_replace_tags().
 *
 * @return string
 *   The html contained in the node.
 */
function cq_get_text_content($parent, $context, $full_filter = TRUE, $delay = FALSE) {
  $retval = '';
  $nodes = $parent->childNodes;
  foreach ($nodes as $node) {
    switch ($node->nodeName) {
      case '#comment':
        break;

      case '#text':
      case '#cdata-section':
        $retval .= $node->nodeValue;
        break;

      case 'formblock':
        $retval .= '<formblock/>';
        break;

      case 'img':
      case 'br':
        $retval .= '<' . $node->nodeName . ' ' . cq_copy_attributes($node) . '/>';
        break;

      case 'h1':
      case 'h2':
      case 'h3':
      case 'h4':
      case 'h5':
      case 'h6':
      case 'sub':
      case 'sup':
      case 'p':
      case 'pre':
      case 'b':
      case 'i':
      case 'u':
      case 'strong':
      case 'em':
      case 'table':
      case 'a':
      case 'tr':
      case 'td':
      case 'th':
      case 'span':
      case 'div':
      case 'ul':
      case 'ol':
      case 'dl':
      case 'dt':
      case 'dd':
      case 'li':
      case 'blockquote':
      case 'cite':
        $retval .= '<' . $node->nodeName . ' ' . cq_copy_attributes($node) . '>' . cq_get_text_content($node, $context, FALSE, $delay) . '</' . $node->nodeName . '>';
        break;

      default:
        $retval .= $node->nodeValue;
        break;
    }
    foreach ($context->getHandledTags() AS $tag) {
      if (drupal_strtolower($node->nodeName) == drupal_strtolower($tag)) {
        $retval .= $context->handleNode($node, $delay);
      }
    }
  }
  if ($full_filter && !$delay) {
    $drupal_node = $context->getNode();
    $lang = $drupal_node->language;
    if (!isset($drupal_node->body[$lang])) {
      $lang = 'und';
      if (!isset($drupal_node->body[$lang])) {
        $langs = array_keys($drupal_node->body);
        $lang = $lang[0];
      }
    }
    $retval = check_markup(trim($retval), $drupal_node->body[$lang][0]['format'], FALSE);
  }
  return $retval;
}

/**
 * Search through the text for any occurrences of [tagName|tagData]. If any are
 * found, and tagName is a registered tag name, then let the context handle the
 * tag.
 *
 * @param string $text
 *   The text to parse for tags.
 * @param CqQuestionInterface $context
 *   The question that can supply extra tag names to handle.
 */
function cq_replace_tags($text, $context = FALSE) {
  if (preg_match_all("/\[([a-zA-Z]+)\|([^]]*)]/i", $text, $match)) {
    $s = array();
    $r = array();
    foreach ($match[1] as $key => $value) {
      $replace = $context->handleTag($value, $match[2][$key]);
      $s[] = $match[0][$key];
      $r[] = $replace;
    }
    // Perform the replacements and return processed field.
    $text = str_replace($s, $r, $text);
  }
  if ($context) {
    $drupal_node = $context->getNode();
    $lang = $drupal_node->language;
    if (!isset($drupal_node->body[$lang])) {
      $lang = 'und';
      if (!isset($drupal_node->body[$lang])) {
        $langs = array_keys($drupal_node->body);
        $lang = $lang[0];
      }
    }
    $text = check_markup(trim($text), $drupal_node->body[$lang][0]['format'], FALSE);
  }
  return $text;
}
