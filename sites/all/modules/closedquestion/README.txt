
-- SUMMARY --

The Drupal module ClosedQuestion adds the Closed Question node-type to Drupal
that takes an XML question definition and displays this question to users.
The ClosedQuestion-nodes are stand-alone and can be used anywhere a normal node
is used.

Features
 * Designed for practice: students can take as many attempts as they like.
 * Many feedback options, feedback can change with attempts.
 * Many question types.
 * Questions can be used anywhere.

Comparison with Quiz
Quiz is made for quizzes. This makes Quiz mainly suitable for testing knowledge,
less for learning. Quiz has:
 * One try per question.
 * Very limited feedback.
 * Limited number of question types.
 * Questions do not work outside of a quiz.


-- REQUIREMENTS --

ClosedQuestion needs php DOM support. On Redhat systems you will have to install
the package php-xml. Try:  yum install php-xml

ClosedQuestion depends on the libraries module.

Recommended are:
 * advanced_help for getting all the documentation.
   http://drupal.org/project/advanced_help
 * qtip for showing tooltips with descriptions in various question types.
   http://drupal.org/project/qtip


-- INSTALLATION --

* Install as usual, see http://drupal.org/node/70151 for further information.

* Create an input format for ClosedQuestion nodes.

* Turn the "Line break converter" rule _off_ for this input format.

* If you use collapse_text, turn _off_ the option:
  "Surround text with an empty form tag"

* Put jquery.json in sites/all/libraries/jquery-json
  get it from: http://code.google.com/p/jquery-json/
  This should result in one of the files (you might have to rename it):
  - sites/all/libraries/jquery-json/jquery.json.js
  - sites/all/libraries/jquery-json/jquery.json.min.js

* Put jquery.jstree  in sites/all/libraries/jquery-jstree
  get it from: http://www.jstree.com/
  This should result in the file and directory:
  - sites/all/libraries/jquery-jstree/jquery.jstree.js
  - sites/all/libraries/jquery-jstree/themes/

* Put jsDraw2d  in sites/all/libraries/jsDraw2D
  get it from: http://jsdraw2d.jsfiction.com/jsDraw2D.js
  This should result in the file and directory (note the capital letters):
  - sites/all/libraries/jsDraw2D/jsDraw2D.js

* To enable drag & drop / select&order questions on touch screen devices: 
  Put jQuery UI Touch Punch in sites/all/libraries/jquery-ui-touch-punch
  get it from: http://touchpunch.furf.com/
  This should result in the file and directory:
  sites/all/libraries/jquery-ui-touch-punch/jquery.ui.touch-punch.js

-- CONFIGURATION --

* None needed


-- USAGE --

* Create a new node of type Closed Question. In the field "Question XML" you can
  define the question. See examples in the advanced help.

* To use the url of images that are attached to the node, use:

  [attachurl:#]
  or
  [attachurl:filename]

  You can specify the image you want to use in two ways:
  - specifying #, which will display the #th uploaded file
  - specifying filename


-- CONTACT --

Current maintainers:
* Hylke van der Schaaf - hylke.vds@gmail.com
* Koos van der Kolk - koosvdkolk@gmail.com

-- DESIGN --

A QlosedQuestion node is a fully self-contained question that can be answered by
a user just as it is.

During node_load an object of type CqQuestionInterface is added as
  $node->question

During node_view $node->question->getOutput() is called, which returns a
complete Drupal form containing the question.

The submit handler for this form is
  closedquestion_submit_answer($form, &$form_state)
which redirects to
  $node->question->submitAnswer($form, $form_state);

If an external object like a ProteusQuiz node or LinearCase node wants to track
the progress of the user, it has several options:
 * Add a listener to the $node->question. This listener is called when the user
   answers the question correctly for the first time and whenever the question
   generates feedback.
 * Call isCorrect(), onceCorrect(), getTries() on $node->question.
 * Look at $node->question->getAnswer(), which returns a CqUserAnswer object
   that contains the answer of the user.

