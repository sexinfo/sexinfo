<div class="container">
  <h1 class="topic-header">SexInfo Topics</h1>
  
  <?php
  // The ID of the taxonomy vocabulary for which you'd like to create a nested list
  $vid = 3;
  $depth = 0;
  $tree = taxonomy_get_tree($vid);
  $counter = 0;

  print '<ul class="taxonomy-parent">';

  foreach ($tree as $term) {
    if ($term->depth > $depth) {
      print '<ul class="taxonomy-nested">';
      $depth = $term->depth;
    }
    if ($term->depth < $depth) {
      print '</ul>';
      $depth = $term->depth;
    }

    // Color for every other list item
    if ($counter % 2 == 0) $className = "item-even";
    else $className = "item-odd";

    if ($term->depth == 0) {
      print "<li class='taxonomy-item " . $className . "' style='font-weight: bold;'>" . $term->name . "</li>";
    } else {
      print "<li class='taxonomy-item " . $className . "'>" . l($term->name, "taxonomy/term/" . 
        $term->tid . "/all") . "</li>";
    }
    
    $counter++;
  }

  print '</ul>';
  ?>
</div>