<?php include 'header.php' ?>

<?php include 'navigation.php' ?>


<div class="container">
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">


<!-- Wrapper for slides -->
    <div class="carousel-inner">

      <div class="item active">
        <img src="http://localhost:8888/sites/all/themes/magazeen_lite/images/modules/sexOnPeriod.png" alt="">
        <div class="carousel-caption">

         <div class="carouselContainer">
           <h3>Sex on Your Period</h3><p>It is common for people to avoid having sexual intercourse during menstruation…</p></div>
           <p><a class="btn btn-primary btn-lg" href = "http://www.soc.ucsb.edu/sexinfo/article/sex-your-period" role="button">Read more</a></p>

        </div>
      </div>

      <div class="item">
        <img src="http://localhost:8888/sites/all/themes/magazeen_lite/images/modules/ejaculate.png" alt="">
        <div class="carousel-caption">
        
          <div class="carouselContainer">
          <h3>Swallowing Your Partner's Ejaculate</h3><p>First off, the notion that semen has the ability to whiten teeth is no more than a myth…</p></div>
          <p><a class="btn btn-primary btn-lg" href = "http://www.soc.ucsb.edu/sexinfo/article/swallowing-your-partners-ejaculate" role="button" >Read more</a></p>
          
        </div>
      </div>

      <div class="item">
        <img src="http://localhost:8888/sites/all/themes/magazeen_lite/images/modules/hymen.png" alt="">
        <div class="carousel-caption">
          
          <div class = "carouselContainer">
          <h3>Is My Hymen Intact?</h3><p>If you are curious about what your hymen (or what is left of your hymen) looks like…</p></div>
          <p><a class="btn btn-primary btn-lg" href = "http://www.soc.ucsb.edu/sexinfo/question/my-hymen-intact" role="button">Read more</a></p>
        
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
