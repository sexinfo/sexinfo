/**
 * @file
 * ClosedQuestion template glue code for the xmlEditor.
 */
(function ($) {
  Drupal.behaviors.closedquestion = {
    attach: function (context, settings) {
      // When a template link on the "Choose a template" tab is clicked, load its XML.
      $('.cq-select-template', context).bind('click.cq-select-template', function (event) {
        // Don't let the link's fragment get into the browser's location.
        event.preventDefault();

        // Get the template name from the link's fragment and load its XML.
        var hash = $(this).attr('href').split('#')[1];
        CQ_LoadTemplate(settings.closedquestion.templates[hash]['xml']);
      });
    }
  };
})(jQuery);
