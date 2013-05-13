<?php
/**
 * @file Functionality needed for upgrade path from nodewords
 */

/**
 * Returns array of fields needed for upgrade from nodewords, keyed by meta name
 * @todo: use hooks to alter known tags
 * @return array
 */
function _metatags_quick_known_fields() {
  return array(
    'abstract' => array(
      'type' => 'meta_contents',
      'widget' => 'metatags_quick_textfield',
      'title' => t('Abstract'),
      'description' => t('Enter a short abstract. Typically it is one sentence.'),
    ),
    /* Comment that out until [#1229822] is solved properly */
    'canonical' => array(
      'type' => 'head_link',
      'widget' => 'metatags_quick_textfield',
      'title' => t('Canonical URL'),
      'description' => t('Canonical URLs are used from the search engines, and allow them to not report duplicate titles for HTML pages that are accessible from different URLs. Use a relative URL without the initial slash; canonical URLs that point to a different domain are normally not accepted.'),
    ),
    'copyright' => array(
      'type' => 'meta_contents',
      'widget' => 'metatags_quick_textfield',
      'title' => t('Copyright'),
      'description' => t('Enter a short copyright statement.'),
    ),
    'description' => array(
      'type' => 'meta_contents',
      'widget' => 'metatags_quick_textarea',
      'title' => t('Description'),
      'description' => t('Enter a description. Limit your description to about 20 words, with a maximum of 255 characters. It should not contain any HTML tags or other formatting.'),
    ),
    'keywords' => array(
      'type' => 'meta_contents',
      'widget' => 'metatags_quick_textfield',
      'title' => t('Keywords'),
      'description' => t('Enter a short abstract. Typically it is one sentence.'),
    ),
    'revisit_after' => array(
      'meta_name' => 'revisit-after',
      'type' => 'meta_contents',
      'widget' => 'metatags_quick_textfield',
      'title' => t('Revisit after'),
      'description' => t('day(s)'),
    ),
    'robots' => array(
      'type' => 'meta_contents',
      'widget' => 'metatags_quick_checkboxes',
      'title' => t('Robots'),
      'options' => 'noarchive,nofollow,noindex,noodp,nosnippet,noydir', 
    ),
  );
}