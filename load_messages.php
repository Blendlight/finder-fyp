<?php
require 'bootstrap.php';

//variable used for offset
$total = $_POST['total'];
$uid =  $user->user_id;
$uid2 = $_POST['uid'];
$response = [];
//ONLY LOAD CORRECT MESSAGES
$q = "SELECT * FROM `message` WHERE 
(user_id='$uid' && user_id2='$uid2') || (user_id='$uid2' && user_id2='$uid')
ORDER BY date  LIMIT $total,1000";

$query = mysqli_query($conx, $q);

if($query)
{
    $response['status'] = 'success';
    $response['messages'] = mysqli_fetch_all($query, 1);
}else{
    $response['status'] = 'failed';
    $response['log'] = 'QUERY ERROR MAYBE I DONT KNOW';
}


echo json_encode($response);