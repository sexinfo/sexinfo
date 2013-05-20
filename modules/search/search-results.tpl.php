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

<style type="text/css">

a.button {
background-image: -moz-linear-gradient(top, #ffffff, #F52887);
background-image: -webkit-gradient(linear,left top,left bottom,
    color-stop(0, #ffffff),color-stop(1, #F52887));
filter: progid:DXImageTransform.Microsoft.gradient
    (startColorStr='#ffffff', EndColorStr='#F52887');
-ms-filter: "progid:DXImageTransform.Microsoft.gradient
    (startColorStr='#ffffff', EndColorStr='#F52887')";
border: 1px solid #fff;
-moz-box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.4);
-webkit-box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.4);
box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.4);
border-radius: 18px;
-webkit-border-radius: 18px;
-moz-border-radius: 18px;
padding: 5px 15px;
text-decoration: none;
float: center;
margin-right: 15px;
margin-bottom: 15px;
display: block;
color: #FFFFFF;
line-height: 24px;
font-size: 20px;
}

a.button:hover {
background-image: -moz-linear-gradient(top, #ffffff, #eeeeee);
background-image: -webkit-gradient(linear,left top,left bottom,
    color-stop(0, #ffffff),color-stop(1, #eeeeee));
filter: progid:DXImageTransform.Microsoft.gradient
    (startColorStr='#ffffff', EndColorStr='#eeeeee');
-ms-filter: "progid:DXImageTransform.Microsoft.gradient
    (startColorStr='#ffffff', EndColorStr='#eeeeee')";
color: #000;
display: block;
}

a.button:active {
background-image: -moz-linear-gradient(top, #F52887, #ffffff);
background-image: -webkit-gradient(linear,left top,left bottom,
    color-stop(0, #F52887),color-stop(1, #ffffff));
filter: progid:DXImageTransform.Microsoft.gradient
    (startColorStr='#F52887', EndColorStr='#ffffff');
-ms-filter: "progid:DXImageTransform.Microsoft.gradient
    (startColorStr='#F52887', EndColorStr='#ffffff')";
text-shadow: 0px -1px 0 rgba(255, 255, 255, 0.5);
margin-top: 1px;
}

</style></head>


   <div class="content clearfix"><div id="search404-result-text"><br><center><p><font size = "5", font color = "#F52887"> Your search yielded no results. <br> Try another search above or send us a question or comment.</font></center>
   	<p><font size = "3", font color = "#000000"><ul>
<li>Check if your spelling is correct.</li>
<li>Remove quotes around phrases to search for each word individually. 
	<ul><li><em>bike shed</em> will often show more results than <em>&quot;bike shed&quot;</em>.</li></ul>
<li>Consider loosening your query with <em>OR</em>. <em>bike OR shed</em> will often show more results than <em>bike shed</em>.</li>
</ul></font></div>
	<center><a class="button" href="/sexinfo/ask-sexperts">Click here to send us a question or comment.</a>
</center>
<?php endif; ?>
