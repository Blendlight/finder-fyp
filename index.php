<?php
/*
include settings.php file so we have:
functions, database, session, ob_start, BASE_PATH, 
base_url, page, etc
see settings.php
*/
require 'bootstrap.php';

/*start page output from here
header
body(template)
footer
*/

//include the page first
//we can change title and other header.php content from page file
ob_start();
include $page_include;
$body = ob_get_clean();

ob_start();
include 'header.php';
$header = ob_get_clean();

ob_start();
include 'footer.php';
$footer = ob_get_clean();

//now print to the page
if($show_page_output){
    print $header;
    print $body;
    print $footer;
}