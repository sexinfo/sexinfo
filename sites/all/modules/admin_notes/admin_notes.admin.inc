<?php

/**
 * @file
 * Admin notes admin pages.
 */

/**
 * Output a sortable table containing all admin notes.
 */
function admin_notes_view() {
  $header = array(
    array('data' => t('Date'), 'field' => 'an.timestamp', 'sort' => 'desc'),
    array('data' => t('User'), 'field' => 'u.name'),
    t('Note'),
    t('Path'),
  );
  
  $query = db_select('admin_notes', 'an')->extend('TableSort')->extend('PagerDefault');
  $query->join('users', 'u', 'an.uid = u.uid');
  $result = $query
    ->fields('an')
    ->fields('u')
    ->orderByHeader($header)
    ->limit(50)
    ->execute()
    ->fetchAllAssoc('anid');
  
  return admin_notes_output($result, 'table', $header);
}

/**
 * Render admin note entries.
 * 
 * Use this function to render and return
 * - an admin note provided as a database query result resource or
 * - a custom admin note provided as an array containing admin note entry objects.
 *
 * This function may look insane to some, but it ensures that implementation of
 * admin notes module into other modules is as easy as possible.
 * 
 * @param array $admin_note
 *   A database query result resource or an array containing admin note entry
 *   objects to output.
 * @param string $format
 *   Whether to output all log entries as 'table', 'list' or plain 'text'.
 * @param array $header
 *   An array containing header data for 'table' output.
 *
 */
function admin_notes_report_output($admin_note, $format = 'table', $header = array()) {
  switch ($format) {
    case 'text':
      // Output delimiter in first line, since this may change.
      $output = '\t' . "\n";

      while ($entry = (is_array($admin_note) ? array_shift($admin_note) : db_fetch_object($admin_note))) {
        $row = array(
          $entry->timestamp,
          $entry->uid,
          $entry->note,
          $entry->path,
        );
        $output .= implode("\t", $row) ."\n";
      }
      break;
      
    case 'list':
      $output = '';
      while ($entry = (is_array($admin_note) ? array_shift($admin_note) : db_fetch_object($admin_note))) {
        $output .= '<li>';
        $output .= '<span class="admin_note-info">'. theme('username', $entry) .' '. format_date($entry->timestamp, 'small') .':</span>';
        $output .= '<span class="admin_note-entry">'. filter_xss_admin($entry->note) .'</span>';
        $output .= '</li>';
      }
      if ($output) {
        $output = '<ul id="admin_note-backlog">'. $output .'</ul>';
      }
      break;

    case 'table':
    default:
      $rows = array();
      while ($entry = (is_array($admin_note) ? array_shift($admin_note) : db_fetch_object($admin_note))) {
        $rows[] = array(
          format_date($entry->timestamp, 'small'),
          theme('username', $entry),
          filter_xss_admin($entry->note),
          l(truncate_utf8($entry->path, 32, FALSE, TRUE), $entry->path),
        );
      }

      if (empty($rows)) {
        $rows[] = array(array('data' => t('No admin notes available.'), 'colspan' => 4));
      }

      $output = theme('table', $header, $rows);
      $output .= theme('pager', NULL, 50, 0);
      break;
  }

  return $output;
}