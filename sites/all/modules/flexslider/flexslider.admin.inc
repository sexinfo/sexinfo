<?php
/**
 * @file
 * Administrative page callbacks for the flexslider module.
 */


/**
 * Menu callback; Listing of all current option sets.
 */
function flexslider_page_optionset_list() {
  $optionsets = flexslider_optionsets();

  $header = array(t('Option Set Name'), array('data' => t('Operations'), 'colspan' => 2));
  $rows = array();
  foreach ($optionsets as $name => $optionset) {
    $rows[] = array(
      l($optionset['title'], 'admin/config/media/flexslider/edit/' . $name),
      l(t('edit'), 'admin/config/media/flexslider/edit/' . $name),
      // Hide the delete link for the 'default' set
      ($name == 'default') ? '' : l(t('delete'), 'admin/config/media/flexslider/delete/' . $name),
    );
  }

  return theme('table', array(
      'header' => $header,
      'rows' => $rows,
      'empty' => t('There are currently no option sets. <a href="!url">Add a new one</a>.', array('!url' => url('admin/config/media/flexslider/add'))),
  ));
}


/**
 * Form builder; Form for adding a new option set.
 */
function flexslider_form_optionset_add($form, &$form_state) {
  $form['title'] = array(
    '#type' => 'textfield',
    '#maxlength' => '255',
    '#title' => t('Title'),
    '#description' => t('A human-readable title for this option set.'),
    '#required' => TRUE,
  );
  $form['name'] = array(
    '#type' => 'machine_name',
    '#maxlength' => '255',
    '#machine_name' => array(
      'source' => array('title'),
      'exists' => 'flexslider_optionset_exists',
    ),
    '#required' => TRUE,
  );

  $form['actions'] = array(
    '#type' => 'actions',
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Create new option set'),
    ),
    'cancel' => array(
      '#type' => 'link',
      '#title' => t('Cancel'),
      '#href' => 'admin/config/media/flexslider',
    )
  );

  return $form;
}

/**
 * Submit handler for adding a new option set.
 */
function flexslider_form_optionset_add_submit($form, &$form_state) {
  $optionset = array(
    'name' => $form_state['values']['name'],
    'title' => $form_state['values']['title'],
    'options' => array(
      'height' => 300,
      'width' => 450,
    ),
  );
  $optionset = flexslider_optionset_save($optionset, TRUE);
  drupal_set_message(t('Option set %name was created.', array('%name' => $optionset['name'])));
  $form_state['redirect'] = 'admin/config/media/flexslider/edit/' . $optionset['name'];
}


/**
 * Theme to embed tables into forms.
 */
function theme_flexslider_form_table($variables) {
  $form = $variables['form'];

  $rows = array();
  foreach (element_children($form) as $row_key) {
    $row = array();
    foreach (element_get_visible_children($form[$row_key]) as $cell_key) {
      $cell = array('data' => drupal_render($form[$row_key][$cell_key]));
      if (!empty($form[$row_key][$cell_key]['#table_attributes']))
        $cell += $form[$row_key][$cell_key]['#table_attributes'];
      $row[] = $cell;
    }
    $rows[] = $row;
  }

  $variables = array();
  foreach ($form as $key => $value) {
    if (element_property($key)) {
      $variables[substr($key, 1)] = $value;
    }
  }
  $variables['rows'] = $rows;

  return theme('table', $variables);
}

/**
 * This function returns an array defining the form elements used to edit the different options.
 */
function flexslider_option_elements() {
  return array(
    'animation' => array(
      '#type' => 'select',
      '#title' => t('Animation'),
      '#description' => t('Select your animation type'),
      '#options' => array(
        'fade'   => t('fade'),
        'slide'  => t('slide'),
      ),
      '#default_value' => 'fade',
    ),
    'slideDirection' => array(
      '#type' => 'checkbox',
      '#title' => t('Thumbnail Carousel'),
      '#description' => t('Select the sliding direction, "horizontal" or "vertical"'),
      '#options' => array(
        'horizontal'   => t('horizontal'),
        'vertical'  => t('vertical'),
      ),
      '#default_value' => 'horizontal',
    ),
    'slideshow' => array(
      '#type' => 'checkbox',
      '#title' => t('Auto slide show'),
      '#description' => t('Animate slider automatically'),
      '#default_value' => TRUE,
    ),
    'slideshowSpeed' => array(
      '#type' => 'textfield',
      '#title' => t('Slideshow speed'),
      '#description' => t('Set the speed of the slideshow cycling, in milliseconds'),
      '#element_validate' => array('_flexslider_validate_integer'),
      '#default_value' => 7000,
    ),
    'animationDuration' => array(
      '#type' => 'textfield',
      '#title' => t('Animation speed'),
      '#description' => t('Set the speed of animations, in milliseconds'),
      '#element_validate' => array('_flexslider_validate_integer'),
      '#default_value' => 600,
    ),
    'directionNav' => array(
      '#type' => 'checkbox',
      '#title' => t('Navigation'),
      '#description' => t('Create navigation for previous/next navigation?'),
      '#default_value' => TRUE,
    ),
    'controlNav' => array(
      '#type' => 'checkbox',
      '#title' => t('Paging control'),
      '#description' => t('Create navigation for paging control of each slide? Note: Leave checked for manualControls usage'),
      '#default_value' => TRUE,
    ),
    'keyboardNav' => array(
      '#type' => 'checkbox',
      '#title' => t('Keyboard navigation'),
      '#description' => t('Allow slider navigating via keyboard left/right keys'),
      '#default_value' => TRUE,
    ),
    'mousewheel' => array(
      '#type' => 'checkbox',
      '#title' => t('Mousewheel navigation'),
      '#description' => t('Allow slider navigating via mousewheel'),
      '#default_value' => TRUE,
    ),
    'prevText' => array(
      '#type' => 'textfield',
      '#title' => t('Previous text'),
      '#description' => t('Set the text for the "previous" directionNav item'),
      '#default_value' => 'Previous',
    ),
    'nextText' => array(
      '#type' => 'textfield',
      '#title' => t('Next text'),
      '#description' => t('Set the text for the "next" directionNav item'),
      '#default_value' => 'Next',
    ),
    'pausePlay' => array(
      '#type' => 'checkbox',
      '#title' => t('Pause/Play'),
      '#description' => t('Create pause/play dynamic element'),
      '#default_value' => FALSE,
    ),
    'pauseText' => array(
      '#type' => 'textfield',
      '#title' => t('Pause text'),
      '#description' => t('Set the text for the "pause" pausePlay item'),
      '#default_value' => 'Pause',
    ),
    'playText' => array(
      '#type' => 'textfield',
      '#title' => t('Play text'),
      '#description' => t('Set the text for the "play" pausePlay item'),
      '#default_value' => 'Play',
    ),
    'randomize' => array(
      '#type' => 'checkbox',
      '#title' => t('Randomize'),
      '#description' => t('Randomize slide order'),
      '#default_value' => FALSE,
    ),
    'slideToStart' => array(
      '#type' => 'textfield',
      '#title' => t('Start slide'),
      '#description' => t('The slide that the slider should start on. Array notation (0 = first slide)'),
      '#element_validate' => array('_flexslider_validate_integer_auto'),
      '#default_value' => 0,
    ),
    'animationLoop' => array(
      '#type' => 'checkbox',
      '#title' => t('Loop animation'),
      '#description' => t('Should the animation loop? If false, directionNav will received "disable" classes at either end'),
      '#default_value' => TRUE,
    ),
    'pauseOnAction' => array(
      '#type' => 'checkbox',
      '#title' => t('Pause on action'),
      '#description' => t('Pause the slideshow when interacting with control elements, highly recommended.'),
      '#default_value' => TRUE,
    ),
    'controlsContainer' => array(
      '#type' => 'textfield',
      '#title' => t('Controls container'),
      '#description' => t('Declare which container the navigation elements should be appended too. Default container is the flexSlider element. Example use would be ".flexslider-container", "#container", etc. If the given element is not found, the default action will be taken.'),
      '#default_value' => '',
    ),
    'manualControls' => array(
      '#type' => 'textfield',
      '#title' => t('Manual controls'),
      '#description' => t('Declare custom control navigation. Example would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.'),
      '#default_value' => '',
    ),
  );
}

/**
 * Returns the form element to use to edit the given option.
 */
function flexslider_option_element($option, $value) {
  $elements = flexslider_option_elements();
  $element = isset($elements[$option]) ? $elements[$option] : array('#type' => 'textfield');

  if ($value !== NULL) {
    if ($element['#type'] == 'select') {
      if ($value === TRUE)
        $value = 'true';
      elseif ($value === FALSE)
        $value = 'false';
    }
    $element['#default_value'] = $value;
  }

  return $element;
}

/**
 * Form  builder; Form to edit a given option set.
 */
function flexslider_form_optionset_edit($form, &$form_state, $optionset) {
  if (empty($form_state['optionset'])) {
    $form_state['optionset'] = $optionset;
  }
  else {
    $optionset = $form_state['optionset'];
  }

  // Print a warning if there's no height and/or width option in the set.
  foreach (array('height', 'width') as $dimension) {
    if (!array_key_exists($dimension, $optionset['options'])) {
      drupal_set_message(t('You should add a %dimension option if you don\'t plan to set it manually via CSS.', array('%dimension' => $dimension)), 'warning');
    }
  }

  // Title
  $form['title'] = array(
    '#type' => 'textfield',
    '#maxlength' => '255',
    '#title' => t('Title'),
    '#default_value' => $optionset['title'],
    '#description' => t('A human-readable title for this option set.'),
    '#required' => TRUE,
  );

  // Show the theme select box if there is more than one theme
  $themes = array_keys(flexslider_get_themes(TRUE));
  if (count($themes) == 1) {
    $form['theme'] = array(
      '#type' => 'hidden',
      '#value' => $themes[0],
    );
  }
  elseif (count($themes) > 1) {
    asort($themes);

    $form['theme'] = array(
      '#type' => 'select',
      '#title' => t('Theme'),
      '#description' => t('As you have more than one flexslider theme installed, please select the one to use.'),
      '#options' => array_combine($themes, $themes),
      '#default_value' => $optionset['theme'],
    );
  }

  // Show select boxes for the various image styles (thumbnail, normal, big)
  $image_style = image_style_options(FALSE);
  $form['image_style'] = array(
    '#type' => 'fieldset',
    '#title' => 'Image style',
    '#tree' => TRUE,
  );
  $form['image_style']['normal'] = array(
    '#type' => 'select',
    '#title' => t('Normal image style'),
    '#description' => t('Image style for the main stage images.'),
    '#empty_option' => t('None (original image)'),
    '#options' => $image_style,
    '#default_value' => $optionset['imagestyle_normal'],
  );

  // Option table
  $form['options'] = array(
    '#theme' => 'flexslider_form_table',
    '#tree' => TRUE,
    '#header' => array(t('Name'), t('Value'), t('Operations')),
  );

  $i = 0;
  foreach ($optionset['options'] as $key => $value) {
    $option_element = flexslider_option_element($key, $value);

    $form['options'][] = array(
      'name' => array(
        '#type' => 'item',
        '#title' => check_plain($key),
        '#description' => isset($option_element['#title']) ? $option_element['#title'] : '',
      ),
      'value' => $option_element + array(
        '#option_name' => $key,
        '#title_display' => 'none',
      ),
      'delete' => array(
        '#type' => 'submit',
        '#name' => 'button_del_' . $i++,
        '#value' => t('Delete'),
        '#submit' => array('flexslider_form_optionset_edit_submit_delete'),
        '#limit_validation_errors' => array(),
      ),
    );
  }

  // 'Add option' row at the end of the table
  $options = array_diff(array_keys(flexslider_option_elements()), array_keys($optionset['options']));
  $options = empty($options) ? array() : array_combine($options, $options);
  $form['options'][] = array(
    'add_option_row' => array(
      '#table_attributes' => array('colspan' => '3', 'class' => array('container-inline')),
      '#tree' => FALSE,
      'new_option' => array(
        '#type' => 'select',
        '#options' => $options,
        '#empty_option' => t('Select or enter:'),
      ),
      'new_option_custom' => array(
        '#type' => 'textfield',
        '#states' => array(
          'visible' => array(
            ':input[name="new_option"]' => array('value' => ''),
          ),
        ),
      ),
      'button_add' => array(
        '#type' => 'submit',
        '#name' => 'add_option',
        '#value' => t('Add option'),
        '#submit' => array('flexslider_form_optionset_edit_submit_add'),
        '#limit_validation_errors' => array(
          array('new_option'),
          array('new_option_custom'),
        ),
      ),
    ),
  );

  $form['actions'] = array(
    '#type' => 'actions',
    'submit' => array(
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => t('Save option set')
    ),
    'cancel' => array(
      '#type' => 'link',
      '#title' => t('Cancel'),
      '#href' => 'admin/config/media/flexslider',
    )
  );

  return $form;
}

/**
 * Validate a form element that should have an integer value.
 */
function _flexslider_validate_integer($element, &$form_state) {
  $value = $element['#value'];
  if ($value !== '' && (!is_numeric($value) || intval($value) != $value || $value < 0)) {
    form_error($element, t('%name must be a positive integer.', array('%name' => $element['#title'])));
  }
}

/**
 * Validate a form element that should have an integer value or the special value 'auto'.
 */
function _flexslider_validate_integer_auto($element, &$form_state) {
  $value = $element['#value'];
  if ($value !== '' && $value !== 'auto' && (!is_numeric($value) || intval($value) != $value || $value < 0)) {
    form_error($element, t('%name must be a positive integer or the special value \'auto\'.', array('%name' => $element['#title'])));
  }
}

/**
 * Validate a form element that should have a number as value.
 */
function _flexslider_validate_number($element, &$form_state) {
  $value = $element['#value'];
  if ($value !== '' && !is_numeric($value)) {
    form_error($element, t('%name must be a number.', array('%name' => $element['#option_name'])));
  }
}

/**
 * Validate a form element that should have a value between 0 and 1.
 */
function _flexslider_validate_opacity($element, &$form_state) {
  $value = $element['#value'];
  if ($value !== '' && (!is_numeric($value) || $value < 0 || $value > 1)) {
    form_error($element, t('%name must be a value between 0 and 1.', array('%name' => $element['#option_name'])));
  }
}

/**
 * Validate a form element that should have an integer value or the special value 'all'.
 */
function _flexslider_validate_preload($element, &$form_state) {
  $value = $element['#value'];
  if ($value !== '' && $value !== 'all' && (!is_numeric($value) || intval($value) != $value || $value < 0)) {
    form_error($element, t('%name must be a positive integer or the special value \'all\'.', array('%name' => $element['#option_name'])));
  }
}

/**
 * Submit handler for 'Add option' button; Add a new option to the set.
 */
function flexslider_form_optionset_edit_submit_add($form, &$form_state) {
  $optionset = &$form_state['optionset'];

//  $optionset['options'] = array_fill_keys(array_keys(flexslider_option_elements()), NULL);

  if (!empty($form_state['values']['new_option'])) {
    $new_option_element = 'new_option';
  }
  elseif (!empty($form_state['values']['new_option_custom'])) {
    $new_option_element = 'new_option_custom';
  }

  if (isset($new_option_element)) {
    $new_option = $form_state['values'][$new_option_element];
    if (!array_key_exists($new_option, $optionset['options'])) {
      // Add the new option with a NULL value. The input element cares for a default value.
      $optionset['options'][$new_option] = NULL;
      // Reset the input field
      $form_state['input'][$new_option_element] = '';
      drupal_set_message(t('Option %name added.', array('%name' => $new_option)));
    }
    else {
      form_set_error($new_option_element, t('This set already includes the %name option.', array('%name' => $new_option)));
    }
  }

  $form_state['rebuild'] = TRUE;
}

/**
 * Submit handler for 'Delete' buttons; Delete an option from the set.
 */
function flexslider_form_optionset_edit_submit_delete($form, &$form_state) {
  $optionset = &$form_state['optionset'];

  $rowindex = $form_state['triggering_element']['#parents'][1];
  $option = $form['options'][$rowindex]['value']['#option_name'];

  unset($optionset['options'][$option]);
  drupal_set_message(t('Option %name removed.', array('%name' => $option)));

  $form_state['rebuild'] = TRUE;
}

/**
 * Submit handler for 'Save option set' button; Save the changed option set.
 */
function flexslider_form_optionset_edit_submit($form, &$form_state) {
  $optionset = &$form_state['optionset'];

  $optionset['title'] = $form_state['values']['title'];
  //$optionset['theme'] = $form_state['values']['theme'];
  //$optionset['imagestyle_thumb']  = $form_state['values']['image_styles']['thumb'];
  //$optionset['imagestyle_normal'] = $form_state['values']['image_styles']['normal'];
  //$optionset['imagestyle_big']    = $form_state['values']['image_styles']['big'];

  if (isset($form_state['values']['options'])) {
    foreach ($form_state['values']['options'] as $index => $values) {
      $element = $form['options'][$index]['value'];
      $value  = $values['value'];
  
      if ($value !== '') {
        // Do some typeguessing here...
        if ($element['#type'] == 'checkbox') {
          $value = (bool) $value;
        }
        elseif (is_numeric($value)) { // || intval($value) != $value || $value < 0)
          $value = (float) $value;
        }
        elseif (strcasecmp($value, 'true') == 0) {
          $value = TRUE;
        }
        elseif (strcasecmp($value, 'false') == 0) {
          $value = FALSE;
        }
      }
  
      $option = $element['#option_name'];
      $optionset['options'][$option] = $value;
    }
  }

  flexslider_optionset_save($optionset);
  drupal_set_message(t('Option set %name changed.', array('%name' => $optionset['name'])));
  $form_state['redirect'] = 'admin/config/media/flexslider';
}


/**
 * Form builder; Form to delete a given option set.
 */
function flexslider_optionset_form_delete($form, &$form_state, $optionset) {
  $form_state['optionset'] = &$optionset;

  return confirm_form(
    $form,
    t('Are you sure you want to delete the option set %name?', array('%name' => $optionset['name'])),
    'admin/config/media/flexslider',
    NULL,
    t('Delete'),  t('Cancel')
  );
}

/**
 * Submit handler for deleting an option set.
 */
function flexslider_optionset_form_delete_submit($form, &$form_state) {
  $optionset = &$form_state['optionset'];

  if ($optionset['name'] == 'default') {
    // Prevent deletion of the default set so we can fall back to it.
    drupal_set_message(t('The default option set may not be deleted!', 'error'));
  }
  else {
    flexslider_optionset_delete($optionset);
    drupal_set_message(t('Option set %name was deleted.', array('%name' => $optionset['name'])));
  }

  $form_state['redirect'] = 'admin/config/media/flexslider';
}


/**
 * Form builder; Form for advanced module settings.
 */
function flexslider_form_settings() {
  $form = array();

  $form['library'] = array(
    '#type' => 'fieldset',
    '#title' => 'Library',
  );

  $form['library']['themes_title'] = array(
    '#type' => 'item',
    '#title' => t('Themes'),
    '#description' => t('The following themes are currently known to the module. Clear the cache to search for new ones.'),
  );
  $form['library']['themes'] = array(
    '#theme' => 'flexslider_form_table',
    '#header' => array(t('Name'), t('CSSfile')),
    '#empty' => t('No themes found!'),
  );
  foreach (flexslider_get_themes() as $theme => $file) {
    $form['library']['themes'][] = array(
      array(
        '#markup' => check_plain($theme),
      ),
      array(
        '#markup' => t('<code>@file</code>', array('@file' => $file)),
      ),
    );
  }
  
  $form['library']['button_clearcache'] = array(
    '#type' => 'submit',
    '#name' => 'button_clearcache',
    '#value' => t('Clear cache'),
    '#submit' => array('flexslider_form_settings_submit_clearcache'),
  );

  // Debug mode toggle
  $form['library']['flexslider_debug'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable debug mode'),
    '#description' => t('Load the human-readable version of the FlexSlider library.'),
    '#default_value' => variable_get('flexslider_debug', FALSE),
    '#access' => user_access('administer flexslider'),
  );

  return $form;
}

/**
 * Submit handler for the advanced module settings form button 'Clear cache'.
 */
function flexslider_form_settings_submit_clearcache($form, &$form_state) {
  cache_clear_all('flexslider_', 'cache', TRUE);
  drupal_set_message(t('Cache cleared.'));
}

/**
 * Submit handler for the advanced module settings.
 */
function flexslider_form_settings_submit($form, &$form_state) {
  drupal_set_message(t('NYI: Nothing done.'));
  
  // Set the debug mode
  variable_set($form_state['values']['flexslider_debug'], FALSE);
}
