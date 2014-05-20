<?php include 'utils/content_tag.php' ?>
<?php include_once 'utils/topics.php' ?>

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

  
  function renderNav($topics) {
    // Generate html from db result
    $result = <<<HTML
    <ul class="topics-nav">
      <div class="header-container">
        <h4>Explore Our Topics!</h4>
      </div>
HTML;

    // For each topic, make a link in the list
    foreach($topics as $topic) {
      $name = $topic['name'];
      $result = $result . sprintf("<li><a href='#%s'>%s</a></li>", urlencode($name), $name);
    }

    // Return generated HTML
    return $result . "</ul>";
  }

  function renderTopics($topics) {
    $result = "<div class=\"topics-container\">";

    // For each topic, make a link in the list
    foreach($topics as $topic) {
      $result = $result . renderTopic($topic);
    }

    // Return generated HTML
    return $result . "</div>";
  }


  function renderTopic($topic) {
    $name = $topic['name'];
    $target = urlencode($name);
    $sections = renderSections($topic['sections']);
    return <<<HTML
<div class="parent-topic" id="$target" class="js-masonry" data-masonry-options='{ "columnWidth": 200, "itemSelector": ".topic-quarter" }'>
  <h2>$name</h2>
  $sections
</div><!-- .parent-topic -->
HTML;
  }

  function renderSections($sections) {
    $left_html = '<div class="grid-left">';
    $right_html = '<div class="grid-right">';

    list($leftsections, $rightsections) = optimizeSectionLayout($sections);
    foreach ($leftsections as $section) {
      $left_html .= renderSection($section);
    }
    foreach ($rightsections as $section) {
      $right_html .= renderSection($section);
    }

    return $left_html . "</div>" . $right_html . "</div>" ;
  }

  function renderSection($section) {
    $children_html = '';
    $articles = $section['articles'];

    foreach($articles as $article) {
      $children_html = $children_html . sprintf("<li class='text-on-image-article'><a href=\"node/%d\">%s</a></li>", $article['nid'], $article['name']);
    }

    $section_name = $section['name'];
    $size = $section['rendersize'] == 1 ? 'quarter' : 'half';
    $image = 'sites/all/themes/magazeen_lite/images/topics/kinky_sex_paraphilia.jpg';
    $section_html = <<<HTML
<div class="topic-{$size}">
  <div class="text-on-image" style="background-image: url('$image')">
    <div class="text-on-image-tint">
    <div class="text-on-image-text">{$section_name}</div>
      <ul class="text-on-image-articles">{$children_html}</ul>
    </div>
  </div>
</div>
HTML;
    return $section_html;
  }

  $topics = generateTopics();
  echo renderNav($topics);
  echo renderTopics($topics);
  ?>
</div><!-- .topics-container -->
