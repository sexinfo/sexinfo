<?php
header('Content-type: text/plain');

require('../core/sex-core.php');

if(isset($_GET['type'])) $type = (int) $_GET['type'];
else $type = 1;

if(isset($_GET['id'])) $id = (int) $_GET['id'];
else $id = 0;

if(isset($_GET['next'])) $next = (int) $_GET['next'];
else $next = 0;

echo data::subcategory_options($type, $id, $next);
?>