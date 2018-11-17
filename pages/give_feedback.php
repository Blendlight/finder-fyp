<?php

if(isset($_POST['submit']))
{
    $uid = $_POST["uid"];
    $feedback = $_POST["feedback"];
    $item = $_POST["item"];
    $profile = user_class($uid);
    $q = "INSERT INTO feedback SET `user_id`='$uid',`item_id`='$item',`feedback`='$feedback'";
    $query = mysqli_query($conx, $q);
    if($query && mysqli_affected_rows($conx))
    {
        set_message("Feedback given to ".$profile->name, 'success');
        header('location: '.page_link('dashboard'));
        exit;
    }else{
        set_message("Failed to add feedback", 'danger');
    }
    
    
}



if(!isset($_GET['uid']))
{
    echo 'user id required for feedback';
    exit;
}
$uid= $_GET['uid'];

$profile = user_class($uid);

if($profile->user_id == 0)
{
    echo 'user doesn\'t exists ';
    exit;
}



$found_items = [];

$q = "SELECT * FROM item WHERE item_status='find' && user_account_user_id=".$user->user_id. " && item_id NOT IN (SELECT item_id FROM feedback)";


$query = mysqli_query($conx, $q);
if($query && mysqli_num_rows($query)>0)
{
    $found_items = $query->fetch_all(1);
}



?>
<div class="container mb-4">
   <div>
       <?= show_messages(); ?>
   </div>
    <h1>Give Feedback to <?= $profile->name; ?></h1>
    <?php if(count($found_items) == 0){?>

    <h3>No found items</h3>
    <p>
        You don't have any found items.
        if you have some lost item mark them found in dashboard and then give feedback to user <a class="btn btn-primary" href="<?= page_link('dashboard'); ?>">Dahsboard</a>
    </p>

    <?php }else{?>
    <form method="post">
        <input type="hidden" name="uid" value="<?= $uid; ?>">
        <div>
            <label for="feedback">Feedback</label>
            <br>
            <textarea name="feedback" id="feedback" cols="30" rows="10"></textarea>
        </div>
        <div>
            <label for="item">Item</label><br>
            <select class="form-control" name="item" id="item">
                <option value="">--Item--</option>
                <?php foreach($found_items as $item){ ?>
                <option value="<?= $item['item_id']; ?>"><?= $item['name']; ?>d</option>
                <?php } ?>
            </select>
        </div>
        <input class="mt-3 btn btn-primary btn-xs" type="submit" value="Give Feedback" name="submit"/>
    </form>
    <?php } ?>
</div>