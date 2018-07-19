<?php
function bib_keyword_search() {
  ?>
  <div class="wrap"><h1>Bibliography Keyword Search</h1></div>

  <div><form enctype="multipart/form-data" action="" method="post">
    <p>Please upload search lists in the proper format.</p>
    <label for="kwupload">Keyword list: </label><input type="file" required name="kwupload" id="kwupload"></input></p>
    <label for="journalupload">Journal list: </label><input type="file" name="journalupload" id="journalupload"></input></p>
    <p><input type="submit" name="keywords-submit" id="submit" class="button button-primary" value="Search Bibliography" /></p>
  </form></div>
  <?php

  if ( ! empty( $_POST['keywords-submit'] ) ) {
    error_log("Starting search");
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
    echo "<p>Checked <b>$a articles</b> for matches against <b>$k keywords</b> across <b>$c clusters</b> and <b>$j journals</b>.</p>";

    // Get distinct keywords
    $keywords = array();
    foreach ($clusters as $cluster) {
      $keywords = array_merge($keywords, (array_keys($cluster['keywords'])));
    }

    // Loop through all bibliography entries
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

      $id = $post->id;
      $articles[$id]['nbr_keywords'] = 0;
      // count number of keywords per keyword cluster
      foreach (array_keys($clusters) as $cluster) {
        $articles[$id]['clusters'][$cluster] = array();
        $articles[$id]['clusters'][$cluster]['count'] = 0;
        $articles[$id]['clusters'][$cluster]['keywords'] = array();
        foreach (array_keys($clusters[$cluster]['keywords']) as $keyword) {
          $keyword = strtolower($keyword);
          $keyword = preg_quote($keyword, "/");
          if (preg_match("/\b$keyword\b/", $title_abstract)) {
            $articles[$id]['nbr_keywords']++;
            $articles[$id]['clusters'][$cluster]['count']++;
            $articles[$id]['clusters'][$cluster]['keywords'][$keyword] = 1;
          } else {
            $articles[$id]['clusters'][$cluster]['keywords'][$keyword] = 0;
          }
        }
      }

      // Get funding, affiliation, and citation information from database
      $funding_info = $post->funding;
      $articles[$id]['funding_info'] = $funding_info;
      $articles[$id]['p2c'] = (strpos($funding_info, 'CPC P2C-Yes') !== false) ? 1 : 0;
      $articles[$id]['t32'] = (strpos($funding_info, 'CPC T32-Yes') !== false) ? 1 : 0;
      $articles[$id]['r24'] = (strpos($funding_info, 'CPC R24-Yes') !== false) ? 1 : 0;

      $affiliation = $post->affiliation;
      $articles[$id]['fellow'] = (strpos($affiliation, 'nofellow') !== false) ? 0 : 1;
      $articles[$id]['tp'] = (strpos($affiliation, 'tplist') !== false) ? 1 : 0;
      $articles[$id]['gra'] = (strpos($affiliation, 'gralist') !== false) ? 1 : 0;
      $articles[$id]['staff'] = (strpos($affiliation, 'stafflist') !== false) ? 1 : 0;

      $articles[$id]['journal'] = $post->{'secondary-title'};
      $articles[$id]['has_journal'] = (in_array($post->{'secondary-title'}, $journals)) ? 1 : 0;

      $authors_array = $post->authors;
      if (isset($authors_array[0])) $articles[$id]['authors'] = implode("; ",$authors_array);
      if (isset($authors_array[0])) $articles[$id]['author1'] = $authors_array[0];
      if (isset($authors_array[1])) $articles[$id]['author2'] = $authors_array[1];
      if (isset($authors_array[2])) $articles[$id]['author3'] = $authors_array[2];
      if (isset($authors_array[3])) $articles[$id]['author4'] = $authors_array[3];
      if (isset($authors_array[4])) $articles[$id]['author5'] = $authors_array[4];
      if (isset($authors_array[5])) $articles[$id]['author6'] = $authors_array[5];
      if (isset($authors_array[6])) $articles[$id]['author7'] = $authors_array[6];
      if (isset($authors_array[7])) $articles[$id]['author8'] = $authors_array[7];
      if (isset($authors_array[8])) $articles[$id]['author9'] = $authors_array[8];
      if (isset($authors_array[9])) $articles[$id]['author10'] = $authors_array[9];
      if (isset($authors_array[10])) $articles[$id]['author_et_al'] = implode("; ",array_slice($authors_array,10));

      $articles[$id]['endnote_id'] = $post->id;
      $articles[$id]['endnote_type'] = strtoupper($post->type);
      $articles[$id]['title'] = $post->title;
      $articles[$id]['year'] = $post->year;
      $articles[$id]['abstract'] = $post->abstract;

    }
    wp_reset_query();
    error_log("Ending search");

  //
  // Output to CSV
  //

    // // PRINT ARTICLES TO PAGE //
    // $articles_with_keywords = array_filter($articles, function ($var) {
    // return ($var['nbr_keywords'] > 0);  });
    // echo "<pre>"; print_r($articles_with_keywords); echo "</pre>";
    //error_log("Ending print");
    $rows = array();

    $header = array(
    "endnote_id",
    "endnote_type",
    "title",
    "authors",
    "author1",
    "author2",
    "author3",
    "author4",
    "author5",
    "author6",
    "author7",
    "author8",
    "author9",
    "author10",
    "author_et_al",
    "has_fellow",
    "has_training_program",
    "has_gra",
    "has_staff",
    "year",
    "abstract",
    "journal",
    "has_journal",
    "funding_info",
    "has_p2c_ack",
    "has_t32_ack",
    "has_r24_ack",
    "nbr_distinct_keywords"
  );


    foreach ($articles as $article) {
      $row = array();
      $row[0] = $article['endnote_id'];
      $row[1] = $article['endnote_type'];
      $row[2] = $article['title'];
      if (isset($article['authors'])) $row[3] = $article['authors'];
      if (isset($article['author1'])) $row[4] = $article['author1'];
      if (isset($article['author2'])) $row[5] = $article['author2'];
      if (isset($article['author3'])) $row[6] = $article['author3'];
      if (isset($article['author4'])) $row[7] = $article['author4'];
      if (isset($article['author5'])) $row[8] = $article['author5'];
      if (isset($article['author6'])) $row[9] = $article['author6'];
      if (isset($article['author7'])) $row[10] = $article['author7'];
      if (isset($article['author8'])) $row[11] = $article['author8'];
      if (isset($article['author9'])) $row[12] = $article['author9'];
      if (isset($article['author10'])) $row[13] = $article['author10'];
      if (isset($article['author_et_al'])) $row[14] = $article['author_et_al']; 


      $rows[] = $row;
    }

   $fp = fopen("temp_keyword_analysis.csv", "w");
   fputcsv($fp, $header);
   foreach ($rows as $line) {
     fputcsv($fp, $line);
   }
   fclose($fp);
  ?>

  <form action="" method="post"><input type="hidden" name="download_csv" value="true">
  <input type="submit" name="submit" class="button" id="submit" value="Download CSV" /></form>

<?php
  }
}

//without hijacking page load, download would include the HTML of the page
add_action("admin_init", "download_csv");
function download_csv() {
  if (isset($_POST['download_csv'])) {
    $filename = "cpc_pubs_keyword_analysis_".date("Ymd").".csv";
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    readfile("temp_keyword_analysis.csv");
    die;
  }
}

?>
