<?php
/**
 * @file
 * Default views for ClosedQuestion module.
 */

/**
 * Implements hook_views_default_views().
 */
function closedquestion_views_default_views() {
  /**
   * Default view to get the list users that answered a question.
   */
  $view = new view;
  $view->name = 'closedquestionusers';
  $view->description = 'The overview of all users that answered a specific question.';
  $view->tag = 'default';
  $view->base_table = 'users';
  $view->human_name = 'ClosedQuestionUsers';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'access user profiles';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['query_comment'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'name' => 'name',
    'uid' => 'uid',
    'once_correct' => 'once_correct',
    'tries' => 'tries',
    'unixtime' => 'unixtime',
  );
  $handler->display->display_options['style_options']['default'] = 'unixtime';
  $handler->display->display_options['style_options']['info'] = array(
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'uid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'once_correct' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'tries' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'unixtime' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
    ),
  );
  $handler->display->display_options['style_options']['override'] = 1;
  $handler->display->display_options['style_options']['sticky'] = 0;
  $handler->display->display_options['style_options']['empty_table'] = 0;
  /* Relationship: ClosedQuestionAnswers: Node Id */
  $handler->display->display_options['relationships']['nid']['id'] = 'nid';
  $handler->display->display_options['relationships']['nid']['table'] = 'cq_user_answer';
  $handler->display->display_options['relationships']['nid']['field'] = 'nid';
  $handler->display->display_options['relationships']['nid']['required'] = 0;
  /* Relationship: ClosedQuestionAnswers: User Id */
  $handler->display->display_options['relationships']['uid']['id'] = 'uid';
  $handler->display->display_options['relationships']['uid']['table'] = 'cq_user_answer';
  $handler->display->display_options['relationships']['uid']['field'] = 'uid';
  $handler->display->display_options['relationships']['uid']['required'] = 0;
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['name']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['name']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = 0;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = 0;
  $handler->display->display_options['fields']['name']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['name']['alter']['html'] = 0;
  $handler->display->display_options['fields']['name']['hide_empty'] = 0;
  $handler->display->display_options['fields']['name']['empty_zero'] = 0;
  $handler->display->display_options['fields']['name']['link_to_user'] = 1;
  $handler->display->display_options['fields']['name']['overwrite_anonymous'] = 0;
  /* Field: User: Uid */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'users';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  $handler->display->display_options['fields']['uid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['uid']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['uid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['uid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['uid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['uid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['uid']['link_to_user'] = 1;
  /* Field: ClosedQuestionAnswers: Once Correct */
  $handler->display->display_options['fields']['once_correct']['id'] = 'once_correct';
  $handler->display->display_options['fields']['once_correct']['table'] = 'cq_user_answer';
  $handler->display->display_options['fields']['once_correct']['field'] = 'once_correct';
  $handler->display->display_options['fields']['once_correct']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['external'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['once_correct']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['once_correct']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['html'] = 0;
  $handler->display->display_options['fields']['once_correct']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['once_correct']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['once_correct']['hide_empty'] = 0;
  $handler->display->display_options['fields']['once_correct']['empty_zero'] = 0;
  $handler->display->display_options['fields']['once_correct']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['once_correct']['format_plural'] = 0;
  /* Field: ClosedQuestionAnswers: Tries */
  $handler->display->display_options['fields']['tries']['id'] = 'tries';
  $handler->display->display_options['fields']['tries']['table'] = 'cq_user_answer';
  $handler->display->display_options['fields']['tries']['field'] = 'tries';
  $handler->display->display_options['fields']['tries']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['external'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['tries']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['tries']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['html'] = 0;
  $handler->display->display_options['fields']['tries']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['tries']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['tries']['hide_empty'] = 0;
  $handler->display->display_options['fields']['tries']['empty_zero'] = 0;
  $handler->display->display_options['fields']['tries']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['tries']['format_plural'] = 0;
  /* Field: ClosedQuestionAnswers: Log Time */
  $handler->display->display_options['fields']['unixtime']['id'] = 'unixtime';
  $handler->display->display_options['fields']['unixtime']['table'] = 'cq_user_answer';
  $handler->display->display_options['fields']['unixtime']['field'] = 'unixtime';
  $handler->display->display_options['fields']['unixtime']['label'] = 'Last Change';
  $handler->display->display_options['fields']['unixtime']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['external'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['unixtime']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['unixtime']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['html'] = 0;
  $handler->display->display_options['fields']['unixtime']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['unixtime']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['unixtime']['hide_empty'] = 0;
  $handler->display->display_options['fields']['unixtime']['empty_zero'] = 0;
  $handler->display->display_options['fields']['unixtime']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['unixtime']['date_format'] = 'short';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Log';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = 'log';
  $handler->display->display_options['fields']['nothing']['alter']['make_link'] = 1;
  $handler->display->display_options['fields']['nothing']['alter']['path'] = 'node/!1/cq_users/[uid]';
  $handler->display->display_options['fields']['nothing']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['external'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['nothing']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['nothing']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['nothing']['alter']['html'] = 0;
  $handler->display->display_options['fields']['nothing']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['nothing']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['nothing']['hide_empty'] = 0;
  $handler->display->display_options['fields']['nothing']['empty_zero'] = 0;
  $handler->display->display_options['fields']['nothing']['hide_alter_empty'] = 0;
  /* Sort criterion: User: Created date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'users';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Contextual filter: Content: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['relationship'] = 'nid';
  $handler->display->display_options['arguments']['nid']['default_action'] = 'empty';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['nid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['nid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['nid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['nid']['specify_validation'] = 1;
  $handler->display->display_options['arguments']['nid']['validate']['type'] = 'numeric';
  $handler->display->display_options['arguments']['nid']['validate']['fail'] = 'empty';
  $handler->display->display_options['arguments']['nid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['nid']['not'] = 0;
  /* Filter criterion: User: Active */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'users';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['group'] = 0;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  $translatables['closedquestionusers'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('CqAnswer - Node relation'),
    t('CqAnswer - User relation'),
    t('Uid'),
    t('Once Correct'),
    t('.'),
    t(','),
    t('Tries'),
    t('Last Change'),
    t('Log'),
    t('log'),
    t('node/!1/cq_users/[uid]'),
    t('All'),
  );
  $views['ClosedQuestionUsers'] = $view;


  /**
   * Default view to get the log of how a user answered a question.
   */
  $view = new view;
  $view->name = 'closedquestionuserlog';
  $view->description = 'The log of how a specific user answered a specific question.';
  $view->tag = 'default';
  $view->base_table = 'users';
  $view->human_name = 'ClosedQuestionUserLog';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'access user profiles';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['query_comment'] = FALSE;
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '100';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = 0;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'name' => 'name',
    'log_id' => 'log_id',
    'once_correct' => 'once_correct',
    'unixtime' => 'unixtime',
    'tries' => 'tries',
    'answer' => 'answer',
    'nid' => 'nid',
    'uid' => 'uid',
  );
  $handler->display->display_options['style_options']['default'] = 'log_id';
  $handler->display->display_options['style_options']['info'] = array(
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'log_id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'once_correct' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'unixtime' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'tries' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'answer' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'nid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
    'uid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
    ),
  );
  $handler->display->display_options['style_options']['override'] = 1;
  $handler->display->display_options['style_options']['sticky'] = 0;
  $handler->display->display_options['style_options']['empty_table'] = 0;
  /* Relationship: ClosedQuestionLog: Log Node Id */
  $handler->display->display_options['relationships']['nid']['id'] = 'nid';
  $handler->display->display_options['relationships']['nid']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['relationships']['nid']['field'] = 'nid';
  $handler->display->display_options['relationships']['nid']['required'] = 0;
  /* Relationship: ClosedQuestionLog: Log User Id */
  $handler->display->display_options['relationships']['uid']['id'] = 'uid';
  $handler->display->display_options['relationships']['uid']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['relationships']['uid']['field'] = 'uid';
  $handler->display->display_options['relationships']['uid']['required'] = 0;
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['name']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['name']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = 0;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = 0;
  $handler->display->display_options['fields']['name']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['name']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['name']['alter']['html'] = 0;
  $handler->display->display_options['fields']['name']['hide_empty'] = 0;
  $handler->display->display_options['fields']['name']['empty_zero'] = 0;
  $handler->display->display_options['fields']['name']['link_to_user'] = 1;
  $handler->display->display_options['fields']['name']['overwrite_anonymous'] = 0;
  /* Field: User: Uid */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'users';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  $handler->display->display_options['fields']['uid']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['external'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['uid']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['uid']['alter']['html'] = 0;
  $handler->display->display_options['fields']['uid']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['uid']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['uid']['hide_empty'] = 0;
  $handler->display->display_options['fields']['uid']['empty_zero'] = 0;
  $handler->display->display_options['fields']['uid']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['uid']['link_to_user'] = 1;
  /* Field: ClosedQuestionLog: Log Entry ID */
  $handler->display->display_options['fields']['log_id']['id'] = 'log_id';
  $handler->display->display_options['fields']['log_id']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['fields']['log_id']['field'] = 'log_id';
  $handler->display->display_options['fields']['log_id']['label'] = 'Entry ID';
  $handler->display->display_options['fields']['log_id']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['external'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['log_id']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['log_id']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['log_id']['alter']['html'] = 0;
  $handler->display->display_options['fields']['log_id']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['log_id']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['log_id']['hide_empty'] = 0;
  $handler->display->display_options['fields']['log_id']['empty_zero'] = 0;
  $handler->display->display_options['fields']['log_id']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['log_id']['format_plural'] = 0;
  /* Field: ClosedQuestionLog: Log Time */
  $handler->display->display_options['fields']['unixtime']['id'] = 'unixtime';
  $handler->display->display_options['fields']['unixtime']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['fields']['unixtime']['field'] = 'unixtime';
  $handler->display->display_options['fields']['unixtime']['label'] = 'Time';
  $handler->display->display_options['fields']['unixtime']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['external'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['unixtime']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['unixtime']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['unixtime']['alter']['html'] = 0;
  $handler->display->display_options['fields']['unixtime']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['unixtime']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['unixtime']['hide_empty'] = 0;
  $handler->display->display_options['fields']['unixtime']['empty_zero'] = 0;
  $handler->display->display_options['fields']['unixtime']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['unixtime']['date_format'] = 'short';
  /* Field: ClosedQuestionLog: Log Once Correct */
  $handler->display->display_options['fields']['once_correct']['id'] = 'once_correct';
  $handler->display->display_options['fields']['once_correct']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['fields']['once_correct']['field'] = 'once_correct';
  $handler->display->display_options['fields']['once_correct']['label'] = 'Once Correct';
  $handler->display->display_options['fields']['once_correct']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['external'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['once_correct']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['once_correct']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['once_correct']['alter']['html'] = 0;
  $handler->display->display_options['fields']['once_correct']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['once_correct']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['once_correct']['hide_empty'] = 0;
  $handler->display->display_options['fields']['once_correct']['empty_zero'] = 0;
  $handler->display->display_options['fields']['once_correct']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['once_correct']['format_plural'] = 0;
  /* Field: ClosedQuestionLog: Log Tries */
  $handler->display->display_options['fields']['tries']['id'] = 'tries';
  $handler->display->display_options['fields']['tries']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['fields']['tries']['field'] = 'tries';
  $handler->display->display_options['fields']['tries']['label'] = 'Tries';
  $handler->display->display_options['fields']['tries']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['external'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['tries']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['tries']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['tries']['alter']['html'] = 0;
  $handler->display->display_options['fields']['tries']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['tries']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['tries']['hide_empty'] = 0;
  $handler->display->display_options['fields']['tries']['empty_zero'] = 0;
  $handler->display->display_options['fields']['tries']['hide_alter_empty'] = 0;
  $handler->display->display_options['fields']['tries']['format_plural'] = 0;
  /* Field: ClosedQuestionLog: Logged Answer */
  $handler->display->display_options['fields']['answer']['id'] = 'answer';
  $handler->display->display_options['fields']['answer']['table'] = 'cq_user_answer_log';
  $handler->display->display_options['fields']['answer']['field'] = 'answer';
  $handler->display->display_options['fields']['answer']['alter']['alter_text'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['make_link'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['absolute'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['external'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['replace_spaces'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['trim_whitespace'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['nl2br'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['word_boundary'] = 1;
  $handler->display->display_options['fields']['answer']['alter']['ellipsis'] = 1;
  $handler->display->display_options['fields']['answer']['alter']['strip_tags'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['trim'] = 0;
  $handler->display->display_options['fields']['answer']['alter']['html'] = 0;
  $handler->display->display_options['fields']['answer']['element_label_colon'] = 1;
  $handler->display->display_options['fields']['answer']['element_default_classes'] = 1;
  $handler->display->display_options['fields']['answer']['hide_empty'] = 0;
  $handler->display->display_options['fields']['answer']['empty_zero'] = 0;
  $handler->display->display_options['fields']['answer']['hide_alter_empty'] = 0;
  /* Sort criterion: User: Created date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'users';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Contextual filter: Content: Nid */
  $handler->display->display_options['arguments']['nid']['id'] = 'nid';
  $handler->display->display_options['arguments']['nid']['table'] = 'node';
  $handler->display->display_options['arguments']['nid']['field'] = 'nid';
  $handler->display->display_options['arguments']['nid']['relationship'] = 'nid';
  $handler->display->display_options['arguments']['nid']['default_action'] = 'empty';
  $handler->display->display_options['arguments']['nid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['nid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['nid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['nid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['nid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['nid']['specify_validation'] = 1;
  $handler->display->display_options['arguments']['nid']['validate']['type'] = 'numeric';
  $handler->display->display_options['arguments']['nid']['validate']['fail'] = 'empty';
  $handler->display->display_options['arguments']['nid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['nid']['not'] = 0;
  /* Contextual filter: User: Uid */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'users';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'empty';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid']['default_argument_skip_url'] = 0;
  $handler->display->display_options['arguments']['uid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['uid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['uid']['specify_validation'] = 1;
  $handler->display->display_options['arguments']['uid']['validate']['type'] = 'user';
  $handler->display->display_options['arguments']['uid']['validate_options']['restrict_roles'] = 0;
  $handler->display->display_options['arguments']['uid']['validate']['fail'] = 'empty';
  $handler->display->display_options['arguments']['uid']['break_phrase'] = 0;
  $handler->display->display_options['arguments']['uid']['not'] = 0;
  /* Filter criterion: User: Active */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'users';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = '1';
  $handler->display->display_options['filters']['status']['group'] = 0;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  $translatables['closedquestionuserlog'] = array(
    t('Master'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('CqLog - Node relation'),
    t('CqLog - User relation'),
    t('Uid'),
    t('Entry ID'),
    t('.'),
    t(','),
    t('Time'),
    t('Once Correct'),
    t('Tries'),
    t('Logged Answer'),
    t('All'),
  );
  $views['ClosedQuestionUserLog'] = $view;

  return $views;
}
