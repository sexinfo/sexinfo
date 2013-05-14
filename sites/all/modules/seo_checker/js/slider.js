(function ($) {
  Sliders = {};
  
  Sliders.changeHandle = function(e,ui) {
    var id = jQuery(ui.handle).parents('div.slider-widget-container').attr('id');
    if (typeof(ui.values) != 'undefined') {
      jQuery.each(ui.values, function(i,val) {
        jQuery("#"+id+"_value_"+i).val(val);
        jQuery("#"+id+"_nr_"+i).text(val+"%");
      });
    } else {
      jQuery("#"+id+"_value_0").val(ui.value);
      jQuery("#"+id+"_nr_0").text(ui.value+"%");
    }
  };
}) (jQuery);