<?php
/**
 * @file
 * Theme implementation to display the Google Analytics detail page.
 */
?>

<div class="google-analytics-detail google-analytics-reports">

  <div class="google-analytics-pageviews">
    <h3><?php print t('Pageviews Over the Past 30 Days'); ?></h3>
    <?php print $pageviews_chart; ?>
  </div>

  <div class="google-analytics-totals">
    <h3><?php print t('This page was viewed !count times', array('!count' => $pageviews)) ?></h3>
    <table>
      <tr class="odd">
        <td><?php print $pageviews; ?></td>
        <th><?php print t('Pageviews'); ?></th>
        <td><?php print $bounce_rate; ?>%</td>
        <th><?php print t('Bounce Rate'); ?></th>
      </tr>
      <tr class="even">
        <td><?php print $unique_pageviews; ?></td>
        <th><?php print t('Unique Views'); ?></th>
        <td><?php print $exit_rate; ?>%</td>
        <th><?php print t('Exit Rate'); ?></th>
      </tr>
      <tr class="odd">
        <td><?php print $avg_time_on_page; ?></td>
        <th><?php print t('Time on Page'); ?></th>
        <td>$<?php print $goal_value; ?></td>
        <th><?php print t('$ Index'); ?></th>
      </tr>
    </table>
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


