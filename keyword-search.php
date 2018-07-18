<?php
function bib_keyword_search() {
  ?>
  <div class="wrap"><h1>Bibliography Keyword Search</h1></div>

  <div><form enctype="multipart/form-data" action="" method="post">
    <p>Please upload search lists in the correct format.</p>
    <label for="kwupload">Keyword list: </label><input type="file" required name="kwupload" id="kwupload"></input></p>
    <label for="journalupload">Journal list: </label><input type="file" name="journalupload" id="journalupload"></input></p>
    <p><input type="submit" name="keywords-submit" id="submit" class="button button-primary" value="Search Bibliography" /></p>
  </form></div>
  <?php

  if ( ! empty( $_POST['keywords-submit'] ) ) {

    $keywords_file = file($_FILES['kwupload']['tmp_name']);
    $journals_file = file($_FILES['journalupload']['tmp_name']);


    $articles = array();
    $clusters = array();
    $journals = array();

    //
    // Read in keywords
    //
    $c = 0;
    $k = 0;
    $j = count($journals_file);
    $a = wp_count_posts('bib')->publish;
    $cluster = "";
    foreach($keywords_file as $line) {
      $line = trim($line);
      if (substr( $line, 0, 2 ) === "- ") {
        $keyword = substr($line,2);
        if ($cluster !== '') {
          if (!in_array($keyword, $clusters[$cluster]['keywords'])) {
            $k++;
            $clusters[$cluster]['keywords'][$keyword]['count'] = 0;
            $clusters[$cluster]['keywords'][$keyword]['ids'] = array();
          } else {
            echo "ERROR: Don't have cluster in clusters dictionary for keyword ".$keyword;
          }
        } else {
          echo "ERROR: Don't know cluster for keyword ".$keyword;
        }
      }
      elseif ($line !== '') {
        $cluster = $line;
        if (!in_array($cluster, $clusters)) {
          $c++;
          $clusters[$cluster]['count'] = 0;
          $clusters[$cluster]['ids'] = array();
          $clusters[$cluster]['keywords'] = array();
        }
      }
    }

    //
    // Read in journals
    //
    foreach($journals_file as $line) {
      $journal = trim($line);
      if ($journal !=="" && !in_array(!$journal, $journals)) {
        $journals[]=$journal;
      }
    }
    $j = count($journals);

    //
    // Check each pub for keywords
    //
    echo "<p>Checking <b>$a articles</b> for matches against <b>$k keywords</b> across <b>$c clusters</b>, and <b>$j journals</b>...</p>";

    // Get distinct keywords
    $keywords = array();
    foreach ($clusters as $cluster) {
      $keywords = array_merge($keywords, (array_keys($cluster['keywords'])));
    }

    //count keywords found per article
    $query = new WP_Query(array(
      'post_type' => 'bib',
      'post_status' => 'publish',
      'posts_per_page' => -1
    ));
    while ($query->have_posts()) {
      global $post;
      $query->the_post();
      $title_abstract = $post->title . " " . $post->abstract;
      $title_abstract = strtolower($title_abstract);
      $title_abstract = preg_replace("/[^A-Za-z0-9 ]/", " ", $title_abstract);

      $id = get_the_ID();
      $articles[$id]['nbr_keywords'] = 0;
      // foreach ($keywords as $keyword) {
      //   $keyword = strtolower($keyword);
      //   if (preg_match("/\b$keyword\b/", $title_abstract)) {
      //     echo "$keyword in #$id, ";
      //     $articles[$id]['nbr_keywords']++;
      //   }
      // }

      // count number of keywords per keyword cluster
      foreach (array_keys($clusters) as $cluster) {
        $articles[$id]['clusters'][$cluster] = array();
        $articles[$id]['clusters'][$cluster]['count'] = 0;
        $articles[$id]['clusters'][$cluster]['keywords'] = array();
        foreach (array_keys($clusters[$cluster]['keywords']) as $keyword) {
          $keyword = strtolower($keyword);
          if (preg_match("/\b$keyword\b/", $title_abstract)) {
            $articles[$id]['nbr_keywords']++;
            $articles[$id]['clusters'][$cluster]['count']++;
            $articles[$id]['clusters'][$cluster]['keywords'][$keyword] = 1;
          } else {
            $articles[$id]['clusters'][$cluster]['keywords'][$keyword] = 0;
          }
        }
      }
    }
    wp_reset_query();

  //
  // Output to CSV
  //

  $articles_with_keywords = array_filter($articles, function ($var) {
    return ($var['nbr_keywords'] > 0);  });

  echo "<pre>"; print_r($articles_with_keywords); echo "</pre>";

  }
}

?>
