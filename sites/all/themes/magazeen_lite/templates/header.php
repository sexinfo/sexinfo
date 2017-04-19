<div id="header">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">
<?php
  $result     = db_query("select * from {node} where status = 1 and promote = 1 order by rand() limit 1");
  $randomNode = $result->fetch()
?>
  <div id="header-left">
    <?php include 'navigation.php' ?> 
  </div>

  <div id="header-right">
      <form action="/sexinfo/" method="post" id="search-block-form" accept-charset="UTF-8">
      <ul>
        <li><input placeholder="Search..." id="search-box" type="text" name="search_block_form" maxlength="128" /></li>
        <li><span onclick="document.getElementById('search-block-form').submit()" data-icon="&#xe090;"></span></li>
        <li><a class="btn" href="https://www.facebook.com/SexInfoOnline"><span data-icon="&#xe00b;"></span></a></li>
        <li><a class="btn" href="https://twitter.com/sexinfoonline"><span data-icon="&#xe009;"></span></a></li>
        <li><a href="https://instagram.com/ucsbsexperts/"><span data-icon="&#58889;"></span></a></li>
      </ul>

      <input name="form_build_id" value="form-AmYRLMZDHrIiZu-Dl2zjnB27KK1EN2AC_XZ2YHVi_vE" type="hidden" />
      <input name="form_id" value="search_block_form" type="hidden" />
      </form>
  </div>

  <div style="clear: both"></div>

</div> <!-- /header -->
