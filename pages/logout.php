<?php
//we don't want to show the output
$show_page_output = false;
session_start();
session_destroy();

header('location:'.page_link('home'));