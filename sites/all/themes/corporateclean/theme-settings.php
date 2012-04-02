<?php
/**
 * Implements hook_form_FORM_ID_alter().
 *
 * @param $form
 *   The form.
 * @param $form_state
 *   The form state.
 */
function corporateclean_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['mtt_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Corporate Clean Theme Settings'),
    '#collapsible' => FALSE,
	'#collapsed' => FALSE,
  );

  $form['mtt_settings']['breadcrumb'] = array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumb'),
    '#collapsible' => TRUE,
	'#collapsed' => FALSE,
  );
  
  $form['mtt_settings']['breadcrumb']['breadcrumb_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show breadcrumb'),
  	'#description'   => t('Use the checkbox to enable or disable Breadcrumb.'),
	'#default_value' => theme_get_setting('breadcrumb_display','corporateclean'),
    '#collapsible' => TRUE,
	'#collapsed' => FALSE,
  );
  
  $form['mtt_settings']['slideshow'] = array(
    '#type' => 'fieldset',
    '#title' => t('Front Page Slideshow'),
    '#collapsible' => TRUE,
	'#collapsed' => FALSE,
  );
  
  $form['mtt_settings']['slideshow']['slideshow_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show slideshow'),
	'#default_value' => theme_get_setting('slideshow_display','corporateclean'),
  );
  
  $form['mtt_settings']['slideshow']['slideshow_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
  	'#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
	'#default_value' => theme_get_setting('slideshow_effect','corporateclean'),
    '#options' => array(
		'blindX' => t('blindX'),
		'blindY' => t('blindY'),
		'blindZ' => t('blindZ'),
		'cover' => t('cover'),
		'curtainX' => t('curtainX'),
		'curtainY' => t('curtainY'),
		'fade' => t('fade'),
		'fadeZoom' => t('fadeZoom'),
		'growX' => t('growX'),
		'growY' => t('growY'),
		'scrollUp' => t('scrollUp'),
		'scrollDown' => t('scrollDown'),
		'scrollLeft' => t('scrollLeft'),
		'scrollRight' => t('scrollRight'),
		'scrollHorz' => t('scrollHorz'),
		'scrollVert' => t('scrollVert'),
		'shuffle' => t('shuffle'),
		'slideX' => t('slideX'),
		'slideY' => t('slideY'),
		'toss' => t('toss'),
		'turnUp' => t('turnUp'),
		'turnDown' => t('turnDown'),
		'turnLeft' => t('turnLeft'),
		'turnRight' => t('turnRight'),
		'uncover' => t('uncover'),
		'wipe' => t('wipe'),
		'zoom' => t('zoom'),
    ),
  );
  
  $form['mtt_settings']['slideshow']['slideshow_effect_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Effect duration (sec)'),
	'#default_value' => theme_get_setting('slideshow_effect_time','corporateclean'),
  );
  
}
