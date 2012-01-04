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


/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */



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
    $form['search_block_form']['#default_value'] = 'Search.';
    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search.';}";
    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search.') {this.value = '';}";
    $form['actions']['submit']['#value'] = t('');
  }    
}



/* UNUSED FUNCTION FOUND FROM ONLINE */
/**
 * Variables preprocess function for the "page" theming hook.
 */
 /*
function magazeenlite_preprocess_page(&$vars) {

  // Do we have a node?
  if (isset($vars['node'])) {

    // Ref suggestions cuz it's stupid long.
    $suggests = &$vars['theme_hook_suggestions'];

    // Get path arguments.
    $args = arg();
    // Remove first argument of "node".
    unset($args[0]);

    // Set type.
    $type = "page__type_{$vars['node']->type}";

    // Bring it all together.
    $suggests = array_merge(
      $suggests,
      array($type),
      theme_get_suggestions($args, $type)
    );

    // if the url is: 'http://domain.com/node/123/edit'
    // and node type is 'blog'..
    // 
    // This will be the suggestions:
    //
    // - page__node
    // - page__node__%
    // - page__node__123
    // - page__node__edit
    // - page__type_blog
    // - page__type_blog__%
    // - page__type_blog__123
    // - page__type_blog__edit
    // 
    // Which connects to these templates:
    //
    // - page--node.tpl.php
    // - page--node--%.tpl.php
    // - page--node--123.tpl.php
    // - page--node--edit.tpl.php
    // - page--type-blog.tpl.php          << this is what you want.
    // - page--type-blog--%.tpl.php
    // - page--type-blog--123.tpl.php
    // - page--type-blog--edit.tpl.php
    // 
    // Latter items take precedence.
  }
}
*/