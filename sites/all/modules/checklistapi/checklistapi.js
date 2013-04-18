(function ($) {
  "use strict";

  /**
   * Provides the summary information for the block settings vertical tabs.
   */
  Drupal.behaviors.checklistapiFieldsetSummaries = {
    attach: function (context) {
      $('#checklistapi-checklist-form .vertical-tabs-panes > fieldset', context).drupalSetSummary(function (context) {
        var total = $(':checkbox.checklistapi-item', context).size(), args = {};
        if (total) {
          args['@complete'] = $(':checkbox.checklistapi-item:checked', context).size();
          args['@total'] = total;
          args['@percent'] = Math.round(args['@complete'] / args['@total'] * 100);
          return Drupal.t('@complete of @total (@percent%)', args);
        }
      });
    }
  };

  /**
   * Adds dynamic item descriptions toggling.
   */
  Drupal.behaviors.checklistapiCompactModeLink = {
    attach: function (context) {
      $('#checklistapi-checklist-form .compact-link a', context).click(function () {
        $(this).closest('#checklistapi-checklist-form').toggleClass('compact-mode');
        var is_compact_mode = $(this).closest('#checklistapi-checklist-form').hasClass('compact-mode');
        $(this)
          .text((is_compact_mode) ? Drupal.t('Show item descriptions') : Drupal.t('Hide item descriptions'))
          .attr('title', (is_compact_mode) ? Drupal.t('Expand layout to include item descriptions.') : Drupal.t('Compress layout by hiding item descriptions.'))
        document.cookie = 'Drupal.visitor.checklistapi_compact_mode=' + ((is_compact_mode) ? 1 : 0);
        return false;
      });
    }
  };

})(jQuery);
