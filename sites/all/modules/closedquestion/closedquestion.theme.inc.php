<?php
/**
 * @file
 * Theme functions used by closedQuestion.
 */

/**
 * Themes a CqOption for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: Asocaitive array containing one element:
 *     - text: string - The text of the option.
 *     - description: string - The description of the option.
 *     - feedback: array - Form array with the feedback items that are used when
 *       the item is selected.
 *     - feedback_notselected: array - Form array with the feedback items that
 *       are used when the item is not selected.
 *
 * @ingroup themeable
 */
function theme_closedquestion_option($elements) {
  $option = $elements['form'];

  $title = t('Option: identifier=%i, Correct=%c', array('%i' => $option['identifier'], '%c' => $option['correct']));
  $body = '<p>' . $option['text'] . '</p>';

  if (isset($option['description'])) {
    $body .= closedquestion_make_fieldset(t('Description'), $option['description']);
  }
  if (isset($option['feedback']) && count($option['feedback']) > 0) {
    $body .= closedquestion_make_fieldset(t('Feedback if selected'), $option['feedback']);
  }

  if (isset($option['feedback_notselected']) && count($option['feedback_notselected']) > 0) {
    $body .= closedquestion_make_fieldset(t('Feedback if not selected'), $option['feedback_notselected']);
  }

  $retval = '<p>' . $title . '</p><p>' . $body . '</p>';

  return $retval;
}

/**
 * Themes an array of CqOption (multiple choice options) for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: Asocaitive array containing one element:
 *     - items: array containing form arrays of the feedback items.
 *
 * @ingroup themeable
 */
function theme_closedquestion_option_list($elements) {
  $form = $elements['form'];
  $options = $form['items'];

  $html = '<ul>';
  foreach ($options as $option) {
    $html .= '<li>' . drupal_render($option) . '</li>' . "\n";
  }
  $html .= '</ul>';
  return $html;
}

/**
 * Themes a single feedback item.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: Associative array containing:
 *     - mintries: int - the minimal tries needed before showing this item.
 *     - maxtries: int - the maximal tries needed before showing this item.
 *     - text: string - the text of the feedback item.
 *
 * @ingroup themeable
 */
function theme_closedquestion_feedback_item($elements) {
  $form = $elements['form'];
  $retval = 'mintries=' . $form['mintries'] . ', maxtries=' . $form['maxtries'] . ':<br/>' . $form['text'] . "\n";
  return $retval;
}

/**
 * Themes an array of CqFeedback items for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: Asocaitive array containing one element:
 *     - items: array containing form arrays of the feedback items.
 *
 * @ingroup themeable
 */
function theme_closedquestion_feedback_list($elements) {
  $form = $elements['form'];

  $retval = '';
  if (isset($form['items']) && count($form['items']) > 0) {
    $retval .= '<ul>';
    foreach ($form['items'] AS $hint) {
      $retval .= '<li>' . drupal_render($hint) . '</li>' . "\n";
    }
    $retval .= '</ul>';
  }
  else {
    $retval .= t('No feedback defined.');
  }
  return $retval;
}

/**
 * Themes an array of CqInlineOption (fillblanks options) for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form Asocaitive array containing one element:
 *     - items: array containing CqOption items.
 *
 * @ingroup themeable
 */
function theme_closedquestion_inline_option_list($elements) {
  $form = $elements['form'];
  $options = $form['items'];

  $retval = '<ul>';
  foreach ($options as $option) {
    $retval .= '<li>' . t('Group: %g, identifier: %i<br/>@text', array(
        '%g' => $option->getGroup(),
        '%i' => $option->getIdentifier(),
        '@text' => $option->getText(),
      )) . '</li>' . "\n";
  }
  $retval .= '</ul>';

  return $retval;
}

/**
 * Themes a CqRange for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: array containing form arrays of the option items.
 *     - correct: int - The correct property of the range.
 *     - minval: number - The minimum value of the range.
 *     - maxval: number - The maximum value of the range.
 *     - feedback: array - Form array with the feedback.
 *
 * @ingroup themeable
 */
function theme_closedquestion_range($elements) {
  $form = $elements['form'];
  dpm($elements);

  $retval = t('Range, correct=@correct, minval=@minval, maxval=@maxval.',
      array(
        '@correct' => $form['correct'],
        '@minval' => $form['minval'],
        '@maxval' => $form['maxval'],
    ));

  $retval .= drupal_render($form['feedback']);

  return $retval;
}

/**
 * Themes a CqMappingAbstract for text review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: array containing form arrays of the option items.
 *     - logic: array - The form for the logic of this item.
 *     - children: array - The forms for the children.
 *
 * @ingroup themeable
 */
function theme_closedquestion_mapping_item($elements) {
  $form = $elements['form'];

  $retval = '';
  $retval .= drupal_render($form['logic']);
  if (count($form['children']['items']) > 0) {
    $retval .= drupal_render($form['children']);
  }

  return $retval;
}

/**
 * Themes a CqMapping for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: array containing form arrays of the option items.
 *     - correct: int - correct value of the mapping.
 *     - children: array - The forms for the logic-children.
 *     - feedback: array - The forms for the feedback.
 *
 * @ingroup themeable
 */
function theme_closedquestion_mapping($elements) {
  $form = $elements['form'];

  $retval = t('Mapping, Correct=%cor', array('%cor' => $form['correct'])) . '<br/>';

  if (count($form['children'])) {
    $retval .= closedquestion_make_fieldset(t('Logic'), $form['children'], TRUE, TRUE);
  }

  if (isset($form['feedback']) && count($form['feedback']) > 0) {
    $retval .= closedquestion_make_fieldset(t('Feedback if matched'), $form['feedback']);
  }

  return $retval;
}

/**
 * Themes an array of CqMapping for text-review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: array containing form arrays of the option items.
 *     - items: array - The forms of the items in this list.
 *
 * @ingroup themeable
 */
function theme_closedquestion_mapping_list($elements) {
  $form = $elements['form'];
  $items = $form['items'];

  $html = '<ul>';
  foreach ($items as $item) {
    $html .= '<li>' . drupal_render($item) . '</li>' . "\n";
  }
  $html .= '</ul>';
  return $html;
}

/**
 * Themes the text of a question form for text review.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: associative array containing:
 *     - text: string - The question text.
 *     - correctFeedback: string - The feedback if answered correct.
 *     - hints: array - List of hints.
 *     - options: array - List of options.
 *     - mappings: array - List of mappings.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_general_text($elements) {
  $form = $elements['form'];

  $retval = '';
  $retval .= closedquestion_make_fieldset('Question text:', drupal_render($form['text']), FALSE, FALSE, TRUE);

  if (isset($form['correctFeeback'])) {
    $retval .= closedquestion_make_fieldset('Feedback if correct:', drupal_render($form['correctFeeback']), FALSE, FALSE, TRUE);
  }

  $retval .= closedquestion_make_fieldset('Hints:', drupal_render($form['hints']), TRUE, FALSE, TRUE);

  if (isset($form['options']) && count($form['options']['items']) > 0) {
    $retval .= closedquestion_make_fieldset('Options:', drupal_render($form['options']), TRUE, FALSE, TRUE);
  }

  if (isset($form['mappings']) && count($form['mappings']['items']) > 0) {
    $retval .= closedquestion_make_fieldset('Mappings:', drupal_render($form['mappings']), TRUE, FALSE, TRUE);
  }

  return $retval;
}

/**
 * Themes the question part of a balance-question form.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: associative array containing:
 *     - questionText: array - Forms array item with question text.
 *     - optionsacc: array - Forms array with the accumulation options.
 *     - optionsflow: array - Forms array with the transport options.
 *     - optionsprod: array - Forms array with the reaction options.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_balance($elements) {
  $formpart = $elements['form'];
  $header = array(
    t('Accumulation'),
    '=',
    t('&Sigma; Transfer'),
    '+',
    t('&Sigma; Reaction')
  );
  $rows = array();
  $row = array();
  $row[] = drupal_render($formpart['optionsacc']);
  $row[] = ' ';
  $row[] = drupal_render($formpart['optionsflow']);
  $row[] = ' ';
  $row[] = drupal_render($formpart['optionsprod']);
  $rows[] = $row;

  $form_pos = strpos($formpart['questionText']['#markup'], '<formblock/>');
  if ($form_pos !== FALSE) {
    $pre_form = substr($formpart['questionText']['#markup'], 0, $form_pos);
    $post_form = substr($formpart['questionText']['#markup'], $form_pos + 12);
  }
  else {
    $pre_form = $formpart['questionText']['#markup'];
    $post_form = '';
  }

  $html = '';
  $html .= $pre_form;
  $variables = array(
    'header' => $header,
    'rows' => $rows,
    'caption' => '',
    'attributes' => array('class' => 'cqTable'),
    'colgroups' => NULL,
    'sticky' => FALSE,
    'empty' => '',
  );
  $html .= theme_table($variables);
  $html .= $post_form;

  return $html;
}

/**
 * Themes the question part of a check-question form.
 *
 * @param array $formpart
 *   The question part of the form.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_check($formpart) {

  $html = '';
  $html .= drupal_render($formpart['questionText']);
  $html .= drupal_render($formpart['options']);

  return $html;
}

/**
 * Themes the question part of a drag&drop-question form.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: The question, containing:
 *     - questionText: Drupal form-field with the quetsion text.
 *     - data['#value']: associative array with content:
 *       - elementname: The base-name used for form elements that need to be
 *           accessed by javascript.
 *       - mapname: The name of the imagemap to use.
 *       - image: associative array with
 *         - url: the url of the image to use.
 *         - height: the height of the image to use.
 *         - width: the height of the image to use.
 *       - hotspots: array containing the hotspots, each containing:
 *         - termid: the id of the hotspot.
 *         - maphtml: the imagemap "area" html for this hotspot.
 *         - description: the description of the hotspot.
 *       - draggables: array containing the draggables, each containing:
 *         - cqvalue: the identifier of the draggable.
 *         - x: the x coordinate of the draggable.
 *         - y: the y coordinate of the draggable.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_drag_drop($elements) {
  $formpart = $elements['form'];
  drupal_add_library('system', 'ui.sortable');
  drupal_add_library('system', 'ui.draggable');
  drupal_add_library('system', 'ui.droppable');
  drupal_add_js(drupal_get_path('module', 'closedquestion') . '/assets/closedquestion_dd.js');

  $data = $formpart['data']['#value'];

  $form_pos = strpos($formpart['questionText']['#markup'], '<formblock/>');
  if ($form_pos !== FALSE) {
    $pre_form = substr($formpart['questionText']['#markup'], 0, $form_pos);
    $post_form = substr($formpart['questionText']['#markup'], $form_pos + 12);
  }
  else {
    $pre_form = $formpart['questionText']['#markup'];
    $post_form = '';
  }

  $html = '';
  $html .= $pre_form;

  $html .= '<div id="' . $data['elementname'] . 'answerContainer" class="cqMatchImgBox">' . "\n";
  $html .= '<img usemap="#' . $data['mapname'] . '" src="' . $data['image']['url'] . '" />' . "\n";

  $start_positions = array(); // Starting positions of the draggables.
  foreach ($data['draggables'] as $id => $draggable) {
    $html .= '  <div cqvalue="' . $id . '" class="' . $draggable['class'] . '">' . $draggable['text'] . '</div>' . "\n";
    // Add the draggable starting position to the javascript settings.
    $start_positions[] = array(
      'cqvalue' => $draggable['cqvalue'],
      'x' => $draggable['x'],
      'y' => $draggable['y'],
    );
  }

  $settings['closedQuestion']['dd'][$data['elementname']]['ddDraggableStartPos'] = $start_positions;
  $settings['closedQuestion']['dd'][$data['elementname']]['ddImage'] = array(
    "height" => $data['image']['height'],
    "width" => $data['image']['width'],
    "url" => $data['image']['url'],
  );

  $html .= '</div>' . "\n";
  $map_html = '';
  $map_html .= '<map name="' . $data['mapname'] . '">' . "\n";
  foreach ($data['hotspots'] as $id => $hotspot) {
    if (!empty($hotspot['description'])) {
      if (module_exists('qtip')) {
        $map_html .= '<area id="' . $hotspot['termid'] . '" ' . $hotspot['maphtml'] . ' class="qtip-link" title=\'' . $hotspot['description'] . '\' href="javascript: void(0)" />' . "\n";
      }
      elseif (module_exists('hovertip')) {
        $map_html .= '<area id="' . $hotspot['termid'] . '" ' . $hotspot['maphtml'] . ' href="javascript: void(0)" />' . "\n";
        $html .= '<div target="' . $hotspot['termid'] . '" class="hovertip">' . $hotspot['description'] . '</div>' . "\n";
      }
    }
  }
  $map_html .= '</map>' . "\n";

  $html .= $map_html;
  $html .= $post_form;

  drupal_add_js($settings, 'setting');

  return $html;
}

/**
 * Themes in inline choice as a dropdown selection, or a free-form text box.
 *
 * @param array $choice
 *   Associative array with the choice parameters:
 *   - name: string, name to use for the input element.
 *   - group: string, group the options belong to.
 *   - style: string, style to use for the input element
 *   - class: string, class to use for the input element.
 *   - size: int, size attribute to use for the input element.
 *   - freeform: int, 1: style as textbox, other: style as selectbox.
 *   - options: array of CqInlineOption, the options to give the user.
 *   - value: currently selected/given answer.
 *
 * @ingroup themeable
 */
function theme_closedquestion_inline_choice($choice) {
  $html = '';

  $style_html = '';
  if (!empty($choice['style'])) {
    $style_html = ' style=' . $choice['style'];
  }
  $class_html = '';
  if (!empty($choice['class'])) {
    $class_html = ' class=' . $choice['class'];
  }

  if ($choice['freeform']) {
    $html .= '<input type="text" name="' . $choice['name'] . '" value="' . $choice['value'] . '"' . $style_html . $class_html . ' size="' . $choice['size'] . '" />';
  }
  else {
    $html .= '<select name="' . $choice['name'] . '" size="1"' . $style_html . $class_html . '>';
    if (is_array($choice['options']) && count($choice['options']) > 0) {
      foreach ($choice['options'] AS $option_id => $option) {
        $selected_tag = '';
        if ($choice['value'] == $option_id) {
          $selected_tag = ' selected';
        }
        $html .= '<option' . $selected_tag . ' value="' . $option_id . '">' . $option->getText() . '</option>';
      }
    }
    else {
      drupal_set_message(t('inlineChoice with id "%id" has no options.', array('%id' => $choice['id'])), 'warning');
    }
    $html .= '</select>';
  }
  return $html;
}

/**
 * Themes the question part of a hotspot-question form.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: array - The question part of the form, containing:
 *     - questionText: Drupal form-field with the quetsion text.
 *     - data['#value']: associative array with content:
 *       - elementname: the base-name used for form elements that need to be
 *           accessed by javascript.
 *       - mapname: the name of the imagemap to use.
 *       - crosshairurl: the url of the crosshair image to use.
 *       - image
 *         - url: the url of the image to use.
 *         - height: the height of the image to use.
 *         - width: the height of the image to use.
 *       - hotspots: array containing the hotspots, each containing:
 *         - termid: the id of the hotspot.
 *         - maphtml: the imagemap "area" html for this hotspot.
 *         - description: the description of the hotspot.
 *       - draggables: array containing the draggables, each containing:
 *         - cqvalue: the identifier of the draggable.
 *         - x: the x coordinate of the draggable.
 *         - y: the y coordinate of the draggable.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_hotspot($elements) {
  $formpart = $elements['form'];
  drupal_add_library('system', 'ui.sortable');
  drupal_add_library('system', 'ui.draggable');
  drupal_add_library('system', 'ui.droppable');
  drupal_add_js(drupal_get_path('module', 'closedquestion') . '/assets/closedquestion_hs.js');

  $data = $formpart['data']['#value'];

  $form_pos = strpos($formpart['questionText']['#markup'], '<formblock/>');
  if ($form_pos !== FALSE) {
    $pre_form = substr($formpart['questionText']['#markup'], 0, $form_pos);
    $post_form = substr($formpart['questionText']['#markup'], $form_pos + 12);
  }
  else {
    $pre_form = $formpart['questionText']['#markup'];
    $post_form = '';
  }

  $html = '';
  $html .= $pre_form;

  $html .= '<div id="' . $data['elementname'] . 'answerContainer" class="cqMatchImgBox">' . "\n";
  $html .= '<img usemap="#' . $data['mapname'] . '" src="' . $data['image']['url'] . '" />' . "\n";

  $start_positions = array(); // Starting positions of the draggables.
  if (isset($data['draggables'])) {
    foreach ($data['draggables'] as $id => $draggable) {
      $html .= '  <div cqvalue="' . $draggable['cqvalue'] . '" class="' . $draggable['class'] . '"><img src="' . $data['crosshairurl'] . '" /></div>' . "\n";
      // Add the draggable starting position to the javascript settings.
      $start_positions[] = array(
        'cqvalue' => $draggable['cqvalue'],
        'x' => $draggable['x'],
        'y' => $draggable['y'],
      );
    }
  }

  $settings['closedQuestion']['hs'][$data['elementname']]['ddDraggableStartPos'] = $start_positions;
  $settings['closedQuestion']['hs'][$data['elementname']]['ddImage'] = array(
    "height" => $data['image']['height'],
    "width" => $data['image']['width'],
    "url" => $data['image']['url'],
  );
  $settings['closedQuestion']['hs'][$data['elementname']]['maxChoices'] = $data['maxchoices'];
  $settings['closedQuestion']['hsimage'] = $data['crosshairurl'];

  $html .= '</div>' . "\n";
  $map_html = '';
  $map_html .= '<map name="' . $data['mapname'] . '">' . "\n";
  if (isset($data['hotspots'])) {
    foreach ($data['hotspots'] as $id => $hotspot) {
      if (!empty($hotspot['description'])) {
        if (module_exists('qtip')) {
          $map_html .= '<area id="' . $hotspot['termid'] . '" ' . $hotspot['maphtml'] . ' class="qtip-link" title=\'' . $hotspot['description'] . '\' href="javascript: void(0)" />' . "\n";
        }
        elseif (module_exists('hovertip')) {
          $map_html .= '<area id="' . $hotspot['termid'] . '" ' . $hotspot['maphtml'] . ' href="javascript: void(0)" />' . "\n";
          $html .= '<div target="' . $hotspot['termid'] . '" class="hovertip">' . $hotspot['description'] . '</div>' . "\n";
        }
      }
    }
  }
  $map_html .= '</map>' . "\n";

  $html .= $map_html;
  $html .= $post_form;

  drupal_add_js($settings, 'setting');

  return $html;
}

/**
 * Themes the question part of a select&order-question form.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: array - The question part of the form, containing:
 *     - questionText: Drupal form-field with the quetsion text.
 *     - data['#value']: associative array with content:
 *       - elementname: The base-name used for form elements that need to be
 *           accessed by javascript.
 *       - duplicates: Are duplicates allowed?
 *       - alignment: string "horizontal" or "normal".
 *       - optionHeight: Minimal height to use for items to force nice alignment
 *         if contents are of varying height, or boolean FALSE if not set.
 *       - sourceTitle: title of the source-section
 *       - unselected: array of items, each one containing:
 *         - identifier: item identifier.
 *         - text: item text.
 *         - description: item description.
 *       - sections: array of target sections, each one containing:
 *         - identifier: section identifier.
 *         - text: section title.
 *         - items: array of items, each one containing:
 *           - identifier: item identifier.
 *           - text: item text.
 *           - description: item description.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_select_order($elements) {
  $formpart = $elements['form'];
  drupal_add_js(drupal_get_path('module', 'closedquestion') . '/assets/closedquestion_so.js');
  drupal_add_library('system', 'ui.sortable');
  drupal_add_library('system', 'ui.draggable');

  $form_pos = strpos($formpart['questionText']['#markup'], '<formblock/>');
  if ($form_pos !== FALSE) {
    $pre_form = substr($formpart['questionText']['#markup'], 0, $form_pos);
    $post_form = substr($formpart['questionText']['#markup'], $form_pos + 12);
  }
  else {
    $pre_form = $formpart['questionText']['#markup'];
    $post_form = '';
  }

  $html = '';
  $html .= $pre_form;

  $data = $formpart['data']['#value'];

  if ($data['duplicates']) {
    $sourceclass = 'cqDDList cqCopyList cqNoDel';
    $targetclass = 'cqDDList cqDropableList';
  }
  else {
    $sourceclass = 'cqDDList cqDropableList cqNoDel';
    $targetclass = 'cqDDList cqDropableList cqNoDel';
  }

  $html .= '<div class="cqSo' . drupal_ucfirst($data['alignment']) . '">' . "\n";
  $html .= '<div class="cqSources" id="' . $data['elementname'] . 'sources">' . "\n";
  $html .= '  <div class="cqSource">' . "\n";
  $html .= '    <p>' . $data['sourceTitle'] . '</p>' . "\n";
  $html .= '<ul id="' . $data['elementname'] . 'source" class="' . $sourceclass . '">' . "\n";
  if (isset($data['unselected'])) {
    foreach ($data['unselected'] as $item) {
      $html .= cq_make_li($item['identifier'], $item['text'], $item['description'], $data['optionHeight']);
    }
  }
  $html .= '<div class="cqSoClear"></div>' . "\n"; // To make sure that with horizontal alignment the ul encloses the li's
  $html .= '</ul>' . "\n";
  $html .= '</div>' . "\n";
  $html .= '</div>' . "\n";

  $html .= '<div class="cqTargets" id="' . $data['elementname'] . 'targets">' . "\n";
  foreach ($data['sections'] as $section_selected) {
    $html .= '  <div class="Cqtarget">' . "\n";
    $html .= '    <p>' . $section_selected['text'] . '</p>' . "\n";
    $html .= '    <ul class="' . $targetclass . '" cqvalue="' . $section_selected['identifier'] . '">' . "\n";
    foreach ($section_selected['items'] as $item) {
      $html .= cq_make_li($item['identifier'], $item['text'], $item['description'], $data['optionHeight']);
    }
    $html .= '    </ul>' . "\n";
    $html .= '  </div>' . "\n";
  }
  $html .= '</div>' . "\n"; // cqSource
  $html .= '</div>' . "\n"; // CqSO_normal

  $html .= '<div style="clear: left;">' . "\n";
  $html .= '</div>' . "\n";
  $html .= $post_form;

  return $html;
}

/**
 * Helper function to make one item for a select&order question
 *
 * @param string $identifier
 *   The item's identifier
 * @param string $text
 *   The item's html content
 * @param string $description
 *   The item's extra description (put in a hovertip popup)
 * @param mixed $height
 *   Minimal height to use for items to force nice alignment if contents are of
 *   varying height, or boolean FALSE if not set.
 *
 * @return string
 *   The html for one item.
 */
function cq_make_li($identifier, $text, $description, $height=FALSE) {
  $retval = '';
  $style = '';
  if ($height) {
    $style = ' style="height: ' . $height . '"';
  }
  $retval .= '<li class="cqDraggable" cqvalue="' . $identifier . '" ' . $style . '>';
  if (!empty($description)) {
    if (module_exists('qtip')) {
      $retval .= '<span class="qtip-link"><span class="qtip-tooltip">' . $description . '</span>' . $text . '</span>';
    }
    elseif (module_exists('hovertip')) {
      $retval .= '<span>' . $text . '</span>';
      $retval .= '<span class="hovertip">' . $description . '</span>';
    }
    else {
      $retval .= $text . '<br/>' . $description;
    }
  }
  else {
    $retval .= $text;
  }
  $retval .= '</li>' . "\n";
  return $retval;
}

/**
 * Themes the back/next links for the sequence question.
 *
 * @param int $index
 *   The index of the current sub-question.
 * @param int $total
 *   The total number of sub-questions.
 * @param string $prev_url
 *   The url to the previous sub-question.
 * @param string $next_url
 *   The url to the next sub-question.
 *
 * @ingroup themeable
 */
function theme_closedquestion_sequence_back_next($vars) {
  $html = '';
  $html .= t('This is part @part of a @total part question.', array('@part' => ($vars['index'] + 1), '@total' => $vars['total']));
  if (!empty($vars['prev_url'])) {
    $html .= ' ' . t('[ <a href="@prevurl">Previous Step</a> ]', array('@prevurl' => $vars['prev_url']));
  }
  if (!empty($vars['next_url'])) {
    $html .= ' ' . t('[ <a href="@nextUrl">Next Step</a> ]', array('@nextUrl' => $vars['next_url']));
  }
  return $html;
}

/**
 * Themes the question part of a check-question form.
 *
 * @param array $elements
 *   Asocaitive array containing one element:
 *   - form: associative array containing:
 *     - items: array - The sub questions.
 *
 * @ingroup themeable
 */
function theme_closedquestion_question_sequence_text($elements) {
  $retval = '';
  foreach ($elements['form']['items'] AS $item) {
    $retval .= closedquestion_make_fieldset($item['title'], $item['question']);
  }
  return $retval;
}

