<div>
  <div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

    <?php print $user_picture; ?>

    <div class="node-meta clearfix">
      <?php print render($title_prefix); ?>
      <h3 class="node-title"><a href="<?php print $node_url ?>" title="<?php print $title; ?>"><?php print $title; ?></a></h3>
      <div id="fb-root"></div>

      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>


      <?php print render($title_suffix); ?>
      <span class="submitted node-info"><?php if ($display_submitted): ?><?php print $submitted; ?><?php endif; ?></span>
    </div><!--/node-meta-->

    <div class="node-box clearfix">

      <div class="node-content clearfix">
        <?php hide($content['links']); ?>
        <?php hide($content['comments']); ?>

        <?php print render($content); ?>
      </div><!--/node-content-->

      <div class="node-footer clearfix">
        <div class="meta">
          <?php
            // Query database table taxonomy_term_data and taxonomy_index
            // So I can get all terms from my node.
            $term = db_query('SELECT t.name, t.tid FROM {taxonomy_index} n LEFT JOIN  {taxonomy_term_data} t ON (n.tid = t.tid) WHERE n.nid = :nid', array(':nid' => $node->nid));

            // db_query in Drupal 7 returns a stdClass object.
            // Value names are corresponding to the fields in your SQL query
            //(in our case "t.name") AND t.tid for build path.
            $tags = '';
            foreach ($term as $record) {
              // I put l() function for make my links.
              $tags .= l($record->name, 'taxonomy/term/' . $record->tid);
            }
          ?>
          <!--Facebook like button-->
          <div class="fb-like" data-send="false" data-width="450" data-show-faces="false" data-font="arial" data-colorscheme="dark"></div>
          <div class="terms">

            <!--Tags + Category -->
            <span class="node-category">Category: </span>
            <?php print $tags; ?>
          </div>
          <?php if ($content['links']): ?>
            <div class="nodelinks">
              <!--Read More -->
              <?php print render($content['links']); ?>
            </div>
          <?php endif; ?>

            <!--Suggested Articles -->
            


        </div><!--/meta-->
      </div><!--/node-footer-->
    </div><!--/node-box-->
  </div>


  <?php if ($content['comments']): ?>
    <?php print render($content['comments']); ?>
  <?php endif; ?>
</div>
