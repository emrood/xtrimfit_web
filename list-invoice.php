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
$limit = 300;
$offset = 0;
$from = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-d'))));
$to = date('Y-m-d');

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
    $totals['unpaid'] = Invoice::getTotalUnpaid($from, $to, 'interval')['total'];
    $totals['pending'] = Invoice::getTotalPending($from, $to, 'interval')['total'];
    $totals['paid'] = Invoice::getTotalPaid($from, $to, 'interval')['total'];
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
                                            class="btn mb-1 btn-outline-success">
                                        Pay??s <span
                                                class="badge badge-success ml-2"><?= '$ ' . number_format($totals['paid'], 2, '.', ',') ?></span>
                                    </button>

                                    <button type="button" style="font-size: 1.2em !important;"
                                            class="btn mb-1 btn-outline-primary">
                                        A recevoir <span
                                                class="badge badge-primary ml-2"><?= '$ ' . number_format($totals['pending'], 2, '.', ',') ?></span>
                                    </button>

                                    <button type="button" style="font-size: 1.2em !important;"
                                            class="btn mb-1 btn-outline-danger">
                                        Impay??s <span
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
                                        <div id="user_list_datatable_info" style="margin-bottom: 10px;" class="dataTables_filter">
                                            <form class="mr-3 position-relative" action="" method="get">
                                                <div class="form-group mb-0" style="display: flex;">
                                                    <input type="search" name="query" class="form-control"
                                                           id="exampleInputSearch" placeholder="Client name..."
                                                           aria-controls="user-list-table">

                                                    <button class="btn btn-outline-dark" style="margin-left: 4px;"><i class="ri-search-2-line"></i></button>

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
                                        <th>P??riode</th>
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
                                                       title="" data-original-title="Imprimer" target="_blank"
                                                       href="/print/invoice.php?invoice_id=<?= $invoice['id'] ?>"><i class="ri-printer-line"></i></a>

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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/b-1.7.1/b-html5-1.7.1/b-print-1.7.1/r-2.2.9/sb-1.1.0/sp-1.3.0/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        // $('#user-list-table').DataTable();

        $('#user-list-table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    });


</script>
</body>
</html>