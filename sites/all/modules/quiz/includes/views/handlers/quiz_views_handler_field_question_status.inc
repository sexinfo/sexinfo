<?php

/**
 * @file
 * Views field handler that translates question status integer constants (as
 * defined in quiz.module) into their human-readable string counterparts.
 */
class quiz_views_handler_field_question_status extends views_handler_field {
  var $text_map = array();

<<<<<<< HEAD
  function pre_render(&$values) {
=======
  function pre_render($values) {
>>>>>>> a20eda4303412d09a1a1ea545ed9255115fd0ad2
    $this->text_map = array(
      QUESTION_RANDOM => t('Random'), // 'Random-ly' better?
      QUESTION_ALWAYS => t('Always'),
      QUESTION_NEVER => t('Never'),
    );
  }

  function render($values) {
    return $this->text_map[$values->{$this->field_alias}];
  }
}
