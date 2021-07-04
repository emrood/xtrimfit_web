
<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Database.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}

$_SESSION['active'] = 'list-pricing';

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
    <!-- Sidebar  -->
    <?php
        include("parts/side_bar.php");
    ?>
    <!-- TOP Nav Bar -->
    <?php
        include("parts/top_nav_bar.php");
    ?>
    <!-- TOP Nav Bar END -->
    <!-- Page Content  -->
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center" scope="col"></th>
                                        <th class="text-center" scope="col">Session</th>
                                        <th class="text-center" scope="col">Standard</th>
                                        <th class="text-center" scope="col">Silver</th>
                                        <th class="text-center" scope="col">Gold</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th class="text-center" scope="row">Info #1</th>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" scope="row">Info #1</th>
                                        <td class="text-center"></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" scope="row">Info #1</th>
                                        <td class="text-center"></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" scope="row">Info #1</th>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" scope="row">Info #1</th>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center" scope="row">Info #1</th>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                        <td class="text-center"><i class="ri-check-line ri-2x text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center">
                                            <h2>$5<small></small></h2>
<!--                                            <button type="button" class="btn btn-primary mt-3">Purchase</button>-->
                                        </td>
                                        <td class="text-center">
                                            <h2>$60<small> / Per month</small></h2>
<!--                                            <button type="button" class="btn btn-primary mt-3">Purchase</button>-->
                                        </td>
                                        <td class="text-center">
                                            <h2>$75<small> / Per month</small></h2>
<!--                                            <button type="button" class="btn btn-primary mt-3">Purchase</button>-->
                                        </td>
                                        <td class="text-center">
                                            <h2>$100<small> / Per month</small></h2>
<!--                                            <button type="button" class="btn btn-primary mt-3">Purchase</button>-->
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
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