<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Invoice.php');
require_once('db/Database.php');
require_once('db/Pricing.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}


$_SESSION['active'] = 'view-customer';

$text = null;
$limit = 40;
$offset = 0;

if (isset($_GET['customer_id']) && !empty($_GET['customer_id'])) {

    $customer = Customer::getById($_GET['customer_id']);
    if ($customer === null) {
        header("location:index.php");
        die();
    }

    $unpaid_invoices = Invoice::getCustomerInvoicesByStatus($customer['id'], null, null, 'Unpaid');
    $pending_invoices = Invoice::getCustomerInvoicesByStatus($customer['id'], null, null, 'Pending');
    $paid_invoices = Invoice::getCustomerInvoicesByStatus($customer['id'], null, null, 'Paid');

} else {
    header("location:index.php");
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
    <div id="content-page" class="content-page">
        <?php
        include("parts/alert.php");
        ?>
        <div class="container-fluid" id="print-div">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">

                        <div class="iq-card-body">

                            <div class="row">
                                <div class="col-lg-6">
                                    <img src="images/logo.gif" class="img-fluid w-25" alt="">
                                </div>
                                <div class="col-lg-6 align-self-center">
                                    <h4 class="mb-0 float-right">Compte client</h4>
                                </div>
                                <div class="col-sm-12">
                                    <hr class="mt-3">
                                    <h5 class="mb-0"><?= $customer['last_name'] . ' ' . $customer['first_name'] ?></h5>
                                    <p>Email: <?= $customer['email'] ?> <br/> Telephone: <?= $customer['phone'] ?>
                                        / <?= $customer['phone_alternative'] ?> </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive-sm">
                                        <table class="table" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th scope="col">Facture</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Date limite</th>
                                                <th scope="col">Montant</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($pending_invoices as $invoice): ?>
                                                <tr>
                                                    <td><a href="view-invoice.php?invoice_id=<?= $invoice['id'] ?>"><?= $invoice['invoice_number'] ?></a></td>
                                                    <td><?= Pricing::getById($invoice['pricing_id'])['name'] ?></td>
                                                    <td>
                                                        <?php switch($invoice['status']): ?><?php case 'Paid': ?>
                                                                <span class="badge badge-success"><?= $invoice['status'] ?></span>
                                                                <?php break; ?>
                                                            <?php case 'Unpaid': ?>
                                                                <span class="badge badge-danger"><?= $invoice['status'] ?></span>
                                                                <?php break; ?>
                                                            <?php case 'Pending': ?>
                                                                <span class="badge badge-info"><?= $invoice['status'] ?></span>
                                                                <?php break; ?>
                                                            <?php endswitch; ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($invoice['to_date'])) ?></td>
                                                    <td>
                                                        <?= '$' . number_format($invoice['total'], 2, '.', ',') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php foreach ($unpaid_invoices as $invoice): ?>
                                                <tr>
                                                    <td><?= $invoice['invoice_number'] ?></td>
                                                    <td><?= Pricing::getById($invoice['pricing_id'])['name'] ?></td>
                                                    <td>
                                                        <?php switch($invoice['status']): ?><?php case 'Paid': ?>
                                                            <span class="badge badge-success"><?= $invoice['status'] ?></span>
                                                            <?php break; ?>
                                                        <?php case 'Unpaid': ?>
                                                            <span class="badge badge-danger"><?= $invoice['status'] ?></span>
                                                            <?php break; ?>
                                                        <?php case 'Pending': ?>
                                                            <span class="badge badge-info"><?= $invoice['status'] ?></span>
                                                            <?php break; ?>
                                                        <?php endswitch; ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($invoice['to_date'])) ?></td>
                                                    <td>
                                                        <?= '$' . number_format($invoice['total'], 2, '.', ',') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <br/>
                            <br/>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5>Historique</h5>
                                    <div class="table-responsive-sm">
                                        <table class="table table-striped" style="width: 100%;">
                                            <thead>
                                            <tr>


                                                <th class="text-center" scope="col">Facture</th>
                                                <th class="text-center" scope="col">Plan</th>
                                                <th class="text-center" scope="col">Status</th>
                                                <th class="text-center" scope="col">Date limite</th>
                                                <th class="text-center" scope="col">Montant</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($paid_invoices as $invoice): ?>
                                                <tr>
                                                    <td class="text-center" scope="row"><a href="view-invoice.php?invoice_id=<?= $invoice['id'] ?>"><?= $invoice['invoice_number'] ?></a></td>
                                                    <td class="text-center"><?= Pricing::getById($invoice['pricing_id'])['name'] ?></td>
                                                    <td class="text-center">
                                                        <?php switch($invoice['status']): ?><?php case 'Paid': ?>
                                                            <span class="badge badge-success"><?= $invoice['status'] ?></span>
                                                            <?php break; ?>
                                                        <?php case 'Unpaid': ?>
                                                            <span class="badge badge-danger"><?= $invoice['status'] ?></span>
                                                            <?php break; ?>
                                                        <?php case 'Pending': ?>
                                                            <span class="badge badge-info"><?= $invoice['status'] ?></span>
                                                            <?php break; ?>
                                                        <?php endswitch; ?>
                                                    </td>
                                                    <td class="text-center"><?= date('d/m/Y', strtotime($invoice['to_date'])) ?></td>
                                                    <td class="text-center">
                                                        <?= '$' . number_format($invoice['total'], 2, '.', ',') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6 text-right">
                                    <button type="button" onclick="PrintElem()" class="btn btn-link mr-3 btn-print"><i class="ri-printer-line"></i>
                                        Imprimer
                                    </button>
<!--                                    <button type="button" class="btn btn-primary">Submit</button>-->
                                </div>
                                <div class="col-sm-12 mt-5">
                                    <b class="text-danger">Notes:</b>
                                    <p></p>
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
<footer class="bg-white iq-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
                    <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
                </ul>
            </div>
            <div class="col-lg-6 text-right">
                Copyright 2020 <a href="#">Vito</a> All Rights Reserved.
            </div>
        </div>
    </div>
</footer>
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

<script>
    function PrintElem()
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');

        mywindow.document.write('<html><head><title>' + document.title  + '</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<h1>' + document.title  + '</h1>');
        mywindow.document.write(document.getElementById("print-div").innerHTML);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
</script>
</body>
</html>