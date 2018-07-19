<?php
$referencetypes = array(
  "book" => "Book",
  "chap" => "Book Chapter",
  "conf" => "Conference Proceeding / Presentation",
  "echap" => "Electronic Book Section",
  "edbook" => "Edited Book",
  "elec" => "Electronic Citation",
  "gen" => "Generic / Unpublished",
  "jour" => "Journal Article",
  "mgzn" => "Magazine Article",
  "rprt" => "Report / Working Paper",
  "ser" => "Serial (Book, Monograph)",
  "thes" => "Thesis / Dissertation"
);

// Field Array
$bibliography_meta_fields = array(
    array(
        'label'=> 'Abstract',
        'desc'  => '',
        'id'    => 'abstract',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'URL',
        'desc'  => 'Enter any URLs for this reference entry, one per line.',
        'id'    => 'url',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Keyword(s)',
        'desc'  => 'Enter any keywords for this reference entry, one per line.',
        'id'    => 'keywords',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Notes',
        'desc'  => 'Please enter any other relevant information',
        'id'    => 'notes',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Reference Type',
        'desc'  => 'Select the bibliographic reference type for this entry such as journal article, book, etc.',
        'id'    => 'type',
        'type'  => 'select',
        'options' => array (
            'book' => array (
                'label' => 'Book',
                'value' => 'book'
            ),
            'chap' => array (
                'label' => 'Book Chapter',
                'value' => 'chap'
            ),
            'conf' => array (
                'label' => 'Conference Proceeding / Presentation',
                'value' => 'conf'
            ),
            'echap' => array (
                'label' => 'Electronic Book Section',
                'value' => 'echap'
            ),
            'edbook' => array (
                'label' => 'Edited Book',
                'value' => 'edbook'
            ),
            'elec' => array (
                'label' => 'Electronic Citation',
                'value' => 'elec'
            ),
            'gen' => array (
                'label' => 'Generic / Unpublished',
                'value' => 'gen'
            ),
            'jour' => array (
                'label' => 'Journal Article',
                'value' => 'jour'
            ),
            'mgzn' => array (
                'label' => 'Magazine Article',
                'value' => 'mgzn'
            ),
            'rprt' => array (
                'label' => 'Report / Working Paper',
                'value' => 'rprt'
            ),
            'ser' => array (
                'label' => 'Serial (Book, Monograph)',
                'value' => 'ser'
            ),
            'thes' => array (
                'label' => 'Thesis / Dissertation',
                'value' => 'thes'
            )
        )
    ),
    array(
        'label'=> 'Secondary Title',
        'desc'  => 'Enter a secondary title for this entry such as the book title for a book chapter',
        'id'    => 'secondary-title',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Series Title',
        'desc'  => 'Enter the book series title',
        'id'    => 'series',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Author(s)',
        'desc'  => 'Enter the authors, one per line, in the format: Lastname,Firstname,Suffix</br>For example: Phillips,Albert,Jr.',
        'id'    => 'authors',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Editor(s)',
        'desc'  => 'Enter any editors, one per line, in the format: Lastname,Firstname,Suffix</br>For example: Phillips,Albert,Jr.',
        'id'    => 'editors',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Series Author(s)',
        'desc'  => 'Please enter any series authors, one per line, in the same format as the primary authors field.',
        'id'    => 'series-authors',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Year Published',
        'desc'  => 'Please enter the year published in the following format: YYYY or Forthcoming or In Press.',
        'id'    => 'year',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Volume Number',
        'desc'  => 'Enter the journal volume',
        'id'    => 'volume',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Issue Number',
        'desc'  => '',
        'id'    => 'number',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Page Numbers',
        'desc'  => '',
        'id'    => 'pages',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Edition',
        'desc'  => '',
        'id'    => 'edition',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Publisher',
        'desc'  => 'Enter the name of the publisher',
        'id'    => 'publisher',
        'type'  => 'text'
    ),
    array(
        'label'=> 'City of Publication',
        'desc'  => 'Enter the location of the publisher',
        'id'    => 'city',
        'type'  => 'text'
    ),
    array(
        'label'=> 'ISSN/ISBN',
        'desc'  => '',
        'id'    => 'isbn',
        'type'  => 'text'
    ),
    array(
        'label'=> 'PMCID',
        'desc'  => '',
        'id'    => 'pmcid',
        'type'  => 'text'
    ),
    array(
        'label'=> 'NIHMSID',
        'desc'  => '',
        'id'    => 'nihmsid',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Reference ID',
        'desc'  => 'External reference number which can consist of any alphanumeric characters',
        'id'    => 'id',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Funding Information*',
        'desc'  => '*May be used by CPC Library, not visible to the public',
        'id'    => 'funding',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Affiliation*',
        'desc'  => '*May be used by CPC Library, not visible to the public',
        'id'    => 'affiliation',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Miscellaneous',
        'desc'  => 'Information such as the type of the work',
        'id'    => 'misc',
        'type'  => 'text'
    )
);

$frontendfields = array(
  //Determines the order and title of fields displayed on the front-end
  "Citation"=>"citation",
  "Abstract"=>"abstract",
  "URL"=>"url",
  "Keyword(s)"=>"keywords",
  "Notes"=>"notes",
  "Reference Type"=>"type",
  "Secondary Title"=>"secondary-title",
  "Series Title"=>"series-title",
  "Author(s)"=>"authors",
  "Editor(s)"=>"editors",
  "Series Author(s)"=>"series-authors",
  "Year Published"=>"year",
  "Date Published"=>"date",
  "Journal Name"=>"journal",
  "Volume Number"=>"volume",
  "Issue Number"=>"number",
  "Pages"=>"pages",
  "Edition"=>"edition",
  "Series Volume"=>"series-volume",
  "Publisher"=>"publisher",
  "City of Publication"=>"city",
  "ISSN/ISBN"=>"isbn",
  "DOI"=>"doi",
  "PMCID"=>"pmcid",
  "NIHMSID"=>"nihmsid",
  "Reference ID"=>"id",
  "Miscellaneous"=>"misc"
);
?>
