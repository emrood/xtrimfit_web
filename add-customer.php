<?php


require_once('../db/User.php');
require_once('../db/Database.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}

$user_check = $_SESSION['user_id'];

//$ses_sql = mysqli_query($db, "select username from admin where username = '$user_check' ");
$ses_sql = User::getById($user_check);


$user = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$login_session = $user['name'];
//var_dump($row);
//die();


?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vito - Responsive Bootstrap 4 Admin Dashboard Template</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Typography CSS -->
    <link rel="stylesheet" href="../css/typography.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Sidebar  -->
    <?php
    include("../parts/side_bar.php");
    ?>
    <!-- TOP Nav Bar -->
    <?php
    include("../parts/top_nav_bar.php");
    ?>
    <!-- TOP Nav Bar END -->
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Add New User</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <form>
                                <div class="form-group">
                                    <div class="add-img-user profile-img-edit">
                                        <img class="profile-pic img-fluid" src="images/user/11.png" alt="profile-pic">
                                        <div class="p-image">
                                            <a href="javascript:void();" class="upload-button btn iq-bg-primary">File Upload</a>
                                            <input class="file-upload" type="file" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="img-extension mt-3">
                                        <div class="d-inline-block align-items-center">
                                            <span>Only</span>
                                            <a href="javascript:void();">.jpg</a>
                                            <a href="javascript:void();">.png</a>
                                            <a href="javascript:void();">.jpeg</a>
                                            <span>allowed</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>User Role:</label>
                                    <select class="form-control" id="selectuserrole">
                                        <option>Select</option>
                                        <option>Web Designer</option>
                                        <option>Web Developer</option>
                                        <option>Tester</option>
                                        <option>Php Developer</option>
                                        <option>Ios Developer </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="furl">Facebook Url:</label>
                                    <input type="text" class="form-control" id="furl" placeholder="Facebook Url">
                                </div>
                                <div class="form-group">
                                    <label for="turl">Twitter Url:</label>
                                    <input type="text" class="form-control" id="turl" placeholder="Twitter Url">
                                </div>
                                <div class="form-group">
                                    <label for="instaurl">Instagram Url:</label>
                                    <input type="text" class="form-control" id="instaurl" placeholder="Instagram Url">
                                </div>
                                <div class="form-group">
                                    <label for="lurl">Linkedin Url:</label>
                                    <input type="text" class="form-control" id="lurl" placeholder="Linkedin Url">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">New User Information</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="new-user-info">
                                <form>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="fname">First Name:</label>
                                            <input type="text" class="form-control" id="fname" placeholder="First Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lname">Last Name:</label>
                                            <input type="text" class="form-control" id="lname" placeholder="Last Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="add1">Street Address 1:</label>
                                            <input type="text" class="form-control" id="add1" placeholder="Street Address 1">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="add2">Street Address 2:</label>
                                            <input type="text" class="form-control" id="add2" placeholder="Street Address 2">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="cname">Company Name:</label>
                                            <input type="text" class="form-control" id="cname" placeholder="Company Name">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>Country:</label>
                                            <select class="form-control" id="selectcountry">
                                                <option>Select Country</option>
                                                <option>Caneda</option>
                                                <option>Noida</option>
                                                <option >USA</option>
                                                <option>India</option>
                                                <option>Africa</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mobno">Mobile Number:</label>
                                            <input type="text" class="form-control" id="mobno" placeholder="Mobile Number">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="altconno">Alternate Contact:</label>
                                            <input type="text" class="form-control" id="altconno" placeholder="Alternate Contact">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email:</label>
                                            <input type="email" class="form-control" id="email" placeholder="Email">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="pno">Pin Code:</label>
                                            <input type="text" class="form-control" id="pno" placeholder="Pin Code">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="city">Town/City:</label>
                                            <input type="text" class="form-control" id="city" placeholder="Town/City">
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="mb-3">Security</h5>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="uname">User Name:</label>
                                            <input type="text" class="form-control" id="uname" placeholder="User Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="pass">Password:</label>
                                            <input type="password" class="form-control" id="pass" placeholder="Password">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="rpass">Repeat Password:</label>
                                            <input type="password" class="form-control" id="rpass" placeholder="Repeat Password ">
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label><input class="mr-2" type="checkbox">Enable Two-Factor-Authentication</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add New User</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper END -->
<!-- Footer -->
<?php
include("parts/footer.php");
?>
<!-- Footer END -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="../js/jquery.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<!-- Appear JavaScript -->
<script src="../js/jquery.appear.js"></script>
<!-- Countdown JavaScript -->
<script src="../js/countdown.min.js"></script>
<!-- Counterup JavaScript -->
<script src="../js/waypoints.min.js"></script>
<script src="../js/jquery.counterup.min.js"></script>
<!-- Wow JavaScript -->
<script src="../js/wow.min.js"></script>
<!-- Apexcharts JavaScript -->
<script src="../js/apexcharts.js"></script>
<!-- Slick JavaScript -->
<script src="../js/slick.min.js"></script>
<!-- Select2 JavaScript -->
<script src="../js/select2.min.js"></script>
<!-- Owl Carousel JavaScript -->
<script src="../js/owl.carousel.min.js"></script>
<!-- Magnific Popup JavaScript -->
<script src="../js/jquery.magnific-popup.min.js"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="../js/smooth-scrollbar.js"></script>
<!-- lottie JavaScript -->
<script src="../js/lottie.js"></script>
<!-- Chart Custom JavaScript -->
<script src="../js/chart-custom.js"></script>
<!-- Custom JavaScript -->
<script src="../js/custom.js"></script>
</body>
</html>