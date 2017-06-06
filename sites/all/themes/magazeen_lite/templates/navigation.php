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
          <li id="menu">
            <a href="/sexinfo/category">Topics  <span style="font-size: 8px;">▼</span></a>
            <div id="topics1">
              <ul>
                <div id="firsthalf">
                  <a href="/sexinfo/category#SexAcrosstheLifecycle"><li>Sex Across the Lifecycle
                  </li></a>
                  <a href="/sexinfo/category#TheBody"><li>The Body
                  </li></a>
                  <a href="/sexinfo/category#SexualActivity"><li>Sexual Activity</li></a>
                  <a href="/sexinfo/category#Pregnancy"><li>Pregnancy</li></a>
                  <a href="/sexinfo/category#BirthControl"><li>Birth Control</li></a>
                  <a href="/sexinfo/category#Abortion"><li>Abortion</li></a>
                  <a href="/sexinfo/category#SexuallyTransmittedInfections"><li>Sexually Transmitted Infections</li></a>
                </div>
                <div id="secondhalf">
                  <a href="/sexinfo/category#LoveRelationships"><li>Love &amp; Relationships</li></a>
                  <a href="/sexinfo/category#GenderIdentitySexualOrientation"><li>Gender, Identity, &amp; Sexual Orientation</li></a>
                  <a href="/sexinfo/category#SexualDifficulties"><li>Sexual Difficulties</li></a>
                  <a href="/sexinfo/category#SexandtheLaw"><li>Sex and the Law</li></a>
                  <a href="/sexinfo/category#BeliefsandSexuality"><li>Beliefs and Sexuality</li></a>
                  <a href="/sexinfo/category#ForEducators"><li>For Educators</li></a>
                  <a href="/sexinfo/category#TeenCorner"><li>Teen Corner</li></a>
                </div>
              </ul>
            </div>
          </li>

          <li class="menu-400 <?php echo on_ask_page() ? 'active' : '' ?>"><a href="/sexinfo/ask-sexperts">Ask the Sexperts</a></li>
          <li class="menu-549 <?php echo on_faq_page() ? 'active' : '' ?>"><a href="/sexinfo/popular-questions">Popular Questions</a></li>
          <!--<li class="menu-635 <?php echo on_ppq_page() ? 'active' : '' ?>"><a href="/sexinfo/ppq">Could I Be Pregnant?</a></li>-->
          <!--<li class="menu-543 <?php echo on_resources_page() ? 'active' : '' ?>"><a href="/sexinfo/article/important-phone-numbersresources-pregnancy">Resources</a></li>
          <li class="menu <?php echo on_for_educators() ? 'active' : '' ?>"><a href="/sexinfo/category#ForEducators">For Educators</a></li>
          <li class="menu <?php echo on_teen_corner() ? 'active' : '' ?>"><a href="/sexinfo/category#TeenCorner">Teen Corner</a></li>-->
          <li id="random-article">
              <a href="/sexinfo/node/<?php echo $randomNode->nid;?>" title="<?php echo $randomNode->title?>">Random Article</a>
          </li>

      </ul><!-- #main-menu -->
  </div><!-- #navigation -->