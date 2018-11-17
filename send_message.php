<?php 
require 'bootstrap.php';

$uid = $user->user_id;
$uid2 = $_POST['uid'];
$message = $_POST['message'];

$response = [];

$q = "
INSERT INTO
  `message`
SET
  `user_id` = '$uid',
  `user_id2` = '$uid2',
  `message` = '$message',
  `date` = NOW()
";
$query = mysqli_query($conx, $q);
if($query && mysqli_affected_rows($conx)>0)
{
    $response['status'] = 'success';
    
}else{
    $response['status'] = 'failed';
}


echo json_encode($response);
