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
   <div class="content clearfix"><div id="search404-result-text"><br><center><p><font size = "5", font color = "#F52887"> Your search yielded no results. <br> Try another search above or send us a question or comment.</font></center>
   	<p><font size = "3", font color = "#000000"><ul>
<li>Check if your spelling is correct.</li>
<li>Remove quotes around phrases to search for each word individually. 
	<ul><li><em>bike shed</em> will often show more results than <em>&quot;bike shed&quot;</em>.</li></ul>
<li>Consider loosening your query with <em>OR</em>. <em>bike OR shed</em> will often show more results than <em>bike shed</em>.</li>
</ul></font>
<center><p style ="padding:6px;color:pink;background-color:black;border:black 2px solid"font size = "5", font color = "#F52887"><a href = "/sexinfo/ask-sexperts">Click here to send us a question or comment.</a></font></p></center></div>
<?php endif; ?>
