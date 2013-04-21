<?php

/**
 * @file
 * EvalMath - PHP Class to safely evaluate math expressions
 *
 * Copyright (C) 2006-2007 Zack Bloom
 * Miles Kaufmann - EvalMath Class Library
 * Hylke van der Schaaf - ClosedQeustion/Drupal integration
 *
 * Modifications for use in ClosedQuestion:
 * - Function handling rewritten to allow functions with 0 and 2 or more
 *   arguments.
 * - Removed uses of eval since php supports variable functions.
 * - Added lcg_value & random function.
 * - Added round and 2-argument number_format (nf) function.
 * - Added min, max, ceil, floor.
 * - Added setVars method to load variables.
 * - Trigger now throws drupal error messages instead of php errors.
 * - Wrapped messages in t().
 * - Converted constructor to __construct().
 * - Added docs to fields and methods.
 * - Changed use of var to private, all properties are private now.
 * - Added getters and setters for some now-private properties.
 * - Public/Private modifiers for all functions.
 * - More descriptive variable names.
 *
 */
class EvalMath {

  /**
   * Flag to indicate wether errors should be shown or suppressed.
   *
   * @var boolean
   */
  private $suppressErrors = FALSE;
  /**
   * The last error that was generated.
   *
   * @var string
   */
  private $lastError = NULL;
  /**
   * The list of currently available variables and constants.
   *
   * @var array of name/value pairs
   */
  private $variables = array('e' => 2.71, 'pi' => 3.14);
  /**
   * The list of currently available user-defined functions.
   *
   * @var array
   */
  private $funcUser = array();
  /**
   * The list of constant names.
   *
   * @var array of string
   */
  private $constants = array('e', 'pi');
  /**
   * The list of build-in functions. Each item has the function name as key
   * and the argument count as value.
   *
   * @var array
   */
  private $funcBuildIn = array(
    'sin' => 1, 'sinh' => 1, 'asin' => 1, 'asinh' => 1,
    'cos' => 1, 'cosh' => 1, 'acos' => 1, 'acosh' => 1,
    'tan' => 1, 'tanh' => 1, 'atan' => 1, 'atanh' => 1,
    'sqrt' => 1, 'abs' => 1, 'log' => 1,
    'round' => 2, 'floor' => 1, 'ceil' => 1, 'min' => 2, 'max' => 2,
    'number_format' => 2,
    'lcg_value' => 0,
  );
  /**
   * A list of function names that are aliases of other functions.
   * Both the function and the alias needs to be in the $fb table for now.
   *
   * @var array of string=>string key/value pairs
   */
  private $funcAliases = array(
    'ln' => 'log',
    'arcsin' => 'asin',
    'arccos' => 'acos',
    'arctan' => 'atan',
    'arcsinh' => 'asinh',
    'arccosh' => 'acosh',
    'arctanh' => 'atanh',
    'random' => 'lcg_value',
    'nf' => 'number_format',
  );

  /**
   * Constructs a new EvalMath object.
   */
  function __construct() {
    $this->variables['pi'] = pi();
    $this->variables['e'] = exp(1);
  }

  /**
   * Evaluate the given expression and return the result.
   * This is an alias for evaluate().
   *
   * @param string $expr
   *   The expression to evaluate.
   *
   * @return number
   *   The result of the evaluation.
   */
  public function e($expr) {
    return $this->evaluate($expr);
  }

  /**
   * Evaluate the given expression and return the result.
   *
   * @param string $expr
   *   The expression to evaluate.
   *
   * @return number
   *   The result of the evaluation.
   */
  public function evaluate($expr) {
    $this->lastError = NULL;
    $expr = trim(drupal_strtolower($expr));

    if (drupal_substr($expr, -1, 1) == ';') {
      $expr = drupal_substr($expr, 0, drupal_strlen($expr) - 1);
    }

    if (preg_match('/^\s*([a-zA-Z]\w*)\s*=\s*(.+)$/', $expr, $matches)) {
      if (in_array($matches[1], $this->constants)) {
        return $this->trigger(t('cannot assign to constant "%m"', array('%m' => $matches[1])));
      }

      if (($tmp = $this->postfixEvaluate($this->infixToPostfix($matches[2]))) === FALSE) {
        return FALSE;
      }

      $this->variables[$matches[1]] = $tmp;

      return $this->variables[$matches[1]];
    }
    elseif (preg_match('/^\s*([a-zA-Z]\w*)\s*\(\s*([a-zA-Z]\w*(?:\s*,\s*[a-zA-Z]\w*)*)\s*\)\s*=\s*(.+)$/', $expr, $matches)) {
      $fnn = $matches[1];

      if (array_key_exists($fnn, $this->funcAliases)) { // Check if the function is an alias
        $fnn = $this->funcAliases[$fnn];
      }
      if (array_key_exists($fnn, $this->funcBuildIn)) {
        return $this->trigger(t('cannot redefine built-in function "%m()"', array('%m' => $matches[1])));
      }

      $args = explode(",", preg_replace("/\s+/", "", $matches[2]));

      $stack = $this->infixToPostfix($matches[3]);
      if ($stack === FALSE) {
        return FALSE;
      }

      for ($i = 0; $i < count($stack); $i++) {
        $token = $stack[$i];

        if (preg_match('/^[a-zA-Z]\w*$/', $token) && !in_array($token, $args)) {
          if (array_key_exists($token, $this->variables)) {
            $stack[$i] = $this->variables[$token];
          }
          else {
            return $this->trigger(t('undefined variable "%t" in function definition', array('%t' => $token)));
          }
        }
      }

      $this->funcUser[$fnn] = array('args' => $args, 'func' => $stack, 'def' => $matches[3]);

      return TRUE;
    }
    else {
      return $this->postfixEvaluate($this->infixToPostfix($expr));
    }
  }

  /**
   * Returns an array of all user-defined variables, with their values.
   *
   * @return array
   *   A list of all user-defined variables as name=>value pairs.
   */
  public function getVars() {
    $output = $this->variables;
    unset($output['pi']);
    unset($output['e']);

    return $output;
  }

  /**
   * Adds the given variables to the interal set of user-defined variables.
   *
   * @param array $vars
   *   An array of name/value pairs, as given by getVars()
   */
  public function setVars($vars) {
    $this->variables = array_merge($this->variables, $vars);
  }

  /**
   * Returns an array of all user-defined functions.
   *
   * @return array of string
   *   A list of all user-defined functions.
   */
  public function getFuncs() {
    $output = array();
    foreach ($this->funcUser as $fnn => $dat) {
      $output[$fnn . '(' . implode(',', $dat['args']) . ')'] = $dat['def'];
    }
    return $output;
  }

  /**
   * Convert infix to postfix notation
   *
   * @param string $expr
   *   The expression to convert
   *
   * @return array
   *   An array with the elements of the expression
   */
  private function infixToPostfix($expr) {
    $index = 0;
    $stack = new EvalMathStack;
    $output = array();
    $ops = array('+', '-', '*', '/', '^', '_');
    $opsRight = array('+' => 0, '-' => 0, '*' => 0, '/' => 0, '^' => 1);
    $opsPrecedence = array('+' => 0, '-' => 0, '*' => 1, '/' => 1, '_' => 1, '^' => 2);

    $expectingOp = FALSE;
    $inFunction = 0;
    $funcHasArg = array();

    if (preg_match("/[^\w\s+*^\/()\.,-]/", $expr, $matches)) {
      return $this->trigger(t('illegal character "%c"', array('%c' => $matches[0])));
    }

    while (1) {
      $op = drupal_substr($expr, $index, 1);
      $ex = preg_match('/^([a-z]\w*\(?|\d+(?:\.\d*)?(?:[Ee][+-]?\d*)?|\.\d+|\()/', drupal_substr($expr, $index), $match);

      if ($op == '-' and !$expectingOp) {
        $stack->push('_');
        $index++;
      }
      elseif ($op == '_') {
        return $this->trigger(t('illegal character "_"'));
      }
      elseif ((in_array($op, $ops) || $ex) && $expectingOp) {
        if ($ex) {
          $op = '*';
          $index--;
        }

        while ($stack->getCount() > 0 && ($o2 = $stack->last()) && in_array($o2, $ops) && ($opsRight[$op] ? $opsPrecedence[$op] < $opsPrecedence[$o2] : $opsPrecedence[$op] <= $opsPrecedence[$o2])) {
          $output[] = $stack->pop();
        }

        $stack->push($op);
        $index++;
        $expectingOp = FALSE;
      }
      elseif ($op == ')' && ($expectingOp || $inFunction)) {
        while (($o2 = $stack->pop()) != '(') {
          if (is_NULL($o2)) {
            return $this->trigger(t('unexpected ) found'));
          }
          else {
            $output[] = $o2;
          }
        }

        if (preg_match("/^([a-z]\w*)\($/", $stack->last(2), $matches)) {
          $fnn = $matches[1];
          $argCount = $stack->pop();
          if ($funcHasArg[$inFunction]) {
            $argCount++;
          }
          $output[] = $stack->pop(); // pop the function and push onto the output
          if (array_key_exists($fnn, $this->funcAliases)) { // Check if the function is an alias
            $fnn = $this->funcAliases[$fnn];
          }
          if (array_key_exists($fnn, $this->funcBuildIn)) { // check the argument count
            if ($argCount != $this->funcBuildIn[$fnn]) {
              return $this->trigger(t('wrong number of arguments (@gc given, @ec expected)', array('@gc' => $argCount, '@ec' => $this->funcBuildIn[$fnn])));
            }
          }
          elseif (array_key_exists($fnn, $this->funcUser)) {
            if ($argCount != count($this->funcUser[$fnn]['args'])) {
              return $this->trigger(t('wrong number of arguments (@gc given, @ec expected)', array('@gc' => $argCount, '@ec' => count($this->funcUser[$fnn]['args']))));
            }
          }
          else {
            return $this->trigger(t('internal error, not a function'));
          }
          $inFunction--;
        }

        $index++;
      }
      elseif ($op == ',' and $expectingOp) {
        while (($o2 = $stack->pop()) != '(') {
          if (is_NULL($o2)) {
            return $this->trigger(t('unexpected , found'));
          }
          else {
            $output[] = $o2;
          }
        }

        if (!preg_match("/^([a-z]\w*)\($/", $stack->last(2), $matches)) {
          return $this->trigger(t('unexpected , found'));
        }

        $stack->push($stack->pop() + 1);
        $stack->push('(');
        $index++;
        $expectingOp = FALSE;
      }
      elseif ($op == '(' and !$expectingOp) {
        $stack->push('(');
        $index++;
        $allow_neg = TRUE;
      }
      elseif ($ex and !$expectingOp) {
        $expectingOp = TRUE;
        $val = $match[1];

        if (preg_match("/^([a-z]\w*)\($/", $val, $matches)) {
          if (array_key_exists($matches[1], $this->funcAliases) ||
              array_key_exists($matches[1], $this->funcBuildIn) ||
              array_key_exists($matches[1], $this->funcUser)) {
            $stack->push($val);
            $stack->push(0);
            $stack->push('(');
            $expectingOp = FALSE;
            // If we are in a function, it'll have at least one argument, this one.
            if ($inFunction) {
              $funcHasArg[$inFunction] = TRUE;
            }
            $inFunction++;
            $funcHasArg[$inFunction] = FALSE;
          }
          else {
            if (!array_key_exists($matches[1], $this->variables)) {
              return $this->trigger(t('unknown variable or function "%f"', array('%f' => $matches[1])));
            }
            $val = $matches[1];
            $output[] = $val;
            // If we are in a function, it'll have at least one argument, this one.
            if ($inFunction) {
              $funcHasArg[$inFunction] = TRUE;
            }
          }
        }
        else {
          $output[] = $val;
          // If we are in a function, it'll have at least one argument, this one.
          if ($inFunction) {
            $funcHasArg[$inFunction] = TRUE;
          }
        }

        $index += drupal_strlen($val);
      }
      elseif ($op == ')') {
        return $this->trigger(t('unexpected ) found'));
      }
      elseif (in_array($op, $ops) and !$expectingOp) {
        return $this->trigger(t('unexpected operator "%op"', array('%op' => $op)));
      }
      else {
        return $this->trigger(t('an unexpected error occured'));
      }

      if ($index == drupal_strlen($expr)) {
        if (in_array($op, $ops)) {
          return $this->trigger(t('operator "%op" lacks operand', array('%op' => $op)));
        }
        else {
          break;
        }
      }

      while (drupal_substr($expr, $index, 1) == ' ') {
        $index++;
      }
    }

    while (!is_NULL($op = $stack->pop())) {
      if ($op == '(') {
        return $this->trigger(t('expecting ) but none found'));
      }

      $output[] = $op;
    }

    return $output;
  }

  /**
   * Evaluate postfix notation
   *
   * @param array $tokens
   *   The list of tokens that make up the expression.
   * @param array $vars
   *   The list of variables set previously.
   *
   * @return number
   *   The final result of the evaluation.
   */
  private function postfixEvaluate($tokens, $vars = array()) {
    if ($tokens == FALSE) {
      return FALSE;
    }

    $stack = new EvalMathStack;

    foreach ($tokens as $token) {
      if (in_array($token, array('+', '-', '*', '/', '^'))) {
        if (is_NULL($op2 = $stack->pop())) {
          return $this->trigger(t('internal error'));
        }

        if (is_NULL($op1 = $stack->pop())) {
          return $this->trigger(t('internal error'));
        }

        switch ($token) {
          case '+':
            $stack->push($op1 + $op2);
            break;

          case '-':
            $stack->push($op1 - $op2);
            break;

          case '*':
            $stack->push($op1 * $op2);
            break;

          case '/':
            if ($op2 == 0) {
              return $this->trigger(t('division by zero'));
            }
            $stack->push($op1 / $op2);
            break;

          case '^':
            $stack->push(pow($op1, $op2));
            break;
        }
      }
      elseif ($token == "_") {
        $stack->push(-1 * $stack->pop());
      }
      elseif (preg_match("/^([a-z]\w*)\($/", $token, $matches)) {
        $fnn = $matches[1];
        if (array_key_exists($fnn, $this->funcAliases)) {
          $fnn = $this->funcAliases[$fnn];
        }
        if (array_key_exists($fnn, $this->funcBuildIn)) {
          $argCount = $this->funcBuildIn[$fnn];
          $args = array();
          for ($i = $argCount; $i > 0; $i--) {
            $arg = $stack->pop();
            if (is_NULL($arg)) {
              return $this->trigger(t('internal error: argument is null'));
            }
            $args[] = $arg;
            $args = array_reverse($args);
          }
          $stack->push(call_user_func_array($fnn, $args));
        }
        elseif (array_key_exists($fnn, $this->funcUser)) {
          $args = array();

          for ($i = count($this->funcUser[$fnn]['args']) - 1; $i >= 0; $i--) {
            if (is_NULL($args[$this->funcUser[$fnn]['args'][$i]] = $stack->pop())) {
              return $this->trigger(t('internal error: argument is null'));
            }
          }

          $stack->push($this->postfixEvaluate($this->funcUser[$fnn]['func'], $args));
        }
      }
      else {
        if (is_numeric($token)) {
          $stack->push($token);
        }
        elseif (array_key_exists($token, $this->variables)) {
          $stack->push($this->variables[$token]);
        }
        elseif (array_key_exists($token, $vars)) {
          $stack->push($vars[$token]);
        }
        else {
          return $this->trigger(t('undefined variable "%token"', array('%token' => $token)));
        }
      }
    }

    if ($stack->getCount() != 1) {
      return $this->trigger(t('internal error, stack not empty'));
    }

    return $stack->pop();
  }

  /**
   * Trigger an error, but nicely, if need be.
   *
   * @param string $msg
   *   The message that describes the error.
   *
   * @return boolean
   *   Always returns FALSE
   */
  private function trigger($msg) {
    $this->lastError = $msg;

    if (!$this->suppressErrors) {
      drupal_set_message(t('Math evaluation error: %msg', array('%msg' => $msg)), 'warning');
    }

    return FALSE;
  }

  /**
   * Getter for the suppressErrors property that indicates whether errors are
   * suppressed or returned in Drupal messages.
   *
   * @return boolean
   *   The value of suppressErrors
   */
  public function getSuppressErrors() {
    return $this->suppressErrors;
  }

  /**
   * Setter for the suppressErrors property that indicates whether errors are
   * suppressed or returned in Drupal messages.
   *
   * @param boolean $suppressErrors
   *   The new value for suppressErrors.
   */
  public function setSuppressErrors($suppressErrors) {
    $this->suppressErrors = $suppressErrors;
  }

  /**
   * Returns the last error message that was generated.
   *
   * @return string
   *   The last error message
   */
  public function getLastError() {
    return $this->lastError;
  }

}

/**
 * A stack implementation for internal use.
 */
class EvalMathStack {

  /**
   * The actual stack.
   *
   * @var array
   */
  private $stack = array();
  /**
   * The number of items on the stack.
   *
   * @var int
   */
  private $count = 0;

  /**
   * Put an item on the stack.
   *
   * @param mixed $val
   *   The item to put on the stack.
   */
  public function push($val) {
    $this->stack[$this->count] = $val;
    $this->count++;
  }

  /**
   * pop an item from the stack.
   *
   * @return mixed
   *   The last item that was put on the stack, or NULL if no items are on the
   *   stack.
   */
  public function pop() {
    if ($this->count > 0) {
      $this->count--;

      return $this->stack[$this->count];
    }

    return NULL;
  }

  /**
   * Return the item that is n places from the end of the stack.
   *
   * @param int $n
   *   The distance from the end to look. $n=1 is the last item on the stack.
   *
   * @return mixed
   *   The item at distance $n from the end of the stack.
   */
  public function last($n=1) {
    return $this->stack[$this->count - $n];
  }

  /**
   * Returns the number of items on the stack.
   *
   * @return int
   *   The number of items on the stack.
   */
  public function getCount() {
    return $this->count;
  }

}
