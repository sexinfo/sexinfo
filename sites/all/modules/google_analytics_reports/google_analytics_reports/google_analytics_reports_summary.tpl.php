<?php
/**
 * @file
 * Theme implementation to display the Google Analytics summary.
 */
?>

<div class="google-analytics-summary google-analytics-reports">
  <div class="google-analytics-visits">
    <h3><?php print t('Visits Over the Past 30 Days'); ?></h3>
    <?php print $visit_chart; ?>
  </div>

  <div class="google-analytics-totals">
    <h3><?php print t('Site Usage'); ?></h3>
    <table>
      <tr class="odd">
        <td><?php print $entrances; ?></td>
        <th><?php print t('Visits'); ?></th>
        <td><?php print $bounces; ?></td>
        <th><?php print t('Bounce Rate'); ?></th>
      </tr>
      <tr class="even">
        <td><?php print $pageviews; ?></td>
        <th><?php print t('Pageviews'); ?></th>
        <td><?php print $time_on_site; ?></td>
        <th><?php print t('Avg. Time on Site'); ?></th>
      </tr>
      <tr class="odd">
        <td><?php print $pages_per_visit; ?></td>
        <th><?php print t('Pages/Visit'); ?></th>
        <td><?php print $new_visits ?></td>
        <th><?php print t('% New Visits'); ?></th>
      </tr>
    </table>
  </div>

  <div class="google-analytics-pages">
    <h3><?php print t('Top Pages'); ?></h3>
    <?php print $pages; ?>
  </div>

  <div class="clearfix">
    <div class="google-analytics-referrals">
      <h3><?php print t('Top Referrals'); ?></h3>
      <?php print $referrals; ?>
    </div>

    <div class="google-analytics-keywords">
      <h3><?php print t('Top Keywords'); ?></h3>
      <?php print $keywords; ?>
    </div>
  </div>
</div>