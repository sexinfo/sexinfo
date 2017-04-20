<?php
include 'utils/topics.php';
$topics = generateTopics();

// TODO: may the coding gods forgive me. Will come back to this later
function page() { return $_GET['q']; }
function on_home_page()      { return page() == 'node/18'; }
function on_topics_page()    { return page() == 'node/392'; }
function on_faq_page()       { return page() == 'frequently-asked-questions'; }
function on_ask_page()       { return page() == 'node/22'; }
function on_resources_page() { return page() == 'node/407'; }
function on_quiz_page()      { return page() == 'node/561'; }
function on_ppq_page()       { return page() == 'node/635'; }
function on_for_educators()  { return page() == 'category#ForEducators'; }
function on_teen_corner()    { return page() == 'category#TeenCorner'; }
?>
<div id="navigation">
      <ul id="main-menu" class="links main-menu">
          <li id="navlogo"><a href="/sexinfo"><img src="/sexinfo/sites/default/files/logoinvert.png"></a></li>
          <li class="menu-537 <?php echo on_topics_page() ? 'active' : '' ?>">
            <a href="/sexinfo/category">Topics  <span style="font-size: 8px;">▼</span></a>
            <div id="topics-menu">
              <ul>
                <?php $index = 0; $size = sizeof($topics); ?>
                <div class="dropdown-half">
                  <?php
                  for (; $index < (($size + 1) / 2); $index++) {
                    $name = $topics[$index]['name'];
                    printf("<li><a href='/sexinfo/category#%s'>%s</a></li>", strip($name), htmlspecialchars($name));
                  } ?>
                </div>

                <div class="dropdown-half">
                  <?php
                  for (; $index < $size; $index++) {
                    $name = $topics[$index]['name'];
                    printf("<li><a href='/sexinfo/category#%s'>%s</a></li>", strip($name), htmlspecialchars($name));
                  } ?>
                </div>

              </ul>
            </div><!-- .topics-menu -->
          </li>

          <li class="menu-400 <?php echo on_ask_page() ? 'active' : '' ?>"><a href="/sexinfo/ask-sexperts">Ask a Sexpert</a></li>
          <li class="menu-549 <?php echo on_faq_page() ? 'active' : '' ?>"><a href="/sexinfo/popular-questions">Popular Questions</a></li>
          <li class="menu-635 <?php echo on_ppq_page() ? 'active' : '' ?>"><a href="/sexinfo/ppq">Could I Be Pregnant?</a></li>
          <!--<li class="menu-543 <?php echo on_resources_page() ? 'active' : '' ?>"><a href="/sexinfo/article/important-phone-numbersresources-pregnancy">Resources</a></li>
          <li class="menu <?php echo on_for_educators() ? 'active' : '' ?>"><a href="/sexinfo/category#ForEducators">For Educators</a></li>
          <li class="menu <?php echo on_teen_corner() ? 'active' : '' ?>"><a href="/sexinfo/category#TeenCorner">Teen Corner</a></li>-->
          <li id="random-article">
              <a href="/sexinfo/node/<?php echo $randomNode->nid;?>" title="<?php echo $randomNode->title?>">Random Article</a>
          </li>

      </ul><!-- #main-menu -->
  </div><!-- #navigation -->
