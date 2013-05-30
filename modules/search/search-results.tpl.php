<?php

/**
 * @file
 * Default theme implementation for displaying search results.
 *
 * This template collects each invocation of theme_search_result(). This and
 * the child template are dependent to one another sharing the markup for
 * definition lists.
 *
 * Note that modules may implement their own search type and theme function
 * completely bypassing this template.
 *
 * Available variables:
 * - $search_results: All results as it is rendered through
 *   search-result.tpl.php
 * - $module: The machine-readable name of the module (tab) being searched, such
 *   as "node" or "user".
 *
 *
 * @see template_preprocess_search_results()
 */
?>
<?php if ($search_results): ?>
  <h2 class="search-title"><?php print t('Search results');?></h2>
  <ol class="search-results <?php print $module; ?>-results">
    <?php print $search_results; ?>
  </ol>
  <?php print $pager; ?>
<?php else : ?>

<div class="content clearfix">
  <div id="search404-result-text">
    <h3 class="pink-header" style="margin-top: 20px">Your search yielded no results.</h3>
    <div id="search-suggestions"></div>
    <h3 class="pink-header" style="font-size: 1.2em; margin-bottom: 25px;">Try another search above or send us a question or comment.</h3>
</div>

<center><a class="button" href="/sexinfo/ask-sexperts">Click here to send us a question or comment.</a></center>

<?php endif; ?>
