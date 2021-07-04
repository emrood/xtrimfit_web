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

$_SESSION['active'] = 'email';

if (isset($_GET['user_id'])) {
    $user = User::getById($_GET['user_id']);
//    var_dump($user);
//    die();
}


$users = User::getUsers(10, 0, null);
$customers = Customer::getCustomers(30, 0, null);



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
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="iq-card iq-border-radius-20">
                                <div class="iq-card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h5 class="text-primary card-title"><i class="ri-pencil-fill"></i>Envoyer un mail</h5>
                                        </div>
                                    </div>
                                    <form class="email-form">
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">A:</label>
                                            <div class="col-sm-10">
                                                <select  id="inputEmail3" class="select2jsMultiSelect form-control" multiple="multiple">
                                                    <?php foreach ($customers as $customer):?>
                                                        <option value="<?= $customer['email'] ?>"><?= $customer['last_name'].' '.$customer['first_name'] ?></option>
                                                    <?php endforeach; ?>
<!--                                                    <option value="Bob Frapples">Bob Frapples</option>-->
<!--                                                    <option value="Barb Ackue">Barb Ackue</option>-->
<!--                                                    <option value="Greta Life">Greta Life</option>-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="cc" class="col-sm-2 col-form-label">Cc:</label>
                                            <div class="col-sm-10">
                                                <select  id="cc" class="select2jsMultiSelect form-control" multiple="multiple">
                                                    <?php foreach ($users as $user):?>
                                                        <option value="<?= $user['email'] ?>"><?= $user['name'] ?></option>
                                                    <?php endforeach; ?>
<!--                                                    <option value="Brock Lee">Brock Lee</option>-->
<!--                                                    <option value="Rick O'Shea">Rick O'Shea</option>-->
<!--                                                    <option value="Cliff Hanger">Cliff Hanger</option>-->
<!--                                                    <option value="Barb Dwyer">Barb Dwyer</option>-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="subject" class="col-sm-2 col-form-label">Objet:</label>
                                            <div class="col-sm-10">
                                                <input type="text"  id="subject" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="subject" class="col-sm-2 col-form-label">Votre message:</label>
                                            <div class="col-md-10">
                                                <textarea class="textarea form-control" rows="5">Available soon !</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row align-items-center">
                                            <div class="d-flex flex-grow-1 align-items-center">
                                                <div class="send-btn pl-3">
                                                    <button type="submit" class="btn btn-primary">Envoyer</button>
                                                </div>
                                                <div class="send-panel">
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded" for="file"> <input type="file" id="file" style="display: none"> <a><i class="ri-attachment-line"></i> </a> </label>
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded"> <a href="javascript:void();"> <i class="ri-map-pin-user-line text-primary"></i></a>  </label>
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded"> <a href="javascript:void();"> <i class="ri-drive-line text-primary"></i></a>  </label>
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded"> <a href="javascript:void();"> <i class="ri-camera-line text-primary"></i></a>  </label>
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded"> <a href="javascript:void();"> <i class="ri-user-smile-line text-primary"></i></a>  </label>
                                                </div>
                                            </div>
                                            <div class="d-flex mr-3 align-items-center">
                                                <div class="send-panel float-right">
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded" ><a href="javascript:void();"><i class="ri-settings-2-line text-primary"></i></a></label>
                                                    <label class="ml-2 mb-0 iq-bg-primary rounded"><a href="javascript:void();">  <i class="ri-delete-bin-line text-primary"></i></a>  </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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