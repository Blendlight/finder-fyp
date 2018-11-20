<?php

//make_menu_link(link, childs=[], login=false, admin=false)

define("LINK_ALL", 0);//show to all
define("LINK_LOGIN_ONLY", 1);//show only to login users
define("LINK_ADMIN_ONLY", 2);//show only to admin
//define("LINK_ADMIN_NOT", 3);//show to everyone except admin
//define("LINK_ADMIN_NOT_LOGIN", 4);//show to login but not admin
define("LINK_NOT_LOGIN", 5);//show to users who are not login

//auto links
$links = array(
    "Home"=>make_menu_link('/home'),
    "Dashboard"=>make_menu_link('/dashboard',[], LINK_LOGIN_ONLY),
    "Inbox"=>make_menu_link('/inbox', [], LINK_LOGIN_ONLY),
    "Admin"=>make_menu_link('admin', [], LINK_ADMIN_ONLY),
    "dropdown"=>make_menu_link('#abc', array(
        "Category"=>make_menu_link('/category')
    ))
);

//manualy
$login_logout_links = array(
    "Login"=>make_menu_link('/login', [], LINK_NOT_LOGIN),
    "Register"=>make_menu_link('/register', [], LINK_NOT_LOGIN),
    "Logout"=>make_menu_link('/logout', [], LINK_LOGIN_ONLY), "Profile"=>make_menu_link('/profile', [], LINK_LOGIN_ONLY),
);








