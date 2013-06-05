<h3>Recent Changes:</h3>
<ul>
<?php $query = db_select('node', 'n')->fields('n', array('nid'))->range(0, 10)->orderBy('changed', 'DESC')->where('`promote` = 1');

      $nodeIDs = $query->execute()->fetchCol();
      $type = 'node';
      $conditions = array();
      $resetCache = FALSE;

      $nodes = entity_load($type, $nodeIDs, $conditions, $resetCache);
      foreach($nodes as $node) {
        echo '<li><a href="node/' . $node->nid . '">' . $node->title . '</a></li>';
      }
      ?>
</ul>
