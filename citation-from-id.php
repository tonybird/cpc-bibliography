<?php
function citation_from_id($id) {
  $f = get_post_meta( $id, 'bib_fields', true );

  foreach ($f as $key=>$value) {
    $f[$key] = trim($value);
  }

  //generate author array for searching by author, and format author list for citation
  $author_arr = explode("\r\n", $f['authors']);
  $authorlist = "";
  if (count($author_arr) == 1 || count($author_arr) == 0) {
    $authorlist = $author_arr[0];
  } else if (count($author_arr) == 2) {
    $authorlist = $author_arr[0] . " & " . $author_arr[1];
  } else {
    for ($i = 0; $i <= count($author_arr)-2; $i++) {
      $authorlist = $authorlist . $author_arr[$i] . "; ";
    }
    $authorlist = $authorlist . "& " . $author_arr[count($author_arr)-1];
  }

  //generate editor array and format editor list for citation
  $editor_arr = explode("\r\n", $f['editors']);
  $editorlist = "";
  if (count($editor_arr) == 1 || count($editor_arr) == 0) {
    $editorlist = $editor_arr[0];
  } else if (count($editor_arr) == 2) {
    $editorlist = $editor_arr[0] . " & " . $editor_arr[1];
  } else {
    for ($i = 0; $i <= count($editor_arr)-2; $i++) {
      $editorlist = $editorlist . $editor_arr[$i] . "; ";
    }
    $editorlist = $editorlist . "& " . $editor_arr[count($editor_arr)-1];
  }

  //create citation base for all content types
  $linkedtitle = "<a href='".get_permalink($id)."'>".get_the_title($id) . '</a>';
  if ($f['reference-type'] === "BOOK") $linkedtitle = "<i>{$linkedtitle}</i>";
  if ($f['year-published']) $year = "({$f['year-published']})";
  if ($authorlist !== "") {
      $citation = "{$authorlist} {$year}. {$linkedtitle}.";
  } else {
    $citation = "{$linkedtitle} {$year}.";
  }

  //add type-specific citation content
  switch($f['reference-type']) {
    case "JOUR":
      $citation = "{$citation} <i>{$f['secondary-title']}";
      if ($f['volume-number']) $citation = "{$citation}, {$f['volume-number']}";
      if ($f['issue-number']) $citation = "{$citation}({$f['issue-number']})";
      $citation = $citation . '</i>';
      if ($f['start-page']) $citation = "{$citation}, {$f['start-page']}";
      $citation = $citation . '.';
      if ($f['pmcid']) $citation = "{$citation} PMCID: {$f['pmcid']}";
        break;
    case "EDBOOK":
    case "CHAP":
      if (count($editor_arr)>1) {
        $editors = "{$editorlist} (Eds.)";
      } else if (count($editor_arr) == 1){
        $editors = "{$editorlist} (Ed.)";
      }
      if ($f['secondary-title']) {
        $citation = "{$citation} In {$editors}, <i>{$f['secondary-title']}</i>";
      } else {
        $citation = "{$citation} In {$editors}";
      }
      if ($f['start-page']) $citation = "{$citation} (pp. {$f['start-page']})";
      $citation = $citation . ".";
    case "BOOK":
      if ($f['publication-city'] && $f['publisher']) $citation = "{$citation} {$f['publication-city']}: {$f['publisher']}.";
      else if ($f['publication-city']) $citation = "{$citation} {$f['publication-city']}.";
      else if ($f['publisher']) $citation = "{$citation} {$f['publisher']}.";
          break;
    case "CONF":
    case "RPRT":
      if ($f['secondary-title']) $citation = "{$citation} {$f['secondary-title']}.";
      if ($f['publication-city'] && $f['publisher']) $citation = "{$citation} {$f['publication-city']}: {$f['publisher']}.";
      else if ($f['publication-city']) $citation = "{$citation} {$f['publication-city']}.";
      else if ($f['publisher']) $citation = "{$citation} {$f['publisher']}.";
          break;
    default:
  }

  // $_POST['bib_fields']['citation']=$citation;
  // $_POST['bib_fields']['author_arr']=$author_arr;
  // $_POST['bib_fields']['editor_arr']=$editor_arr;
  return $citation;
}

 ?>
