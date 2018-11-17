<?php 
if(!$is_login)
{
    set_message("You must be login to post a lost item.", 'warning');
    header('location: '.page_link('login'));
}

$error = false;

if(isset($_POST['submit']))
{   
    $success = true;
    $item_id = $_POST['id'];
    $name = $_POST["name"];
    $details = $_POST["details"];
    $location = $_POST["location"];
    $date = $_POST["date"];
    $submit = $_POST["submit"];
    $delete_images = [];
    if(isset($_POST['delete_images']))
    {
        $delete_images = $_POST['delete_images'];
    }

    $q = "
UPDATE
  `item`
SET
  `name` = '{$name}',
  `description` = '{$details}',
  `location` = '{$location}',
  `lost_date` = '{$date}'
WHERE
    item_id='$item_id'
";

    $result = mysqli_query($conx, $q);
    if($result && mysqli_affected_rows($conx))
    {
        //now upload images
        set_message('Item updated ', 'success');

    }else{
        set_message('Failed to Update item maybe you have not make any changes or query failure', 'danger');
    }

    //array for holding insert query values
    $img_inserts = [];
    if(isset($_FILES['images']))
    {
        $images = upload_files_formater($_FILES['images']);
        if(count($images)==1 && $images[0]['name'] == '')
        {
            $images = [];
        }
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
        if(!empty($img_inserts)){
            $q_img = 'INSERT INTO `image`(`item_id`, `name`) VALUES '.implode(', ', $img_inserts);
            $result = mysqli_query($conx, $q_img);
            if(!$result)
            {
                set_message("Something went wrong", 'danger');
                set_message("query: ".$q_img, 'info');
            }
        }

    }


    if(!empty($delete_images))
    {
        $sq ="SELECT * FROM image WHERE image_id IN (".implode(", ", $delete_images).")";
        $squery = mysqli_query($conx, $sq);
        if($squery && mysqli_num_rows($squery)>0){
            $images = mysqli_fetch_all($squery, 1);


            $q = "DELETE FROM image WHERE image_id IN (".implode(", ", $delete_images).")";
            $result = mysqli_query($conx, $q);
            if($result && mysqli_affected_rows($conx)>0)
            {
                set_message('Image deleted from database', 'success');
                //now unlink images
                foreach($images as $image)
                {
                    $image_path = BASE_PATH.'/uploads/images/'.$image['name'];
                    if(file_exists($image_path))
                    {
                        unlink($image_path);
                    }
                }
            }
        }
    }



}

if(!isset($_GET['id']))
{
    set_message('Id not availible in URL', 'danger');
    $error = true;
}

$item_id = $_GET['id'];

$item = get_item($item_id);
if(!$item)
{
    $error = true;
    set_message("Item not found of id ".$item_id, 'danger');
}


?><section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Update lost Item <span class="text-info"><?= $item['name']; ?></span></h2>
                </div>
            </div>
        </div>
        <div>
            <?php show_messages(); ?>
        </div>
        <?php if($error): ?>
        <h4>Goto <a href="<?= page_link('dashboard'); ?>">dashboard</a></h4>
        <?php else: ?>
        <div class="row">
            <div class="col-sm-12 col-md-8 offset-md-2">
                <!-- product card -->
                <div class="card">
                    <div class="card-body">
                        <form enctype="multipart/form-data" method="POST" class="form">
                            <input type="hidden" name="id" value="<?= $item_id; ?>">
                            <div>
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input value="<?= $item['name']; ?>" required type="text" class="form-control" id="name" name="name">
                                <p><small>Name of item.use descriptive name</small></p>
                            </div>
                            <div class="mt-4">
                                <label for="description">Details <span class="text-danger">*</span></label>
                                <textarea required name="details" id="detils" cols="30" rows="10" class="form-control"><?= $item['description']; ?></textarea>
                                <p>
                                    <small>
                                        Give brief information about the item.
                                    </small>
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="location">Location <span class="text-danger">*</span></label>
                                <input value="<?= $item['location']; ?>" required type="text" class="form-control" id="location" name="location">
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
                                <input value="<?= date('Y-m-d',strtotime($item['lost_date'])); ?>" required type="date" class="form-control" id="date" name="date">
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
                                        Upload new images 
                                    </small>
                                </p>
                            </div>
                            <div class="mt-4">
                               <h4>Delete images</h4>
                                <label for="delete_images">Existing Images</label>
                                <p>
                                    <small>
                                        select those images you want to delete
                                    </small>
                                </p>
                                <div>
                                    <table class="table table-bordered table-striped table-hovered">
                                        <thead>
                                            <tr>
                                                <td></td>
                                                <td>Image</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($item['images'] as $image): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="delete_images[]" value="<?= $image['image_id'] ?>">
                                                </td>
                                                <td>
                                                    <img class="img-fluid" src="<?= image_path($image['name']); ?>" alt="">
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <p>
                                    <small>
                                        select those images you want to delete
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
        <?php endif; ?>
    </div>
</section>