<?php

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