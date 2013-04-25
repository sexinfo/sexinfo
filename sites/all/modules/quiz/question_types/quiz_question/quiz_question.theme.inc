<?php

/*
 * @file
 * Themes for the quiz question module.
 */

/**
 * Theme the question creation form
 *
 * @param $form
 *  Question creation form
 */
<<<<<<< HEAD
function theme_quiz_question_creation_form($variables) {
  $form = $variables['form'];
=======
function theme_quiz_question_creation_form($form) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
  module_load_include('inc', 'quiz', 'quiz.admin');
  quiz_set_auto_title();
}

/**
 * Theme the question navigation form(Back, Next, Skip buttons...)
 *
 * @param $form
 *  Form holding the navigation buttons
 */
<<<<<<< HEAD
function theme_quiz_question_navigation_form($variables) {
  $form = $variables['form'];
  if (!isset($form['#last'])) {
    return drupal_render_children($form);
  }
  else {
    $submit = drupal_render($form['submit']) . drupal_render($form['op']);
    $to_return = '<div>' . drupal_render_children($form) . '</div>';
    $to_return .= '<div><em>' . t('This is the last question. Press Finish to deliver your answers') . '</em></div>';
    $to_return .= '<div>' . $submit . '</div>';
    return $to_return;
  }
}
=======
function theme_quiz_question_navigation_form($form) {
  return drupal_render($form);
}
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
