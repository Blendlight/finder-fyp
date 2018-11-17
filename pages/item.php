<?php

if(!isset($_GET['id'])){
    echo 'This page need to be called with id';
    exit;
}

$id = $_GET['id'];


$item = get_item($id);
if(!$item){
    set_message("item not found of id ".$id, 'danger');
}else{

    $q = 'SELECT * FROM feedback WHERE item_id='.$item['item_id'];
    $query = mysqli_query($conx, $q);
    $feedback = null;
    if($query && mysqli_num_rows($query)>0)
    {
        $feedback = mysqli_fetch_assoc($query);
        $feedback['user'] = user_class($feedback['user_id']);
    }

    $item['feedback'] = $feedback;

}
$profile = user_class($item['user_account_user_id']);

?><section class="blog single-blog section">
    <div class="container">
        <div>
            <?php show_messages(); ?>
        </div>
        <?php if($item == null): ?>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque accusantium illum modi dolorem id natus officia aspernatur eveniet vel consequuntur dolores corporis facilis ea ut ullam, unde sunt ad nesciunt.
        </p>
        <?php else: ?>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <article class="single-post">
                    <h3><?= $item['name']; ?></h3>
                    <ul class="list-inline">
                        <li class="list-inline-item">by <a href="<?= page_link("dashboard&id=".$item['user']->user_id); ?>"><?= $item['user']->name ; ?></a></li>
                        <li class="list-inline-item">On <?= $item['post_date']; ?></li>
                    </ul>
                    <div class="row">
                        <?php 
                        $c = 0;
                        foreach($item['images'] as $image):
                        $c++;
                        ?>
                        <div class="col-md-4">
                            <img src="<?= BASE_URL.'/uploads/images/'.$image['name']; ?>" alt="">
                        </div>
                        <?php
                        if($c%3==0)
                        {
                            echo '<div class="clearfix"></div>';
                        }
                        endforeach;
                        ?>
                    </div>
                    <h3>Description</h3>
                    <p><?= $item['description']; ?></p>
                    <h3>Lost in</h3>
                    <p><?= $item['location']; ?></p>
                    <h3>On date</h3>
                    <p><?= $item['lost_date']; ?></p>
                    <?php if($item['feedback']){?>
                    <hr>
                    <h1>Feedback</h1>
                    <p>
                        <?= $item['feedback']['feedback']; ?>
                    </p>
                    <p>
                        Found by <a href="<?= page_link('dashboard&id='.$item['feedback']['user']->user_id); ?>"><?= $item['feedback']['user']->name; ?>
                        </a>
                    </p>
                    <?php } ?>
                    <?php if($item['item_status'] == 'lost'){ ?>
                    <h3 class="text-success">If you have found it Contact me on <span class="text-info"><?= $profile->phone; ?></span></h3>
                    <h3> Or in chat</h3>
                    <?php } ?>
                    <a class="btn btn-primary" href="<?= page_link('chat&uid='.$profile->user_id); ?>">Send message to <?= $profile->name; ?></a>


                    <?php if(0): ?>
                    <ul class="social-circle-icons list-inline">
                        <li class="list-inline-item text-center"><a class="fa fa-facebook" href=""></a></li>
                        <li class="list-inline-item text-center"><a class="fa fa-twitter" href=""></a></li>
                        <li class="list-inline-item text-center"><a class="fa fa-google-plus" href=""></a></li>
                        <li class="list-inline-item text-center"><a class="fa fa-pinterest-p" href=""></a></li>
                        <li class="list-inline-item text-center"><a class="fa fa-linkedin" href=""></a></li>
                    </ul>
                    <?php endif; ?>
                </article>
                <?php if(0): ?>
                <div class="block comment">
                    <h4>Leave A Comment</h4>
                    <form action="#">
                        <!-- Message -->
                        <div class="form-group mb-30">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" rows="8"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <!-- Name -->
                                <div class="form-group mb-30">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <!-- Email -->
                                <div class="form-group mb-30">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-transparent">Leave Comment</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif ?>
    </div>
</section>