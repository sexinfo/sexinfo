<!--Popular Topics -->
<div class="span4 front-module">
  <h4 class="module-title">Popular Topics</h4>

  <div class="module-content">
    <div class="list-half">
      <a href="<?php print $base_path . "article/overview" ?>">Abortion</a>
      <a href="<?php print $base_path . "category/body" ?>">The Body</a>
      <a href="<?php print $base_path . "category/sexual-orientations" ?>">Sexual Orientations</a>
      <a href="<?php print $base_path . "category/pregnancy" ?>">Pregnancy</a>
      <a href="<?php print $base_path . "taxonomy/term/442/all" ?>">Sexual Activity</a>
      <a href="<?php print $base_path . "category/basics-sexuality" ?>">Basics of Sexuality</a>
    </div>

    <div class="list-half">
      <a href="<?php print $base_path . "article/sti-symptom-chart" ?>">Sexually Transmitted Infections</a>
      <a href="<?php print $base_path . "category/love-relationships/love" ?>">Love &amp; Relationships</a>
      <a href="<?php print $base_path . "category/sexual-difficulties/male-difficulty" ?>">Sexual Difficulties</a>
      <a href="<?php print $base_path . "taxonomy/term/447/all" ?>">Sexual Violence</a>
    </div>
  </div>

  <div class="module-footer">
    <a href="<?php print $base_path . "category" ?>">All Topics &raquo;</a>
  </div>
</div>



<!-- Frequently Asked Questions -->
<div class="span4 front-module">
  <h4 class="module-title">Frequently Asked Questions</h4>

  <div class="module-content">
    <div class="faq-image">
      <a href="http://www.soc.ucsb.edu/sexinfo/question/faq-how-do-i-increase-sexual-arousal">
      <img class="" src="<?php print path_to_theme() . '/images/modules/' . 'arousal.jpg'; ?>" />
      <div class="caption-slide">
        <h3>How Do I Increase Sexual Arousal?</h3></a>
        <p>There are many ways to increase your and your partner’s sexual arousal. Sexual arousal is&hellip;
        <a href="http://www.soc.ucsb.edu/sexinfo/question/faq-how-do-i-increase-sexual-arousal" class="more-button">Read More</a></p>
      </div>
    </div>
  </div>
=======
  <!-- Middle box
  ========================-->
  <div class="column-third">
    <!-- Frequently Asked Questions -->
    <h4 class="module-title">Frequently Asked Questions</h4>
    <div class="module">
      <div class="node-box">
        <div class="node-content">
          <div class="faq-image">
            <a href="http://www.soc.ucsb.edu/sexinfo/question/faq-how-do-i-increase-sexual-arousal"><img class="" src="<?php print path_to_theme() . '/images/modules/' . 'arousal.jpg'; ?>" />
            <div class="caption-slide">
              <h3>How Do I Increase Sexual Arousal?</h3></a>
              <p>There are many ways to increase your and your partner’s sexual arousal. Sexual arousal is&hellip;
              <a href="http://www.soc.ucsb.edu/sexinfo/question/faq-how-do-i-increase-sexual-arousal" class="more-button">Read More</a></p>
            </div><!-- .caption-slide -->
          </div><!-- .faq-image -->
        </div><!--.node-content-->
        <div class="node-footer">
          <a href="<?php print $base_path . "question" ?>">All Questions &raquo;</a>
        </div><!--.node-footer-->
      </div><!--.node-box-->
    </div><!-- .module -->
  </div><!-- .column-third -->
  <div class="module-footer">
    <a href="<?php print $base_path . "question" ?>">All Questions &raquo;</a>
  </div>
</div>



<!-- Quizzes -->
<div class="span4 front-module">
  <h4 class="module-title">Test Your Knowledge</h4>

  <div class="module-content ask-module">
    <p>Do you think you know it all? Are you an expert on masturbation, LGBTQ facts, paraphilias, or pregnancy and abortion? Quiz yourself <a href="<?php print $base_path . "quizzes" ?>">here</a> to see if you really are.
    
    <tbody id="quiz-browser-body" class="browser-table">
<?php
/**
 * @file
 * Handles the layout of the quiz question browser.
 *
 *
 * Variables available:
 * - $form
 */

// We need to separate the title and the checkbox. We make a custom options array...
$full_options = array();
foreach ($form['titles']['#options'] as $key => $value) {
  $full_options[$key] = $form['titles'][$key];
  $full_options[$key]['#title'] = '';
}

// We make the question rows
foreach ($form['titles']['#options'] as $key => $value): ?>
  <?php
  // Find nid and vid
  $matches = array();
  preg_match('/([0-9]+)-([0-9]+)/', $key, $matches);
  $quest_nid = $matches[1];
  $quest_vid = $matches[2]; ?>
  
  <tr class="quiz-question-browser-row" id="browser-<?php print $key ?>">
    <td width="35"><?php print drupal_render($full_options[$key]) ?> </td>
    <td>
      <?php print l($value, "node/513", array(
        'html' => TRUE,
        'query' => array('destination' => $_GET['q']),
        'attributes' => array('target' => 'blank')
      )); ?>
    </td>
    <td><?php print $form['types'][$key]['#value'] ?></td>
    <td><?php print $form['changed'][$key]['#value'] ?></td>
    <td><?php print $form['names'][$key]['#value'] ?></td>
  </tr>
<?php endforeach ?>

<?php if (count($form['titles']['#options']) == 0) {
  print t('No questions were found');
}
?>
</tbody>
    </p>
  </div>

  <div class="module-footer">
    <a href="<?php print $base_path . "quizzes" ?>">Take a quiz &raquo;</a>
  </div>
</div>
