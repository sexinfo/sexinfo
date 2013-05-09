<?php
/**
 * @file
 * Provides inline structured data using Microdata format.
 */

/**
 * Implements hook_form_FORM_ID_alter().
 */
function microdata_form_node_type_form_alter(&$form, &$form_state) {
  module_load_include('inc', 'microdata', 'microdata.admin');

  $entity_type = 'node';
  $bundle_type = $form['#node_type']->type;

  $mapping = microdata_get_mapping($entity_type, $bundle_type);

  // Create the vertical tab for microdata settings.
  $form['microdata'] = array(
    '#type'   => 'fieldset',
    '#title'  => t('Microdata settings'),
    '#collapsible' => TRUE,
    '#group' => 'additional_settings',
    '#tree' => TRUE,
  );
  $form['microdata'][$entity_type] = array(
    '#type'   => 'container',
  );

  // Add the bundle mapping fields into the vertical tab.
  $form['microdata'][$entity_type] = array_merge($form['microdata'][$entity_type], microdata_get_bundle_type_mapping_form($mapping, $entity_type, $bundle_type));

  // Add the submit handler.
  $form['#submit'] = array_merge($form['#submit'], array('microdata_bundle_type_mapping_form_submit'));
}

/**
 * Implements hook_form_FORMID_alter().
 *
 * Adds microdata options to vocabulary form.
 *
 * @see taxonomy_form_vocabulary()
 */
function microdata_form_taxonomy_form_vocabulary_alter(&$form, $form_state, $edit = array()) {
  module_load_include('inc', 'microdata', 'microdata.admin');

  $form['microdata'] = array(
    '#type'   => 'fieldset',
    '#title'  => t('Term microdata settings'),
    '#collapsible' => TRUE,
    '#group' => 'additional_settings',
    '#tree'   => TRUE,
  );

  $entity_type = 'taxonomy_term';
  $bundle_type = $form['#vocabulary']->machine_name;
  $mapping = microdata_get_mapping($entity_type, $bundle_type);
  $form['microdata'][$entity_type] = array(
    '#type'   => 'container',
  );
  // Add the bundle mapping fields into the vertical tab.
  $form['microdata'][$entity_type] = array_merge($form['microdata'][$entity_type], microdata_get_bundle_type_mapping_form($mapping, $entity_type, $bundle_type));

  // Add the submit handler.
  $form['#submit'] = array_merge($form['#submit'], array('microdata_bundle_type_mapping_form_submit'));
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function microdata_form_field_ui_field_edit_form_alter(&$form, &$form_state) {
  module_load_include('inc', 'microdata', 'microdata.admin');

  // @todo Fix this?
  // If the form is set to rebuild, it skips form_execute_handlers at line 829
  // in form.inc.
  if (!empty($form_state['process_input'])) {
    form_execute_handlers('submit', $form, $form_state);
  }

  $field_name = $form['#field']['field_name'];
  $instance = $form['instance'];
  $label = isset($instance['label']) ? $instance['label']['#default_value'] : $instance['field_name'];
  $entity_type = $instance['entity_type']['#value'];
  $entity_info = entity_get_info($entity_type);
  $bundle = $instance['bundle']['#value'];
  $bundle_label = $entity_info['bundles'][$bundle]['label'];
  $mapping = microdata_get_mapping($entity_type, $bundle);
  $module = $form['#field']['module'];

  // Show the itemtype of the entity.
  if (!empty($mapping['#itemtype'])) {
    foreach ($mapping['#itemtype'] as $itemtype) {
      $itemtypes[] = l($itemtype, $itemtype);
    }
    $itemtype_msg = t('@label uses the itemtype !itemtype.', array('@label' => $bundle_label, '!itemtype' => implode(', ', $itemtypes)));
  }
  else {
    $edit_path = $entity_info['bundles'][$bundle]['admin']['real path'];
    $itemtype_msg = t('No itemtype has been defined for %bundle. Define one on the !edit.', array('%bundle' => $bundle_label, '!edit' => l(check_plain($bundle_label) . ' edit page', $edit_path)));
  }

  // Create the fieldset tab.
  $form['microdata'] = array(
    '#type'   => 'fieldset',
    '#title'  => t('@label Microdata Mapping', array('@label' => $label)),
    '#description' => $itemtype_msg,
    '#prefix' => '<div id="microdata-fieldset-wrapper">',
    '#suffix' => '</div>',
    '#tree'   => TRUE,
  );
  // Add the mapping form fields for the field. If 'Use suggested mapping' was
  // clicked, the input values will be populated with the default mapping
  // values.
  $form['microdata']['fields'][$field_name]['#type'] = 'container';
  $form['microdata']['fields'][$field_name] = array_merge($form['microdata']['fields'][$field_name], microdata_get_instance_mapping_form($field_name, $instance));
  $form_state['input']['microdata']['fields'][$field_name]['field'] = array();

  // Get the suggested mapping from $form_state if it is there.
  if (empty($form_state['field_mapping'])) {
    $field_mapping = $mapping[$field_name];
  }
  else {
    // Merge in the original mapping, which has the value types for the field
    // and properties.
    $field_mapping = array_merge($mapping[$field_name], $form_state['field_mapping']);
  }

  // Add the suggested mapping for the field.
  $main_field_input = &$form_state['input']['microdata']['fields'][$field_name]['field'];
  $main_field_input['itemprop'] = $field_mapping['#itemprop'];
  $main_field_input['is_item'] = isset($field_mapping['#is_item']) ? $field_mapping['#is_item'] : NULL;
  $main_field_input['item_fieldset']['itemtype'] = $field_mapping['#itemtype'];

  // Add the suggested mappings for the properties.
  foreach (_microdata_get_field_properties($entity_type, $bundle, $field_name) as $subfield_name => $subfield) {
    $form_state['input']['microdata']['fields'][$field_name]['subfields'][$subfield_name] = array();
    $subfield_mapping = $field_mapping[$subfield_name];
    $subfield_input = &$form_state['input']['microdata']['fields'][$field_name]['subfields'][$subfield_name];
    $subfield_input['itemprop'] = $subfield_mapping['#itemprop'];
    $subfield_input['item_fieldset']['itemtype'] = $subfield_mapping['#itemtype'];
  }

  // Add the button for suggested mappings.
  $suggestions = module_invoke($module, 'microdata_suggestions');
  drupal_alter('microdata_suggestions', $suggestions);
  if (!empty($suggestions)) {
    $form_state['suggested_mappings'] = $suggestions['fields'][$form['#field']['type']];
    $defined_mappings = array_keys($form_state['suggested_mappings']);

    $form['suggested_mapping'] = array(
      '#type' => 'radios',
      '#title' => t('Suggested Microdata Mappings'),
      '#options' => array_combine($defined_mappings, $defined_mappings),
    );
    $form['suggested_mapping']['get_suggested_mapping'] = array(
      '#type'     => 'submit',
      '#value'    => t('Use suggested mapping'),
      '#executes_submit_callback' => TRUE,
      '#submit'   => array('microdata_get_default_field_mapping'),
      // See the examples in ajax_example.module for more details on the
      // properties of #ajax.
      '#ajax'     => array(
        'callback'  => 'microdata_get_default_field_mapping_callback',
        'wrapper'   => 'microdata-fieldset-wrapper',
      ),
      '#weight' => 10,
    );
  }

  $form['submit']['#weight'] = 1;

  // Add submit and validate handlers.
  $form['#validate'] = array_merge($form['#validate'], array('microdata_form_field_ui_field_edit_form_validate'));
  $form['#submit'] = array_merge($form['#submit'], array('microdata_form_field_ui_field_edit_form_submit'));

}

/**
 * Validation callback.
 */
function microdata_form_field_ui_field_edit_form_validate($form, &$form_state) {
  // @todo What needs to be validated?
}

/**
 * Submit callback; saves the bundle-type specific mapping properties.
 */
function microdata_bundle_type_mapping_form_submit($form, &$form_state) {
  $values = $form_state['values']['microdata'];
  unset($values['fields']);
  foreach ($values as $entity_type => $fieldset) {
    $bundle_type = $fieldset['bundle_type'];
    // @todo Remove this type based checking.
    if (empty($bundle_type)) {
      switch ($entity_type) {
        case 'node':
          $bundle_type = $form_state['values']['type'];
          break;

        case 'taxonomy_vocabulary':
          $bundle_type = 'taxonomy_vocabulary';
          break;

        case 'taxonomy_term':
          $bundle_type = $form_state['values']['machine_name'];
          break;
      }
    }

    $mapping = _microdata_load_mapping($entity_type, $bundle_type);

    $mapping['#itemtype'] = drupal_explode_tags($fieldset['itemtype']);
    $mapping['title']['#itemprop'] = drupal_explode_tags($fieldset['title_itemprop']);

    if ($fieldset['is_item'] == TRUE) {
      $mapping['#is_item'] = TRUE;
      $mapping['#itemid_token'] = $fieldset['itemid_settings']['itemid_token'];
    }
    else {
      $mapping['#is_item'] = FALSE;
    }

    if ($entity_type == 'taxonomy_term') {
      $mapping['description']['#itemprop'] = $fieldset['description_itemprop'];
      $mapping['url']['#itemprop'] = $fieldset['url_itemprop'];
    }

    microdata_save_mapping($entity_type, $bundle_type, $mapping);
  }
}

/**
 * Submit callback; saves field mapping in both UIs.
 */
function microdata_form_field_ui_field_edit_form_submit($form, &$form_state) {
  $entity_type = isset($form_state['#entity_type']) ? $form_state['#entity_type'] : $form['instance']['entity_type']['#value'];
  $bundle = isset($form_state['#bundle_type']) ? $form_state['#bundle_type'] : $form['instance']['bundle']['#value'];
  $values = $form_state['values'];

  $mapping = _microdata_load_mapping($entity_type, $bundle);
  foreach ($values['microdata']['fields'] as $field_name => $field_values) {
    $subfields = _microdata_get_field_properties($entity_type, $bundle, $field_name);
    // If the field itself is microdata enabled, add the mapping for the field.
    if (isset($field_values['field'])) {
      $mapping[$field_name] = array(
        '#itemprop' => drupal_explode_tags($field_values['field']['itemprop']),
      );

      // If the field should be handled as an item, add the is_item flag to the
      // field's mapping and save the itemtype.
      // @todo Consider adding the itemid.
      if (!empty($field_values['field']['is_item'])) {
        $mapping[$field_name]['#is_item'] = TRUE;

        if (isset($field_values['field']['item_fieldset']['itemtype'])) {
          $mapping[$field_name]['#itemtype'] = drupal_explode_tags($field_values['field']['item_fieldset']['itemtype']);
        }
        else {
          $mapping[$field_name]['#itemtype'] = array();
        }
      }
      else {
        $mapping[$field_name]['#is_item'] = FALSE;
      }
    }

    // Add the mappings for field properties.
    foreach ($subfields as $subfield_name => $subfield) {
      $element = array(
        '#itemprop' => drupal_explode_tags($field_values['subfields'][$subfield_name]['itemprop']),
      );

      // If the subfield contains an item, save an itemtype scoped to this item.
      // @todo Consider whether a subfield should ever be able to save an item.
      if (isset($field_values[$subfield_name]['item_fieldset']['itemtype'])) {
        $element['#itemtype'] = $field_values['subfields'][$subfield_name]['item_fieldset']['itemtype'];
      }
      else {
        $element['#itemtype'] = '';
      }
      $mapping[$field_name][$subfield_name] = $element;
    }
  }

  microdata_save_mapping($entity_type, $bundle, $mapping);
}

/**
 * Submit handler for the "get-default-mappings" button.
 *
 * It just increments the max counter and causes a rebuild.
 */
function microdata_get_default_field_mapping($form, &$form_state) {
  $selected = $form_state['values']['suggested_mapping'];
  $form_state['field_mapping'] = $form_state['suggested_mappings'][$selected];

  $form_state['rebuild'] = TRUE;
}

/**
 * Callback for "get-default-mappings"  button.
 *
 * This simply selects and returns the fieldset with the mapping in it.
 */
function microdata_get_default_field_mapping_callback($form, $form_state) {
  return $form['microdata'];
}

/**
 * Form builder helper function.
 *
 * Gets a form element setup to collect different parts of the mapping.
 */
function _microdata_get_form_elements($element, $field_name, $mapping, $label = NULL) {
  switch ($element) {
    case 'itemprop':
      if (empty($label)) {
        $label = t('Field property(s)');
      }

      $itemtypes = array();
      if (!empty($mapping['#itemtype'])) {
        foreach ($mapping['#itemtype'] as $itemtype) {
          // Change '/' to ';' (as recommended at http://us.php.net/urlencode)
          // so the itemtype isn't recognized as a path by the browser.
          $itemtypes[] = str_replace('/', ';', $itemtype);
        }
      }
      else {
        $itemtypes[] = 'empty';
      }
      return array(
        '#type' => 'textfield',
        '#title' => $label,
        '#default_value' => empty($mapping[$field_name]['#itemprop']) ? '' : implode(', ', $mapping[$field_name]['#itemprop']),
        '#description' => t('Comma-separated list. Example: !properties',
          array(
            '!properties' => 'name, http://xmlns.com/foaf/0.1/name',
          )
        ),
        '#autocomplete_path' => 'microdata_autocomplete/itemprop/' . urlencode(check_plain(implode(', ', $itemtypes))),
        '#resizable' => FALSE,
      );

    case 'is_item':
      return array(
        '#type' => 'checkbox',
        '#title' => t('Handle as an item in microdata'),
        '#default_value' => isset($mapping['#is_item']) ? $mapping['#is_item'] : $mapping['#value_type'] == 'item',
      );

    case 'item_fieldset':
      return array(
        '#type' => 'fieldset',
        '#title' => t('@label Item', array('@label' => $label)),
      );

    case 'itemtype':
      if (empty($mapping[$field_name]['#entity_type'])) {
        // The mapping passed in is either for the entity or for a field that
        // has sub-properties.
        if (empty($field_name)) {
          $default_value = empty($mapping['#itemtype']) ? array() : implode(', ', $mapping['#itemtype']);
        }
        else {
          $default_value = empty($mapping[$field_name]['#itemtype']) ? array() : implode(', ', $mapping[$field_name]['#itemtype']);
        }
        return array(
          '#type' => 'textfield',
          '#title' => 'Item Type',
          '#default_value' => $default_value,
          '#description' => t('For example, !types.',
            array(
              '!types' => 'http://schema.org/Person',
            )
          ),
          '#autocomplete_path' => 'microdata_autocomplete/itemtype',
          '#resizable' => FALSE,
        );
      }
      else {
        return array(
          '#markup' => t("This field's value is a <em>{$mapping[$field_name]['#entity_type']}</em> entity. You will need to go to the entity's configuration page to edit its microdata mapping."),
        );
      }
  }
}
