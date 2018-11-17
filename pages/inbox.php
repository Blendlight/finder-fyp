<?php 

$uid = $user->user_id;

//select messages where current user is sender
$sender_query = mysqli_query($conx, "SELECT DISTINCT(user_id2) FROM message WHERE user_id='$uid'");
//select messages where current user is receiver
$reciver_query = mysqli_query($conx, "SELECT DISTINCT(user_id) FROM message WHERE user_id2='$uid'");

//fetch index array
$senders = mysqli_fetch_all($sender_query);
$recivers = mysqli_fetch_all($reciver_query);
//merge both arrays
$peers = array_merge($senders, $recivers);

//array return from mysqli_fetch_all is like
// 0=>[0=>Hello]
// 1=>[0=>World]
// we need inner content so we map through it and return Hello and world
//which is on index 0 so we have final array which is like
//0=>Hello
//1=>World
$peers_ids = array_map(function($el){
    return $el[0];
}, $peers);

//some of ids will be repeated we will need unique ids
$peers_ids = array_unique($peers_ids);


$messages = [];
$q = "";
foreach($peers_ids as $uid2)
{
    $message_query = mysqli_query($conx, "SELECT * FROM message WHERE (user_id='$uid' && user_id2='$uid2') || (user_id='$uid2' && user_id2='$uid') ORDER BY date DESC LIMIT 1");   
    $message = mysqli_fetch_assoc($message_query);
    $messages[] = $message;
}

//now sort all messages by date desc
usort($messages, function($a, $b){
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    if($t1 == $t2)
    {
        return 0;
    }
    return $t1<$t2?1:-1;
});
?>

<div class="container">

    <?php 

    foreach($messages as $message)
    {
        //we get the other user id
        if($message['user_id'] ==  $user->user_id)
        {
            $otherId = $message['user_id2'];
        }else{
            $otherId = $message['user_id'];
        }
        $profile = user_class($otherId);
    ?>
    <div class="inbox-message mt-5 mb-5">
        <div class="row">
            <div class="col-md-1 offset-1">
                <img class="img-fluid rounded-circle" src="<?= $profile->profile_image_path; ?>" alt="">
            </div>
            <div class="col-md-10">
                <h4 class="mb-0"><?= $profile->user_name; ?></h4>
                <div class="small">
                    <small>
                    On <?= $message['date']; ?>
                    </small>
                </div>
                <div>
                   <strong>
                       <?php 
                       if($message['user_id'] == $user->user_id)
                       {
                           echo 'You:';
                       }else{
                           echo 'He:';
                       }
                       ?>
                   </strong>
                    <span><?= $message['message']; ?></span>
                    <a class="btn btn-primary btn-xs" href="<?= page_link('chat&uid='.$profile->user_id); ?>">View</a>
                </div>
            </div>
        </div>
    </div>
    <?php 
    }
    ?>

</div>






