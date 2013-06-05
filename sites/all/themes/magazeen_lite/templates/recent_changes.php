<?php
  include 'utils/utils.php';
  include 'utils/content_tag.php';
?>

<?php if (logged_in()): ?>
  <div class="recent-changes-container">
    <div class="clearfix">
      <h3>Recent Changes:</h3>
      <a href="#" class="close-changes">&times;</a>
    </div>

    <ul class="stripe-list">
      <?php
        $query   = db_select('node', 'n')->fields('n', array('nid'))->range(0, 10)->orderBy('changed', 'DESC')->where('`promote` = 1');
        $nodeIDs = $query->execute()->fetchCol();
        $type    = 'node';
        $conditions = array();
        $resetCache = FALSE;
        $counter    = 0;

        $nodes = entity_load($type, $nodeIDs, $conditions, $resetCache);
        foreach($nodes as $node) {
          if ($counter % 2 == 0) $className = "item-even";
          else $className = "item-odd";

          $link = content_tag('a', $node->title, array('href' => 'node/' . $node->nid));
          print content_tag('li', $link, array('class' => $className));

          $counter++;
        }
      ?>
    </ul>
  </div>
<?php endif; ?>


