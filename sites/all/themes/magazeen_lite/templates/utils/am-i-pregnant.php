<?php
  // Decode /data/topics.json as associative array
  function getData() {
    $fileData = file_get_contents("data/am-i-pregnant.json", true);
    return json_decode($fileData, true);
  }
?>
