<?php
/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return
 *   A string containing the breadcrumb output.
 */
function corporateclean_breadcrumb($variables){
  $breadcrumb = $variables['breadcrumb'];
  if (!empty($breadcrumb)) {
    $breadcrumb[] = drupal_get_title();
    return '<div class="breadcrumb">' . implode(' <span class="breadcrumb-separator">/</span> ', $breadcrumb) . '</div>';
  }
}

function corporateclean_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
  
    unset($form['search_block_form']['#title']);
	
    $form['search_block_form']['#title_display'] = 'invisible';
	$form_default = t('Search');
    $form['search_block_form']['#default_value'] = $form_default;
    $form['actions']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/search-button.png');

 	$form['search_block_form']['#attributes'] = array('onblur' => "if (this.value == '') {this.value = '{$form_default}';}", 'onfocus' => "if (this.value == '{$form_default}') {this.value = '';}" );
  }
}

/**
 * Add javascript files for page--front jquery slideshow.
 */
drupal_add_js(drupal_get_path('theme', 'corporateclean') . '/js/jquery.cycle.all.min.js');

//Initialize slideshow using theme settings
$effect=theme_get_setting('slideshow_effect','corporateclean');
$effect_time=theme_get_setting('slideshow_effect_time','corporateclean')*1000;

drupal_add_js('jQuery(document).ready(function($) {  

$("#slideshow").cycle({
	fx:    "'.$effect.'",
	speed:  "slow",
	timeout: "'.$effect_time.'",
	pager:  "#slider-navigation",
	pagerAnchorBuilder: function(idx, slide) {
		return "#slider-navigation li:eq(" + (idx) + ") a";
	},
	after: onAfter
});

function onAfter(curr, next, opts, fwd){
	var $ht = $(this).height();
	$(this).parent().animate({height: $ht});
}

});',
array('type' => 'inline', 'scope' => 'header', 'weight' => 5)
);

?>