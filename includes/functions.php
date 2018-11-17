<?php

function make_menu_link($link, $childs=[], $access=LINK_ALL){
    $link =  [
        'link'=>$link,
        'childs' => $childs,
        'access'=>$access
    ];
    save_page_access($link);
    return $link;
}

function page_link($link)
{
    return BASE_URL.'?page='.$link;
}

function pre($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

//format the $_FILES array
//group elements details together 
function upload_files_formater($arr)
{
    $result = [];
    if($arr)
    {
        $length = count($arr['name']);
        $ext =  pathinfo($arr['name'][0], 4);
        for($i=0; $i < $length; $i++)
        {
            $result[] = array(
                'name' => $arr['name'][$i],
                'new_name' => uniqid().sprintf("%04d", rand(0,1000)).'.'.$ext,
                'type' => $arr['type'][$i],
                'tmp_name' => $arr['tmp_name'][$i],
                'error' => $arr['error'][$i],
                'size' => $arr['size'][$i],
            );
        }
    }
    return $result;
}


function user_class($id=null)
{
    global $conx, $default_user_fields;

    //convert to user
    $default_user = (object)$default_user_fields;
    $user = $default_user;
    if($id)
    {
        $q = mysqli_query($conx, "SELECT * FROM `user_account` WHERE user_id='{$id}'");

        if($q && mysqli_num_rows($q)>0){
            $user = mysqli_fetch_object($q);
            if($user->profile_image == null)
            {
                $user->profile_image_path = $default_user->profile_image_path;
            }else{
                $user->profile_image_path = BASE_URL.'/uploads/images/'.$user->profile_image;
            }
            $user->is_login = true;
            $user->is_active = false;
            $user->is_admin = false;
            if($user->role_id == ROLE_ADMIN)
            {
                $user->is_admin = true;
            }
            if($user->user_status == 1)
            {
                $user->is_active = true;
            }
        }else{
            $user = $default_user;
        }

    }

    return $user;
}



//this is only for 2 level menus 
//todo:make it n level
function render_menu_links($links, $level=0)
{
    global $page, $is_login, $is_admin;
    ob_start();
    if($level==0){
?>
<ul class="navbar-nav ml-auto main-nav ">
    <?php foreach($links as $title=>$prop):
                  $link = $prop['link'];
                  $childs = $prop['childs'];
                  $access = $prop['access'];

                  if(!have_access($access))
                  {
                      continue;
                  }



                  $li_class = '';
                  if("/$page" == $link)
                  {
                      $li_class = 'active';
                  }
                  $href = page_link(substr($link, 1));

                  if($link[0] == '#')
                  {
                      $href = $link;
                  }


                  $childs_rendered = "";

                  $drop_down = false;

                  if($childs)
                  {
                      $nested_childs = render_menu_links($childs, $level+1);
                      $childs_rendered = $nested_childs['links'];
                      if($nested_childs['contains_active'])
                      {
                          $li_class = 'active';
                      }
                      $drop_down = true;
                  }


    ?>
    <?php if($drop_down == false): ?>
    <li class="nav-item <?= $li_class; ?>">
        <a class="nav-link" href="<?= $href; ?>"><?= $title; ?></a>
    </li>
    <?php else: ?>
    <li class="nav-item <?= $li_class; ?> dropdown dropdown-slide">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $title; ?> <span><i class="fa fa-angle-down"></i></span>
        </a>
        <!-- Dropdown list -->
        <?php echo $childs_rendered ?>

    </li>
    <?php endif; ?>

    <?php endforeach;?>
</ul>
<?php 

                 }else
    {

        //if nested links contains active so it will return true
        //else false so parent link will get active class
        $contains_active = false;
?>
<div class="dropdown-menu dropdown-menu-right">
    <?php foreach($links as $title=>$prop): 
        $link = $prop['link'];
        $childs = $prop['childs'];
        $access = $prop['access'];

        if($access == LINK_LOGIN_ONLY && $is_login == false)
        {
            continue;
        }

        if($access == LINK_ADMIN_ONLY && $is_admin == false)
        {
            continue;
        }

        $li_class = '';
        if("/$page" == $link)
        {
            $li_class = 'active';
            $contains_active = true;
        }
        $href = BASE_URL.'?page='.substr($link, 1);

        if($link[0] == '#')
        {
            $href = $link;
        }

    ?>
    <a class="dropdown-item <?php echo $li_class ?>" href="<?= $href; ?>"><?= $title; ?></a>
    <?php endforeach; ?>
</div>
<?php

        return ['links'=>ob_get_clean(), 'contains_active'=>$contains_active];

    }
    return ob_get_clean();
}

function have_access($access)
{
    global $is_login, $is_admin;
    if($access == LINK_LOGIN_ONLY && !$is_login)
    {
        return false;
    }elseif($access == LINK_NOT_LOGIN && $is_login)
    {
        return false;
    }elseif($access == LINK_ADMIN_ONLY && $is_admin==false)
    {
        return false;
    }

    return true;
}

//save all make_menu_link links in this
// when called without link it will reutrn all links just
// if called with link it will save it and return all links
function save_page_access($link=null)
{
    static $links = [];
    if($link)
    {
        $links[$link['link']] = $link; 
    }

    return $links;
}

function user_have_page_access_in_links($page)
{
    //get all links which are saved in save_page_access
    $links = save_page_access();
    $access = true;
    if(isset($links["/$page"]))
    {

        $link = $links["/$page"];
        $access = user_have_page_access($link);
    }

    return $access;
}

function user_have_page_access($link) 
{
    $access = $link['access'];
    return have_access($access);
}

function links_to_linear_array($links)
{
    $result = [];

    foreach($links as $title=>$ops)
    {
        $result[$ops['link']] = [
            'title'=>$title,
            'link'=>$ops['link'],
            'access'=>$ops['access']
        ];

        if($ops['childs'])
        {
            $childs_links = links_to_linear_array($ops['childs']);
            $result = array_merge($result, $childs_links);

        }

    }


    return $result;
}


function set_message($msg=null, $type='success')
{
    $_SESSION['msgs'][$type][] = $msg;
}

function clear_messages($type=null)
{
    if($type)
    {
        if(isset($_SESSION['msgs'][$type])){
            unset($_SESSION['msgs'][$type]);
        }
    }else{
        unset($_SESSION['msgs']);
    }
}

function show_messages()
{
    if(!isset($_SESSION['msgs']))
    {
        return;
    }
    
    $all_msgs = $_SESSION['msgs'];
    foreach($all_msgs as $type=>$msgs)
    {
        if(count($msgs)==0)
        {
            continue;
        }
?>
<div class="alert alert-<?= $type; ?>">
    <div class="close" data-dismiss="alert">&times;</div>
    <?php
        foreach($msgs as $msg)
        {
    ?>
    <div class="alert-msg">
        <?php print_r($msg); ?>
    </div>
    <?php 
        }
    ?>
</div>
<?php 
    }
    //unset the msgs
    unset($_SESSION['msgs']);
}


function get_item($item_id)
{
    global $conx;
    $item = null;
    $q = 'SELECT * FROM item WHERE item_id='.$item_id;
    $query = mysqli_query($conx, $q);

    if($query && mysqli_num_rows($query)>0)
    {
        $item = mysqli_fetch_assoc($query);
        $item['user'] = user_class($item['user_account_user_id']);
        $q = 'SELECT * FROM image WHERE item_id='.$item['item_id'];
        $query = mysqli_query($conx, $q);
        $images = [];
        if($query && mysqli_num_rows($query)>0)
        {
            $images = $query->fetch_all(1);
        }
        $item['images'] = $images;
    }

    return $item;
}

function image_path($name)
{
    if(file_exists(BASE_PATH.'/uploads/images/'.$name)){
        $name =  BASE_URL.'/uploads/images/'.$name;
    }else{
        $name =  BASE_URL.'/images/404.png';
    }
    return $name;
}


