<?php
/**
 * General settings form
 * @return renderable array
 */
function metatags_quick_admin_settings() {
  $current_settings = variable_get('metatags_quick_settings', _metatags_quick_settings_default());
  $module_path = drupal_get_path('module', 'metatags_quick');
  $fields = field_info_fields();
  $metatags_found = FALSE;

  include_once $module_path . '/known_tags.inc';
  $known_tags = _metatags_quick_known_fields();

  $form['global'] = array(
    '#type' => 'fieldset',
    '#title' => t('Global settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  foreach ($fields as $key => $field) {
    if ($field['module'] != 'metatags_quick') {
      continue;
    }
    $metatags_found = TRUE;
  }
  if (!$metatags_found) {
    $form['global']['basic_init'] = array(
      '#markup' => t('No meta tags found in your installation') . '<br/>',
    );
  }
  
  $form['global']['use_path_based'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use path-based meta tags'),
    '#default_value' => variable_get('metatags_quick_use_path_based', 1),
    '#return_value' => 1,
  );
  $form['global']['remove_tab'] = array(
    '#type' => 'checkbox', 
    '#title' => t('Hide Path-based Metatags tab'), 
    '#default_value' => variable_get('metatags_quick_remove_tab', 0),
  );
  $form['global']['default_field_length'] = array(
    '#type' => 'textfield',
    '#title' => t('Default maximum length'),
    '#description' => t('Default maximum length for the meta tag fields'),
    '#default_value' => variable_get('metatags_quick_default_field_length', 255),
  );
  $form['global']['show_admin_hint'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show entities/bundles hint in admin'),
    '#default_value' => variable_get('metatags_quick_show_admin_hint', 1),
  );

  $form['standard_tags'] = array(
    '#type' => 'fieldset',
    '#title' => t('Create and attach'),
    '#description' => t('The following meta tags are known to the module and can be created automatically. However, you are not limited to this list and can define tags of your own using the Fields UI.'),
    '#collapsible' => TRUE,
    '#attached' => array(
      'js' => array($module_path . '/js/admin.js'),
      'css' => array($module_path . '/css/admin.css'),
    ),
  );
  if (variable_get('metatags_quick_show_admin_hint', 1)) {
    $form['standard_tags']['hint'] = array(
      '#prefix' => '<div class="messages status">',
      '#markup' => t('<strong>Hint</strong>: press on entity type name to edit individual bundles settings (you can hide this hint in global settings).'),
      '#suffix' => '</div>',
    );
  }
  
  $field_instances = field_info_instances();

  // Build the sortable table header.
  $header = array(
    'title' => array('data' => t('Bundle/entity')),
  );
  foreach ($known_tags as $name => $tag) {
    $header[$name] = $tag['title'];
  }
  //$header['_select_all'] = t('Select all');
  
  foreach (field_info_bundles() as $entity_type => $bundles) {
    $entity_info = entity_get_info($entity_type);
    if (!$entity_info['fieldable']) {
      continue;
    }
    $options[$entity_type]['data'] = array(
      'title' => array(
        'class' => array('entity_type_collapsible', 'entity_type_collapsed', "entity_name_$entity_type"),
        'data' => check_plain($entity_info['label']),
      )
    );
    foreach ($known_tags as $name => $tag) {
      $bundle_workable[$name] = FALSE;
      $options[$entity_type]['data'][$name] = array(
        'data' => array(
          '#type' => 'checkbox',
          '#attributes' => array('class' => array('cb_bundle_parent', "cb_bundle_name_{$entity_type}_{$name}")),      
          '#return_value' => 1,
          '#checked' => FALSE,
        ),
      );
    }
    /*$options[$entity_type]['data']['_select_all'] = array(
      'data' => array(
      '#type' => 'checkbox',
      '#return_value' => 1,
      '#checked' => FALSE,
      ),
    );*/
    
    // How do we mark that specific meta is already attached to bundle
    $checked_markup = array(
      '#markup' => theme('image', 
        array(
          'path' => 'misc/watchdog-ok.png',
          'width' => 18,
          'height' => 18,
          'alt' => 'ok',
          'title' => 'ok',
        )),
    );
        
    foreach ($bundles as $key => $bundle) {
      // Which meta tags are set for this bundle?
      $meta_set = array();
      foreach ($field_instances[$entity_type][$key] as $bundle_instance) {
        if ($bundle_instance['widget']['module'] == 'metatags_quick') {
          $field_info = field_info_field_by_id($bundle_instance['field_id']);
          $meta_set[$field_info['settings']['meta_name']] = 1;
        }        
      }

      $options[$entity_type . '_' . $key] = array(
        'class' => array('entity_type_children', "entity_child_$entity_type"),
        'style' => 'display: none',
        'data' => array(
          'title' => array(
            'class' => array('entity_type_child_title'),
            'data' => $bundle['label'],
          ),
        ),
      );
      foreach ($known_tags as $name => $tag) {
        $tag_name = isset($tag['meta_name']) ? $tag['meta_name'] : $name;
        if (empty($meta_set[$tag_name])) {
          // Mark parent bundle as workable - display checkbox.
          $bundle_workable[$name] = TRUE;
          $options[$entity_type . '_' . $key]['data'][$name] = array(
            'data' => array(
              '#name' => $entity_type . '[' . $key . '][' . $name . ']',
              '#type' => 'checkbox',
              '#attributes' => array('class' => array('cb_bundle_child', "cb_child_{$entity_type}_{$name}")),
              '#return_value' => 1,
              '#checked' => FALSE,
            )
          );
        }
        else {
          $options[$entity_type . '_' . $key]['data'][$name]['data'] = $checked_markup; 
        }
      }
      /*$options[$entity_type . '_' . $key]['data']['_select_all']['data'] = array(
        '#type' => 'checkbox',
        '#return_value' => 1,
        '#checked' => FALSE,
      );*/
    }
    // Now check if we have completely set bundles
    foreach ($known_tags as $name => $tag) {
      if (!$bundle_workable[$name]) {
        $options[$entity_type]['data'][$name]['data'] = $checked_markup; 
      }
    }
  }
  
  $form['standard_tags']['existing'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $options,
    '#empty' => t('No content available.'),
  );
  
  $form['standard_tags']['basic_init_op'] = array(
    '#type' => 'submit',
    '#value' => t('Attach'),
  );

  $form['op'] = array(
    '#value' => t('Submit'),
    '#type' => 'submit', 
  );
  return $form;
}

/*
 * Submit handler
 */
function metatags_quick_admin_settings_submit($form, &$form_state) {
  variable_set('metatags_quick_use_path_based', $form_state['values']['use_path_based']);
  variable_set('metatags_quick_remove_tab', $form_state['values']['remove_tab']);
  variable_set('metatags_quick_default_field_length', $form_state['values']['default_field_length']);
  variable_set('metatags_quick_show_admin_hint', $form_state['values']['show_admin_hint']);
  if ($form_state['clicked_button']['#value'] == t('Attach')) {
    _metatags_quick_admin_fields_create_attach($form_state['input']);
  }
  else {
  }
  drupal_set_message(t('Meta tags (quick) settings saved'), 'status');
}

/*
 * Path-based meta tags editing form.
 * @param string $path - path being edited.
 */
function metatags_quick_admin_path($form, &$form_state) {
  $args = func_get_args();
  if (count($args) > 3) {
    $lang = $args[2];
    $path = $args[3];
  }
  else {
    return MENU_ACCESS_DENIED;
  }
  
  $controller = new DrupalDefaultEntityController('metatags_path_based');
  $entity_id = db_select('metatags_quick_path_based', 'm')
    ->fields('m', array('id'))
    ->condition('lang', $lang)
    ->condition('path', $path)
    ->execute()
    ->fetchField();
  if (!$entity_id) {
    $entity_id = db_insert('metatags_quick_path_based')
      ->fields(array('lang' => $lang, 'path' => $path))
      ->execute();
  }
  $entities = $controller->load(array($entity_id));
  $form_state['entity'] = $entities[$entity_id];
  field_attach_form('metatags_path_based', $entities[$entity_id], $form, $form_state);
  // Do we have any fields attached?
  $childen = element_children($form);
  if (!$childen) {
    $form['empty_message'] = array(
      '#markup' => t('No fields attached to the path-based meta tags. Please !define them first in the module settings screen',
        array('!define' => l(t('define'), 'admin/config/search/metatags_quick'))) . '<br/>',
    );
  }
  $form['op'] = array(
    '#value' => t('Submit'),
    '#type' => 'submit', 
  );  
  return $form;
}

function metatags_quick_admin_path_validate($form, &$form_state) {
  entity_form_field_validate('metatags_path_based', $form, $form_state);
}

function metatags_quick_admin_path_submit($form, &$form_state) {
  if (!$form_state['entity']) {
    form_set_error();
    drupal_set_message(t('Wrong path'), 'error');
    return;
  }
  
  $entity = $form_state['entity'];
  entity_form_submit_build_entity('metatags_path_based', $entity, $form, $form_state);
  field_attach_update('metatags_path_based', $entity);
  
  drupal_set_message('Meta tags updated', 'status');
  if(isset($_GET['destination'])) {
    $form_state['redirect'] = $_GET['destination'];
  }
  else {
    $form_state['redirect'] = FALSE;
  }
}

// Create known meta fields.
function _metatags_quick_admin_fields_create_attach($input) {
  foreach (field_info_bundles() as $entity_type => $bundles) {
    $entity_info = entity_get_info($entity_type);
    if (!$entity_info['fieldable']) {
      continue;
    }
    
    foreach ($bundles as $key => $bundle) {
      if (isset($input[$entity_type][$key])) {
        foreach ($input[$entity_type][$key] as $meta_name => $meta_value) {
          _metatags_quick_field_attach($entity_type, $key, $meta_name);
        }
      }
    }
  }
}
  
function _metatags_quick_field_attach($entity_type, $bundle, $meta_name) {
  static $meta_fields = FALSE;
  static $field_id = array();
  static $known_tags = FALSE;
  
  // Get metatags_quick fields info
  if (!$meta_fields) {
    include_once drupal_get_path('module', 'metatags_quick') . '/known_tags.inc';
    $known_tags = _metatags_quick_known_fields();
    
    foreach(field_info_fields() as $name => $field_info) {
      if ($field_info['module'] == 'metatags_quick') {
        $meta_fields[$field_info['settings']['meta_name']] = $field_info;
        $field_id[$field_info['settings']['meta_name']] = $field_info['id'];
      }
    }
  }
  
    // Ignore unknown tags.
  if (!isset($known_tags[$meta_name])) {
    return;
  }
  // Check if meta field exists, create if necessary.
  $meta_name_real = empty($known_tags[$meta_name]['meta_name']) ? $meta_name : $known_tags[$meta_name]['meta_name']; 
  if (empty($field_id[$meta_name_real])) {
    $field = array(
      'field_name' => "meta_$meta_name", 
      'type' => 'metatags_quick', 
      'module' => 'metatags_quick',
      'settings' => array(
        'meta_name' => (isset($known_tags[$meta_name]['meta_name']) ? $known_tags[$meta_name]['meta_name'] : $meta_name),
        'max_length' => variable_get('metatags_quick_default_field_length', 255),
      ), 
      'cardinality' => 1,
    );
    $field = field_create_field($field);
    $field_id[$meta_name] = $field['id'];
    $meta_fields[$meta_name_real] = $field;
  }
  else {
    $field = $meta_fields[$meta_name_real];
  }

  // Do nothing if instance already exists.
  if (isset($field['bundles'][$entity_type])
    && in_array($bundle, $field['bundles'][$entity_type])) {
    return;
  }
  
  // Now create field instance attached to requested bundle
  $instance = array(
    'field_name' => $field['field_name'],
    'entity_type' => $entity_type,
    'bundle' => $bundle,
    'label' => '(Meta)' . $known_tags[$meta_name]['title'],
    'formatter' => 'metatags_quick_default',
    'widget' => array(
      'type' => $known_tags[$meta_name]['widget'],
      'weight' => 0,
    ),
  );
  if (isset($known_tags[$meta_name]['options'])) {
    $instance['settings']['options'] = $known_tags[$meta_name]['options'];
  }
  field_create_instance($instance);
}

/**
 * Display all paths that have defined meta tags
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based($form, $form_state) {
  // get current language and module path
  global $language;
  $module_path = drupal_get_path('module', 'metatags_quick');
  // grab language list
  $languages = _metatags_quick_language_list();
  // create table form
  $form['new_path_based'] = array(
    '#type' => 'fieldset',
    '#title' => t('New path'),
    '#collapsible' => FALSE,
    '#attached' => array(
      'js' => array($module_path . '/js/admin.js'),
      'css' => array($module_path . '/css/admin.css'),
    ),
    'new_path' => array(
      '#type' => 'container',
      '#prefix' => '<div style="float:left">',
      '#suffix' => '</div><div style="clear:both;"> </div>',
      'path' => array(
        '#type' => 'textfield',
        '#title' => t('Path:'),
        '#maxlength' => 255,
        '#description' => t('Enter path starting without a leading "/". "*" can be used as a wildcard at the end of a path.'),
        '#prefix' => '<div style="float:left;">',
        '#suffix' => '</div>',
      ),
      'language' => array(
        '#type' => 'select',
        '#title' => t('Select language'),
        '#options' => $languages,
        '#default_value' => $language->language,
        '#prefix' => '<div style="float:left; margin-left: 40px;">',
        '#suffix' => '</div><div style="clear:both;"> </div>',
      ),
      'new_path_submit' => array(
        '#type' => 'submit',
        '#value' => t('Create'),
        '#attributes' => array('style' => 'margin-bottom: 20px;')
      )
    )
  );
  $form['path_based'] = array(
    '#type' => 'fieldset',
    '#title' => t('Manage Paths'),
    '#collapsible' => FALSE
  );
  $header = array(
    'path' => array('data' => t('Path'), 'field' => 'p.path'),
    'language' => array('data' => t('Language'), 'field' => 'p.lang', 'width' => '10%'),
    'operations' => array('data' => t('Operations'), 'width' => '30%', 'colspan' => 2),
  );
  $query = db_select('metatags_quick_path_based', 'p')->extend('PagerDefault')->extend('TableSort');
  $result = $query
   ->fields('p')
   ->limit(50)
   ->orderByHeader($header)
   ->execute();
  // set up to fill options array
  $destination = drupal_get_destination();
  $options = array();
  // fill options array
  foreach ($result as $path) {
    $options[] = array(
      'path' => $path->path,
      'language' =>  $languages[$path->lang],
      'edit' =>
        l(t('Edit'), 'admin/config/search/metatags_quick/path_based/edit',
          array('query' => array('pid' => $path->id, 'destination' => $destination['destination']))),
      'delete' =>
        l(t('Delete'), 'admin/config/search/metatags_quick/path_based/delete',
          array('query' => array('pid' => $path->id, 'destination' => $destination['destination']))),
    );
  }
  // add created table
  $form['path_based']['existing'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $options,
    '#empty' => t('No content available.'),
    '#attributes' => array('id' => 'path_based'),
  );
  // add default page
  $form['path_based']['pager'] = array('#markup' => theme('pager', array('tags' => NULL)));
  // return listing form
  return $form;
}

/**
 * Validates the existance of a valid path provided by the user. Path has to be
 * at least 2 characters long.
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_validate($form, $form_state) {
  if(isset($form_state['values']['path'])) {
    // create path and empty entity
    if(strlen(trim($form_state['values']['path'])) < 2) {
        form_set_error('path', t('Illegal path entered.'));
    }
  }
}

/**
 * Handles the creation of a new path by redirecting to
 * metatags_quick_admin_path_based_edit() which autocreates new
 * entities if they don't exist yet for a given path and language.
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_submit($form, &$form_state) {
  if (isset($form_state['values']['path'])) {
    // redirect to existing form
    $destination = drupal_get_destination();
    $form_state['redirect'] = array(
      'admin/config/search/metatags_quick/path_based/edit',
      array(
       'query' => array(
         'lang' => check_plain($form_state['values']['language']),
         'path' => check_plain($form_state['values']['path']),
         'destination' => $destination['destination'])
       )
    );
  }
}

/**
 * Displays a form to the user allowing him to edit the path and all the fields
 * of the metatag entity associated with that path
 *
 * @param array $form
 * @param array $form_state
 */
function metatags_quick_admin_path_based_edit($form, &$form_state) {
  $controller = new DrupalDefaultEntityController('metatags_path_based');
  if (isset($_GET['pid'])) {
    $entity_id = (int)$_GET['pid'];
  }
  else {
    $entity_id = db_select('metatags_quick_path_based', 'm')
      ->fields('m', array('id'))
      ->condition('lang', check_plain($_GET['lang']))
      ->condition('path', check_plain($_GET['path']))
      ->execute()
      ->fetchField();
  }
  if (!$entity_id) {
    $entity_id = db_insert('metatags_quick_path_based')
      ->fields(array('lang' => check_plain($_GET['lang']), 'path' => check_plain($_GET['path'])))
      ->execute();
    $entity = new stdClass();
    $entity->id = $entity_id;
    $entity->lang = check_plain($_GET['lang']);
    $entity->path = check_plain($_GET['path']);
  }
  else {
    $entities = $controller->load(array($entity_id));
    $entity = $entities[$entity_id];
  }
  
  $form_state['entity'] = $entity;
  field_attach_form('metatags_path_based', $entity, $form, $form_state);
  //
  $form['entity_id'] = array(
    '#type' => 'hidden',
    '#value' => $entity_id,
  );  
  $form['path'] = array(
    '#type' => 'textfield',
    '#title' => t('Edit path'),
    '#description' => t('Enter path starting without a leading "/". "*" can be used as a wildcard at the end of a path.'),
    '#default_value' => html_entity_decode($entity->path),
    '#maxlength' => 255,
    '#weight' => '-50',
  );
  $form['lang'] = array(
    '#title' => t('Edit language'),
    '#type' => 'select',
    '#options' => _metatags_quick_language_list(),
    '#default_value' => $entity->lang,
    '#weight' => '-40',
  );
  //
  $form['submit'] = array(
    '#value' => t('Submit'),
    '#type' => 'submit',
    '#weight' => '49'
  );
  $form['cancel'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
    '#weight' => 20,
    '#submit' => array('metatags_quick_admin_path_based_edit_cancel'),
    '#weight' => '50'
  );
  return $form;
}

/**
 * Returns back to the metatags listing
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_edit_cancel($form, &$form_state) {
  if(isset($_GET['destination'])) {
    $form_state['redirect'] = $_GET['destination'];
  }
}

/**
 * Checks if the user has submitted a valid path. Path must be at least 2
 * characters long
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_edit_validate($form, &$form_state) {  
  if(isset($form_state['values']['path'])) {
    // Create path and empty entity
    if(strlen(trim($form_state['values']['path'])) < 2) {
      form_set_error('path', t('Illegal path entered.'));
    }
  }
  field_attach_form_validate('metatags_path_based', $form_state['entity'], $form, $form_state);
}

/**
 * Updates the metatags entity for a given path after the user has submitted the
 * form. Also updates the path if it has been altered.
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_edit_submit($form, &$form_state) {
  // abort if there is no valid entity
  if (!$form_state['entity']) {
    form_set_error();
    drupal_set_message(t('Wrong path'), 'error');
    return;
  }
  // Update path if it has been altered
  if ($form_state['values']['path'] != html_entity_decode($form_state['entity']->path)
    || $form_state['values']['lang'] != $form_state['entity']->lang) {
    // check if there isn't already a path like the new path
    $entity_id = db_select('metatags_quick_path_based', 'm')
      ->fields('m', array('id'))
      ->condition('lang', check_plain($form_state['values']['lang']))
      ->condition('path', check_plain($form_state['values']['path']))
      ->execute()
      ->fetchField();
    // if not continue
    if(!$entity_id) {
      db_update('metatags_quick_path_based')
        ->fields(array(
          'path' => check_plain(trim($form_state['values']['path'])),
          'lang' => $form_state['values']['lang'],
      ))
      ->condition('id', $form_state['entity']->id)
      ->execute();
    }
    else {
      // Otherwise abort with error message
      form_set_error('path', t('Another set of meta tags exists for this path and language'));
      return;
    }
  }
  // update entity fields
  $entity = $form_state['entity'];
  entity_form_submit_build_entity('metatags_path_based', $entity, $form, $form_state);
  field_attach_update('metatags_path_based', $entity);
  // display message and redirect
  drupal_set_message('Meta tags updated', 'status');
  if(isset($_GET['destination'])) {
    $form_state['redirect'] = $_GET['destination'];
  }
  else {
    $form_state['redirect'] = FALSE;
  }
}

/**
 * Display a delete confirmation for the user allowing him to choose to proceed
 * or abort the removal.
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_delete($form, &$form_state) {
  // Delete confirmation
  if(isset($_GET['pid'])) {
    $pid = (int)$_GET['pid'];
  	$controller = new DrupalDefaultEntityController('metatags_path_based');
  	$entities = $controller->load(array($pid));
  	
  	if (empty($entities)) {
      $form['intro'] = array(
        '#markup' => t('Path not found'),
      );
  	  return $form;  	  	
  	}
  	
  	$path_object = $entities[$pid];
  	$form_state['#path_object'] = $path_object;
  	 
    $form['intro'] = array(
      '#type' => 'item',
      '#markup' => t('Do you really want to delete the following path:'),
    );
    
    $form['item'] = array(
      '#type' => 'item',
      '#markup' => htmlspecialchars_decode($path_object->path) . '(' . $path_object->lang . ')',
      '#prefix' => '<ul><li>',
      '#suffix' => '</ul></li>'
    );
    $form['note'] = array(
      '#type' => 'item',
      '#markup' => t('This action cannot be undone.'),
    );
    $form['actions'] = array(
      '#type' => 'actions',
      'delete' => array(
        '#type' => 'submit',
        '#value' => t('Delete'),
        '#submit' => array('metatags_quick_admin_path_based_delete_submit')),
      'cancel' => array(
        '#type' => 'submit',
        '#value' => t('Cancel'),
        '#limit_validation_errors' => array(),
        '#submit' => array('metatags_quick_admin_path_based_delete_cancel')));
    return $form;
  }
}

/**
 * Deletion has been confirmed. Item will be deleted if it is a valid one.
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_delete_submit($form, &$form_state) {
  metatags_path_based_delete($form_state['#path_object']->id);
}

/**
 * Cancels the deletion and should return to the listing
 *
 * @param type $form
 * @param type $form_state
 */
function metatags_quick_admin_path_based_delete_cancel($form, &$form_state) {
  if(isset($_GET['destination'])) {
    $form_state['redirect'] = $_GET['destination'];
  }
}

/**
 * creates a select list compatible list of languages keyed by language tags and
 * with localized language values.
 *
 * @global type $language
 * @return array $languages
 */
function _metatags_quick_language_list() {
  global $language;
  $languages = array();
  foreach(language_list() as $lang) {
    $languages[$lang->language] = t($lang->name);
  }
  return $languages;
}

/**
 * Delete path-based meta tags
 * @param object $entity
 */
function metatags_path_based_delete($entity) {
  if(!is_object($entity)) {
    $controller = new DrupalDefaultEntityController('metatags_path_based');
    $entity = db_select('metatags_quick_path_based', 'm')
      ->fields('m', array('id'))
      ->condition('id', (int)$entity)
      ->execute()
      ->fetchField();
    if($entity) {
      $values = array_values(entity_load('metatags_path_based', array($entity))); 
      $entity = array_pop($values);
    }
  }
  if(!$entity || !is_object($entity) || !isset($entity->path)) {
    return false;
  }
  // do the deletion
  field_attach_delete('metatags_path_based', $entity);
  db_delete('metatags_quick_path_based')
    ->condition('id', $entity->id)
    ->execute();
  drupal_set_message(t('Deleted path-based meta tags for @path', array('@path' => $entity->path)), 'status');
}
