<?php
/**
 * @file
 * Theme implementation to display the Google Analytics Reports dashboard.
 */
?>

<div class="google-analytics-summary google-analytics-reports">
  <div class="google-analytics-visits">
    <h3><?php print t('Sessions over the last 30 days'); ?></h3>
    <?php print $chart; ?>
  </div>
  
  <div class="google-analytics-pages">
    <h3><?php print t('Top Pages'); ?></h3>
    <?php print $pages; ?>
  </div>
  <p><?php print $sessions; ?></p>
</div>
