<?php

/**
 * @file
 * Default theme implementation for the SEO Checklist "Intro" tab.
 *
 * Available variables:
 * - $volacci_logo: An HTML image tag for the Volacci logo.
 *
 * @see template_preprocess()
 * @see template_preprocess_seo_checklist_intro_tab()
 * @see template_process()
 */
?>
<h3>How to use the Drupal SEO Checklist</h3>
<p>Please read these instructions to get the most out of your Drupal Search Engine Optimization efforts.</p>

<h4>Important warning</h4>
<p>This checklist will not search engine optimize your site. It was written as a guide for Drupal SEO experts. If you need help with Drupal SEO best practices, the search engines' latest changes, your brand's target audience, or strategic marketing objectives, consider using a Drupal-specific Internet Marketing consultant like <a href="http://www.volacci.com/contact?utm_source=seo_checklist&amp;utm_medium=backend&amp;utm_content=text&amp;utm_campaign=volacci_seo">Volacci</a> or ask your Drupal developer for a recommendation.</p>

<h4>Getting started</h4>
<p>Each time you open the SEO Checklist, it will look to see if any tasks have already been completed. For example, if you've already turned on clean URLs then that item will be checked.  You still need to click "Save" to time and date stamp the automatically-checked items.</p>
<p>The best way to proceed is to start at the top and work your way through each tab until you're done, clicking save after each completed item.</p>

<h4>How it's organized</h4>
<p>The sections are listed from most important to least important. The tasks in each section are also ordered from most to least important. A notable exception to this is the Tools section.</p>
<ul>
  <li><strong>Tools:</strong> The tools section contains  items that will help you get things done faster. They are not necessary for good SEO, but they are highly recommended.</li>
  <li><strong>Save Button:</strong> Be sure to click the save button after you check off each item. This will create a time and date stamp so that you can easily see when each task was completed.</li>
  <li><strong>Links:</strong> Many tasks have links next to them. Some links are to drupal.org, outside websites, or to admin sections of your own site. Links to outside resources will open in a new window.</li>
  <li><strong>Help:</strong> Some items have "More info" links. These will take you to appropriate documentation pages where you can read more about a module or important concept.</li>
</ul>

<h4>A note about pre-release modules</h4>
<p><em>Some recommended modules may not be considered ready for production websites. These modules are usually marked with "beta" or "dev" or "alpha" on Drupal.org. Please be very careful when installing any module&mdash;even those that have been fully tested and released&mdash;but be especially careful with dev, alpha, or beta modules.</em></p>

<h4>Credits</h4>
<p>The Drupal SEO Checklist was created by <a href="http://drupal.org/user/46676">Ben Finklea</a>, the CEO of <a href="http://www.volacci.com/?utm_source=seo_checklist&amp;utm_medium=backend&amp;utm_content=text&amp;utm_campaign=volacci_seo">Volacci</a> and a long-time Drupal community member. Development was paid for exclusively by Volacci. Special thanks to <a href="http://drupal.org/user/236758">Travis Carden</a> who created the <a href="http://drupal.org/project/checklistapi">Checklist API</a> and ported the Drupal SEO Checklist module to use it. </p>
<p id="seo-checklist-intro-volacci">
  <a href="http://www.volacci.com/?utm_source=seo_checklist&amp;utm_medium=backend&amp;utm_content=logo&amp;utm_campaign=volacci_seo"><?php print $volacci_logo; ?></a>
  <strong>Marketing Intelligence.</strong>
</p>
