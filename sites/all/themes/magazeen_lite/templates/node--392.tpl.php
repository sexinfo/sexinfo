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

  <div class="parent-topic" id="basics_of_sexuality">
    <h2>Basics of Sexuality</h2>

    <div class="grid-left">
      <?php echo renderTopicQuarter('An Overview of Sexuality') ?>
      <?php echo renderTopicQuarter('Puberty') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicQuarter('Aging and the Sexual Response') ?>
      <?php echo renderTopicQuarter('Talking About Sex') ?>
    </div>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="the_body">
    <h2>The Body</h2>

    <div class="grid-left">
      <?php echo renderTopicQuarter('Female Reproductive System') ?>
      <?php echo renderTopicQuarter('Male Reproductive System') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicQuarter('Sexual Response') ?>
      <?php echo renderTopicQuarter('Sex Determination') ?>
    </div>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="sexual_activity">
    <h2>Sexual Activity</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Masturbation') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicQuarter('Sex With Others') ?>
        <?php echo renderTopicQuarter('Kinky Sex and Paraphilia') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Spice Up Your Sex Life') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Sex Under the Influence') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="pregnancy">
    <h2>Pregnancy</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Getting Pregnant') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('During Pregnancy') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicQuarter('Adolescent Pregnancy') ?>
        <?php echo renderTopicQuarter('Risks While Pregnant') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('After Pregnancy') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="contraception">
    <h2>Contraception</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Birth Control Overview') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicQuarter('Barrier Methods') ?>
        <?php echo renderTopicQuarter('Natural Methods') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Hormonal Methods') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Other Methods') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="abortion">
    <h2>Abortion</h2>

    <div class="grid-left">
      <?php echo renderTopicQuarter('Alternative Options') ?>
      <?php echo renderTopicQuarter('Types of Abortion') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicHalf('Making the Decision') ?>
    </div>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="sexually_transmitted_infections">
    <h2>Sexually Transmitted Infections</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('STI Overview') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Bacterial Infections') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Parasitic Infections') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Viral Infections') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Dealing with STIs') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Non-STIs') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="health">
    <h2>Health</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('General Male Health') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Fitness and Sexuality') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Appearance/Body Image') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicQuarter('General Female Health') ?>
        <?php echo renderTopicQuarter('Medical Conditions') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Cancer') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Medical Procedures') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="love_and_relationships">
    <h2>Love &amp; Relationships</h2>

    <div class="grid-left">
      <?php echo renderTopicHalf('Love') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicQuarter('Dating') ?>
      <?php echo renderTopicQuarter('Communication') ?>
    </div>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="sexual_orientations">
    <h2>Sexual Orientations</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Sexual Identities') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Queer Sexuality') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Coming Out') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Rights') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="sexual_difficulties">
    <h2>Sexual Difficulties</h2>

    <div class="grid-left">
      <?php echo renderTopicHalf('Male Difficulty') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicHalf('Female Difficulty') ?>
    </div>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="sex_and_the_law">
    <h2>Sex and the Law</h2>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Sexual Abuse in Childhood') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Domestic Violence') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicQuarter('Sexual Assault') ?>
        <?php echo renderTopicQuarter('Pornography') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Other Non-Consensual Sexual Behaviors') ?>
      </div>
    </section>

    <section>
      <div class="grid-left">
        <?php echo renderTopicHalf('Laws') ?>
      </div>

      <div class="grid-right">
        <?php echo renderTopicHalf('Resources') ?>
      </div>
    </section>
  </div><!-- .parent-topic -->



  <div class="parent-topic" id="beliefs_and_sexuality">
    <h2>Beliefs and Sexuality</h2>

    <div class="grid-left">
      <?php echo renderTopicHalf('Sex and Religion') ?>
    </div>

    <div class="grid-right">
      <?php echo renderTopicHalf('Sex Around the World') ?>
    </div>
  </div><!-- .parent-topic -->



</div><!-- .topics-container -->
