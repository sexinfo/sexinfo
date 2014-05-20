<?php

  // Generates a valid ID or NAME value out of the given input string
  function strip($input) {
    return preg_replace("/[^a-zA-Z0-9\-_]+/", "", $input);
  }

  // Does the database queries to return a datastructure of what the topics page would look like
  function generateTopics() {
    $topicsresult = db_query('SELECT * FROM taxonomy_term_data A, taxonomy_term_hierarchy B WHERE A.tid=B.tid AND A.vid=3 AND B.parent=0 ORDER BY weight ASC');

    $topics = [];

    foreach ($topicsresult as $topicresult) {
      $topic = [];
      $topic['name'] = $topicresult->name;
      $topic['tid'] = $topicresult->tid;
      $topic['sections'] = generateSections($topic);
      $topics[] = $topic;
    }

    return $topics;
  }

  function generateSections($topic) {
    $sectionsresult = db_query('SELECT * FROM taxonomy_term_hierarchy A, taxonomy_term_data B WHERE A.tid = B.tid AND parent=' . $topic['tid']);

    $sections = [];
    foreach ($sectionsresult as $sectionresult) {
      $section = [];
      $section['name'] = $sectionresult->name;
      $section['image'] = 'sites/all/themes/magazeen_lite/images/topics/kinky_sex_paraphilia.jpg';
      $section['tid'] = $sectionresult->tid;
      $section['articles'] = generateArticles($section);
      $section['rendersize'] = sizeof($section['articles']) > 6 ? 2 : 1;
      $sections[] = $section;
    }

    return $sections;
  }

  function generateArticles($section) {
    // Generate children html which is links to articles
    // The status=1 part makes sure we only select published articles
    // This is because there are a lot of cases were we have old, 
    // and terrible articles in circulation that we don't want normal users to see.
    $articlesresult = db_query('SELECT DISTINCT * FROM taxonomy_index A, node B WHERE A.nid = B.nid AND B.status=1 AND A.tid=' . $section['tid']);
    $articles = [];
    foreach($articlesresult as $articleresult) {
      $article = [];
      $article['nid'] = $articleresult->nid;
      $article['name'] = $articleresult->title;
      $articles[] = $article;
    }

    return $articles;
  }

  function optimizeSectionLayout($sections) {
    $leftsections = [];
    $rightsections = [];

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
