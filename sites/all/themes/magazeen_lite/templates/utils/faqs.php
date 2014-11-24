<?php

  // Decode /data/topics.json as associative array
  function getImagesForFAQs() {
    $fileData = file_get_contents("data/FAQs.json", true);
    return json_decode($fileData, true);
  }

  // Does the database queries to return a datastructure of what the topics page would look like
  function generateFAQs() {
    $topics = array();

    $topic = array();
    $topic['name'] = "Letters From You";
    $topic['sections'] = generateSectionsFAQ($topic);
    $topics[] = $topic;

    return $topics;
  }

  function generateSectionsFAQ($topic) {
    $sectionsresult = db_query('SELECT * FROM `taxonomy_term_data` WHERE vid = 6');

    $sections = array();
    foreach ($sectionsresult as $sectionresult) {
      $section = array();
      $section['name'] = $sectionresult->name;
      $section['image'] = 'kinky_sex_paraphilia.jpg';
      $section['tid'] = $sectionresult->tid;
      $section['articles'] = generateSectionFAQ($section);
      $section['rendersize'] = sizeof($section['articles']) > 6 ? 2 : 1;
      $sections[] = $section;
    }

    return $sections;
  }

  function generateSectionFAQ($section) {
    // Generate children html which is links to articles
    // The status=1 part makes sure we only select published articles
    // This is because there are a lot of cases were we have old, 
    // and terrible articles in circulation that we don't want normal users to see.
    $articlesresult = db_query('SELECT * FROM (SELECT DISTINCT nid FROM `taxonomy_index` WHERE tid=' . $section['tid'] . ') as A INNER JOIN node B on A.nid = B.nid AND B.status=1');
    $articles = array();
    foreach($articlesresult as $articleresult) {
      $article = array();
      $article['nid'] = $articleresult->nid;
      $article['name'] = $articleresult->title;
      $articles[] = $article;
    }

    return $articles;
  }

  function optimizeFAQLayout($sections) {
    $leftsections = array();
    $rightsections = array();

    $leftsize = 0;
    $rightsize = 0;

    // Count the total size of the sections by quarters
    $totalsize = 0;
    $lastlargeindex = -1;
    $lastsmallindex = -1;
    for ($i = 0; $i < sizeof($sections); $i++) {
      $section = $sections[$i];
      $totalsize += $section['rendersize'];
      if ($section['rendersize'] == 1) $lastsmallindex = $i;
      else $lastlargeindex = $i;
    }

    // If the last element isn't small, make it small
    if ($lastlargeindex != -1 && $lastsmallindex != -1 && $lastlargeindex > $lastsmallindex) {
      $temp = $sections[$lastsmallindex];
      $sections[$lastsmallindex] = $sections[$lastlargeindex];
      $sections[$lastlargeindex] = $temp;
      $swap = $lastlargeindex;
      $lastlargeindex = $lastsmallindex;
      $lastsmallindex = $swap;
    }

    // Modify based on the way sections work
    $howmanytomakebigger = $totalsize % 2;
    foreach ($sections as $section) {
      if ($section['rendersize'] == 1 && $howmanytomakebigger > 0) {
        $howmanytomakebigger -= 1;
        $section['rendersize'] = 2;
      }

      $size = $section['rendersize'];
      if ($leftsize <= $rightsize) {
        $leftsections[] = $section;
        $leftsize += $size;
      } else {
        $rightsections[] = $section;
        $rightsize += $size;
      }
    }

    return array($leftsections, $rightsections);
  }
?>
