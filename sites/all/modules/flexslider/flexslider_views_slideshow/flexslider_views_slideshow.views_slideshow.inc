<?php

/**
 * @file
 * Describe the file
 *
 * @author Mathew Winstone (minorOffense) <mwinstone@coldfrontlabs.ca>
 */

/**
 * Implements hook_views_slideshow_slideshow_info().
 */
function flexslider_views_slideshow_views_slideshow_slideshow_info() {
  $options = array(
    'flexslider_views_slideshow' => array(
      'name' => t('FlexSlider'),
      'accepts' => array(
        'goToSlide',
        'nextSlide',
        'pause',
        'play',
        'previousSlide',
      ),
      'calls' => array(
        'transitionBegin',
        'transitionEnd',
        'goToSlide',
        'pause',
        'play',
        'nextSlide',
        'previousSlide',
      ),
    ),
  );

  // @todo figure out which options to add

  return $options;
}

/**
 * Implements hook_views_slideshow_option_definition().
 */
function flexslider_views_slideshow_views_slideshow_option_definition() {
  // Default options for FlexSlider
  $options['flexslider_views_slideshow'] = array(
    'contains' => array(
      'animation' => array('default' => 'fade'),
      'animationduration' => array('default' => 6000),
      'slidedirection' => array('default' => 'horizontal'),
      'slideshow' => array('default' => TRUE),
      'slideshowspeed' => array('default' => 7000),
      'directionnav' => array('default' => TRUE),
      'controlnav' => array('default' => TRUE),
      'keyboardnav' => array('default' => TRUE),
      'mousewheel' => array('default' => FALSE),
      'prevtext' => array('default' => t('Previous')),
      'nexttext' => array('default' => t('Next')),
      'pauseplay' => array('default' => FALSE),
      'pausetext' => array('default' => t('Pause')),
      'playtext' => array('default' => t('Play')),
      'randomize' => array('default' => FALSE),
      'slidetostart' => array('default' => 0), // integer value, not boolean
      'animationloop' => array('default' => TRUE),
      'pauseonaction' => array('default' => TRUE),
      'pauseonhover' => array('default' => TRUE),
      'controlscontainer' => array('default' => ''),
      'manualcontrols' => array('default' => ''),
      'startcallback' => array('default' => 'function() {}'),
      'beforecallback' => array('default' => 'function() {}'),
      'aftercallback' => array('default' => 'function() {}'),
      'endcallback' => array('default' => 'function() {}'),
    ),
  );
  // @todo figure out which options to define

  return $options;
}

/**
 * Configuration form within the Views Slideshow settings pane
 */
function flexslider_views_slideshow_views_slideshow_slideshow_type_form(&$form, &$form_state, &$view) {
    // For a full list of options
    // @see http://flex.madebymufffin.com/

    // Animation
    $form['flexslider_views_slideshow']['animation'] = array(
      '#type' => 'select',
      '#title' => t('Animation'),
      '#multiple' => FALSE,
      '#description' => t('The type of animation to use between slides.'),
      '#options' => array(
        'fade' => t('Fade'),
        'slide' => t('Slide'),
      ),
      '#default_value' => $view->options['flexslider_views_slideshow']['animation'],
    );

    // Animation Duration
    $form['flexslider_views_slideshow']['animationduration'] = array(
      '#type' => 'textfield',
      '#title' => t('Animation Duration'),
      '#description' => t('The duraction of the transition animations, in milliseconds.'),
      '#size' => 40,
      '#maxlength' => 255,
      '#default_value' => $view->options['flexslider_views_slideshow']['animationduration'],
    );


    // Slide Direction
    $form['flexslider_views_slideshow']['slidedirection'] = array(
      '#type' => 'select',
      '#title' => t('Slide Direction'),
      '#multiple' => FALSE,
      '#description' => t('The the direction of the slide transition.'),
      '#options' => array(
        'horizontal' => t('Horizontal'),
        'vertical' => t('Vertical'),
      ),
      '#default_value' => $view->options['flexslider_views_slideshow']['slidedirection'],
    );

    // Slideshow Mode
    $form['flexslider_views_slideshow']['slideshow'] = array(
      '#type' => 'checkbox',
      '#title' => t('Automatic Playback'),
      '#description' => t('Automatically transition between slides.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['slideshow'],
    );

    // Slideshow Speed
    $form['flexslider_views_slideshow']['slideshowspeed'] = array(
      '#type' => 'textfield',
      '#title' => t('Slideshow Speed'),
      '#description' => t('The duration each slide is visible between transitions, in milliseconds.'),
      '#size' => 40,
      '#maxlength' => 255,
      '#default_value' => $view->options['flexslider_views_slideshow']['slideshowspeed'],
    );

    // Direction Navigation
    $form['flexslider_views_slideshow']['directionnav'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add Direction Navigation'),
      '#description' => t('Add navigation links for previous and next slides.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['directionnav'],
    );

    // Control Navigation
    $form['flexslider_views_slideshow']['controlnav'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add Navigation Controls'),
      '#description' => t('Add navigation controls for individual slides.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['controlnav'],
    );

    // Keyboard Navigation
    $form['flexslider_views_slideshow']['keyboardnav'] = array(
      '#type' => 'checkbox',
      '#title' => t('Keyboard Navigation'),
      '#description' => t('Allow slider navigating via keyboard left/right keys.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['keyboardnav'],
    );

    // Mousewheel Navigation
    $form['flexslider_views_slideshow']['mousewheel'] = array(
      '#type' => 'checkbox',
      '#title' => t('Mousewheel Navigation'),
      '#description' => t('Allow slider navigating via mousewheel.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['mousewheel'],
    );

    /*
    'prevtext' => array('default' => t('Previous')),
    'nexttext' => array('default' => t('Next')),
    */

    // Pause/Play Element
    $form['flexslider_views_slideshow']['pauseplay'] = array(
      '#type' => 'checkbox',
      '#title' => t('Mousewheel Navigation'),
      '#description' => t('Create pause/play dynamic element.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['pauseplay'],
    );

    /*
    'pausetext' => array('default' => t('Pause')),
    'playtext' => array('default' => t('Play')),
    */

    // Randomize
    $form['flexslider_views_slideshow']['randomize'] = array(
      '#type' => 'checkbox',
      '#title' => t('Randomize Slides'),
      '#description' => t('Randomize the playback order of the slides.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['randomize'],
    );


    // 'slidetostart' => array('default' => 0), // integer value, not boolean

    // Loop Animation
    $form['flexslider_views_slideshow']['animationloop'] = array(
      '#type' => 'checkbox',
      '#title' => t('Loop'),
      '#description' => t('Loop the slide animation.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['animationloop'],
    );

    // Pause on Action
    $form['flexslider_views_slideshow']['pauseonaction'] = array(
      '#type' => 'checkbox',
      '#title' => t('Pause on Action'),
      '#description' => t('Pause the slideshow when interacting with control elements, highly recommended.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['pauseonaction'],
    );

    // Pause on Hover
    $form['flexslider_views_slideshow']['pauseonhover'] = array(
      '#type' => 'checkbox',
      '#title' => t('Pause on Hover'),
      '#description' => t('Pause the slideshow when hovering over slider, then resume when no longer hovering.'),
      '#default_value' => $view->options['flexslider_views_slideshow']['pauseonhover'],
    );

    /*
    'controlscontainer' => array('default' => ''),
    'manualcontrols' => array('default' => ''),
    'startcallback' => array('default' => ''),
    'beforecallback' => array('default' => ''),
    'aftercallback' => array('default' => ''),
    'endcallback' => array('default' => ''),
    */


}

function flexslider_views_slideshow_views_slideshow_slideshow_type_form_validate(&$form, &$form_state, &$view) {
  // @todo add validation for any fields in the settings form
}