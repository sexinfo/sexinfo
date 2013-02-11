<?php
/**
 * @file
 * Contains the chart display plugin.
 *
 * @author Jurriaan Roelofs http://drupal.org/user/52638
 */

/**
 * The plugin that handles the chart style.
 *
 * @ingroup views_style_plugins
 */
class views_dataviz_plugin_style_dataviz extends views_plugin_style {
  function option_definition () {
    $options = parent::option_definition();
    $options['type'] = array('default' => 'PieChart');
    $options['_curveType'] = array('default' => 'none');
    $options['_pieSliceText'] = array('default' => 'value');
    $options['_width'] = array('default' => '');
    $options['_height'] = array('default' => '');
    $options['chart_area_width'] = array('default' => '80%');
    $options['chart_area_height'] = array('default' => 'auto');
    $options['chart_area_left'] = array('default' => 90);
    $options['chart_area_top'] = array('default' => 50);
    $options['colors'] = array('default' => '');
    $options['_enableInteractivity'] = array('default' => 1);

    return $options;
  }

  function options_form(&$form, &$form_state) {
    // Include ctools dependent support
    ctools_include('dependent');

    parent::options_form($form, $form_state);

    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type'),
      '#description' => t('Chart type, see <a href="http://code.google.com/apis/chart/">Google Chart API documentation</a>.'),
      '#options' => views_dataviz_types(),
      '#required' => TRUE,
      '#default_value' => $this->options['type'],
      '#id' => 'dataviz-chart-type',
    );
    // Google chart options are _prefixed so that they can be autoloaded later
    $form['_curveType'] = array(
      '#type' => 'select',
      '#dependency' => array(
        'dataviz-chart-type' => array('LineChart'),
      ),
      '#title' => t('Line smoothing'),
      '#description' => t('The angles of the line will be smoothed.'),
      '#options' => array(0 => 'none', 'function' => 'function'),
      '#default_value' => $this->options['_curveType'],
    );
    // Google chart options are _prefixed so that they can be autoloaded later
    $form['_pieSliceText'] = array(
      '#type' => 'select',
      '#dependency' => array(
        'dataviz-chart-type' => array('PieChart'),
      ),
      '#title' => t('Pie Slice Text'),
      '#description' => t('The content of the text displayed on the slice.'),
      '#options' => array(
        'percentage' => 'percentage',
        'value' => 'value',
        'label' => 'label',
        'none' => 'none',
      ),
      '#default_value' => $this->options['_pieSliceText'],
    );
    $form['_width'] = array(
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#description' => t('Chart container width in pixels. Leave blank for auto width.'),
      '#size' => 8,
      '#required' => FALSE,
      '#default_value' => $this->options['_width'],
    );
    $form['_height'] = array(
      '#type' => 'textfield',
      '#title' => t('Height'),
      '#description' => t('Chart container height in pixels. Leave blank for auto height.'),
      '#size' => 8,
      '#required' => FALSE,
      '#default_value' => $this->options['_height'],
    );
    $form['chart_area_width'] = array(
      '#type' => 'textfield',
      '#title' => t('Chart Area Width'),
      '#description' => t('Width of the chart. Enter an integer, percentage, or auto.'),
      '#size' => 8,
      '#required' => FALSE,
      '#default_value' => $this->options['chart_area_width'],
    );
    $form['chart_area_height'] = array(
      '#type' => 'textfield',
      '#title' => t('Chart Area Height'),
      '#description' => t('Height of the chart. Enter an integer, percentage, or auto.'),
      '#size' => 8,
      '#required' => FALSE,
      '#default_value' => $this->options['chart_area_height'],
    );
    $form['chart_area_left'] = array(
      '#type' => 'textfield',
      '#title' => t('Chart Area Left Margin'),
      '#description' => t('Distance of chart area from left edge. Enter an integer, percentage, or auto.'),
      '#size' => 8,
      '#required' => FALSE,
      '#default_value' => $this->options['chart_area_left'],
    );
    $form['chart_area_top'] = array(
      '#type' => 'textfield',
      '#title' => t('Chart Area Top Margin'),
      '#description' => t('Distance of chart area from top edge. Enter an integer, percentage, or auto.'),
      '#size' => 8,
      '#required' => FALSE,
      '#default_value' => $this->options['chart_area_top'],
    );
    $form['colors'] = array(
      '#type' => 'textfield',
      '#title' => t('Colors'),
      '#description' => t('Space separated list of colors to use for the chart elements. Use valid css colors such as red, #00ff00, rgb(200,0,0) etc.'),
      '#required' => FALSE,
      '#default_value' => $this->options['colors'],
    );
    $form['_enableInteractivity'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable Interactivity'),
      '#description' => t('Whether the chart throws user-based events or reacts to user interaction.'),
      '#default_value' => $this->options['_enableInteractivity'],
    );
  }

  function render() {
    static $views_dataviz_log = -1;
    if (!(++$views_dataviz_log)) {
      // Add js only once.
      drupal_add_js('if (!Boolean(Drupal.settings.Dataviz)) { Drupal.settings.Dataviz = new Object(); } if (!Boolean(Drupal.settings.Dataviz.chart)) { Drupal.settings.Dataviz.chart = new Object();google.load("visualization", "1", {packages:["corechart", "gauge", "orgchart"]}); }', 'inline');
      drupal_add_js('https://www.google.com/jsapi', array('type' => 'external', 'weight' => JS_LIBRARY));
      drupal_add_js(drupal_get_path('module', 'views_dataviz') . '/js/dataviz-goo-chart.js');
    }
    $output = '';
    $settings = array();
    $settings = array();
    $options = $this->options;
    $sets = func_get_args();
    $one = count($sets) == 1;
    $set = 0;
    $chartArea = new stdClass();
    $chartArea->width = $options['chart_area_width'];
    $chartArea->height = $options['chart_area_height'];
    $chartArea->left = $options['chart_area_left'];
    $chartArea->top = $options['chart_area_top'];

    foreach ($sets as $title => $records) {
      $chart_id = 'dataviz_ch' . $this->display->vid . '_'. $this->display->id . '_'. $views_dataviz_log . '_'. $set;
      $output .= '<div class="dataviz" id="' . $chart_id . '"></div>';
      $settings['chart'][$chart_id] = array(
        'data' => array(),
        'chartType' => $options['type'],
        'containerId' =>  $chart_id,
        'options' => array(
          'forceIFrame' => FALSE,
          'chartArea' => $chartArea,
          'title' => $one ? $this->display->display_title : $title,
        ),
      );

      foreach ($options as $key => $option) {
        if (($option) && ($key{0} == '_')) {
          $key = ltrim($key, '_');
          $settings['chart'][$chart_id]['options'][$key] = $option;
        }
      }

      if ($options['colors']) {
        $colors = explode(' ', $options['colors']);
        $settings['chart'][$chart_id]['options']['colors'] = $colors;
      }

      $data = array();

      end($this->view->field);
      do {
        $field = current($this->view->field);
        $data[0][] = $field->options['label'];
      } while(prev($this->view->field));

      $header = @array_pop(array_keys($this->view->field));
      foreach ($records as $row_index => $row) {
        foreach ($this->view->field as $key => $field) {
          if (!$field->options['exclude']) {
            $value = strip_tags($field->advanced_render($row));
            // Jumping through some hoops to push new values to the beginning of the
            // array instead of to the end. Needed because in views we want values
            // before labels but G Charts wants labels before values.
            if (!is_array($data[$row_index+1])) $data[$row_index+1] = array();
            array_unshift($data[$row_index+1], ($key === $header) ? $value : floatVal($value)) ;
          }
        }
      }

      $settings['chart'][$chart_id]['data'] = $data;

      // Allow modules to alter the chart based on views context.
      drupal_alter('views_dataviz', $settings['chart'][$chart_id], $this->view->name, $this->display->id);

      // Since view expects string output we can't save render array for later.
      $set++;
    }

    $output .= '<script>Drupal.settings.Dataviz.chart.' . $chart_id . ' = null;jQuery.extend(true, Drupal.settings.Dataviz, ' . json_encode($settings) . ');</script>';

    return $output;
  }
}
