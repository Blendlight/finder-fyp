<?php
/*
settings file for site
code in { ... } block will not affect anything
only used for seprating code
*/

//var used for extra scripts tags
//use full scripts tag with src or script
//<script src="..."></script>
//<script>let w....</script>
//array structure
$scripts = [];

$scripts[] = "<script>console.log('scripts are set in bootstrap.php')</script>";

/*site CONSTANTS*/
{
    //define constant BASE_DIR to currentWorkingDirectroy
    //f:xampp/htdocs/proje
    define("BASE_PATH", getcwd());
    define("BASE_DIR", getcwd());
    
    

    // define constant BASE_URL for root address of site like
    // https://site.com, http://localhost/finder
    //use this constant when including css js or creating links in site
    define("BASE_URL", "http://localhost/finder");
    //set base url in js also
    $scripts[] = "<script>window.BASE_URL = '".BASE_URL."';</script>";

    //this should match with ids of database role table
    define('ROLE_ADMIN', 1);
    define('ROLE_AUTHENTICATED', 2);
    define('ROLE_ANONYMOUSE', 3);
}

/*basic site config*/
{
    //start session
    session_start();
    //output buffer start
    //for header(location) problems
    ob_start();

    include BASE_DIR.'/includes/functions.php';
}

/*PAGE REWRITE LOGIC COMES HERE*/
{
    //currently ingoring rewrite rules logic
}

/*database*/
{
    //define database variables
    //$DB_HOST, $DB_USER, $DB_PASS, $DB_NAME
    $DB_HOST = "localhost";
    $DB_USER = "root";
    $DB_PASS = "";
    $DB_NAME = "finder";
    include BASE_DIR.'/includes/conx.php';
}

/* user logic here */
{

    $default_user_fields = array(
        'user_id' => 0,
        'role_id' => 3,
        'user_name' => '',
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'user_password' => '',
        'user_status' => 0,
        'gender' => '',
        'cnic' => '',
        'profile_image'=>'',
        'profile_image_path'=>BASE_URL.'/images/profile.png',
        'is_admin' => false,
        'is_login' => false,
        'is_active' => false,
    );


    $is_login = false;
    $is_admin = false;
    $is_active = false;

    $user = null;

    //if we have id in session try to get data of that userid
    if(isset($_SESSION['id']))
    {

        $user = user_class($_SESSION['id']);
        if($user->is_login){
            $is_login = true;
            if($user->is_active)
            {
                $is_active = true;
            }
            if($user->is_admin)
            {
                $is_admin = true;
            }
        }else{
            //delete $_SESSION if user doesn't exists in table
            unset($_SESSION['id']);
        }

    }else{
        $user = user_class();
    }
    
    //set user in js also
    //we will use json method
    //encode from php and decode at js 
    //for security reasons we will remove some fields from user
    //first we convert user object to array
    $user_array = (array) $user;
    //we unset passowrd
    unset($user_array['user_password']);
    //encode data
    $user_data_json = json_encode($user_array);
    //script tag will create user variable in window
    $scripts[] = "<script>
    window.user = JSON.parse('$user_data_json');
    </script>";

}



/*page links*/
{
    include 'links.php';
}

/*page url logic*/
{
    //set p to home.php by default
    $page = "home";
    //if we have p(page) in url use that
    $page = isset($_GET['page'])?$_GET['page']:$page;
    //set page variable
    $page_include = 'pages/'.$page.'.php';
    //check if page doesn't exists 
    //set page to 404
    if(!is_file($page_include))
    {
        $page_include = 'pages/404.php';
    }elseif(!user_have_page_access_in_links($page))//check page is only for admin or for login user
    {
        $page_include = 'pages/access_denied.php';
    }


}

/*Page setting here*/
{
    //default title of page
    //change at top of template
    $title = "Item finder";
    $show_page_output = true;
}



