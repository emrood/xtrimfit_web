
<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Database.php');

//use Database;
//use User;


session_start();

//if (!isset($_SESSION['user'])) {
//    header("location:login.php");
//    die();
//}


$_SESSION['active'] = 'mobile-app';






?>
<!doctype html>
<html lang="en">
<?php
    include("parts/head.php");
?>
<body>
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<!-- Wrapper Start -->
<div class="wrapper">
    <div class="iq-comingsoon pt-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-8 text-center">
                    <div class="iq-comingsoon-info">
                        <a href="#">
                            <img src="images/logo-full2.png" class="img-fluid w-25" alt="">
                        </a>
                        <h2 class="mt-4 mb-1">Stay tunned, we're launching very soon</h2>
                        <p>We are working very hard to give you the best experience possible!</p>
                        <ul class="countdown" data-date="Sep 02 2021 20:20:22">
                            <li><span data-days>0</span>Days</li>
                            <li><span data-hours>0</span>Hours</li>
                            <li><span data-minutes>0</span>Minutes</li>
                            <li><span data-seconds>0</span>Seconds</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <form class="iq-comingsoon-form mt-5">
                        <div class="form-group">
                            <input type="email" class="form-control mb-0" id="exampleInputEmail1"  placeholder="Enter email address">
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper END -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Appear JavaScript -->
<script src="js/jquery.appear.js"></script>
<!-- Countdown JavaScript -->
<script src="js/countdown.min.js"></script>
<!-- Counterup JavaScript -->
<script src="js/waypoints.min.js"></script>
<script src="js/jquery.counterup.min.js"></script>
<!-- Wow JavaScript -->
<script src="js/wow.min.js"></script>
<!-- Apexcharts JavaScript -->
<script src="js/apexcharts.js"></script>
<!-- Slick JavaScript -->
<script src="js/slick.min.js"></script>
<!-- Select2 JavaScript -->
<script src="js/select2.min.js"></script>
<!-- Owl Carousel JavaScript -->
<script src="js/owl.carousel.min.js"></script>
<!-- Magnific Popup JavaScript -->
<script src="js/jquery.magnific-popup.min.js"></script>
<!-- Smooth Scrollbar JavaScript -->
<script src="js/smooth-scrollbar.js"></script>
<!-- lottie JavaScript -->
<script src="js/lottie.js"></script>
<!-- Chart Custom JavaScript -->
<script src="js/chart-custom.js"></script>
<!-- Custom JavaScript -->
<script src="js/custom.js"></script>
</body>
</html>