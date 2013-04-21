<?php
/**
 * @file
 * Views data hook for ClosedQuestion module.
 */

/**
 * Implements hook_views_data().
 */
function closedquestion_views_data() {
  $data = array();
  $data['cq_user_answer'] = array(
    'table' => array(
      'group' => 'ClosedQuestionAnswers',
      'join' => array(
        'node' => array(
          'left_field' => 'nid',
          'field' => 'nid',
        ),
        'users' => array(
          'left_field' => 'uid',
          'field' => 'uid',
        ),
      )
    ),
    'unixtime' => array(
      'title' => t('Log Time'),
      'help' => t('The time/date for this attempt?'),
      'field' => array(
        'handler' => 'views_handler_field_date',
        'click sortable' => TRUE,
      ),
      // Information for accepting it as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_date',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting it as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_date',
      ),
      // Information for sorting on it.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'once_correct' => array(
      'title' => t('Once Correct'),
      'help' => t('Has the user completed this question?'),
      // Information for displaying the nid
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      // Information for accepting it as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting a nid as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
      ),
      // Information for sorting on a nid.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'tries' => array(
      'title' => t('Tries'),
      'help' => t('Number of times the user gave an answer.'),
      // Information for displaying the nid
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      // Information for accepting a nid as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting a nid as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
      ),
      // Information for sorting on a nid.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'nid' => array(
      'title' => t('Node Id'),
      'help' => t('A reference to the node of the question.'),
      // Because this is a foreign key to the {node} table. This allows us to
      // have, when the view is configured with this relationship, all the fields
      // for the related node available.
      'relationship' => array(
        'base' => 'node',
        'field' => 'nid',
        'handler' => 'views_handler_relationship',
        'label' => t('CqAnswer - Node relation'),
      ),
    ),
    'uid' => array(
      'title' => t('User Id'),
      'help' => t('A reference to the User.'),
      // Because this is a foreign key to the {node} table. This allows us to
      // have, when the view is configured with this relationship, all the fields
      // for the related node available.
      'relationship' => array(
        'base' => 'users',
        'field' => 'uid',
        'handler' => 'views_handler_relationship',
        'label' => t('CqAnswer - User relation'),
      ),
    ),
  );

  $data['cq_user_answer_log'] = array(
    'table' => array(
      'group' => 'ClosedQuestionLog',
      'join' => array(
        'node' => array(
          'left_field' => 'nid',
          'field' => 'nid',
        ),
        'users' => array(
          'left_field' => 'uid',
          'field' => 'uid',
        ),
      ),
    ),
    'log_id' => array(
      'title' => t('Log Entry ID'),
      'help' => t('The sequence number of this log entry.'),
      // Information for displaying it
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      // Information for accepting it as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name field' => 'Log Id',
        'numeric' => TRUE,
      ),
      // Information for accepting it as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
      ),
      // Information for sorting on it.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'unixtime' => array(
      'title' => t('Log Time'),
      'help' => t('The time/date for this attempt?'),
      // Information for displaying the nid
      'field' => array(
        'handler' => 'views_handler_field_date',
        'click sortable' => TRUE,
      ),
      // Information for accepting it as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_date',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting it as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_date',
      ),
      // Information for sorting on it.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'once_correct' => array(
      'title' => t('Log Once Correct'),
      'help' => t('Has the user completed this question?'),
      // Information for displaying it
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      // Information for accepting it as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting it as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
      ),
      // Information for sorting on it.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'tries' => array(
      'title' => t('Log Tries'),
      'help' => t('Number of times the user gave an answer.'),
      // Information for displaying it
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      // Information for accepting it as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting it as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
      ),
      // Information for sorting on it.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'answer' => array(
      'title' => t('Logged Answer'),
      'help' => t('The answer the user gave for this attempt'),
      // Information for displaying the nid
      'field' => array(
        'handler' => 'views_handler_field',
        'click sortable' => TRUE,
      ),
      // Information for accepting a nid as an argument
      'argument' => array(
        'handler' => 'views_handler_argument_string',
        'name field' => 'Once Correct',
        'numeric' => TRUE,
      ),
      // Information for accepting a nid as a filter
      'filter' => array(
        'handler' => 'views_handler_filter_string',
      ),
      // Information for sorting on a nid.
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'nid' => array(
      'title' => t('Log Node Id'),
      'help' => t('A reference to the node of the question.'),
      // Because this is a foreign key to the {node} table. This allows us to
      // have, when the view is configured with this relationship, all the fields
      // for the related node available.
      'relationship' => array(
        'base' => 'node',
        'field' => 'nid',
        'handler' => 'views_handler_relationship',
        'label' => t('CqLog - Node relation'),
      ),
    ),
    'uid' => array(
      'title' => t('Log User Id'),
      'help' => t('A reference to the User.'),
      // Because this is a foreign key to the {node} table. This allows us to
      // have, when the view is configured with this relationship, all the fields
      // for the related node available.
      'relationship' => array(
        'base' => 'users',
        'field' => 'uid',
        'handler' => 'views_handler_relationship',
        'label' => t('CqLog - User relation'),
      ),
    ),
  );

  return $data;
}
