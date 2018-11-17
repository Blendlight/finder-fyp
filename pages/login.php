<?php 

if(isset($_POST['submit']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $q = "SELECT * FROM `user_account` WHERE (`user_name`='$username' || `email`='$username') && `user_password`='$password'";
    
    $query = mysqli_query($conx, $q);
    if($query && mysqli_num_rows($query)>0)
    {
        $result = mysqli_fetch_assoc($query);
        $_SESSION['id'] = $result['user_id'];
        header('location:'.page_link('home'));
    }else{
        set_message('Your username or password is not correct.', 'danger');
    }
    
}

?><section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Login</h2>
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
                        <form method="POST" class="form">
                            <div>
                                <label for="username">Username or Email</label>
                                <input required type="text" class="form-control" id="username" name="username">
                            </div>
                            <div>
                                <label for="password ">Password</label>
                                <input required type="password" class="form-control" id="password" name="password">
                            </div>
                            <div>
                                <input required type="submit" name="submit" value="Login" class="btn btn-primary mt-3">
                            </div>
                        </form>
                        <p class="card-text mt-5">If you don't have account create one now. <a href="<?= page_link('register'); ?>">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>