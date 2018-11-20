<?php
include "settings.php";
ob_start();
if(!$is_login)
{
    header('location: ../index.php');
}else
{
    include "header.php";
    if(is_file($page_real))
    {
        include $page_real;
    }else
    {
        include "templates/404.php";
    }
    include "footer.php";

}