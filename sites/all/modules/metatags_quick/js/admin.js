/**
 * 
 */
(function ($) {
 Drupal.behaviors.metatags_quick = {
  attach: function(context) {
	$('.entity_type_collapsible').click(function(){
	 var classList =$(this).attr('class').split(/\s+/);
	 $.each(classList, function(index, item){
	   if (item.substring(0, 12) == 'entity_name_') {
		 $('.entity_child_'+item.substring(12)).toggle();    
	   }
	 });
    });  

	$('.cb_bundle_parent').click(function(){
	 var classList =$(this).attr('class').split(/\s+/);
	 var curstate = this.checked;
	 $.each(classList, function(index, item){
	   if (item.substring(0, 15) == 'cb_bundle_name_') {
		 $('.cb_child_'+item.substring(15)).attr('checked', curstate);    
	   }
	 });
	});  
  }
 }
}(jQuery));