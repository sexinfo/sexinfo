<?php
  // Decode /data/topics.json as associative array
  function getQuestions() {
    $fileData = file_get_contents("data/questions.json", true);
    return json_decode($fileData, true);
  }

  // Decode /data/topics.json as associative array
  function getResponses() {
    $fileData = file_get_contents("data/responses.json", true);
    return json_decode($fileData, true);
  }
?>
