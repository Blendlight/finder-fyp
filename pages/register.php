<?php 

if(isset($_POST['submit']))
{
    $username = $_POST["username"];
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $cnic = $_POST["cnic"];
    $g = $_POST["gender"];
    $password = $_POST["password"];

    if($g == 'female')
    {
        $gender = 'Female';
    }else{
        $gender = 'Male';
    }




    $roleid = ROLE_AUTHENTICATED;
    $status = 0;

    $query = "
INSERT INTO
  `user_account`
SET
  `role_id` = '$roleid',
  `user_name` = '$username',
  `name` = '$fullname',
  `email` = '$email',
  `phone` = '$phone',
  `address` = '$address',
  `user_password` = '$password',
  `user_status` = $status,
  `gender` = '$gender',
  `cnic` = '$cnic' ";
    
    $image_name = null;
    
    if(isset($_FILES['image']) && $_FILES['image']['error']==0)
    {
        $image_name = uniqid()."-".$_FILES['image']['name'];
        $query .= ", profile_image='$image_name'";
        move_uploaded_file($_FILES['image']['tmp_name'], BASE_PATH.'/uploads/images/'.$image_name);
    }

    $result = mysqli_query($conx, $query);
    if($result && mysqli_affected_rows($conx)>0)
    {
        $_SESSION['id'] = mysqli_insert_id($conx);
        header('location: '.page_link('home'));
    }else{
        echo 'failed';
    }
}



?><section class="section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Register</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-8 offset-md-2">
                <!-- product card -->
                <div class="card">
                    <div class="card-body">
                        <p>All fields are required. <span class="text-danger">*</span></p>
                        <form method="POST" enctype="multipart/form-data" class="form">
                            <div>
                                <label for="username">Username</label>
                                <input required type="text" class="form-control" id="username" name="username">
                            </div>
                            <div>
                                <label for="fullname">Full name</label>
                                <input required type="text" class="form-control" id="fullname" name="fullname">
                            </div>
                            <div>
                                <label for="email">Email</label>
                                <input required type="email" class="form-control" id="email" name="email">
                            </div>
                            <div>
                                <label for="phone">Phone</label>
                                <input required type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div>
                                <label for="address">Address</label>
                                <textarea required class="form-control" id="address" name="address"></textarea>
                            </div>
                            <div>
                                <label for="cnic">CNIC</label>
                                <input required class="form-control" id="cnic" name="cnic" />
                            </div>
                            <div>
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">--SELECT GENDER--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label for="password">Password</label>
                                <input required type="password" class="form-control" id="password" name="password">
                            </div>
                            <div>
                                <label for="image">Image</label>
                                <input class="form-control" type="file" name="image" />
                            </div>
                            <div>
                                <input required type="submit" name="submit" value="Register" class="btn btn-primary mt-3">
                            </div>
                        </form>
                        <p class="card-text mt-5">Already have account <a href="<?= page_link('login'); ?>">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>