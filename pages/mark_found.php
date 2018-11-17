<?php 

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $q = "UPDATE item SET item_status='find' WHERE item_id='$id'";
    $query = mysqli_query($conx, $q);
    if($query && mysqli_affected_rows($conx)>0)
    {
        set_message("Item $id set to found", "success");
    }else{
        set_message("Item not updated ".$q, "danger");
    }
}

header('location:'.page_link('dashboard'));
?>