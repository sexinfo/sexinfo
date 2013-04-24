<?php
/**
 * @file
 * Handles the layout of the quiz question browser.
 *
 *
 * Variables available:
 * - $form
 */

// Add js
$p = drupal_get_path('module', 'quiz') .'/theme/';
drupal_add_js($p .'quiz_question_browser.js', 'module');

// We need to separate the title and the checkbox. We make a custom options array...
$full_options = array();
foreach ($form['titles']['#options'] as $key => $value) {
  $full_options[$key] = $form['titles'][$key];
  $full_options[$key]['#title'] = '';
}
// Print ahah targets
print drupal_render($form['ahah_target_all']);
print drupal_render($form['ahah_target']);

$rows = array();
$cols = array();

// We make the filter row
$cols[] = drupal_render($form['filters']['all']);
$cols[] = drupal_render($form['filters']['title']);
$cols[] = drupal_render($form['filters']['type']);
$cols[] = drupal_render($form['filters']['changed']);
$cols[] = drupal_render($form['filters']['name']);
$rows[] = array('data' => $cols, 'id' => 'quiz-question-browser-filters');

// We make the question rows
foreach ($form['titles']['#options'] as $key => $value) {
  $cols = array();

  // Find nid and vid
  $matches = array();
  preg_match('/([0-9]+)-([0-9]+)/', $key, $matches);
  $quest_nid = $matches[1];
  $quest_vid = $matches[2];

  // The checkbox(without the title)
  $cols[] = array('data' => drupal_render($full_options[$key]), 'width' => 35);

  // The title
  $cols[] = l($value, "node/$quest_nid", array('html' => TRUE, 'query' => array('destination' => $_GET['q']), 'attributes' => array('target' => 'blank')));

  $cols[] = $form['types'][$key]['#value'];
  $cols[] = $form['changed'][$key]['#value'];
  $cols[] = $form['names'][$key]['#value'];

  $rows[] = array('data' => $cols, 'class' => 'quiz-question-browser-row', 'id' => 'browser-'. $key);
}

print theme('table', $form['#header'], $rows, array('class' => 'browser-table'));

if (count($form['titles']['#options']) == 0)
  print t('No questions were found');

print '<div id="before-pager"></div>';
print $form['pager']['#value'];
print drupal_render($form['add_to_get']);
print drupal_render($form['ahah_target_all_end']);
?>