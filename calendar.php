<?php


require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Customer.php');
require_once('db/Rooms.php');
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

$rooms = Rooms::getRooms();
$customers = Customer::getCustomers(1000, 0);



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
<!--                    <div class="iq-card calender-small">-->
<!--                        <div class="iq-card-body">-->
<!--                            <input type="text" class="flatpicker d-none">-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title ">Salle disponible</h4>
                            </div>

                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <a href="#"><i class="fa fa-plus  mr-0" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="m-0 p-0 job-classification">
                                <?php foreach ($rooms as $room): ?>
                                    <li class=""><i class="ri-check-line" style="background-color: <?= $room['color'] ?>;"></i><?= $room['name'] ?></li>
                                <?php endforeach; ?>
<!--                                <li class=""><i class="ri-check-line bg-danger"></i>Sauna</li>-->
<!--                                <li class=""><i class="ri-check-line bg-success"></i>Massage room</li>-->
<!--                                <li class=""><i class="ri-check-line bg-warning"></i>Equipe C</li>-->
<!--                                <li class=""><i class="ri-check-line bg-info"></i>Equipe D</li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Réservation du jour</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="m-0 p-0 today-schedule">
                                <li class="d-flex">
                                    <div class="schedule-icon"><i class="ri-checkbox-blank-circle-fill text-primary"></i></div>
                                    <div class="schedule-text"> <span>Mr Joseph</span>
                                        <span>09:00 to 12:00</span></div>
                                </li>
                                <li class="d-flex">
                                    <div class="schedule-icon"><i class="ri-checkbox-blank-circle-fill text-success"></i></div>
                                    <div class="schedule-text"> <span>Mr Pierre</span>
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
                                <a href="#" data-toggle="modal" data-target=".modal-add-reservation" class="btn btn-primary"><i class="ri-add-line mr-2"></i>Nouvelle réservation</a>
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
    <div class="modal fade modal-add-reservation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouvelle réservation (Available soon)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Veuillez remplir tous les champs disponible.</p>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Client</label>
                                    <select class="form-control" id="exampleFormControlSelect1">
                                        <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer['id'] ?>"><?= $customer['first_name'].' '.$customer['last_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="fuulname" placeholder="Nom">
                                </div>
                                <div class="form-group">
                                    <input type="text" required class="form-control" name="telephone" placeholder="Telephone">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sale</label>
                                    <select class="form-control" id="exampleFormControlSelect1">
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?= $room['id'] ?>"><?= $room['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Note</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
                                </div>
                                <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>"
                                       class="form-control" placeholder="">
                                <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>"
                                       class="form-control" placeholder="">
                            </div>
                            <div class="col">
<!--                                <input type="text" required name="fingerprint" class="form-control"-->
<!--                                       placeholder="UID">-->
                                <label for="exampleFormControlSelect1">Date / Heure</label>
                                <input style="margin: auto;" type="text" class="flatpicker d-none">

                                <br/>
                                <hr/>
                                <div class="row">
                                    <div class="col">
                                        <input type="time" name="from_time" class="form-control" id="exampleInputtime" value="<?= date('H:i', strtotime('+1 hour', strtotime(date('Y-m-d H:i:s')))) ?>">
                                    </div>
                                    <div class="col">
                                        <input type="time" name="to_time" class="form-control" id="exampleInputtime" value="<?= date('H:i', strtotime('+2 hour', strtotime(date('Y-m-d H:i:s')))) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <a type="button" style="color: white;" class="btn btn-secondary" data-dismiss="modal">Fermer</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
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
