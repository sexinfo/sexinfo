/**
 * Google Chart Tools javascript
 *
 */

(function($) {
  Drupal.behaviors.datavizGooChart = {
    attach: function(context, settings) {
      // Need to check this or the views admin page crashes
      if (settings.Dataviz) {
        var chart = new Object;
        // Loop on the charts in the settings.
        for (var chartId in settings.Dataviz.chart) {
          // Filter by context
          if ($('#'+chartId, context).length) {
            // Data table.
            var data = new google.visualization.arrayToDataTable(settings.Dataviz.chart[chartId].data);
            var options = settings.Dataviz.chart[chartId].options;
            var element = document.getElementById(settings.Dataviz.chart[chartId].containerId);
            if (element) {
              chart[settings.Dataviz.chart[chartId]] = new google.visualization[settings.Dataviz.chart[chartId].chartType](element);
              chart[settings.Dataviz.chart[chartId]].draw(data, options);
            }
          }
        }
      }
    }
  };
})(jQuery);
