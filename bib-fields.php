<?php
$referencetypes = array(
  "BOOK" => "Book",
  "CHAP" => "Book Chapter",
  "CONF" => "Conference Proceeding / Presentation",
  "ECHAP" => "Electronic Book Section",
  "EDBOOK" => "Edited Book",
  "ELEC" => "Electronic Citation",
  "GEN" => "Generic / Unpublished",
  "JOUR" => "Journal Article",
  "MGZN" => "Magazine Article",
  "RPRT" => "Report / Working Paper",
  "SER" => "Serial (Book, Monograph)",
  "THES" => "Thesis / Dissertation"
);

$textareas = array
//Multi-line text areas with optional description
(
  array(
    "id" => "abstract",
    "title" => "Abstract",
    "desc" => "",
  ),
  array(
    "id" => "keywords",
    "title" => "Keyword(s)",
    "desc" => "Enter any keywords for this reference entry, one per line.",
  ),
  array(
    "id" => "notes",
    "title" => "Notes",
    "desc" => "Please enter any other relevant information",
  ),
  array(
    "id" => "authors",
    "title" => "Author(s)",
    "desc" => "Enter the authors, one per line, in the format: Lastname,Firstname,Suffix</br>For example: Phillips,Albert,Jr.",
  ),
  array(
    "id" => "editors",
    "title" => "Editor(s)",
    "desc" => "Enter any editors, one per line, in the format: Lastname,Firstname,Suffix</br>For example: Phillips,Albert,Jr.",
  ),
  array(
    "id" => "series-authors",
    "title" => "Series Author(s)",
    "desc" => "Please enter any series authors, one per line, in the same format as the primary authors field.",
  )
);

$textfields = array
//Single-line text fields with optional descriptions
(
  array(
    "id" => "secondary-title",
    "title" => "Secondary Title",
    "desc" => "Enter a secondary title for this entry such as the book title for a book chapter, or a journal/periodical/magazine name for an article",
  ),
  array(
    "id" => "url",
    "title" => "URL",
    "desc" => "Enter a URL for this reference entry. URLs without http:// will be treated as DOIs",
  ),
  array(
    "id" => "series-title",
    "title" => "Series Title",
    "desc" => "Enter the book series title",
  ),
  array(
    "id" => "year-published",
    "title" => "Year Published",
    "desc" => "Please enter the year published in the following format: YYYY or Forthcoming or In Press.",
  ),
  array(
    "id" => "date-published",
    "title" => "Date Published",
    "desc" => "Please enter any additional date information in the following format: YYYY/MM/DD/Other as in 2010/// or 2010/04/01/ or 2010///Spring or ///Forthcoming.",
  ),
  array(
    "id" => "volume-number",
    "title" => "Volume Number",
    "desc" => "Enter the journal volume",
  ),
  array(
    "id" => "issue-number",
    "title" => "Issue Number",
    "desc" => "",
  ),
  array(
    "id" => "start-page",
    "title" => "Start Page Number",
    "desc" => "",
  ),
  array(
    "id" => "end-page",
    "title" => "End Page Number",
    "desc" => "",
  ),
  array(
    "id" => "edition",
    "title" => "Edition",
    "desc" => "",
  ),
  array(
    "id" => "series-volume",
    "title" => "Series Volume",
    "desc" => "Enter the book series volume",
  ),
  array(
    "id" => "publisher",
    "title" => "Publisher",
    "desc" => "Enter the name of the publisher",
  ),
  array(
    "id" => "publication-city",
    "title" => "City of Publication",
    "desc" => "Enter the location of the publisher",
  ),
  array(
    "id" => "issn-isbn",
    "title" => "ISSN/ISBN",
    "desc" => "",
  ),
  array(
    "id" => "doi",
    "title" => "DOI",
    "desc" => "DOI reference number",
  ),
  array(
    "id" => "pmcid",
    "title" => "PMCID",
    "desc" => "",
  ),
  array(
    "id" => "nihmsid",
    "title" => "NIHMSID",
    "desc" => "",
  ),
  array(
    "id" => "reference-id",
    "title" => "Reference ID",
    "desc" => "External reference number which can consist of any alphanumeric characters",
  ),
  array(
    "id" => "misc",
    "title" => "Miscellaneous",
    "desc" => "Information such as the type of the work",
  )
);

$frontendfields = array(
  //Determines the order and title of fields displayed on the front-end
  "Citation"=>"citation",
  "Abstract"=>"abstract",
  "URL"=>"url",
  "Keyword(s)"=>"keywords",
  "Notes"=>"notes",
  "Reference Type"=>"reference-type",
  "Secondary Title"=>"secondary-title",
  "Series Title"=>"series-title",
  "Author(s)"=>"authors",
  "Editor(s)"=>"editors",
  "Series Author(s)"=>"series-authors",
  "Year Published"=>"year-published",
  "Date Published"=>"date-published",
  "Journal Name"=>"journal",
  "Volume Number"=>"volume-number",
  "Issue Number"=>"issue-number",
  "Start Page Number"=>"start-page",
  "End Page Number"=>"end-page",
  "Edition"=>"edition",
  "Series Volume"=>"series-volume",
  "Publisher"=>"publisher",
  "City of Publication"=>"publication-city",
  "ISSN/ISBN"=>"issn-isbn",
  "DOI"=>"doi",
  "PMCID"=>"pmcid",
  "NIHMSID"=>"nihmsid",
  "Reference ID"=>"reference-id",
  "Miscellaneous"=>"misc"
);
?>
