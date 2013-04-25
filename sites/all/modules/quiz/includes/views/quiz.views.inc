<?php

/**
 * @file
 * This file provides metadata to the Views 2 API for views integration.
 *
 */
function quiz_views_handlers() {
  return array(
    'info' => array(
      'path' => QUIZ_VIEWS_DIR . '/handlers',
    ),
    'handlers' => array(
      // Fields
      'quiz_views_handler_field_takes' => array(
        'parent' => 'views_handler_field',
      ),
      'quiz_views_handler_field_time' => array(
        'parent' => 'views_handler_field',
      ),
      'quiz_views_handler_field_question_status' => array(
        'parent' => 'views_handler_field',
      ),
      'quiz_views_handler_field_user_quiz_state' => array(
        'parent' => 'views_handler_field',
      ),
      'quiz_views_handler_field_score_aggregate' => array(
        'parent' => 'views_handler_field_numeric',
      ),
      'quiz_views_handler_field_number_questions' => array(
        'parent' => 'views_handler_field',
      ),
      // Filters
      'quiz_views_handler_filter_question_status' => array(
        'parent' => 'views_handler_filter_in_operator',
      ),
      'quiz_views_handler_filter_quiz_nid' => array(
        'parent' => 'views_handler_filter',
      ),
      'quiz_views_handler_filter_user_quiz_state' => array(
        'parent' => 'views_handler_filter',
      ),
      'quiz_views_handler_filter_user_nullable' => array(
        'parent' => 'views_handler_filter',
      ),
      // Relationships
      'quiz_views_handler_relationship_vid_from_nid' => array(
        'parent' => 'views_handler_relationship',
      ),
      // Arguments
      'quiz_views_handler_argument_quiz_nid' => array(
        'parent' => 'views_handler_argument_numeric',
      ),
      'quiz_views_handler_argument_user_uid_nullable' => array(
        'parent' => 'views_handler_argument_user_uid',
      ),
    ),
  );
}

/**
 * Implementation of hook_views_data().
 */
function quiz_views_data() {
  $data = array();
  // Quiz Node Properties (additions to Node)
  $data['quiz_node_properties'] = array(

    // Table Definition
    'table' => array(
      'group' => t('Quiz'),
      // Base tables:
      'base' => array(
        'field' => 'vid',
        'title' => t('Quiz'),
        'help' => t('Quizzes are collections of questions that are taken and scored.'),
        'weight' => 0,
      ),

      // Allow attachment to a node:
      'join' => array(
        // This is vid because vid is always more specific. FIXME changed to try to fix the joining problems
        'node' => array(
          'left_field' => 'nid',
          'field' => 'nid',
          'type' => 'INNER',
        ),
        'node_revisions' => array(
          'left_field' => 'vid',
          'field' => 'vid',
          'type' => 'INNER',
        ),
      ),
    ),

    // Field definitions
    'nid' => array(
      'title' => t('Quiz Node NID'),
      'help' => t('The ID of the Quiz Node.'),
      'field' => array(
        'handler' => 'views_handler_field',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'quiz_views_handler_argument_quiz_nid',
        'name field' => 'title',
        'name table' => 'node_revisions',
        'numeric' => TRUE,
        'validate type' => 'vid',
        'nid field' => 'nid',
      ),
      'filter' => array(
        'handler' => 'quiz_views_handler_filter_quiz_nid',
        'title' => t('Quiz Version'),
        'help' => t('Filter results to a specific subset of quiz versions.'),
        'group by' => 'vid',
        // 'secondary group by' => 'vid',
      ),
      'sort' => array('handler' => 'views_handler_sort'),
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node',
        'field' => 'nid',
        'label' => t('node'),
      ),
    ),
    'vid' => array(
      'title' => t('Quiz Node VID'),
      'help' => t('The Revision ID of the Quiz Node.'),
      'field' => array(
        'handler' => 'views_handler_field',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name field' => 'title',
        'name table' => 'node_revisions',
        'numeric' => TRUE,
        'validate type' => 'vid',
      ),
      // Related to NODE
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node_revisions',
        'field' => 'vid',
        'label' => t('quiz node revision'),
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'number_of_random_questions' => array(
      'title' => t('Number of random questions'),
      'help' => t('The number of questions on this quiz that will be randomly selected.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        //'name_field' => 'title',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'pass_rate' => array(
      'title' => t('Pass rate'),
      'help' => t('The percentage of questions that must be correct before the quiz is passed.'),
      'field' => array('handler' => 'views_handler_field_numeric', 'click sortable' => TRUE),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        //'name_field' => 'title',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'shuffle' => array(
      'title' => t('Randomization'),
      'help' => t('Indicates how questions will be randomized.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
    'sort' => array('handler' => 'views_handler_sort'),
    ),
    'backwards_navigation' => array(
      'title' => t('Backwards navigation'),
      'help' => t('Indicates whether quiz takers can go back to previous questions.'),
      'field' => array(
        'handler' => 'views_handler_field_boolean',
        'click sortable' => TRUE,
      ),
    'sort' => array('handler' => 'views_handler_sort'),
    ),
    'repeat_until_correct' => array(
      'title' => t('Repeat until correct'),
      'help' => t('Require the user to re-try the question until they answer it correctly.'),
      'field' => array(
        'handler' => 'views_handler_field_boolean',
        'click sortable' => TRUE,
      ),
    'sort' => array('handler' => 'views_handler_sort'),
    ),

    'feedback_time' => array(
      'title' => t('Feedback Time'),
      'help' => t('Indicates whether quiz takers will get feedback after every question.'),
      'field' => array(
        'handler' => 'views_handler_field_boolean',
        'click sortable' => TRUE,
      ),
    'sort' => array('handler' => 'views_handler_sort'),
    ),
    'time_limit' => array(
      'title' => t('Time limit'),
      'help' => t('The time limit on a quiz.'),
      'field' => array(
        'handler' => 'quiz_views_handler_field_time',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'quiz_open' => array(
      'title' => t('Open time'),
      'help' => t('The first time a new quiz can be taken.'),
      'field' => array(
        'handler' => 'views_handler_field_date',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_date',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort_date'),
    ),
    'quiz_close' => array(
      'title' => t('Close time'),
      'help' => t('The last time a new quiz can be taken.'),
      'field' => array(
        'handler' => 'views_handler_field_date',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_date',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort_date'),
    ),
    'quiz_always' => array(
      'title' => t('Always Available'),
      'help' => t('Indicates whether quiz is always available (Open and Close time are ignored).'),
      'field' => array(
        'handler' => 'views_handler_field_boolean',
        'click sortable' => TRUE,
      ),
    'sort' => array('handler' => 'views_handler_sort'),
    ),
    'takes' => array(
      'title' => t('Allowed takes'),
      'help' => t('The number of times a quiz can be taken.'),
      'field' => array(
        'handler' => 'quiz_views_handler_field_takes',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'tid' => array(
      'title' => t('Term ID'),
      'help' => t('The term ID used to select questions based on taxonomy.'),
      'field' => array(
        'handler' => 'views_handler_field_taxonomy',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'numeric' => TRUE,
      ),
      'sort' => array('handler' => 'views_handler_sort'),

      // Related to TERM
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'term_data',
        'field' => 'tid',
        'label' => t('term'),
      ),
    ), // End tid
  ); // End quiz_node_properties

  $data['quiz_node_relationship'] = array(
    'table' => array(
      'group' => t('Quiz Questions'),
      'join' => array(
        // This is vid because vid is always more specific. We never work with
        // nid.
        'quiz_node_properties' => array(
          'left_field' => 'vid',
          'field' => 'parent_vid',
          'type' => 'INNER',
        ),
      ),
    ),
    'parent_nid' => array(
      'title' => t('Quiz Node ID'),
      'help' => t('The node id of the quiz to which the question is attached.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        // This'll get us the node title for the title, which may or may not be
        // preferable; that's the only difference between this handler and the
        // standard numeric handler
        'handler' => 'views_handler_argument_node_nid',
        'numeric' => TRUE,
        'validate type' => 'nid',
      ),
      'filter' => array(
        'handler' => 'quiz_views_handler_filter_quiz_nid',
        'help' => t('Filter results to a particular subset of quiz versions.'),
        'nid field' => 'parent_nid',
        'vid field' => 'parent_vid',
        'secondary group' => 'child_nid',
      ),
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node',
        'field' => 'nid',
        'label' => t('Quiz node id'),
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'parent_vid' => array(
      'title' => t('Quiz Node VID'),
      'help' => t('The node revision id of the quiz to which the question is attached.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        // This'll get us the node title for the title, which may or may not be preferable
        'handler' => 'views_handler_argument_node_vid',
        'numeric' => TRUE,
        'validate type' => 'vid',
      ),
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node_revisions',
        'field' => 'vid',
        'label' => t('Quiz node revision'),
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'child_nid' => array(
      'title' => t('Question Node ID'),
      'help' => t('The question node id.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        // This'll get us the node title for the title, which may or may not be
        // preferable; that's the only difference between this handler and the
        // standard numeric handler
        'handler' => 'views_handler_argument_node_nid',
        'numeric' => TRUE,
        'validate type' => 'nid',
      ),
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node',
        'field' => 'nid',
        'label' => t('Question node nid'),
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'child_vid' => array(
      'title' => t('Question Node VID'),
      'help' => t('The question node revision id.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        // This'll get us the node title for the title, which may or may not be preferable
        'handler' => 'views_handler_argument_node_vid',
        'numeric' => TRUE,
        'validate type' => 'vid',
      ),
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node_revisions',
        'field' => 'vid',
        'label' => t('Question node revision'),
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
    ),
    'question_status' => array(
      'title' => t('Question Status'),
      'help' => t('Field indicating the state of a question for the relevant quiz.'),
      'field' => array(
        'handler' => 'quiz_views_handler_field_question_status',
        'click sortable' => TRUE,
      ),
      'filter' => array(
        'handler' => 'quiz_views_handler_filter_question_status',
        'help' => t('Filter results based on the question\'s status in the relevant quiz.'),
      ),
      'sort' => array(
        'handler' => 'views_handler_sort',
      ),
      'number_questions' => array(
        'title' => t('Number of Questions'),
        'help' => t('The number of questions assigned to the quiz.'),
        'field' => array(
          'handler' => 'quiz_views_handler_field_number_questions',
        ),
        'sort' => array(
          'handler' => 'views_handler_sort',
        )
      ),
    ),
  ); // End quiz_node_relationship

  $data['quiz_node_results'] = array(
    // Table Definition
    'table' => array(
      'group' => 'Quiz Results',
      'join' => array(
        // This is vid because vid is always more specific.
        'quiz_node_properties' => array(
          'left_field' => 'vid',
          'field' => 'vid',
          'type' => 'LEFT', // I think...
        ),
      ),
    ),

    // Field descriptions:
    'result_id' => array(
      'title' => t('Result ID'),
      'help' => t('The ID identifying a particular set of quiz results. This ID is unique with respect to the quiz nid and vid of the quiz, uid of the quizee, and the take of the quiz.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'name_field' => 'title',
        'numeric' => TRUE,
        'validate type' => 'result_id',
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'nid' => array(
      'real field' => 'vid',
      'title' => t('Quiz Node NID'),
      'help' => t('The ID of the Quiz Node.'),
      'argument' => array(
        'handler' => 'quiz_views_handler_argument_quiz_nid',
        //'name_field' => 'title',
        'numeric' => TRUE,
        'validate type' => 'vid',
      ),
      'filter' => array(
        'handler' => 'quiz_views_handler_filter_quiz_nid',
        'help' => t('Filter results to a particular subset of quiz versions.'),
        'nid field' => 'nid',
        'vid field' => 'vid',
      ),
      'sort' => array('handler' => 'views_handler_sort'),
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node',
        'field' => 'nid',
        'label' => t('node'),
      ),
    ),
    'vid' => array(
      'title' => t('Quiz Node VID'),
      'help' => t('The Version ID of the Quiz Node.'),
      'field' => array(
        'handler' => 'views_handler_field',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        //'name_field' => 'title',
        'numeric' => TRUE,
        'validate type' => 'vid',
      ),
      // Related to NODE
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'node_revisions',
        'field' => 'vid',
        'label' => t('node revision'),
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'uid' => array(
      'title' => t('User'),
      'help' => t('The ID of the user who took this quiz.'),
      'field' => array(
        'handler' => 'views_handler_field_user',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'quiz_views_handler_argument_user_uid_nullable',
        'numeric' => TRUE,
        'validate type' => 'nid',
        'name field' => 'name',
        'name table' => 'users',
      ),
      'filter' => array(
        'handler' => 'quiz_views_handler_filter_user_nullable',
        'help' => t('Filter on the user who owns the quiz results.'),
      ),
      'sort' => array('handler' => 'views_handler_sort'),
      // Related to USERS
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'users',
        'field' => 'uid',
        'label' => t('user'),
      ),
    ),
    'time_start' => array(
      'title' => t('Quiz Start Time'),
      'help' => t('Time the quiz was started.'),
      'field' => array(
        'handler' => 'views_handler_field_date',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_date',
        //'name_field' => 'title',
        'numeric' => TRUE,
        'validate type' => 'nid',
      ),
      'sort' => array('handler' => 'views_handler_sort_date'),
    ),
    'time_end' => array(
      'title' => t('Quiz End Time'),
      'help' => t('Time the quiz was finished.'),
      'field' => array(
        'handler' => 'views_handler_field_date',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_date',
        //'name_field' => 'title',
        'numeric' => TRUE,
        'validate type' => 'nid',
      ),
      'sort' => array('handler' => 'views_handler_sort_date'),
      'filter' => array(
        'handler' => 'views_handler_filter_date',
        'allow empty' => TRUE,
      ),
    ),
    'score' => array(
      'title' => t('Score'),
      'help' => t('Score on the Quiz.'),
      'field' => array(
        'handler' => 'views_handler_field_numeric',
        'click sortable' => TRUE,
      ),
      'argument' => array(
        'handler' => 'views_handler_argument_numeric',
        'numeric' => TRUE,
        'validate type' => 'nid',
      ),
      'sort' => array('handler' => 'views_handler_sort'),
      'filter' => array(
        'handler' => 'views_handler_filter_numeric',
        'help' => t('Filter quiz results by Score.'),
        'allow empty' => TRUE,
      ),
    ),
    'is_invalid' => array(
      'title' => t('Is Invalid'),
      'help' => t('Indicates whether or not a quiz result is valid. This is a simple database boolean; the meaning of the "valid" flag should vary according to your use case.'),
      'field' => array(
        'handler' => 'views_handler_field_boolean',
      ),
      'filter' => array(
        'handler' => 'views_handler_filter_boolean_operator',
      ),
      'sort' => array('handler' => 'views_handler_sort'),
    ),
    'score_aggregate' => array(
      'title' => t('Score Aggregations'),
      'help' => t('Aggregate scores on quizzes using a variety of different algorithms.'),
      'real field' => 'score',
      'field' => array(
        'handler' => 'quiz_views_handler_field_score_aggregate',
        'float' => TRUE,
        'group field' => 'nid',
      ),
    ),
    'quiz_state' => array(
      'title' => t('Quiz State'),
      'help' => t('The state of the quiz for the provided user. Calculated on the fly; can be "Finished," "In Progress," or "Not Started".'),
      'field' => array(
        'handler' => 'quiz_views_handler_field_user_quiz_state',
        'click sortable' => TRUE,
      ),
      'filter' => array(
        'handler' => 'quiz_views_handler_filter_user_quiz_state',
        'help' => t('Filter the results based on the state of quizzes with respect to users (i.e., whether they have started, finished, or are in progress on the quiz).'),
      ),
    )
  ); // End quiz_node_results
  return $data;
}
