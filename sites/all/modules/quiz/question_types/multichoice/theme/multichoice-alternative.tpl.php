<?php
/**
 * @file
 * Handles the layout of the multichoice answering form
 *
 *
 * Variables available:
 * - $form
 */

?>
<?php
$p = drupal_get_path('module', 'multichoice');
<<<<<<< HEAD
drupal_add_css($p .'/theme/multichoice.css');

// Add script for using the entire alternative row as a button
drupal_add_js(
"( function($) {
  Drupal.behaviors.multichoiceAlternativeBehavior = {
    attach: function(context, settings) {
      $('.multichoice_row')
      .once()
      .filter(':has(:checkbox:checked)')
      .addClass('selected')
      .end()
      .click(function(event) {
        $(this).toggleClass('selected');
        if (event.target.type !== 'checkbox') {
          $(':checkbox', this).attr('checked', function() {
            return !this.checked;
          });
          $(':radio', this).attr('checked', true);
          if ($(':radio', this).html() != null) {
            $('.multichoice_row').removeClass('selected');
              $(this).addClass('selected');
          }
        }
      });
    }
  };
})(jQuery);", 'inline');
=======
drupal_add_css($p .'/theme/multichoice.css', 'module', 'all');

// Add script for using the entire alternative row as a button
drupal_add_js("Drupal.behaviors.multichoiceAlternativeBehavior = function(context) {
  $('.multichoice_row')
  .filter(':has(:checkbox:checked)')
  .addClass('selected')
  .end()
  .click(function(event) {
    $(this).toggleClass('selected');
    if (event.target.type !== 'checkbox') {
      $(':checkbox', this).attr('checked', function() {
        return !this.checked;
      });
      $(':radio', this).attr('checked', true);
      if ($(':radio', this).html() != null) {
        $('.multichoice_row').removeClass('selected');
    	  $(this).addClass('selected');
      }
    }
  });
};", 'inline');
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2

// We want to have the checkbox in one table cell, and the title in the next. We store the checkbox and the titles
$options = $form['#options'];
$fullOptions = array();
$titles = array();
foreach ($options as $key => $value) {
  $fullOptions[$key] = $form[$key];
  $titles[$key] = $form[$key]['#title'];
  $fullOptions[$key]['#title'] = '';
  unset($form[$key]);
}
unset($form['#options']);
<<<<<<< HEAD
print drupal_render_children($form);
=======
print drupal_render($form);
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2

// We use the stored checkboxes and titles to generate a table for the alternatives
foreach ($titles as $key => $value) {
  $row = array();
  $row[] = array('data' => drupal_render($fullOptions[$key]), 'width' => 35, 'class' => 'selector-td');
  $row[] = $value;
<<<<<<< HEAD
  $rows[] = array('data' => $row, 'class' => array('multichoice_row'));
}
print theme('table', array('header' => array(), 'rows' => $rows));
?>
=======
  $rows[] = array('data' => $row, 'class' => 'multichoice_row');
}
print theme('table', NULL, $rows);
?>
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
