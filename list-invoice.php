<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Invoice.php');
require_once('db/Database.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}


$_SESSION['active'] = 'list-invoice';

$text = null;
$limit = 40;
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


//var_dump($from);
//die();

$count = (int)Invoice::count()['qty'];

$pages = (int)ceil($count / 40);

$invoices = Invoice::filter($text, $limit, $offset, $from, $to);

$totals = [];

if ($_SESSION['user']['role'] === 'Administrateur') {
    $totals['unpaid'] = Invoice::getTotalUnpaid()['total'];
    $totals['pending'] = Invoice::getTotalPending()['total'];
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
                                <h4 class="card-title">Liste des factures</h4>
                            </div>

                            <?php if ($_SESSION['user']['role'] === 'Administrateur'): ?>
                                <div class="pull-right" style="font-size: 1em !important;">
                                    <button type="button" style="font-size: 1.2em !important;"
                                            class="btn mb-1 btn-outline-primary">
                                        A recevoir <span
                                                class="badge badge-primary ml-2"><?= '$ ' . number_format($totals['pending'], 2, '.', ',') ?></span>
                                    </button>

                                    <button type="button" style="font-size: 1.2em !important;"
                                            class="btn mb-1 btn-outline-danger">
                                        Impayés <span
                                                class="badge badge-danger ml-2"><?= '$ ' . number_format($totals['unpaid'], 2, '.', ',') ?></span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <a href="view-invoice.php?invoice_id=new" class="btn btn-primary">Nouvelle seance</a>
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                        <div id="user_list_datatable_info" class="dataTables_filter">
                                            <form class="mr-3 position-relative" action="" method="get">
                                                <div class="form-group mb-0">
                                                    <input type="search" name="query" class="form-control"
                                                           id="exampleInputSearch" placeholder="Search"
                                                           aria-controls="user-list-table">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="user-list-files d-flex float-right">
                                            <a class="iq-bg-primary" href="javascript:void();">
                                                Print
                                            </a>
                                            <a class="iq-bg-primary" href="javascript:void();">
                                                Excel
                                            </a>
                                            <a class="iq-bg-primary" href="javascript:void();">
                                                Pdf
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid"
                                       aria-describedby="user-list-page-info">
                                    <thead>
                                    <tr>
                                        <th>Numero</th>
                                        <th>Client</th>
                                        <th>Période</th>
                                        <th>Montant</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($invoices as $invoice): ?>
                                        <tr>

                                            <td><a style="font-weight: bold !important; "
                                                   href="view-invoice.php?invoice_id=<?= $invoice['id'] ?>"><?= $invoice['invoice_number'] ?></a>
                                            </td>
                                            <td>
                                                <?php
                                                $customer = Customer::getById($invoice['customer_id']);
                                                ?>
                                                <?= $customer['last_name'] . ' ' . $customer['first_name'] ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($invoice['from_date'])) . ' au ' . date('d/m/Y', strtotime($invoice['to_date'])) ?></td>
                                            <td><?= '$' . number_format($invoice['total'], 2, '.', ',') ?></td>
                                            <?php if ($invoice['status'] === 'Paid'): ?>
                                                <td><span class="badge iq-bg-success"><?= $invoice['status'] ?></span>
                                                </td>
                                            <?php elseif ($invoice['status'] === 'Pending'): ?>
                                                <td><span class="badge iq-bg-warning"><?= $invoice['status'] ?></span>
                                                </td>
                                            <?php else: ?>
                                                <td><span class="badge iq-bg-danger"><?= $invoice['status'] ?></span>
                                                </td>
                                            <?php endif; ?>

                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    <a class="iq-bg-info" data-toggle="tooltip" data-placement="top"
                                                       title="" data-original-title="Imprimer"
                                                       href="#"><i class="ri-printer-line"></i></a>

                                                        <a class="iq-bg-primary" data-toggle="tooltip"
                                                           data-placement="top" title="" data-original-title="Edit"
                                                           href="/view-invoice.php?invoice_id=<?= $invoice['id'] ?>"><i
                                                                    class="ri-pencil-line"></i></a>

                                                </div>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                    </tbody>
                                </table>
                            </div>

                            <?php if ($pages > 1): ?>
                                <div class="row justify-content-between mt-3">
                                    <div id="user-list-page-info" class="col-md-6">
                                        <span>Showing 1 to <?= $limit ?> of <?= $count ?> entries</span>
                                    </div>
                                    <div class="col-md-6">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-end mb-0">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#">Suivant</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            <?php endif; ?>
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
<script src="js/alert.js"></script>
<script src="js/filter.js"></script>
</body>
</html>