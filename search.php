<?php 
include 'bootstrap.php';
//header it to template search page

header('location:'.page_link('search&name='.$_GET['name'].'&location='.$_GET['location']));

?>