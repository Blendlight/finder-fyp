<?php
//define host and other variables before including this file

$conx = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if(!$conx)
{
    echo mysqli_connect_error();
    die();
}