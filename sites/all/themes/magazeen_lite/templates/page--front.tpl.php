<?php include 'header.php' ?>

<?php include 'navigation.php' ?>


<div class="container">
  <div id="carousel-example-generic" class="carousel slide">


<!-- Wrapper for slides -->
    <div class="carousel-inner">

      <div class="item active">
        <img src="http://www.soc.ucsb.edu/sexinfo/sites/all/themes/magazeen_lite/images/modules/pregnant.jpg" alt="">
        <div class="carousel-caption">

         <div class="container">
           <h3>Can I Get Pregnant If...?</h3><p>Becoming a mother is a very important and amazing transition in life. Bringing a new life into the world brings…</p></div>
           <p><a class="btn btn-primary btn-lg" role="button">Read more</a></p>

        </div>
      </div>

      <div class="item">
        <img src="http://localhost:8888/sites/all/themes/magazeen_lite/images/modules/arousal.jpg" alt="">
        <div class="carousel-caption">
        
          <div class="container">
          <h3>Sexual Behaviors</h3><p>Sexual techniques are methods for increasing a partner's sexual arousal. These include activites that…</p></div>
          <p><a class="btn btn-primary btn-lg" role="button">Read more</a></p>
          
        </div>
      </div>

      <div class="item">
        <img src="http://localhost:8888/sites/all/themes/magazeen_lite/images/modules/arousal.jpg" alt="">
        <div class="carousel-caption">
          
          <div class = "container">
          <h3>Can I Get Pregnant If...?</h3><p>Becoming a mother is a very important and amazing transition in life. Bringing a new life into the world brings…</p></div>
          <p><a class="btn btn-primary btn-lg" role="button">Read more</a></p>
        
        </div>
      </div>
    </div>
    
      <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
      </a>
      <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
      </a>
  </div>
</div>

<div class = "container">
  <div class="front-container cloud-container">
    <?php include 'modules/tag_cloud.php' ?>
  </div>


  <div class="front-container">
    <?php include 'modules/box_grid.php' ?>
  </div>

</div><!-- .container -->


<?php include 'footer.php' ?>
