<?php
/**
 * @file
 *  Views include file for metatags_quick
 */

/**
 * Implements hook_views_post_render().
 */
function metatags_quick_views_post_render(&$view, $output, $cache) {
  // If the view is a page display, has a path and it is a taxonomy term path.
  if (($view->display_handler instanceof views_plugin_display_page) && $view->display_handler->has_path() && ($view->get_path() == 'taxonomy/term/%')) {

    $term = taxonomy_term_load($view->args[0]);
    $fields = field_info_instances('taxonomy_term', $term->vocabulary_machine_name);

    foreach ($fields as $name => $instance) {
      $settings = field_get_display($instance, 'full', 'taxonomy_term');
      // Check for fields which use this module for display, and render them.
      if ($settings['module'] = 'metatags_quick') {
        field_view_field('taxonomy_term', $term, $name, $settings);
      }
    }
  }
}
