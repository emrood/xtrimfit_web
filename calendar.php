<?php


require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Customer.php');
require_once('db/Rooms.php');
require_once('db/Pricing.php');
require_once('db/Invoice.php');
require_once('db/Reservation.php');
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
$pricings = Pricing::getByType('session');


$from = null;
$to = null;

if (isset($_GET['from_date'])) {
    $from = $_GET['from_date'];
}

if (isset($_GET['to_date'])) {
    $to = $_GET['to_date'];
}

$reservations = Reservation::getByDate($from, $to);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//    var_dump($_POST);
//    die();

    $customer = Customer::getById($_POST['customer_id']);
    $pricing = Pricing::getById($_POST['pricing_id']);


    $invoice_number = Invoice::invoice_num($_POST['customer_id'], 7, 'XS' . $_POST['customer_id'] . '-');

    $_POST['from_date'] = $_POST['reservation_date'] . ' ' . $_POST['from_time'] . ':00';
    $_POST['to_date'] = $_POST['reservation_date'] . ' ' . $_POST['to_time'] . ':00';
    $_POST['status'] = 'Pending';
    $_POST['paid_date'] = date('Y-m-d H:i:s');
    $_POST['rate_id'] = 1;
    $_POST['rate'] = 0;
    $_POST['invoice_number'] = $invoice_number;
    $_POST['id'] = 0;
    $_POST['price'] = $pricing['price'];
    $_POST['total'] = $pricing['price'];


    $new_session_invoice = Invoice::convertRowToObject($_POST);
//    var_dump($new_session_invoice);
//    die();

    $result = Invoice::insert($new_session_invoice);


    $registered_invoice = Invoice::getByInvoiceNumber($invoice_number);


    $_POST['invoice_id'] = $registered_invoice['id'];
    $reservation = Reservation::convertRowToObject($_POST);
    $result = Reservation::insert($reservation);

//    var_dump($result);
//    die();


    header('location:view-invoice.php?invoice_id=' . $registered_invoice['id']);
    die();


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
    <script>
        var calendar;

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calen');

            calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'timeGrid', 'list', 'bootstrap'],
                locale: 'fr',
                firstDay: 1,
                // header: {
                //     center: 'dayGridMonth, dayGridWeek, timeGrid, list' // buttons for switching between views
                // },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth, dayGridWeek, timeGrid, list'
                },
                views: {
                    timeGrid: {
                        type: 'timeGrid',
                        buttonText: 'jour',
                    },
                    dayGridMonth: {
                        type: 'dayGridMonth',
                        buttonText: 'mois',
                    },
                    dayGridWeek: {
                        type: 'dayGridWeek',
                        buttonText: 'semaine',
                    },
                    list: {
                        type: 'list',
                        buttonText: 'liste',
                    },
                },

            });

            // var myEvent = {
            //     title: "my new event",
            //     // allDay: true,
            //     start: new Date(),
            //     end: new Date()
            // };
            //
            // // console.log(myEvent.end);
            // calendar.addEvent(myEvent);

            calendar.render();
        });
    </script>
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
                                <h4 class="card-title">Réservations</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="m-0 p-0 today-schedule">
                                <?php foreach ($reservations as $reservation): ?>
                                    <li class="d-flex">
                                        <div class="schedule-icon"><i class="ri-checkbox-blank-circle-fill"
                                                                      style="color: <?= Rooms::getById($reservation['room_id'])['color'] ?>;"></i>
                                        </div>
                                        <div class="schedule-text">
                                            <span><a style="font-weight: bold;" href="view-invoice.php?invoice_id=<?= $reservation['invoice_id'] ?>"><?= $reservation['fullname'] ?></a></span>
                                            <span>Tél: <?= $reservation['phone'] ?></span>
                                            <?php if (isset($_GET['from_date']) && isset($_GET['to_date']) && $_GET['from_date'] !== $_GET['to_date']): ?>
                                                <span><?= date("d M Y", strtotime($reservation['reservation_date'])) ?></span>
                                            <?php endif; ?>
                                            <span><?= date("h:i A", strtotime($reservation['from_time'])) ?>
                                                a <?= date("h:i A", strtotime($reservation['to_time'])) ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title ">Salles disponibles</h4>
                            </div>

                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <a href="#"><i class="fa fa-plus  mr-0" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="m-0 p-0 job-classification">
                                <?php foreach ($rooms as $room): ?>
                                    <li class=""><i class="ri-check-line"
                                                    style="background-color: <?= $room['color'] ?>;"></i><?= $room['name'] ?>
                                    </li>
                                <?php endforeach; ?>
                                <!--                                <li class=""><i class="ri-check-line bg-danger"></i>Sauna</li>-->
                                <!--                                <li class=""><i class="ri-check-line bg-success"></i>Massage room</li>-->
                                <!--                                <li class=""><i class="ri-check-line bg-warning"></i>Equipe C</li>-->
                                <!--                                <li class=""><i class="ri-check-line bg-info"></i>Equipe D</li>-->
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="col-md-9">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Planning</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <a href="#" data-toggle="modal" data-target=".modal-add-reservation"
                                   class="btn btn-primary"><i class="ri-add-line mr-2"></i>Nouvelle réservation</a>
                            </div>
                        </div>
                        <!--                        <div class="iq-card-body">-->
                        <!--                            <div id='calendar1'></div>-->
                        <!--                        </div>-->
                        <div class="iq-card-body">
                            <div id='calen'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-add-reservation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="post" action="">
                <input type="hidden" name="discount_percentage" value="0">
                <input type="hidden" name="taxe_percentage" value="0">
                <input type="hidden" name="fees" value="0">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouvelle réservation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">Veuillez remplir tous les champs disponible.</p>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Client</label>
                                    <select class="form-control" name="customer_id" id="exampleFormControlSelect1">
                                        <?php foreach ($customers as $customer): ?>
                                            <option fullname="<?= $customer['last_name'] . ' ' . $customer['first_name'] ?>"
                                                    value="<?= $customer['id'] ?>"
                                                    phone="<?= $customer['phone'] ?>"><?= $customer['last_name'] . ' ' . $customer['first_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" required class="form-control" value="" name="fullname"
                                           placeholder="Nom">
                                </div>
                                <div class="form-group">
                                    <input type="text" required class="form-control" value="" name="phone"
                                           placeholder="Telephone">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Plan</label>
                                    <select class="form-control" name="pricing_id" id="exampleFormControlSelect1">
                                        <?php foreach ($pricings as $pricing): ?>
                                            <option value="<?= $pricing['id'] ?>"><?= $pricing['name'] . ' / ' . number_format($pricing['price'], 2, '.', ',') . '$' ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sale</label>
                                    <select class="form-control" name="room_id" id="exampleFormControlSelect1">
                                        <?php foreach ($rooms as $room): ?>
                                            <option value="<?= $room['id'] ?>"><?= $room['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Note</label>
                                    <textarea class="form-control" name="comment" id="exampleFormControlTextarea1"
                                              rows="1"></textarea>
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
                                <div class="form-group calendar-container" style="margin: auto;">
                                    <input style="margin: auto;" required name="reservation_date" type="text"
                                           class="flatpicker d-none">
                                </div>

                                <br/>
                                <hr/>
                                <div class="row">
                                    <div class="col">
                                        <input type="time" required name="from_time" class="form-control"
                                               id="exampleInputtime"
                                               value="<?= date('H:i', strtotime('+1 hour', strtotime(date('Y-m-d H:i:s')))) ?>">
                                    </div>
                                    <div class="col">
                                        <input type="time" required name="to_time" class="form-control"
                                               id="exampleInputtime"
                                               value="<?= date('H:i', strtotime('+2 hour', strtotime(date('Y-m-d H:i:s')))) ?>">
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
<style>
    .calendar-container div {
        margin: auto;
    }
</style>
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
<script src="js/filter.js"></script>
<script src="js/constants.js"></script>
<script>
    $(function () {
        // console.log( "ready!" );

        updateView();


        $('select[name="customer_id"]').change(function () {
            updateView();
        });

        $(window).keydown(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                return false;
            }
        });

        // var myCalendar = $('#calendar1');
        // myCalendar = require("fullcalendar/core/main.d.ts");
        // console.log(myCalendar);
        // myCalendar.Calendar();
        // var myEvent = {
        //     title:"my new event",
        //     allDay: true,
        //     start: new Date(),
        //     end: new Date()
        // };
        // // myCalendar = new Calendar( 'renderEvent', myEvent );
        //
        // Calendar.prototype.addEvent();
        //
        // addEvent(myEvent, 'test', 2, myCalendar);

        function localJsonpCallback(json) {
            alert(json.toString());
        }


        loadEventsFromApi();


    });

    function updateView() {
        // console.log($('select[name="customer_id"]').val());

        let customer_id = parseInt($('select[name="customer_id"]').val());

        if (customer_id === 1) {
            $('input[name="fullname"]').attr('type', 'text');
            $('input[name="phone"]').attr('type', 'text');
            $('input[name="fullname"]').attr('value', '');
            $('input[name="phone"]').attr('value', '');
        } else {
            $('input[name="fullname"]').attr('type', 'hidden');
            $('input[name="phone"]').attr('type', 'hidden');
            $('input[name="fullname"]').attr('value', $('select[name="customer_id"] option:selected').attr('fullname'));
            $('input[name="phone"]').attr('value', $('select[name="customer_id"] option:selected').attr('phone'));
        }
    }

    function loadEventsFromApi() {
        var evts = [];
        const Http = new XMLHttpRequest();
        const url = xtrim_api + 'reservations';
        Http.open("GET", url);
        Http.send();
        Http.onreadystatechange = (e) => {
            // console.log(Http.responseText);
            let data = JSON.parse(Http.responseText);
            console.log('NUMBER_OF _OBJECTS', Object.keys(data).length);
            console.log('DATAS', data);

            if (Http.readyState === 4 && Http.status === 200)
            {

                for (var event in data) {
                    console.log(event, data[event]);
                    var from_date = data[event].reservation_date + " " + data[event].from_time;
                    var to_date = data[event].reservation_date + " " + data[event].to_time;
                    // Split timestamp into [ Y, M, D, h, m, s ]
                    var from_date_array = from_date.split(/[- :]/);
                    var to_date_array = from_date.split(/[- :]/);
                    // Apply each element to the Date function
                    var f = new Date(Date.UTC(from_date_array[0], from_date_array[1] - 1, from_date_array[2], from_date_array[3], from_date_array[4], from_date_array[5]));
                    var t = new Date(Date.UTC(to_date_array[0], to_date_array[1] - 1, to_date_array[2], to_date_array[3], to_date_array[4], to_date_array[5]));
                    var myEvent = {
                        title: data[event].fullname + ' / ' + data[event].phone,
                        color: data[event].color,
                        start: f,
                        end: t
                    };

                    calendar.addEvent(myEvent);
                }

            }
        }

    }

    function xtrim(data) {
        alert("XTRIM");
    }
</script>
</body>
</html>
