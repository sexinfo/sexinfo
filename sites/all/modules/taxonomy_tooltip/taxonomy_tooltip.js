(function ($) {
  Drupal.behaviors.taxonomy_tooltip = {
    attach: function(context, settings) {
      $('span.taxonomy-tooltip-element').mouseover(function(event) {
        console.log(this);
        var tooltip = $('.taxonomy-tooltip.' + $(this).attr('rel'));
        //var parent = document.getElementsByClassName("taxonomy-tooltip-element")[0].parentElement;
        var parent = $(this).parent();
        //console.log(parent);

        // Check if tooltip element already attached
        if (parent.has(tooltip[0])) {
          console.log("Tooltip already attached!")
          positionTooltip(this, tooltip);
          tooltip.show();
        } else {
          console.log("Attaching tooltip...");
          $('span.taxonomy-tooltip-element').after(tooltip[0]);
          positionTooltip(this, tooltip);
          tooltip.show();
        }
      }).mouseout(function() {
        $('.taxonomy-tooltip.' + $(this).attr('rel')).hide();
      });
    }
  }
})(jQuery);

function positionTooltip(termElement, tooltip) {
  //console.log(tooltip[0]);
  var termRect = $(termElement).position();
  console.log(termRect);
  var tPosX = termRect.left - 30;
  var tPosY = termRect.top + 20;
  tooltip.css({
    'position': 'absolute',
    'top': tPosY + 'px',
    'left': tPosX + 'px'
  });
  console.log(tooltip.position());
};
