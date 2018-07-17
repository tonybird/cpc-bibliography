<?php

//https://carlalexander.ca/designing-entities-wordpress-custom-post-types/

add_shortcode( 'classtest', 'class_test_func');

function class_test_func() {
  require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');

  $lib = new RefLib();
  $lib->SetContentsFile(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/cpc-ris.txt');
  $importlib = array_slice($lib->refs, 1300, 5);

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
      if (is_string($v)) $v = trim($v);
      if (is_array($v)) $v = array_map('trim', $v);
      $this->$k = $v;
    }
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

    //create citation base for all content types
    $linkedtitle = "<a href='".get_permalink($id)."'>".$this->title . '</a>';
    if ($this->type === "BOOK") $linkedtitle = "<i>{$linkedtitle}</i>";
    if ($this->year) $year = "(".$this->year.")";
    if ($authorlist !== "") {
      $citation = "{$authorlist} {$year}. {$linkedtitle}.";
    } else {
      $citation = "{$linkedtitle} {$year}.";
    }

    //add type-specific citation content
    switch($this->type) {
      case "jour":
      $citation = "{$citation} <i>".$this->{'secondary-title'};
      if ($this->volume) $citation = "{$citation}, ".$this->volume;
      if ($this->number) $citation = "{$citation}(".$this->number.")";
      $citation = $citation . '</i>';
      if ($this->pages) $citation = "{$citation}, ".$this->pages;
      $citation = $citation . '.';
      if ($this->pmcid) $citation = "{$citation} PMCID: ".$this->pmcid;
      break;
      case "edbook":
      case "chap":
      if (count($editor_arr)>1) {
        $editors = "{$editorlist} (Eds.)";
      } else if (count($editor_arr) == 1){
        $editors = "{$editorlist} (Ed.)";
      }
      if ($this->{'secondary-title'}) {
        $citation = "{$citation} In {$editors}, <i>".$this->{'secondary-title'}."</i>";
      } else {
        $citation = "{$citation} In {$editors}";
      }
      if ($this->pages) $citation = "{$citation} (pp. ".$this->pages.")";
      $citation = $citation . ".";
      case "book":
      if ($this->city && $this->publisher) $citation = $citation . " " . $this->city .": ".$this->publisher.".";
      else if ($this->city) $citation = $citation . " " . $this->city . ".";
      else if ($this->publisher) $citation = $citation . " " . $this->publisher.".";
      break;
      case "conf":
      case "rprt":
      if ($this->{'secondary-title'}) $citation = "{$citation} ".$this->secondarytitle.".";
      if ($this->city && $this->publisher) $citation = $citation . " " . $this->city .": ".$this->publisher.".";
      else if ($this->city) $citation = $citation . " " . $this->city . ".";
      else if ($this->publisher) $citation = $citation . " " . $this->publisher.".";
      break;
      default:
    }

    $this->citation = $citation;
    return $this->citation;
  }

}

 ?>
