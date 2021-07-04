<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Invoice.php');
require_once('db/CashFund.php');
require_once('db/Database.php');
require_once('db/Rate.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}


$_SESSION['active'] = 'list-funds';

$text = null;
$limit = 200;
$offset = 0;
$from = null;
$to = null;

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $text = $_GET['query'];
}


if (isset($_GET['from_date'])) {
    $from = $_GET['from_date'];
}

if (isset($_GET['to_date'])) {
    $to = $_GET['to_date'];
}

$rates = Rate::getRates();

//var_dump($from);
//die();

$count = (int)Invoice::count()['qty'];

$pages = (int)ceil($count / 200);

//$invoices = Invoice::filter($text, $limit, $offset, $from, $to);


if ($_SESSION['user']['role'] === 'Administrateur') {

}


//foreach ($customers as $customer){
//    var_dump($customer);
//}
//
//die();


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
        <?php
        include("parts/alert.php");
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Gestion de caisse | <a href="#" data-toggle="tooltip" data-placement="top" title=""  data-original-title="Exporter sur excel"><i class="ri-file-excel-2-line"></i></a> </h4>
                            </div>

                            <!--                                <div class="pull-right" style="font-size: 1em !important;">-->
                            <!--                                    <button type="button" style="font-size: 1.2em !important;"-->
                            <!--                                            class="btn mb-1 btn-outline-primary">-->
                            <!--                                        A recevoir <span-->
                            <!--                                                class="badge badge-primary ml-2">-->
                            <? //= '$ ' . number_format(0, 2, '.', ',') ?><!--</span>-->
                            <!--                                    </button>-->
                            <!---->
                            <!--                                    <button type="button" style="font-size: 1.2em !important;"-->
                            <!--                                            class="btn mb-1 btn-outline-danger">-->
                            <!--                                        Impayés <span-->
                            <!--                                                class="badge badge-danger ml-2">-->
                            <? //= '$ ' . number_format(0, 2, '.', ',') ?><!--</span>-->
                            <!--                                    </button>-->
                            <!--                                </div>-->

                            <div>
                                <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_deposit"
                                       style="font-weight: bold"><i class="ri-add-circle-line"></i>Depot</a>
                                <?php endif ?>
                                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_withdrawal"
                                   style="font-weight: bold"><i class="">- </i>Retrait</a>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="nav nav-pills mb-3 col-md-4" style="margin: auto;" id="pills-tab" role="tablist">
                                <?php foreach ($rates as $rate): ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ((int)$rate['id'] === 1) ? 'active' : '' ?>"
                                           id="pills-<?= $rate['name'] ?>-tab" data-toggle="pill"
                                           href="#pills-<?= $rate['name'] ?>" role="tab"
                                           aria-controls="pills-<?= $rate['name'] ?>"
                                           aria-selected="true"><?= $rate['name'] ?></a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                            <div class="tab-content" id="pills-tabContent-2">
                                <?php foreach ($rates as $rate): ?>
                                    <div class="tab-pane fade show <?= ((int)$rate['id'] === 1) ? 'active' : '' ?>"
                                         id="pills-<?= $rate['name'] ?>" role="tabpanel"
                                         aria-labelledby="pills-<?= $rate['name'] ?>-tab">

                                        <?php $transactions = CashFund::filter($text, $rate['id'], $limit, $offset, $from, $to); ?>
                                        <div>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th class="text-left" scope="col" width="30%">Description</th>
                                                    <th class="text-center" scope="col" width="20%">Date</th>
                                                    <th class="text-center" scope="col" width="15%">Credit</th>
                                                    <th class="text-center" scope="col" width="15%">Debit</th>
                                                    <th class="text-right" scope="col" width="20%">Balance</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($transactions as $transaction): ?>
                                                    <tr>
                                                        <th class="text-left"
                                                            scope="row"><?= $transaction['comment'] ?></th>
                                                        <td class="text-center"><?= date('d/m/Y g:i A', strtotime($transaction['created_at'])) ?></td>
                                                        <td class="text-center"><?= ($transaction['type'] === 'Depot') ? number_format((double)$transaction['amount'], 2, '.', ',') : '-' ?></td>
                                                        <td class="text-center"><?= ($transaction['type'] === 'Retrait') ? number_format((double)$transaction['amount'], 2, '.', ',') : '-' ?></td>
                                                        <td class="text-right"
                                                            style="font-weight: bold"><?= number_format($transaction['balance'], 2, '.', ',') . ' ' . $rate['name'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                <!-- Modal deposit -->
                <div class="modal fade" id="modal_deposit" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="cashfunds/save-cashfund.php" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Enregistrer un dépôt</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>">
                                    <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>">
                                    <input type="hidden" name="id" value="0">
                                    <input type="hidden" name="type" value="Depot">
                                    <input type="hidden" name="action" value="save_deposit">
                                    <div class="row form-group" style="margin-left: 8px; margin-right: 8px;">
                                        <input type="text" required class="form-control" name="comment"
                                               placeholder="Description">
                                    </div>
                                    <br/>
                                    <hr/>
                                    <br/>
                                    <div class="row">
                                        <div class="col">
                                            <input type="number" required step="0.01" class="form-control" name="amount"
                                                   placeholder="Montant">
                                        </div>
                                        <div class="col">
                                            <select class="form-control" name="rate_id" id="exampleFormControlSelect1">
                                                <?php foreach ($rates as $rate): ?>
                                                    <option value="<?= $rate['id'] ?>"><?= $rate['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <button type="submit" onclick="this.form.submit();" class="btn btn-primary">
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif ?>


            <!-- Modal withdrawal -->
            <div class="modal fade" id="modal_withdrawal" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form action="cashfunds/save-cashfund.php" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Enregistrer une sortie de fond
                                (retrait)</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                                <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>">
                                <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>">
                                <input type="hidden" name="id" value="0">
                                <input type="hidden" name="type" value="Retrait">
                                <input type="hidden" name="action" value="save_withdrawal">
                                <div class="row form-group" style="margin-left: 8px; margin-right: 8px;">
                                    <input type="text" required class="form-control" name="comment"
                                           placeholder="Description">
                                </div>
                                <br/>
                                <hr/>
                                <br/>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" required step="0.01" class="form-control" name="amount"
                                               placeholder="Montant">
                                    </div>
                                    <div class="col">
                                        <select class="form-control" name="rate_id" id="exampleFormControlSelect1">
                                            <?php foreach ($rates as $rate): ?>
                                                <option value="<?= $rate['id'] ?>"><?= $rate['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </div>
                    </form>
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
<script src="js/alert.js"></script>
<script src="js/filter.js"></script>
</body>
</html>