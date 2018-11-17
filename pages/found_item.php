<?php 
?><section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Return found items to their owners</h2>
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
                        <form action="<?= BASE_URL.'/search.php';?>" class="form">
                            <div>
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control" id="name" name="name">
                                <p><small>Name of item.use simple name samsung mobile etc</small></p>
                            </div>
                            <div class="mt-4">
                                <label for="location">City <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control" id="location" name="location">
                                <p>
                                    <small>
                                        Location where you found item.
                                        please use pattern.
                                    </small>
                                    <span class="text-danger">city</span>
                                </p>
                            </div>
                            <div>
                                <input required type="submit" name="submit" value="Search" class="btn btn-primary mt-3">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>