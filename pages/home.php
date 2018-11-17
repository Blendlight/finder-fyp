<?php 
$title = "Home of item finder";

$items = [];
$query = mysqli_query($conx, "SELECT * FROM item WHERE item_status='lost' ORDER BY post_date DESC");
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
<!--===============================
=            Hero Area            =
================================-->

<section class="hero-area bg-1 text-center overly">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Header Contetnt -->
                <div class="content-block">
                    <h1>Lost OR Found something</h1>
                    <p>Help people and get helped by people</p>
                    <div class="short-popular-category-list text-center">
                        <a href="<?= page_link('lost_item'); ?>" class="btn btn-primary">Lost something</a>
                        <a href="<?= page_link('found_item'); ?>" class="btn btn-primary">Found something</a>
                    </div>

                </div>
                <!-- Advance Search -->
                <div class="advance-search">
                    <form action="#">
                        <div class="row">
                            <!-- Store Search -->
                            <div class="col-lg-6 col-md-12">
                                <div class="block d-flex">
                                    <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="search" placeholder="Search for store">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="block d-flex">
                                    <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="search" placeholder="Search for store">
                                    <!-- Search Button -->
                                    <button class="btn btn-main">SEARCH</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- Container End -->
</section>

<!--===================================
=            Client Slider            =
====================================-->


<!--===========================================
=            Popular deals section            =
============================================-->

<section class="popular-deals section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Most recent Items</h2>                    
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach($items as $item){ 
    if(isset($item['images'][0]))
    {
        $img = BASE_URL."/uploads/images/".$item['images'][0]['name'];
    }else{
        //default image
        $img = BASE_URL."/images/404.png";
    }




            ?>
            <div class="col-sm-12 col-lg-4">
                <!-- product card -->
                <div class="product-item bg-light">
                    <div class="card">
                        <div class="thumb-content">
                            <!-- <div class="price">$200</div> -->
                            <a href="<?= page_link('item&id='.$item['item_id']); ?>">
                                <img class="card-img-top img-fluid" src="<?= $img; ?>" alt="Card image cap">
                            </a>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title"><a href=""><?= $item['name']; ?></a></h4>
                            <ul class="list-inline product-meta">
                                <li class="list-inline-item">
                                    <a href=""><i class="fa fa-calendar"></i><?= $item['lost_date']; ?></a>
                                </li>
                            </ul>
                            <p class="card-text"><?= $item['description']; ?></p>
                        </div>
                    </div>
                </div>



            </div>
            <?php } ?>
        </div>
    </div>
</section>