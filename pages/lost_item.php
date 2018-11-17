<?php 
if(!$is_login)
{
    set_message("You must be login to post a lost item.", 'warning');
    header('location: '.page_link('login'));
}

if(isset($_POST['submit']))
{
    $success = true;
    $name = $_POST["name"];
    $details = $_POST["details"];
    $location = $_POST["location"];
    $date = $_POST["date"];
    $submit = $_POST["submit"];

    $q = "
INSERT INTO
  `item`
SET
  `user_account_user_id` = '{$user->user_id}',
  `name` = '{$name}',
  `description` = '{$details}',
  `location` = '{$location}',
  `item_status` = 'lost',
  `lost_date` = '{$date}',
  `post_date` = now()
  ";

    $result = mysqli_query($conx, $q);
    if($result && mysqli_affected_rows($conx))
    {
        //now upload images
        set_message('Item added to site ', 'success');
        $item_id = mysqli_insert_id($conx);
        //array for holding insert query values
        $img_inserts = [];
        if(isset($_FILES['images']))
        {
            $images = upload_files_formater($_FILES['images']);
            foreach($images as $img)
            {
                if($img['error'] == 0)
                {
                    $img_inserts[] = "('$item_id', '{$img['new_name']}')";
                    set_message("Image uploaded ".$img['name'], 'success');
                    move_uploaded_file($img['tmp_name'], BASE_PATH.'/uploads/images/'.$img['new_name']);
                }else{
                    set_message("Can't upload image ".$img['name']." because of error ".$img['error'], 'warning');
                }
            }
        }
        $q_img = 'INSERT INTO `image`(`item_id`, `name`) VALUES '.implode(', ', $img_inserts);
        $result = mysqli_query($conx, $q_img);
        if(!$result)
        {
           set_message("Something went wrong", 'danger');
           set_message("query: ".$q_img, 'info');
        }
    }else{
        set_message('Failed to add item ', 'danger');
    }
    
    
    if($success)
    {
//        header('location: '.page_link('item?id='.$item_id));
    }

}

?><section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Post details about lost Item</h2>
                </div>
            </div>
        </div>
        <div>
            <?php show_messages(); ?>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-8 offset-md-2">
                <!-- product card -->
                <div class="card">
                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST" class="form">
                            <div>
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control" id="name" name="name">
                                <p><small>Name of item.use descriptive name</small></p>
                            </div>
                            <div class="mt-4">
                                <label for="description">Details <span class="text-danger">*</span></label>
                                <textarea required name="details" id="detils" cols="30" rows="10" class="form-control"></textarea>
                                <p>
                                    <small>
                                        Give brief information about the item.
                                    </small>
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="location">Location <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control" id="location" name="location">
                                <p>
                                    <small>
                                        Location where you lost item.
                                        please use pattern.
                                    </small>
                                    <span class="text-danger">city, location</span>
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input required type="date" class="form-control" id="date" name="date">
                                <p>
                                    <small>
                                        Date you lost item.
                                    </small>
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="images">Images</label>
                                <div>
                                    <input name="images[]" id="images"
                                           type="file" multiple accept="image/*">
                                </div>
                                <p>
                                    <small>
                                        Images of item 
                                    </small>
                                </p>
                            </div>
                            <div>
                                <input required type="submit" name="submit" value="Add" class="btn btn-primary mt-3">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>