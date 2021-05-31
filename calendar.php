<?php


require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Customer.php');
require_once('db/Constants.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}

$_SESSION['active'] = 'calendar';

if (isset($_GET['user_id'])) {
    $user = User::getById($_GET['user_id']);
//    var_dump($user);
//    die();
}




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
            <div class="row row-eq-height">
                <div class="col-md-3">
                    <div class="iq-card calender-small">
                        <div class="iq-card-body">
                            <input type="text" class="flatpicker d-none">
                        </div>
                    </div>
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title ">Classification</h4>
                            </div>

                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <a href="#"><i class="fa fa-plus  mr-0" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="m-0 p-0 job-classification">
                                <li class=""><i class="ri-check-line bg-danger"></i>Equipe A</li>
                                <li class=""><i class="ri-check-line bg-success"></i>Equipe B</li>
                                <li class=""><i class="ri-check-line bg-warning"></i>Equipe C</li>
                                <li class=""><i class="ri-check-line bg-info"></i>Equipe D</li>
                            </ul>
                        </div>
                    </div>
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Horaire du jour</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="m-0 p-0 today-schedule">
                                <li class="d-flex">
                                    <div class="schedule-icon"><i class="ri-checkbox-blank-circle-fill text-primary"></i></div>
                                    <div class="schedule-text"> <span>Entraînement avec Mr Joseph</span>
                                        <span>09:00 to 12:00</span></div>
                                </li>
                                <li class="d-flex">
                                    <div class="schedule-icon"><i class="ri-checkbox-blank-circle-fill text-success"></i></div>
                                    <div class="schedule-text"> <span>Entraînement avec Mr Pierre</span>
                                        <span>16:00 to 19:00</span></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Planning (Available soon)</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <a href="#" class="btn btn-primary"><i class="ri-add-line mr-2"></i>Ajouter un entraînement</a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div id='calendar1'></div>
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
<!-- Full calendar -->
<script src='fullcalendar/core/main.js'></script>
<script src='fullcalendar/daygrid/main.js'></script>
<script src='fullcalendar/timegrid/main.js'></script>
<script src='fullcalendar/list/main.js'></script>
<!-- Flatpicker Js -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Chart Custom JavaScript -->
<script src="js/chart-custom.js"></script>
<!-- Custom JavaScript -->
<script src="js/custom.js"></script>
</body>
</html>
