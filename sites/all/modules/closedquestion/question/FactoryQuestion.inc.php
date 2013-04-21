<?php
/**
 * @file
 * Factory methods for creating questions from XML.
 */

/**
 * hook_closedquestion_question_factory() implementation worker.
 *
 * Factory method for constructing question objects.
 *
 * @param string $type
 *   the question type, as defined by the module's
 *   hook_closedquestion_question_types() implementation.
 * @param CqUserAnswerInterface $user_answer
 *   to use for storing the user's answer
 * @param Object $node
 *   Drupal node object that this comes from.
 *
 * @return CqQuestion
 *   The question, or NULL if construction wasn't possible.
 */
function _closedquestion_closedquestion_question_factory($type, &$user_answer, &$node) {
  $classes = array(
    'balance' => 'Balance',
    'check' => 'Check',
    'dragdrop' => 'DragDrop',
    'fillblanks' => 'Fillblanks',
    'flash' => 'Flash',
    'hotspot' => 'Hotspot',
    'option' => 'Option',
    'selectorder' => 'SelectOrder',
    'pattern' => 'SelectOrder',
    'sequence' => 'Sequence',
    'value' => 'Value',
  );
  if (isset($classes[$type])) {
    $class_name = 'CqQuestion' . $classes[$type];
    if (class_exists($class_name)) {
      return new $class_name($user_answer, $node);
    }
  }
  drupal_set_message(t('Invalid question type %type in @function', array('%type' => $type, '@function' => __FUNCTION__)), 'error');
}

/**
 * Takes plain-text XML, parses it into a DOMElement and passes that on to the
 * appropriate question factory to construct the question object.
 *
 * @param String $xml_content
 *   containing the XML.
 * @param CqUserAnswerInterface $user_answer
 *   to use for storing the user's answer
 * @param Object $node
 *   Drupal node object that this comes from.
 *
 * @return CqQuestion
 *   The question.
 */
function cq_question_from_xml($xml_content, &$user_answer, &$node) {
  if (strlen($xml_content) == 0) {
    drupal_set_message(t('Your question XML is empty. Please supply a question.'), 'error');
    return NULL;
  }

  $dom = new DomDocument();
  if (!$dom) {
    drupal_set_message(t('DOM-XML php extension probably not installed, please see the requirements section in the README.txt'), 'error');
    return NULL;
  }

  if (!$dom->loadXML($xml_content)) {
    drupal_set_message(t('Problem loading question in node %nid', array('%nid' => $node->nid)), 'error');
    return NULL;
  }

  $xpath = new DOMXPath($dom);
  $questions = $xpath->query('/question');
  $first_child = $questions->item(0);
  if ($first_child) {
    $question_types = _closedquestion_get_question_types();
    $type = drupal_strtolower($first_child->getAttribute('type'));
    if (!isset($question_types[$type])) {
      drupal_set_message(t('Unknown question type: %type', array('%type' => $type)), 'error');
      return NULL;
    }
    // Call the question factory function of the module that declares it
    // implements this question type.
    $function = $question_types[$type] . '_closedquestion_question_factory';
    $question = $function($type, $user_answer, $node);
  }
  else {
    drupal_set_message(t('It seems your XML does not contain a &lt;question&gt; tag as root tag.'), 'error');
    return NULL;
  }

  return $question;
}
