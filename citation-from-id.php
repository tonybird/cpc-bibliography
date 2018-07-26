<?php
function citation_from_id($id) {

  // // $author_arr = explode("\r\n", $f['authors']);
  // $author_arr = get_field('authors', $id);
  // $authorlist = "";
  // if (count($author_arr) == 1 || count($author_arr) == 0) {
  //   $authorlist = $author_arr[0];
  // } else if (count($author_arr) == 2) {
  //   $authorlist = $author_arr[0] . " & " . $author_arr[1];
  // } else {
  //   for ($i = 0; $i <= count($author_arr)-2; $i++) {
  //     $authorlist = $authorlist . $author_arr[$i] . "; ";
  //   }
  //   $authorlist = $authorlist . "& " . $author_arr[count($author_arr)-1];
  // }
  //
  // //generate editor array and format editor list for citation
  // $editor_arr = get_field('editors',$id);
  // $editorlist = "";
  // if (count($editor_arr) == 1 || count($editor_arr) == 0) {
  //   $editorlist = $editor_arr[0];
  // } else if (count($editor_arr) == 2) {
  //   $editorlist = $editor_arr[0] . " & " . $editor_arr[1];
  // } else {
  //   for ($i = 0; $i <= count($editor_arr)-2; $i++) {
  //     $editorlist = $editorlist . $editor_arr[$i] . "; ";
  //   }
  //   $editorlist = $editorlist . "& " . $editor_arr[count($editor_arr)-1];
  // }
  //
  // //create citation base for all content types
  // $linkedtitle = "<a href='".get_permalink($id)."'>".get_the_title($id) . '</a>';
  // if (get_field('type',$id) === "BOOK") $linkedtitle = "<i>{$linkedtitle}</i>";
  // if (get_field('year',$id)) $year = "(".get_field('year',$id).")";
  // if ($authorlist !== "") {
  //     $citation = "{$authorlist} {$year}. {$linkedtitle}.";
  // } else {
  //   $citation = "{$linkedtitle} {$year}.";
  // }
  //
  // //add type-specific citation content
  // $type = trim(get_field('type'));
  // switch(get_field('type',$id)) {
  //   case "jour":
  //     $citation = "{$citation} <i>".get_field('secondary-title', $id);
  //     if (get_field('volume',$id)) $citation = "{$citation}, ".get_field('volume',$id);
  //     if (get_field('number',$id)) $citation = "{$citation}(".get_field('number',$id).")";
  //     $citation = $citation . '</i>';
  //     if (get_field('pages',$id)) $citation = "{$citation}, ".get_field('pages',$id);
  //     $citation = $citation . '.';
  //     if (get_field('pmcid',$id)) $citation = "{$citation} PMCID: ".get_field('pmcid',$id);
  //       break;
  //   case "edbook":
  //   case "chap":
  //     if (count($editor_arr)>1) {
  //       $editors = "{$editorlist} (Eds.)";
  //     } else if (count($editor_arr) == 1){
  //       $editors = "{$editorlist} (Ed.)";
  //     }
  //     if (get_field('secondary-title',$id)) {
  //       $citation = "{$citation} In {$editors}, <i>".get_field('secondary-title',$id)."</i>";
  //     } else {
  //       $citation = "{$citation} In {$editors}";
  //     }
  //     if (get_field('pages',$id)) $citation = "{$citation} (pp. ".get_field('pages',$id).")";
  //     $citation = $citation . ".";
  //   case "book":
  //     if (get_field('city',$id) && get_field('publisher',$id)) $citation = $citation . " " . get_field('city',$id) .": ".get_field('publisher',$id).".";
  //     else if (get_field('city',$id)) $citation = $citation . " " . get_field('city',$id) . ".";
  //     else if (get_field('publisher',$id)) $citation = $citation . " " . get_field('publisher',$id).".";
  //         break;
  //   case "conf":
  //   case "rprt":
  //     if (get_field('secondary-title',$id)) $citation = "{$citation} ".get_field('secondary-title',$id).".";
  //     if (get_field('city',$id) && get_field('publisher',$id)) $citation = $citation . " " . get_field('city',$id) .": ".get_field('publisher',$id).".";
  //       else if (get_field('city',$id)) $citation = $citation . " " . get_field('city',$id) . ".";
  //     else if (get_field('publisher',$id)) $citation = $citation . " " . get_field('publisher',$id).".";
  //         break;
  //   default:
  // }
  //
  // // $_POST['bib_fields']['citation']=$citation;
  // // $_POST['bib_fields']['author_arr']=$author_arr;
  // // $_POST['bib_fields']['editor_arr']=$editor_arr;
  // // return $citation;
  echo "citation_from_id() is deprecated";
  return "citation_from_id() is deprecated";
}

 ?>
