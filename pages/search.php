<?php 

$title = "Search";
$items = [];

$search_pattern = [];
if(isset($_GET['name']))
{
    $search_pattern[] = " name LIKE '%{$_GET['name']}%' ";
    
}

if(isset($_GET['location']))
{
    $search_pattern[] = " location LIKE '%{$_GET['location']}%' ";
}


$q = 'SELECT * FROM item WHERE '.implode(' && ', $search_pattern);
$query = mysqli_query($conx, $q);

if($query && mysqli_num_rows($query)>0)
{
    $items = $query->fetch_all(1);

    foreach($items as $key=>$item)
    {
        $q = 'SELECT * FROM image WHERE item_id='.$item['item_id'];
        $query = mysqli_query($conx, $q);
        $images = [];
        if($query && mysqli_num_rows($query)>0)
        {
            $images = $query->fetch_all(1);
        }
        $items[$key]['images'] = $images;
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
            <div class="col-md-10 offset-md-1">
                <!-- Recently Favorited -->
                <div class="widget dashboard-container my-adslist">
                    <h3 class="widget-header">Search Result <?= $_GET['name']; ?></h3>
                    <table class="table table-responsive product-dashboard-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Title</th>
                                <th>Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($items as $item): ?>
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