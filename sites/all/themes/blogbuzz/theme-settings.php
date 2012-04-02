<?php
// $Id: theme-settings.php,v 1.2.2.1 2011/01/23 05:03:13 antsin Exp $

/*
+----------------------------------------------------------------+
|   BlogBuzz for Dupal 7.x - Version 1.0                         |
|   Copyright (C) 2011 Antsin.com All Rights Reserved.           |
|   @license - GNU GENERAL PUBLIC LICENSE                        |
|----------------------------------------------------------------|
|   Theme Name: BlogBuzz                                         |
|   Description: BlogBuzz by Antsin                              |
|   Author: Antsin.com                                           |
|   Website: http://www.antsin.com/                              |
|----------------------------------------------------------------+
*/

  $form['tnt_container']['style_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Blogbuzz color setting'),
    '#description' => t('Use color setting to change default color for your theme.'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['tnt_container']['style_settings']['style'] = array(
    '#type' => 'select',
    '#title' => t('Color'),
    '#default_value' => theme_get_setting('style'),
    '#options' => array(
      'stone'    => t('Stone Soft'),
	  'pink'     => t('Fresh Pink'),
      'blue'     => t('Sky Blue'),
	  'chocolate'=> t('Chocolate Milk'),
    ),
  ); 
 
  return $form; 
?>