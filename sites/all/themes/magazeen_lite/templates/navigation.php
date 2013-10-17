<!-- 
<?php if (!empty($main_menu)): ?>
  <div id="navigation">
    <div class="container clearfix">

      <?php print theme('links', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'main-menu')))); ?>

    </div>
  </div><!-- #navigation --   (>)
<?php endif;?>
-->

  <div id="navigation">
    <div class="container clearfix">

      <ul id="main-menu" class="links main-menu"><li class="menu-339 active-trail first active"><a href="/home" title="" class="active-trail active">Home</a></li>
<li class="menu-537"><a href="/category">Topics</a>
	<div id "topics-menu">

		<ul>
      <li><a href="sub1.html" >Basics of Sexuality</a></li>
      <li><a href="sub2.html" >The Body</a></li>
      <li><a href="sub3.html" >Beliefs and Sexuality</a></li>
      <li><a href="sub4.html" >Contraception</a></li>
      <li><a href="sub5.html" >Abortion</a></li>
      <li><a href="sub6.html" >Health</a></li>
      <li><a href="sub6.html" >Love & Relationships</a></li>
      <li><a href="sub6.html" >Pregnancy</a></li>
      <li><a href="sub6.html" >Sex and the Law</a></li>
      <li><a href="sub6.html" >Sexual Activity</a></li>
      <li><a href="sub6.html" >Sexual Difficulties</a></li>
      <li><a href="sub6.html" >Sexual Orientations</a></li>
      <li><a href="sub6.html" >Sexually Transmitted Infections</a></li>
    </ul>


  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript">
$(document).ready(function () { 
<<<<<<< HEAD

  var $menu = $('.menu-537 div');
  
  $('.menu-537').hover(
    function () {
      //show its submenu
      $menu.show();
=======
  
  $('#navigation ul li.menu-537').hover(
    function () {
      //show its submenu
      $('#navigation ul li.menu-537 div').show();
>>>>>>> 0d48228ca5ba26eda52f67a49c4919501754f8b9

    }, 
    function () {
      //hide its submenu
<<<<<<< HEAD
      $menu.hide();   
=======
      $('#navigation ul li.menu-537 div').hide();   
>>>>>>> 0d48228ca5ba26eda52f67a49c4919501754f8b9
    }
  );
  
});
  </script>






	</div>	
</li>

<li class="menu-549"><a href="/frequently-asked-questions">FAQs</a></li>
<li class="menu-400"><a href="/ask-sexperts">Ask the Sexperts</a></li>
<li class="menu-543"><a href="/article/resources">Resources</a></li>
<li class="menu-739 last"><a href="/quizzes">Test Your Knowledge</a></li>
</ul>
    </div>
  </div>