<?php

function magazeenlite_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';
    $output .= '<div class="breadcrumb">' . implode(' > ', $breadcrumb) . '</div>';
    return $output;
  }
}

function magazeenlite_preprocess_comment(&$variables) {
    $variables['submitted'] = t('!datetime — !username', array('!username' => $variables['author'], '!datetime' => $variables['created']));
}

function magazeenlite_preprocess_node(&$variables) {
    $variables['submitted'] = t('!datetime — !username', array('!username' => $variables['name'], '!datetime' => $variables['date']));
}

/**
  * Theme override for search form.
  */
function magazeenlite_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    $form['search_block_form']['#title'] = '';
    $form['search_block_form']['#default_value'] = 'Search...';
    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search...';}";
    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search...') {this.value = '';}";
    $form['actions']['submit']['#value'] = t('Search');
  }
}
