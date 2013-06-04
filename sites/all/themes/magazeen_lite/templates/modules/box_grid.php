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
        <p>There are many ways to increase your and your partner’s sexual arousal. Sexual arousal is... <a href="http://www.soc.ucsb.edu/sexinfo/question/faq-how-do-i-increase-sexual-arousal">Read More &gt;</a>
        </p>
      </div>
    </div>
  </div>
</div>



<!-- Quizzes -->
<div class="span4 front-module">
  <h4 class="module-title">Test Your Knowledge</h4>

  <div class="module-content ask-module">
    <p>Do you think you know it all? Are you an expert on masturbation, LGBTQ facts, paraphilias, or pregnancy and abortion? Quiz yourself <a href="<?php print $base_path . "quizzes" ?>">here</a> to see if you really are.</p>
$handler = new stdClass();
$handler->disabled = FALSE; /* Edit this to true to make a default handler disabled initially */
$handler->api_version = 1;
$handler->name = 'page_test_your_knowledge_panel_context';
$handler->task = 'page';
$handler->subtask = 'test_your_knowledge';
$handler->handler = 'panel_context';
$handler->weight = 0;
$handler->conf = array(
  'title' => 'Panel',
  'no_blocks' => 0,
  'pipeline' => 'standard',
  'body_classes_to_remove' => '',
  'body_classes_to_add' => '',
  'css_id' => '',
  'css' => '',
  'contexts' => array(),
  'relationships' => array(),
);
$display = new panels_display();
$display->layout = 'onecol';
$display->layout_settings = array();
$display->panel_settings = array(
  'style_settings' => array(
    'default' => NULL,
    'middle' => NULL,
  ),
);
$display->cache = array();
$display->title = 'Test Your Knowledge';
$display->content = array();
$display->panels = array();
  $pane = new stdClass();
  $pane->pid = 'new-1';
  $pane->panel = 'middle';
  $pane->type = 'node';
  $pane->subtype = 'node';
  $pane->shown = TRUE;
  $pane->access = array();
  $pane->configuration = array(
    'nid' => '513',
    'links' => 0,
    'leave_node_title' => 0,
    'identifier' => '',
    'build_mode' => 'teaser',
    'link_node_title' => 0,
    'override_title' => 0,
    'override_title_text' => '',
  );
  $pane->cache = array();
  $pane->style = array(
    'settings' => NULL,
  );
  $pane->css = array();
  $pane->extras = array();
  $pane->position = 0;
  $pane->locks = array();
  $display->content['new-1'] = $pane;
  $display->panels['middle'][0] = 'new-1';
$display->hide_title = PANELS_TITLE_FIXED;
$display->title_pane = 'new-1';
$handler->conf['display'] = $display;
  </div>

  <div class="module-footer">
    <a href="<?php print $base_path . "quizzes" ?>">Take a quiz &raquo;</a>
  </div>
</div>
