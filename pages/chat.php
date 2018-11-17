<?php 
if(!isset($_GET['uid']))
{
    echo "user id is required for chat which is not present go back to <a href='".page_link('home')."'>Home</a>";
    exit;
}
$uid  = $_GET['uid'];

//don't send text to self
if($uid == $user->user_id)
{
    echo "currently not supported sending messages to self <a href='".page_link('home')."'>Home</a>";
    exit;
}

$profile = user_class($uid);


//user with id zero means doesn't exist
if($profile->user_id == 0)
{
    echo "Can't find that user go back to <a href='".page_link('home')."'>Home</a>";
    exit;
}

//add script tag at end of page
//add info about current user in js
$profile_array = (array) $profile;
unset($profile_array['user_password']);

$profile_array_json = json_encode($profile_array);

$scripts[] = "<script>
window.profile = JSON.parse('$profile_array_json');
</script>";

$scripts[] = '<script src="'.BASE_URL.'/js/chat.js"></script>';


?><section class="user-profile section">
    <div class="container">
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
                        <a class="btn btn-primary" href="<?= page_link('give_feedback&uid='.$profile->user_id); ?>">Give Feedback</a>
                    </div>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
                <!--				Chat form-->
                <div class="widget">
                    <h3 class="widget-header user">Send messages</h3>
                    <div class="main-container">
                        <div id="messages-container" class="messages-container">
                        </div>
                        <div class="new_messages">New messages</div>
                    </div>
                    <form id="message-form">
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text" name="message" class="form-control" />
                            </div>
                            <div class="col-md-3">
                                <input type="submit" value="Send" class="btn btn-primary btn-block">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>