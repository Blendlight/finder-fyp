<?php 
$title = "Dashboard";

if(isset($_GET['id']))
{
    $userid = $_GET['id'];
    $profile = user_class($userid);
    if($profile->is_login == false)
    {
        $profile = $user;
        set_message('User with id '.$userid." doesn't exists.", 'warning');
    }
}else{
    $profile = $user;
}

//check we are viewing our dashboard or someone else 
$self = false;
if($user->user_id == $profile->user_id)
{
    $self = true;
}





$lost_items = [];

$q = "SELECT * FROM item WHERE item_status='lost' && user_account_user_id=".$profile->user_id;
$query = mysqli_query($conx, $q);
if($query && mysqli_num_rows($query)>0)
{
    $lost_items = $query->fetch_all(1);

    foreach($lost_items as $key=>$item)
    {
        $q = 'SELECT * FROM image WHERE item_id='.$item['item_id'];
        $query = mysqli_query($conx, $q);
        $images = [];
        if($query && mysqli_num_rows($query)>0)
        {
            $images = $query->fetch_all(1);
        }
        $lost_items[$key]['images'] = $images;
    }

}


$found_items = [];

$q = "SELECT * FROM item WHERE item_status='find' && user_account_user_id=".$profile->user_id;

$query = mysqli_query($conx, $q);
if($query && mysqli_num_rows($query)>0)
{
    $found_items = $query->fetch_all(1);

    foreach($found_items as $key=>$item)
    {
        $q = 'SELECT * FROM image WHERE item_id='.$item['item_id'];
        set_message($q);
        $query = mysqli_query($conx, $q);
        $images = [];
        if($query && mysqli_num_rows($query)>0)
        {
            $images = $query->fetch_all(1);
        }
        $found_items[$key]['images'] = $images;


        $q = 'SELECT * FROM feedback WHERE item_id='.$item['item_id'];
        $query = mysqli_query($conx, $q);
        $feedback = null;
        if($query && mysqli_num_rows($query)>0)
        {
            $feedback = mysqli_fetch_assoc($query);
        }

        $found_items[$key]['feedback'] = $feedback;
    }

}


$q = "SELECT * FROM feedback WHERE user_id='".$profile->user_id."'";
$query = mysqli_query($conx, $q);
$feedbacks = [];
if($query && mysqli_num_rows($query)>0)
{
    $feedbacks = mysqli_fetch_all($query, 1);
    foreach($feedbacks as $key=>$feedback)
    {
        $feedbacks[$key]['item'] = get_item($feedback['item_id']);
    }
}
?>
<!--==================================
=            User Profile            =
===================================-->
<section class="dashboard section">
    <!-- Container Start -->
    <div class="container">
        <div>
            <?php show_messages(); ?>
        </div>
        <!-- Row Start -->
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                <div class="sidebar">
                    <!-- User Widget -->
                    <div class="widget user-dashboard-profile">
                        <!-- User Image -->
                        <div class="profile-thumb">
                            <img src="<?= $profile->profile_image_path; ?>" alt="" class="rounded-circle">
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= $profile->name; ?></h5>
                        <p><?= $profile->user_name; ?></p>
                        <?php if($self){ ?>
                        <a href="<?= page_link('profile'); ?>" class="btn btn-main-sm">Edit Profile</a>
                        <?php }else{ ?>
                        <a href="<?= page_link('chat&uid='.$profile->user_id); ?>" class="btn btn-main-sm">Chat</a> | 
                        <a class="btn btn-info btn-main-sm" href="<?= page_link('give_feedback&uid='.$profile->user_id); ?>">Give Feedback</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
                <!-- Recently Favorited -->
                <div class="widget dashboard-container my-adslist">
                    <h3 class="widget-header">Feedbacks</h3>
                    <?php 
                    foreach($feedbacks as $feedback){
                    ?>
                    <div class="feedback">
                        <div class="widget user-dashboard-profile">
                            <!-- User Image -->
                            <a href="<?= page_link('dashboard&id='.$feedback['item']['user']->user_id); ?>">
                                <div class="profile-thumb">
                                    <img src="<?= $feedback['item']['user']->profile_image_path; ?>" alt="" class="rounded-circle">
                                </div>
                                <!-- User Name -->

                                <h5 class="text-center"><?= $feedback['item']['user']->name; ?></h5>
                                <p>
                                    <?= $feedback['item']['user']->user_name; ?>

                                </p>
                            </a>
                            <div>
                                <?= $feedback['feedback']; ?>
                            </div>
                            <div>
                                View item <a href="<?= page_link('item&id='.$feedback['item']['item_id']); ?>"><?= $feedback['item']['name']; ?></a>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                </div><!-- Recently Favorited -->
                <div class="widget dashboard-container my-adslist">
                    <h3 class="widget-header">Lost Items</h3>
                    <table class="table table-responsive product-dashboard-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Item Title</th>
                                <th>Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($lost_items as $item): ?>
                            <tr>
                                <td class="product-thumb">
                                    <img width="80px" height="auto" src="<?= BASE_URL.'/uploads/images/'.$item['images'][0]['name']; ?>" alt="image description"></td>
                                <td class="product-details">
                                    <h3 class="title"><?= $item['name']; ?></h3>
                                    <span class="add-id"><strong>ID:</strong><?= $item['item_id']; ?></span>
                                    <span><strong>Posted on: </strong><time><?= date('Y-m-d', strtotime($item['post_date'])); ?></time> </span>
                                    <span class="status active"><strong>Status</strong><?= $item['item_status']; ?></span>
                                    <span class="location"><strong>Location</strong><?= $item['location']; ?></span>
                                </td>
                                <td>
                                    <?= substr($item['description'], 0, 100); ?>...
                                </td>
                                <td class="action" data-title="Action">
                                    <div class="">
                                        <ul class="list-inline justify-content-center">
                                            <li class="list-inline-item">
                                                <a title="View" class="view" href="<?= page_link('item&id='.$item['item_id']); ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>		
                                            </li>
                                            <li class="list-inline-item">
                                                <a title="View" class="edit" href="<?= page_link('item_edit&id='.$item['item_id']); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>		
                                            </li>
                                            <li class="list-inline-item">
                                                <a title="View" class="delete" href="<?= page_link('item_delete&id='.$item['item_id']); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
                <!-- Recently Favorited -->
                <div class="widget dashboard-container my-adslist">
                    <h3 class="widget-header">Found Items</h3>
                    <table class="table table-responsive product-dashboard-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Item Title</th>
                                <th>Description</th>
                                <th>Feedback</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($found_items as $item): ?>
                            <tr>
                                <td class="product-thumb">
                                    <img width="80px" height="auto" src="<?= BASE_URL.'/uploads/images/'.$item['images'][0]['name']; ?>" alt="image description"></td>
                                <td class="product-details">
                                    <h3 class="title"><?= $item['name']; ?></h3>
                                    <span class="add-id"><strong>ID:</strong><?= $item['item_id']; ?></span>
                                    <span><strong>Posted on: </strong><time><?= date('Y-m-d', strtotime($item['post_date'])); ?></time> </span>
                                    <span class="status active"><strong>Status</strong><?= $item['item_status']; ?></span>
                                    <span class="location"><strong>Location</strong><?= $item['location']; ?></span>
                                </td>
                                <td>
                                    <?= substr($item['description'], 0, 100); ?>...
                                </td>
                                <td>
                                    <?php 
                                    if(!$item['feedback']){
                                        echo '<span class="text-info">No feedback Given</span>';
                                    }else{
                                        echo $item['feedback']['feedback'];
                                    }
                                    ?>
                                </td>
                                <td class="action" data-title="Action">
                                    <div class="">
                                        <ul class="list-inline justify-content-center">
                                            <li class="list-inline-item">
                                                <a title="View" class="view" href="<?= page_link('item&id='.$item['item_id']); ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>		
                                            </li>
                                            <li class="list-inline-item">
                                                <a title="View" class="edit" href="<?= page_link('item_edit&id='.$item['item_id']); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>		
                                            </li>
                                            <li class="list-inline-item">
                                                <a title="View" class="delete" href="<?= page_link('item_delete&id='.$item['item_id']); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>


        </div>
        <!-- Row End -->
    </div>
    <!-- Container End -->
</section>