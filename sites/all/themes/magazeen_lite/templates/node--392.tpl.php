<?php include 'utils/content_tag.php' ?>

<?php
  // Decode /data/topics.json as associative array
  function getTopics() {
    $fileData = file_get_contents("data/topics.json", true);
    return json_decode($fileData, true);
  }

  // TODO: remove this
  $GLOBALS['topics'] = getTopics();

  // Render a half-width topic box for a specified topic name
  //
  //  $size - The size of the section, 'half' or 'quarter'
  //  $topicName - Name of the topic JSON section, e.g. 'The Sexual Response Cycle'
  //
  // The heredoc can't be indented!
  // See http://php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
  function renderTopicSection($size, $topicName) {
    $topics = $GLOBALS['topics'];
    $topic  = $topics[$topicName];
    $image  = path_to_theme() . '/images/topics/' .  $topic['image'];

    // Pre-construct children list items to substitute into heredoc string
    // Ex: <li class="text-on-image-article"><a href="http://...">Sex Around The World</a></li>
    $children = "";
    foreach($topic['children'] as $name => $url) {
      $link = content_tag("a", $name, array("href" => $url));
      $children .= content_tag('li', $link, array('class' => 'text-on-image-article'));
    }

return <<<HTML
<div class="topic-{$size}">
  <div class="text-on-image" style="background-image: url('$image')">
    <div class="text-on-image-tint">
    <div class="text-on-image-text">{$topicName}</div>
      <ul class="text-on-image-articles">{$children}</ul>
    </div>
  </div>
</div>
HTML;
  }

  function renderTopicHalf($topicName) {
    return renderTopicSection('half', $topicName);
  }

  function renderTopicQuarter($topicName) {
    return renderTopicSection('quarter', $topicName);
  }

  function renderNav() {
      // Query DB for top level topics
      $topics = db_query('SELECT * FROM taxonomy_term_data A, taxonomy_term_hierarchy B WHERE A.tid=B.tid AND A.vid=3 AND B.parent=0 ORDER BY weight ASC');

      // Generate html from db result
      $result = <<<HTML
<ul class="topics-nav">
  <div class="header-container">
    <h4>Explore Our Topics!</h4>
  </div>
HTML;

      // For each topic, make a link in the list
      foreach($topics as $topic) {
        $result = $result . sprintf("<li><a href='#%s'>%s</a></li>", urlencode($topic->name), $topic->name);
      }

      // Return generated HTML
      return $result;
  }

  echo renderNav();
  ?>

<ul class="topics-nav">
  <div class="header-container">
    <h4>Explore Our Topics!</h4>
  </div>

  <li><a href="#basics_of_sexuality">Sex Across The Lifecycle</a></li>
  <li><a href="#the_body">The Body</a></li>
  <li><a href="#sexual_activity">Sexual Activity</a></li>
  <li><a href="#pregnancy">Pregnancy</a></li>
  <li><a href="#contraception">Birth Control</a></li>
  <li><a href="#abortion">Abortion</a></li>
  <li><a href="#sexually_transmitted_infections">Sexually Transmitted Infections (STIs)</a></li>
  <li><a href="#health">Health</a></li>
  <li><a href="#love_and_relationships">Love and Relationships</a></li>
  <li><a href="#sexual_orientations">Sexual Orientations</a></li>
  <li><a href="#sexual_difficulties">Sexual Difficulties</a></li>
  <li><a href="#sex_and_the_law">Sex and the Law</a></li>
  <li><a href="#beliefs_and_sexuality">Beliefs and Sexuality</a></li>
</ul>


<div class="topics-container">

  <div class="parent-topic" id="basics_of_sexuality">
    <h2>Sex Across the Lifecycle</h2>

    <div class="grid-left">
      <?php echo renderTopicHalf('Adolescent Sexuality') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicQuarter('Aging and the Sexual Response') ?>
      <?php echo renderTopicQuarter('Talking About Sex') ?>
    </div>
  </div><!-- .parent-topic -->

</div><!-- .topics-container -->
