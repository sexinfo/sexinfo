  <div id="navigation">
    <div class="container clearfix">

      <ul id="main-menu" class="links main-menu">
        <li class="menu-339 active-trail first active"><a href="/sexinfo/home" title="" class="active-trail active">Home</a></li>

        <li class="menu-537">
          <a href="/sexinfo/category">Topics</a>
          <div id="topics-menu">
            <ul>
              <div class="dropdown-half">
                <li><a href="#" >Basics of Sexuality</a></li>
                <li><a href="#" >The Body</a></li>
                <li><a href="#" >Beliefs and Sexuality</a></li>
                <li><a href="#" >Contraception</a></li>
                <li><a href="#" >Abortion</a></li>
                <li><a href="#" >Health</a></li>
                <li><a href="#" >Love &amp; Relationships</a></li>
              </div>

              <div class="dropdown-half">
                <li><a href="#" >Pregnancy</a></li>
                <li><a href="#" >Sex and the Law</a></li>
                <li><a href="#" >Sexual Activity</a></li>
                <li><a href="#" >Sexual Difficulties</a></li>
                <li><a href="#" >Sexual Orientations</a></li>
                <li><a href="#" >Sexually Transmitted Infections</a></li>
              </div>

            </ul>
          </div><!-- .topics-menu -->
        </li>

        <li class="menu-549"><a href="/sexinfo/frequently-asked-questions">FAQs</a></li>
        <li class="menu-400"><a href="/sexinfo/ask-sexperts">Ask the Sexperts</a></li>
        <li class="menu-543"><a href="/sexinfo/article/important-phone-numbersresources">Resources</a></li>
        <?php
          $result     = db_query("select * from {node} where status = 1 and promote = 1 order by rand() limit 1");
          $randomnode = $result->fetch()
        ?>
        <li class="menu-543"><a href="/sexinfo/node/<?php echo $randomNode->nid;?>" title="<?php echo $randomNode->title?>">Random Article</a></li>
        <li class="menu-739 last"><a href="/sexinfo/quizzes">Test Your Knowledge</a></li>
      </ul><!-- #main-menu -->
    </div>
  </div><!-- #navigation -->
