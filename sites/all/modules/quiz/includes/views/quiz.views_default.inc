<?php

/*
 * @file
<<<<<<< HEAD
 * Handles default views.
 * Reads views from all the .view.inc files in the views/defaults directory.
 */

/**
 * Implements hook_views_default_views().
 */
function quiz_views_default_views() {
  $files = file_scan_directory(QUIZ_VIEWS_DIR . '/defaults', '/\.inc/');
  $views = array();
  foreach ($files as $path => $file) {
    require DRUPAL_ROOT . '/' . $path;
=======
 * Handles Default views.
 * // Declare all the .view files in the views subdir that end in .view
 */

function quiz_views_default_views() {
  $files = file_scan_directory(QUIZ_VIEWS_DIR . '/defaults', '.inc');

  $views = array();
  foreach ($files as $path => $file) {
    require $path;
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    $views[$file->name] = $view;
  }
  return $views;
}
