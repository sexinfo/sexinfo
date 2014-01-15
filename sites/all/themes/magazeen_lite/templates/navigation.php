  <div id="navigation">
    <div class="container clearfix">

      <ul id="main-menu" class="links main-menu">
        <li class="menu-339 active-trail first active"><a href="/sexinfo/home" title="" class="active-trail active">Home</a></li>

        <li class="menu-537">
          <a href="/sexinfo/category">Topics</a>
          <div id="topics-menu">
            <ul>
              <div class="dropdown-half">
                <li><a href="/sexinfo/category#basics_of_sexuality" >Basics of Sexuality</a></li>
                <li><a href="/sexinfo/category#the_body" >The Body</a></li>
                <li><a href="/sexinfo/category#sexual_activity" >Sexual Activity</a></li>
                <li><a href="/sexinfo/category#pregnancy" >Pregnancy</a></li>
                <li><a href="/sexinfo/category#contraception" >Contraception</a></li>
                <li><a href="/sexinfo/category#abortion" >Abortion</a></li>
                <li><a href="/sexinfo/category#sexually_transmitted_infections" >Sexually Transmitted Infections</a></li>
              </div>

              <div class="dropdown-half">
                <li><a href="/sexinfo/category#health" >Health</a></li>
                <li><a href="/sexinfo/category#love_and_relationships" >Love &amp; Relationships</a></li>
                <li><a href="/sexinfo/category#sexual_orientations" >Sexual Orientations</a></li>
                <li><a href="/sexinfo/category#sexual_difficulties" >Sexual Difficulties</a></li>
                <li><a href="/sexinfo/category#sex_and_the_law" >Sex and the Law</a></li>
                <li><a href="/sexinfo/category#beliefs_and_sexuality" >Beliefs and Sexuality</a></li>
              </div>

            </ul>
          </div><!-- .topics-menu -->
        </li>

        <li class="menu-549"><a href="/sexinfo/frequently-asked-questions">FAQs</a></li>
        <li class="menu-400"><a href="/sexinfo/ask-sexperts">Ask the Sexperts</a></li>
        <li class="menu-543"><a href="/sexinfo/article/important-phone-numbersresources">Resources</a></li>
        <?php
          $result     = db_query("select * from {node} where status = 1 and promote = 1 order by rand() limit 1");
          $randomNode = $result->fetch()
        ?>
        <li class="menu-543"><a href="/sexinfo/node/<?php echo $randomNode->nid;?>" title="<?php echo $randomNode->title?>">Random Article</a></li>
        <li class="menu-739 last"><a href="/sexinfo/quizzes">Test Your Knowledge</a></li>
      </ul><!-- #main-menu -->
    </div>
  </div><!-- #navigation -->
