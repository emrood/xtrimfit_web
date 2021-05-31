<?php


require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Invoice.php');
require_once('db/Customer.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}


if($_SESSION['user']['role'] !== 'Administrateur'){
    header("location:list-invoice.php");
    die();
}

$user_check = $_SESSION['user_id'];

$_SESSION['active'] = 'index';


$total_paid = Invoice::getTotalPaid(date('Y-m-d'))['total'];
$total_unpaid = Invoice::getTotalUnpaid(date('Y-m-d'))['total'];
$total_pending = Invoice::getTotalPending(date('Y-m-d'))['total'];

$average_paid = ($total_paid * 100) / ($total_paid + $total_pending + $total_unpaid);

//var_dump($total_paid.' '.$total_pending.' '.$total_unpaid);
//die();

$pending_invoices = Invoice::getLastPendingInvoices();
$paid_invoices = Invoice::getLastPaidInvoices();
$unpaid_invoices = Invoice::getLastUnpaidInvoices();


////$ses_sql = mysqli_query($db, "select username from admin where username = '$user_check' ");
//$ses_sql = User::getById($user_check);
//
//
//$users = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
//
//$login_session = $users['name'];
//var_dump($row);
//die();


?>
<!doctype html>
<html lang="en">
<?php
    include("parts/head.php");
?>
<body class="sidebar-main-active right-column-fixed header-top-bgcolor">
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
    <div id="content-page" class="content-page" style="margin-right: 0 !important;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Factures a envoyer</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-primary mr-2"><i
                                                class="ri-inbox-fill"></i></div>
                                    <h2><?= Invoice::count('status', 'Pending')['qty'] ?></h2>
                                </div>
                                <div class="iq-map text-primary font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Chiffre d'affaire</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-danger mr-2"><i
                                                class="ri-radar-line"></i></div>
                                    <h2>$<?= number_format($total_paid,2,'.', ',')?></h2></div>
                                <div class="iq-map text-danger font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Revenu moyen</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-warning mr-2"><i
                                                class="ri-price-tag-3-line"></i></div>
                                    <h2><?= number_format($average_paid, 2, ',', '.') ?>%</h2></div>
                                <div class="iq-map text-warning font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Nombre de clients</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-info mr-2"><i
                                                class="ri-user-star-line"></i></div>
                                    <h2><?= ((int) Customer::count()['qty'] - 1) ?></h2></div>
                                <div class="iq-map text-info font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-7">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height overflow-hidden">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Statistiques</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                 <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton"
                                         style="">
<!--                                        <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>-->
<!--                                        <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>-->
<!--                                        <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>-->
                                        <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Imprimer</a>
                                        <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Telecharger</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="home-chart-02"></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height" style="background: transparent;">
                        <div class="iq-card-body rounded p-0"
                             style="background: url(images/page-img/01.png) no-repeat;    background-size: cover; height: 423px;">
                            <div class="iq-caption">
                                <h1>0</h1>
                                <p>Factures</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Factures ouvertes</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                 <span class="dropdown-toggle text-primary" id="dropdownMenuButton5"
                                       data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="dropdownMenuButton5">
<!--                                        <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>-->
<!--                                        <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>-->
<!--                                        <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>-->
                                        <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Imprimer</a>
                                        <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Telecharger</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless">
                                    <thead>
                                    <tr>
                                        <th scope="col">Facture</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Montant</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($pending_invoices as $invoice): ?>
                                    <tr>
                                        <td><a href="view-invoice.php?invoice_id=<?= $invoice['id'] ?>"><?= $invoice['invoice_number'] ?></a></td>
                                        <td>
                                            <?php
                                            $customer = Customer::getById($invoice['customer_id']);
                                            ?>
                                            <?= $customer['last_name'] . ' ' . $customer['first_name'] ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($invoice['from_date'])) ?></td>
                                        <td>$<?= number_format($invoice['total'], 2, '.', '.') ?></td>
                                        <td>
                                            <div class="badge badge-pill badge-info">Envoyer par email</div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Dettes</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                 <span class="dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1"
                                         style="">
                                        <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>Detail</a>
                                        <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Imprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="suggestions-lists m-0 p-0">
                                <?php foreach ($unpaid_invoices as $invoice): ?>
                                    <?php
                                        $customer = Customer::getById($invoice['customer_id']);
                                    ?>
                                    <li class="d-flex mb-4 align-items-center">
                                        <div class="profile-icon iq-bg-danger"><span><i class="ri-check-fill"></i></span>
                                        </div>
                                        <div class="media-support-info ml-3">
                                            <h6><a href="view-customer.php?customer_id=<?= $customer['id'] ?>"><?= $customer['last_name'] . ' ' . $customer['first_name'] ?></a></h6>
                                            <p class="mb-0"><span class="text-danger"><?= $invoice['invoice_number'] ?></span></p>
                                        </div>
                                        <div class="media-support-amount ml-3">
                                            <h6><span class="text-secondary">$</span><b> <?= number_format($invoice['total'], 2, '.', '.') ?></b></h6>
                                            <p class="mb-0">dû</p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 row m-0 p-0">
                    <div class="col-md-6">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Evolution de la clientele</h4>
                                </div>
                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton-one" data-toggle="dropdown">
                                    <i class="ri-more-fill"></i>
                                    </span>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuButton-one" style="">
                                            <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                            <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                            <a class="dropdown-item" href="#"><i
                                                        class="ri-pencil-fill mr-2"></i>Edit</a>
                                            <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                            <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="iq-card-body p-0">
                                <div id="home-chart-01"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">Evolution des couts</h4>
                                </div>
                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <div class="dropdown">
                                    <span class="dropdown-toggle" id="dropdownMenuButton-two" data-toggle="dropdown">
                                    <i class="ri-more-fill"></i>
                                    </span>
                                        <div class="dropdown-menu dropdown-menu-right"
                                             aria-labelledby="dropdownMenuButton-two" style="">
                                            <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                            <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                            <a class="dropdown-item" href="#"><i
                                                        class="ri-pencil-fill mr-2"></i>Edit</a>
                                            <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                            <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="iq-card-body p-0">
                                <div id="home-chart-05"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">historique de paiement</h4>
                            </div>
                            <div class="iq-card-header-toolbar d-flex align-items-center">
                                <div class="dropdown">
                                 <span class="dropdown-toggle" id="dropdownMenuButton-four" data-toggle="dropdown">
                                 <i class="ri-more-fill"></i>
                                 </span>
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="dropdownMenuButton-four" style="">
                                        <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>Details</a>
                                        <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Imprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <ul class="suggestions-lists m-0 p-0">
                                <?php foreach ($paid_invoices as $invoice): ?>
                                <li class="d-flex mb-4 align-items-center">
                                    <div class="profile-icon bg-success"><span><i class="ri-money-dollar-box-line"></i></span>
                                    </div>
                                    <div class="media-support-info ml-3">
                                        <h6><a href="view-invoice.php?invoice_id=<?= $invoice['id'] ?>"><?= $invoice['invoice_number'] ?></a></h6>
                                        <p class="mb-0"><?= date('d/m/Y', strtotime($invoice['paid_date'])) ?></p>
                                    </div>
                                    <div class="media-support-amount ml-3">
                                        <h6 class="text-success">+ <?= number_format($invoice['total'], 2, '.', '.') ?></h6>
                                        <p class="mb-0">USD</p>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<!-- am core JavaScript -->
<script src="js/core.js"></script>
<!-- am charts JavaScript -->
<script src="js/charts.js"></script>
<!-- am animated JavaScript -->
<script src="js/animated.js"></script>
<!-- am kelly JavaScript -->
<script src="js/kelly.js"></script>
<!-- am maps JavaScript -->
<script src="js/maps.js"></script>
<!-- am worldLow JavaScript -->
<script src="js/worldLow.js"></script>
<!-- Chart Custom JavaScript -->
<script src="js/chart-custom.js"></script>
<!-- Custom JavaScript -->
<script src="js/custom.js"></script>

</body>
</html>
