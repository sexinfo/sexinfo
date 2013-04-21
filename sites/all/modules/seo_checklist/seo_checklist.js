(function ($) {

  "use strict";
  Drupal.behaviors.seo_checklist = {
    attach: function (context) {

      // Open external links in a new window.
      $('#checklistapi-checklist-form fieldset a', context).filter(function () {
        // Ignore non-HTTP (e.g. mailto:) link.
        return this.href.indexOf('http') === 0;
      }).filter(function () {
        // Filter out links to the same domain.
        return this.hostname && this.hostname !== location.hostname;
      }).each(function () {
        // Send all links to drupal.org to the same window. Open others in their
        // own windows.
        $(this).attr('target', (this.hostname === 'drupal.org') ? 'drupal_org' : '_blank');
      });

    }
  };

})(jQuery);
