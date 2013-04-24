<?php

/*
 * @file
 * Handles Default views.
 * // Declare all the .view files in the views subdir that end in .view
 */

function quiz_views_default_views() {
  $files = file_scan_directory(QUIZ_VIEWS_DIR . '/defaults', '.inc');

  $views = array();
  foreach ($files as $path => $file) {
    require $path;
    $views[$file->name] = $view;
  }
  return $views;
}
