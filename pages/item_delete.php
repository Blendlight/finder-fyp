<?php
if(!$is_login)
{
    set_message("You must be login to delete a lost item.", 'danger');
    header('location: '.page_path('login'));
}

if(isset($_POST['submit']))
{
    $id = $_POST['id'];
    $item = get_item($id);
    $q = mysqli_query($conx, 'DELETE FROM item WHERE item_id='.$id);
    $qimage = mysqli_query($conx, 'DELETE FROM image WHERE item_id='.$id);

    //delte images
    foreach($item['images'] as $image)
    {
        if(is_file(BASE_PATH.'/uploads/images/'.$image['name'])){
            unlink(BASE_PATH.'/uploads/images/'.$image['name']);
            set_message('deleted image '.$image['name'], 'success');
        }else{
            set_message('not deleted image '.$image['name'], 'danger');
        }
    }

    if($q && mysqli_affected_rows($conx)>0)
    {
        set_message('Item deleted', 'success');
    }else{
        set_message('Item deletion failed', 'danger');
    }

    header('location: '.page_link('dashboard'));
    exit;
}

$error = false;
$item = null;
$id = null;
if(!isset($_GET['id']))
{
    set_message("Id not present.", 'danger');
    $error = true;
}else{
    $id = $_GET['id'];
    $item = get_item($id);
}

if(!$item && !$error)
{
    set_message("Item with ID $id not found.", 'danger');
    $error = true;
}

?>
<div class="container mt-5 mb-5">
    <div>
        <?php show_messages(); ?>
    </div>
    <?php if(!$error): ?>
    <h3>You are going to delete item <span class="text-info"><?= $item['name']; ?></span></h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $item['item_id']; ?>">
        <input class="btn btn-danger" type="submit" value="Delete" name="submit">
        <a href="<?= page_link('dashboard'); ?>" class="btn btn-default">Cancel</a>
    </form>
    <?php endif; ?>
</div>



