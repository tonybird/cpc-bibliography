<?php

//https://carlalexander.ca/designing-entities-wordpress-custom-post-types/

add_shortcode( 'classtest', 'class_test_func');

function class_test_func() {
  require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');

  // $str = file_get_contents(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/multiline-abstract.ris');
  // function remove_breaks($matches) {
  // 	return preg_replace( "/\r|\n/", " ", $matches[1] )."\nAD  -";
  // }
  // $str = preg_replace_callback ( "/(AB  - (.*[\n]){2,}?)[A-Z]{2}  -/" , "remove_breaks" , $str );
  // echo "<pre>".$str."</pre>";
  // file_put_contents(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/multiline-abstract-output.ris', $str);

  $lib = new RefLib();
  // $lib->SetContentsFile($string);
  $lib->SetContentsFile(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/cpc-endnote.xml');
  // $importlib = array_slice($lib->refs, 1300, 5);

  print_r($lib);
  echo "</br></br>";
  $importlib = $lib->refs;

  foreach($importlib as $num => $fields) {
    $b = new Bibliography_Entry();
    $b->add_reflib_meta($fields);

    $id = wp_insert_post($b->get_bib_post_data());
    echo "Importing <b>{$b->title}</b></br>";
    if(is_wp_error($id)){
      echo $id->get_error_message();
    }
  }
}


class Bibliography_Entry
{
  const POST_TYPE = 'bib';

  public function get_bib_post_meta() {
    $this->generate_citation();
    $meta_arr = array();
    foreach ($this as $k => $v) {
      $meta_arr[$k] = $v;
    }
    return $meta_arr;
  }

  public function get_bib_post_data() {
    return array(
      'post_title' => $this->title,
      'post_name' => $this->id,
      'post_type' => 'bib',
      'post_status' => 'publish',
      'meta_input' => $this->get_bib_post_meta()
    );
  }

  public function set_bib_field($k, $v) {
    $this->$k = $v;
  }

  public function add_reflib_meta($fields) {
    foreach ($fields as $k => $v) {
      if (is_string($v)) {
        $v = trim($v);
        echo "$k: $v</br>";
      }
      if (is_array($v)) {
        echo "$k: ";
        $v = array_map('trim', $v);
        print_r($v);
        echo "</br>";
      }
      $this->$k = $v;
    }
  }

  public function set_post_id($pid) {
    $this->post_id = $pid;
  }

  public function generate_citation() {

    // $author_arr = $this->authors;
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
    // $editor_arr = $this->editors;
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
    // $editors = "";
    // if (count($editor_arr)>1) $editorlist = "$editorlist (Eds.)";
    // else if ((count($editor_arr)) == 1 && !empty($editor_arr[0])) {
    //     $editorlist = "$editorlist (Ed.)";
    //   }
    //
    // $pages = $this->pages;
    // if ($volume && $pages) {
    //   $volume = "$volume, ";
    //   $pages = "$pages.";
    // } elseif ($volume) $volume = "$volume.";
    // elseif ($pages) $pages = "$pages.";
    //
    // $journal = $this->{'title-secondary'};
    // if ($journal && ($volume || $pages)) {
    //   $journal = "$journal, ";
    // } else if ($journal) $journal = "$journal.";
    //
    // if ($this->year) $year = "($this->year).";
    // $title = $this->title;
    //
    // $city = "";
    // $publisher = "";
    // if ($this->publisher && $this->city) {
    //   $city = "$this->city: ";
    //   $publisher = "$this->publisher.";
    // } else if ($this->city) $city = "$city.";
    //   else if ($this->publisher) $publisher = "$publisher.";
    //
    // $url = get_permalink($id);
    //
    // switch($this->type) {
    //
    //   case "Journal Article":
    //   case "Magazine":
    //     if ($this->volume && $this->number) $volume = "$this->volume($this->number)";
    //     else if ($this->volume) $volume = $this->volume;
    //     else if ($this->number) $volume = $this->number;
    //     else $volume = "";
    //     $citation = "$authorlist $year <a href='$url'>$title.</a> <i>$journal</i> <i>$volume</i> $pages";
    //     break;
    //
    //   case "Book":
    //   case "Serial":
    //     $title = "<i>$title</i>";
    //   case "Conference":
    //   case "Report":
    //     if ($editorlist && $authorlist) {
    //       $citation = "$authorlist $year <a href='$url'>$title.</a> {$editorlist} {$city}{$publisher}";
    //     } else if ($editorlist) {
    //       $citation = "$editorlist $year <a href='$url'>$title.</a> {$city}{$publisher}";
    //     } else if ($authorlist) {
    //       $citation = "$authorlist $year <a href='$url'>$title.</a> {$city}{$publisher}";
    //     } else {
    //       $citation = "<a href='$url'>$title</a> {$year} {$city}{$publisher}";
    //     }
    //
    //     break;
    //
    //   case "Thesis/Dissertation":
    //     $citation = "$authorlist $year <a href='$url'>$title.</a> Master's thesis / Doctoral dissertation, $publisher";
    //   break;
    //
    //   case "Book Section":
    //   case "Book Chapter":
    //   case "Edited Book":
    //   case "Generic":
    //     $booktitle = "";
    //     if ($this->{'title-secondary'}) {
    //       $booktitle = "<i>".$this->{'title-secondary'}."</i>";
    //       if ($this->pages) $booktitle = "$booktitle (pp. $pages).";
    //     }
    //     $title = "<i>$title</i>";
    //
    //     if ($editorlist && $authorlist) {
    //       if ($booktitle) $citation = "$authorlist $year <a href='$url'>$title.</a> In $editorlist, $booktitle. $city $publisher";
    //       else $citation = "$authorlist $year <a href='$url'>$title.</a> In $editorlist. $city $publisher";
    //     } else if ($editorlist) {
    //       if ($booktitle) $citation = "<a href='$url'>$title.</a> $year. In $editorlist, $booktitle. $city $publisher";
    //       else $citation = "<a href='$url'>$title.</a> $year. $editorlist. $city $publisher";
    //     } else if ($authorlist) $citation = "$authorlist $year <a href='$url'>$title.</a> {$city}{$publisher}";
    //     else $citation = "<a href='$url'>$title</a> {$year} {$city}{$publisher}";
    //
    //   break;
    //
    //   if ($this->pmcid) $citation = "$citation PMCID: $this->pmcid";
    //   if ($this->nihmsid) $citation = "$citation NIHMSID: $this->nihmsid";
    //
    //   if ($citation = "") $citation = "<a href='$url'>$title.</a>";
    //
    // // if ($this->type == "Journal Article" || $this->type == "Magazine") {
    // //   if ($this->volume && $this->number) $volume = "$this->volume($this->number)";
    // //   else if ($this->volume) $volume = $this->volume;
    // //   else if ($this->number) $volume = $this->number;
    // //   else $volume = "";
    // //
    // //
    // //
    // //   $citation = "$authorlist $year <a href='$url'>$title.</a> <i>$journal</i> <i>$volume</i> $pages";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $author_arr = $this->authors;
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
    // $authorlist = "AUTHORS GO HERE: ".$this->authors;

    //generate editor array and format editor list for citation
    $editor_arr = $this->editors;
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

    if ($this->post_id != null) {
      $id = $this->post_id;
    }
    //create citation base for all content types
    $linkedtitle = "<a href='".get_permalink($id)."'>".$this->title . '</a>';
    if ($this->type === "Book" || $this->type === "Book Section" || "Book Chapter") $linkedtitle = "<i>{$linkedtitle}</i>";
    if ($this->year) $year = "(".$this->year.")";
    if ($authorlist !== "") {
      $citation = "{$authorlist} {$year}. {$linkedtitle}.";
    } else {
      $citation = "{$linkedtitle} {$year}.";
    }

    //add type-specific citation content
    switch($this->type) {
      case "Journal Article":
      case "Magazine":
      $citation = "{$citation} <i>".$this->{'title-secondary'};
      if ($this->volume) $citation = "{$citation}, ".$this->volume;
      if ($this->number) $citation = "{$citation}(".$this->number.")";
      $citation = $citation . '</i>';
      if ($this->pages) $citation = "{$citation}, ".$this->pages;
      $citation = $citation . '.';
      if ($this->pmcid) $citation = "{$citation} PMCID: ".$this->pmcid;
      break;
      case "Edited Book":
      case "Book Section":
      case "Book Chapter":
      case "Generic":
      if (count($editor_arr)>1) {
        $editors = "{$editorlist} (Eds.)";
      } else if ((count($editor_arr)) == 1 && !empty($editor_arr[0])) {
        $editors = "{$editorlist} (Ed.)";
      }

      if ($this->{'title-secondary'} && $editors != "") {
        $citation = "{$citation} In {$editors}, <i>".$this->{'title-secondary'}."</i>";
      } else if ($editors != "") {
        $citation = "{$citation}. {$editors}";
      } else if ($this->{'title-secondary'} != "") {
        $citation = "{$citation} In <i>".$this->{'title-secondary'}."</i>";
      }

      if ($this->pages) $citation = "{$citation} (pp. ".$this->pages.")";
      if (substr($citation,-1) !=".") $citation = $citation . ".";
      case "Book":
      case "Serial":
      case "Report":
      if ($this->city && $this->publisher) $citation = $citation . " " . $this->city .": ".$this->publisher.".";
      else if ($this->city) $citation = $citation . " " . $this->city . ".";
      else if ($this->publisher) $citation = $citation . " " . $this->publisher.".";
      break;
      case "Conference":
      case "Report":
      if ($this->{'title-secondary'}) $citation = "{$citation} ".$this->secondarytitle.".";
      if ($this->city && $this->publisher) $citation = $citation . " " . $this->city .": ".$this->publisher.".";
      else if ($this->city) $citation = $citation . " " . $this->city . ".";
      else if ($this->publisher) $citation = $citation . " " . $this->publisher.".";
      break;
      case "Thesis":
      if ($publisher) $citation = $citation." Master's thesis / Doctoral dissertation, $publisher.";
      else $citation = $citation . " Master's thesis / Doctoral dissertation.";
      default:
    }

    $this->authorlist = $authorlist;
    $this->citation = $citation;
    return $this->citation;
  }
}

 ?>
