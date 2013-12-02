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
    $image  = $topic['image'];

    // Pre-construct children list items to substitute into heredoc string
    // Ex: <li class="text-on-image-article"><a href="http://...">Sex Around The World</a></li>
    $children = "";
    foreach($topic['children'] as $name => $url) {
      $link = content_tag("a", $name, array("href" => $url));
      $children .= content_tag('li', $link, array('class' => 'text-on-image-article'));
    }

return <<<HTML
<div class="topic-{$size}">
  <div class="text-on-image" style="background: url('$image') no-repeat">
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

?>

<div class="topics-container">

  <div class="parent-topic">
    <h2>Basics of Sexuality</h2>

    <div class="grid-left">
      <?php echo renderTopicHalf('Sex In The Life Cycle') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicHalf('Talking About Sex') ?>
    </div>
  </div><!-- .parent-topic -->



  <div class="parent-topic">
    <h2>The Body</h2>

    <div class="grid-left">
      <?php echo renderTopicQuarter('Sex Determination') ?>
      <?php echo renderTopicQuarter('Sexual Response') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicQuarter('Female Body') ?>
      <?php echo renderTopicQuarter('Male Body') ?>
    </div>
  </div><!-- .parent-topic -->

</div><!-- .topics-container -->
