<?php

$bibliography_meta_fields = array(
    array(
        'label'=> 'Abstract',
        'desc'  => '',
        'id'    => 'abstract',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'URLs',
        'desc'  => 'Enter any URLs for this reference entry, one per line.',
        'id'    => 'urls',
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
                'value' => 'Book'
            ),
            'chap' => array (
                'label' => 'Book Section',
                'value' => 'Book Section'
            ),
            'conf' => array (
                'label' => 'Conference Proceeding / Presentation',
                'value' => 'Conference'
            ),
            'edbook' => array (
                'label' => 'Edited Book',
                'value' => 'Edited Book'
            ),
            'gen' => array (
                'label' => 'Generic / Unpublished',
                'value' => 'Generic'
            ),
            'jour' => array (
                'label' => 'Journal Article',
                'value' => 'Journal Article'
            ),
            'mgzn' => array (
                'label' => 'Magazine Article',
                'value' => 'Magazine'
            ),
            'rprt' => array (
                'label' => 'Report / Working Paper',
                'value' => 'Report'
            ),
            'ser' => array (
                'label' => 'Serial (Book, Monograph)',
                'value' => 'Serial'
            ),
            'thes' => array (
                'label' => 'Thesis / Dissertation',
                'value' => 'Thesis'
            )
        )
    ),
    array(
        'label'=> 'Secondary Title',
        'desc'  => 'Enter a secondary title for this entry such as the book title for a book chapter',
        'id'    => 'title-secondary',
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
        'label'=> 'Funding*',
        'desc'  => '*May be used by CPC Library, not visible to the public',
        'id'    => 'funding',
        'type'  => 'text'
    ),
    array(
        'label'=> 'CPC Notes*',
        'desc'  => '*May be used by CPC Library, not visible to the public',
        'id'    => 'cpc-notes',
        'type'  => 'text'
    ),
    array(
        'label'=> 'PMC*',
        'desc'  => '*May be used by CPC Library, not visible to the public',
        'id'    => 'translated-title',
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
  "URLs"=>"urls",
  "Keyword(s)"=>"keywords",
  "Notes"=>"notes",
  "Reference Type"=>"type",
  "Secondary Title"=>"title-secondary",
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
