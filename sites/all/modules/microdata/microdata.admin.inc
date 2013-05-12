<?php

/**
 * @file
 * Microdata administration and module settings UI.
 */

/**
 * Form builder; The microdata vocabulary settings form.
 */
function microdata_vocabulary_settings() {
  $options = array();
  $enabled_vocabs = microdata_get_enabled_vocabularies();

  foreach (microdata_get_vocabulary_info() as $vocab_name => $vocab) {
    $label = $vocab['label'];
    $description = isset($vocab['description']) ? $vocab['description'] : '';

    $form[$vocab_name] = array(
      '#type' => 'checkbox',
      '#title' => $label,
      '#description' => $description,
      '#default_value' => isset($enabled_vocabs[$vocab_name]) ? TRUE : FALSE,
    );
  }

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save configuration'),
  );

  return $form;
}

/**
 * Submit callback; Caches microdata vocabularies.
 */
function microdata_vocabulary_settings_submit($form, &$form_state) {
  $enabled_vocabs = array();
  $vocabulary_info = microdata_get_vocabulary_info();
  $vocabs = array_keys($vocabulary_info);

  foreach ($vocabs as $vocab_name) {
    if ($form_state['values'][$vocab_name]) {
      $enabled_vocabs[$vocab_name] = $vocab_name;
      $cache = cache_get($vocab_name, 'cache_microdata_vocabulary');
      // @todo Add expires.
      if (empty($cache)) {
        // @todo Move this to new function microdata_update_vocabulary_schema.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $vocabulary_info[$vocab_name]['import_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $schema = json_decode($data);
        cache_set($vocab_name, $schema, 'cache_microdata_vocabulary');
      }
    }
  }

  variable_set('microdata_enabled_vocabularies', $enabled_vocabs);
}

/**
 * A modal callback for bundle mapping.
 */
function microdata_ajax_bundle_mapping($entity_type, $bundle_name, $js = NULL) {
  // Fall back if $js is not set.
  if (!$js) {
    return drupal_get_form('microdata_bundle_mapping_form');
  }

  ctools_include('modal');
  ctools_include('ajax');
  $form_state = array(
    'title' => t('Login'),
    'ajax' => TRUE,
    '#entity_type' => $entity_type,
    '#bundle_type' => $bundle_name,
  );
  $output = ctools_modal_form_wrapper('microdata_bundle_mapping_form', $form_state);
  if (!empty($form_state['executed'])) {
    // We'll just overwrite the form output if it was successful.
    $output = array();
    $inplace = ctools_ajax_text_button(t('remain here'), 'ctools_ajax_sample/nojs/login/inplace', t('Go to your account'));
    $output[] = ctools_modal_command_display(t('Login Success'), '<div class="modal-message">Mapping saved. You can now choose whether to ' . $inplace . '.</div>');
  }
  print ajax_render($output);
  exit;
}

/**
 * Form builder helper function.
 *
 * Creates the full bundle mapping form, including fields, for Microdata's
 * custom UI.
 */
function microdata_bundle_mapping_form($form, &$form_state) {
  $entity_type = $form_state['#entity_type'];
  $bundle_type = $form_state['#bundle_type'];
  $mapping = microdata_get_mapping($entity_type, $bundle_type);
  $form['microdata'] = array(
    '#type'   => 'container',
    '#tree' => TRUE,
  );
  $form['microdata'][$entity_type] = microdata_get_bundle_type_mapping_form($mapping, $entity_type, $bundle_type);

  foreach (field_info_instances($entity_type, $bundle_type) as $field_name => $instance) {
    $form['microdata']['fields'][$field_name] = microdata_get_instance_mapping_form($field_name, $instance);
    $form['microdata']['fields'][$field_name]['#type'] = 'fieldset';
    $form['microdata']['fields'][$field_name]['#title'] = check_plain($instance['label']);
  }

  // Add submit.
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 5,
    '#submit' => array('microdata_bundle_type_mapping_form_submit', 'microdata_form_field_ui_field_edit_form_submit'),
  );

  return $form;
}

/**
 * Form builder helper function.
 *
 * Creates the bundle mapping form to be added to forms like node_type. This is
 * also used by microdata_bundle_mapping_form to add the bundle type settings
 * to Microdata's custom UI.
 */
function microdata_get_bundle_type_mapping_form($mapping, $entity_type, $bundle_type) {
  $form_element['itemtype'] = _microdata_get_form_elements('itemtype', '', $mapping);

  $form_element['is_item'] = array(
    '#type' => 'checkbox',
    '#title' => t('Handle as an item in microdata'),
    '#default_value' => isset($mapping['#is_item']) ? $mapping['#is_item'] : $mapping['#value_type'] == 'item',
  );
  $form_element['itemid_settings'] = array(
    '#type' => 'container',
    '#states' => array(
      'visible' => array(
        ':input[name="microdata[' . $entity_type . '][is_item]"]' => array('checked' => TRUE),
      ),
    ),
  );
  // Get the default itemid token for known entity types.
  switch ($entity_type) {
    case 'node':
      $default_token = '[node:url]';
      break;

    case 'taxonomy_term':
      $default_token = '[term:url]';
      break;

    case 'user':
      $default_token = '[user:url]';
      break;

    case 'taxonomy_vocabulary':
      $default_token = '[comment:url]';
      break;
  }
  $form_element['itemid_settings']['itemid_token'] = array(
    '#type' => 'textfield',
    '#title' => t('Token to use for itemid'),
    '#default_value' => isset($mapping['#itemid_token']) ? $mapping['#itemid_token'] : $default_token,
  );

  $form_element['title_itemprop'] = _microdata_get_form_elements('itemprop', 'title', $mapping, t('Itemprop(s) for title field'));
  $form_element['bundle_type'] = array(
    '#type' => 'hidden',
    '#default_value' => $bundle_type,
  );
  if ($entity_type == 'taxonomy_term') {
    // Additional options that are unique to terms.
    $form_element['description_itemprop'] = _microdata_get_form_elements('itemprop', 'description', $mapping, t('Property for term description'));
    $form_element['url_itemprop'] = _microdata_get_form_elements('itemprop', 'url', $mapping, t("Property for term's url on this site (i.e. http://example.com/taxonomy/term/1)"));
  }

  return $form_element;
}

/**
 * Form builder helper function.
 *
 * Creates the field instance mapping form to be added to Field UI forms. This
 * is also used by microdata_bundle_mapping_form to add the instance's settings
 * to the bundle in Microdata's custom UI.
 */
function microdata_get_instance_mapping_form($field_name, $instance) {
  $label = isset($instance['label']) ? $instance['label']['#default_value'] : $instance['field_name'];
  $entity_type = is_array($instance['entity_type']) ? $instance['entity_type']['#value'] : $instance['entity_type'];
  $bundle = is_array($instance['bundle']) ? $instance['bundle']['#value'] : $instance['bundle'];
  $entity_info = entity_get_info($entity_type);
  $bundle_label = $entity_info['bundles'][$bundle]['label'];

  $mapping = microdata_get_mapping($entity_type, $bundle);

  $form_element = array();

  // Only display the config options if there is a recognized value_type for
  // this field.
  if (isset($mapping[$field_name]['#value_type'])) {
    $form_element['field']['itemprop'] = _microdata_get_form_elements('itemprop', $field_name, $mapping);

    if ($mapping[$field_name]['#value_type'] === 'item_option') {
      $form_element['field']['is_item'] = _microdata_get_form_elements('is_item', $field_name, $mapping[$field_name]);
      $form_element['field']['item_fieldset'] = _microdata_get_form_elements('item_fieldset', $field_name, $mapping);
      $form_element['field']['item_fieldset']['#states'] = array(
        'visible' => array(
          ':input[name="microdata[fields][' . $field_name . '][field][is_item]"]' => array('checked' => TRUE),
        ),
      );
      $form_element['field']['item_fieldset']['itemtype'] = _microdata_get_form_elements('itemtype', $field_name, $mapping);
    }
  }

  // If the field declares properties, then add form fields for subfield
  // mappings.
  $subfields = _microdata_get_field_properties($entity_type, $bundle, $field_name);
  if (!empty($subfields)) {
    $form_element['subfields'] = array(
      '#type' => 'fieldset',
      '#title' => t('Properties'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
    );

    $mapping = $mapping[$field_name];
    // Iterate through subfields and add the same form fields for each one.
    foreach ($subfields as $subfield_name => $subfield) {
      $form_element['subfields'][$subfield_name] = array();
      $form_state['input']['microdata']['subfields'][$subfield_name] = array();
      $subfield_form = &$form_element['subfields'][$subfield_name];
      $subfield_input = &$form_state['input']['microdata']['subfields'][$subfield_name];

      $label = $subfield['label'];

      $subfield_form['itemprop'] = _microdata_get_form_elements('itemprop', $subfield_name, $mapping, $label);
      $subfield_input['itemprop'] = $mapping['#itemprop'];

      if ($mapping[$subfield_name]['#value_type'] == 'item') {
        $subfield_form['item_fieldset'] = _microdata_get_form_elements('item_fieldset', $field_name, $mapping);

        $subfield_form['item_fieldset']['itemtype'] = _microdata_get_form_elements('itemtype', $subfield_name, $mapping, $label);
        $subfield_input['item_fieldset']['itemtype'] = $mapping['#itemtype'];
      }
    }
  }

  return $form_element;
}

/**
 * Callback function for viewing all bundles' RDF mappings.
 */
function microdata_mapping_overview() {
  // Include the CTools tools that we need.
  ctools_include('ajax');
  ctools_include('modal');

  // Add CTools' javascript to the page.
  ctools_modal_add_js();

  $render = array();
  $entities = entity_get_info();
  $fields = field_info_instances();
  $render['tabs'] = array(
    '#type' => 'vertical_tabs',
  );

  // Create a tab for each entity.
  foreach ($entities as $entity_type => $entity) {
    $render['tabs'][$entity_type] = array(
      '#type' => 'fieldset',
      '#title' => check_plain($entity['label']),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );

    foreach ($entity['bundles'] as $bundle_name => $bundle) {
      $rows = array();
      $field_mappings = array();
      $itemtype = array();
      $bundle_fields = $fields[$entity_type][$bundle_name];

      $microdata_mapping = microdata_get_mapping($entity_type, $bundle_name);

      // Set itemtype.
      if (isset($microdata_mapping['#itemtype'])) {
        $itemtype = $microdata_mapping['#itemtype'];
      }

      foreach ($bundle_fields as $field_name => $field) {
        $field_mappings[$field_name]['microdata_mapping'] = isset($microdata_mapping[$field_name]) ? $microdata_mapping[$field_name] : '';
        $field_mappings[$field_name]['label'] = $fields[$entity_type][$bundle_name][$field_name]['label'];
      }

      // Theme this bundle's table and add it to it's parent entity's tab.
      $variables = array(
        'entity_type' => $entity_type,
        'bundle_type' => $bundle_name,
        'bundle' => $bundle,
        'itemtype' => $itemtype,
        'field_mappings' => $field_mappings,
      );
      $render['tabs'][$entity_type][$bundle_name][] = theme('microdata_mapping_admin', $variables);
      $render['tabs'][$entity_type][$bundle_name]['edit'] = array(
        '#type' => 'markup',
        '#markup' => ctools_modal_text_button(t('edit'), "admin/config/services/microdata/mappings/manage/$entity_type/$bundle_name/nojs", t('Login via modal')),
      );
    }
  }

  return $render;
}

/**
 * Theme function to output the table for a bundle's mappings.
 */
function theme_microdata_mapping_admin($variables) {
  $bundle_type = $variables['bundle_type'];
  $entity_type = $variables['entity_type'];
  $field_mappings = $variables['field_mappings'];
  $bundle_label = check_plain($variables['bundle']['label']);

  $itemtypes = array();
  foreach ($variables['itemtype'] as $itemtype) {
    $itemtypes[] = l($itemtype, $itemtype);
  }
  $itemtype = !empty($itemtypes) ? implode(', ', $itemtypes) : '&mdash;';
  $property_info = entity_get_property_info($entity_type);
  $rows = array();

  // Add the table header for this bundle.
  $header = array(t('Fields'), t('Property Name(s)'), t('Itemtype(s)'));

  // Display a title for each bundle above the mappings table.
  $title = "<h3>$bundle_label</h3>";
  $title .= "<p>" . t('Item Type:') . ' ' . $itemtype . '</p>';

  $fields = field_info_instances($entity_type, $bundle_type);
  foreach ($fields as $field_name => $field) {
    $field_mapping = $field_mappings[$field_name]['microdata_mapping'];
    $field_info = field_info_field($field_name);
    if (microdata_enabled(field_info_field_types($field_info['type']))) {
      $rows[] = array(
        'data' => theme('microdata_mapping_admin_overview_row', array('field' => $field, 'microdata_mapping' => $field_mapping)),
      );
    }
    else {
      // @TODO if the field is not microdata enabled, should it still display
      // with a message?
    }
    if (isset($property_info['bundles'][$bundle_type]['properties'][$field_name]['property info'])) {
      $properties = $property_info['bundles'][$bundle_type]['properties'][$field_name]['property info'];
      $md_properties = array_filter($properties, 'microdata_enabled');
      foreach ($md_properties as $property_name => $property) {
        $property['label'] = $field['label'] . ' - ' . $property['label'];
        $rows[] = array(
          'data' => theme('microdata_mapping_admin_overview_row', array('field' => $property, 'microdata_mapping' => $field_mapping[$property_name])),
        );
      }
    }
  }

  // If there are no properties, display a message inside the table.
  if (!count($rows)) {
    $rows[] = array(
      'data' => array(array('data' => t('No fields or properties have been configured for this bundle.'), 'colspan' => 5)),
    );
  }

  // Return the table for this bundle.
  return array(
    '#prefix' => $title,
    '#theme' => 'table',
    '#rows' => $rows,
    '#header' => $header,
  );
}

/**
 * Theme function to output a field's row in the bundle mapping table.
 */
function theme_microdata_mapping_admin_overview_row($variables) {
  $field = $variables['field'];
  $mapping = $variables['microdata_mapping'];
  $field_label = '<strong>' . t($field['label']) . '</strong>';
  if ($mapping['#value_type'] == 'item') {
    if (isset($mapping['#entity_type'])) {
      $itemtype = '<em>see ' . check_plain($mapping['#entity_type']) . ' mapping</em>';
    }
    elseif (isset($mapping['#itemtype'])) {
      $itemtype = implode(', ', $mapping['#itemtype']);
    }
  }
  else {
    $itemtype = '-';
  }

  $itemprop = isset($mapping['#itemprop']) ? implode(', ', $mapping['#itemprop']) : '';

  $row = array($field_label, check_plain($itemprop), check_plain($itemtype));

  return $row;
}
