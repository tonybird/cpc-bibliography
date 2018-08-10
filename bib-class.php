<?php

add_shortcode( 'classtest', 'class_test_func');

function class_test_func() {
  require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');

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
