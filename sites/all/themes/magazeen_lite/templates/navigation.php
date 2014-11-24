<?php
include 'utils/topics.php';
$topics = generateTopics();

// TODO: may the coding gods forgive me. Will come back to this later
function page() { return $_GET['q']; }
function on_home_page()      { return page() == 'node/18'; }
function on_topics_page()    { return page() == 'node/392'; }
function on_faq_page()       { return page() == 'node/634'; }
function on_ask_page()       { return page() == 'node/22'; }
function on_resources_page() { return page() == 'node/407'; }
function on_quiz_page()      { return page() == 'node/561'; }
?>

<div id="navigation">
    <div class="container clearfix">

      <ul id="main-menu" class="links main-menu">
      <li class="menu-339 <?php echo on_home_page() ? 'active' : '' ?>"><a href="/sexinfo/home" title="">Home</a></li>

        <li class="menu-537 <?php echo on_topics_page() ? 'active' : '' ?>">
          <a href="/sexinfo/category">Topics</a>
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

        <li class="menu-549 <?php echo on_faq_page() ? 'active' : '' ?>"><a href="/sexinfo/letters-from-you">Letters From You</a></li>
        <li class="menu-400 <?php echo on_ask_page() ? 'active' : '' ?>"><a href="/sexinfo/ask-sexperts">Ask the Sexperts</a></li>
        <li class="menu-543 <?php echo on_resources_page() ? 'active' : '' ?>"><a href="/sexinfo/article/important-phone-numbersresources-pregnancy">Resources</a></li>
        <?php
          $result     = db_query("select * from {node} where status = 1 and promote = 1 order by rand() limit 1");
          $randomNode = $result->fetch()
        ?>
        <li class="menu-543"><a href="/sexinfo/node/<?php echo $randomNode->nid;?>" title="<?php echo $randomNode->title?>">Random Article</a></li>
        <li class="menu-739 last  <?php echo on_quiz_page() ? 'active' : '' ?>"><a href="/sexinfo/quizzes">Test Your Knowledge</a></li>
      </ul><!-- #main-menu -->
    </div>
  </div><!-- #navigation -->
