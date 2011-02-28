<?php
header('Content-type: text/plain');

require('../core/sex-core.php');

$mysqli = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);

if(isset($_GET['id'])) $id = (int) $_GET['id'];
else $id = 0;

if(isset($_GET['type'])) $type = (int) $_GET['type'];
else $type = 1;

$result = $mysqli->query("SELECT * FROM `sex_type` ORDER BY `type_id` ASC");
echo "Type <select name=\"type\" id=\"type-select\">\n";
while($row = $result->fetch_assoc()) {
  $typeid = (int) $row['type_id'];
  $typename = $row['type_name'];
  echo "<option value=\"$typeid\"";
  if($type == $typeid) echo ' selected="selected"';
  echo ">$typename ($typeid)</option>\n";
}
echo "</select>\n";
$result->close();

echo "<div id=\"places\">\n";

if($id > 0) {
  $result = $mysqli->query("SELECT * FROM `sex_bridge` WHERE `bridge_content_id`='$id'");
  
  $pid = 0;
  
  while($placement = $result->fetch_assoc()) {
    $catlist = data::fetch_category_ancestry($placement['bridge_category_id']);
    $levels = count($catlist);
    
    echo "<div class=\"place\" id=\"place-$pid\">\n<a href=\"javascript:SexInfo.Content.deletePlacement($pid);\">[-]</a>\n";
    
    $cid = 0;
    $curr = 0;
    $next = $catlist[0]['id'];
    
    do {
      echo "<select class=\"cat-select\" name=\"cat[$pid][$cid]\" id=\"cat-$pid-$cid\">\n";
      echo data::subcategory_options($type, $curr, $next);
      echo "</select>\n";
      if($next > 0) echo "<span class=\"bridge\" id=\"bridge-$pid-$cid\"> &gt; </span>\n";
      
      ++$cid;
      $curr = $next;
      $next = $cid < $levels ? $catlist[$cid]['id'] : 0;
    } while($cid <= $levels);
    
    echo "</div>\n";
    
    ++$pid;
  }
  
  $result->close();
}

echo "</div>\n";

echo '<div id="add-link"><a href="javascript:SexInfo.Content.addPlacement();">[add a category]</a></div>' . "\n";
echo '<div><a href="javascript:SexInfo.Content.confirmResetEditArea();">[reset]</a>' . "\n";
echo '<a href="javascript:SexInfo.Content.cancelEdits();">[cancel]</a></div>' . "\n";
?>