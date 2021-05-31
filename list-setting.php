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



$_SESSION['active'] = 'list-setting';

if($_SESSION['user']['role'] !== 'Administrateur'){
    header("location:list-invoice.php");
    die();
}

//$text = null;
//$limit = 40;
//$offset = 0;
//
//if (isset($_GET['invoice_id']) && !empty($_GET['invoice_id'])) {
//
//    $pricings = Pricing::getPricings();
//
//    if($_GET['invoice_id'] === 'new'){
//        $invoice = json_decode(json_encode(new Invoice(0, Invoice::invoice_num(1, 7, 'XS1-'), 1, 1, $pricings[0]['price'], 0, 0, 0, $pricings[0]['price'], 'Paid', date('Y-m-d'),  date('Y-m-d'), date('Y-m-d'), '', date('Y-m-d H:i:s'))), true);
//    }else{
//        $invoice = Invoice::getById($_GET['invoice_id']);
//    }
//
//
//    if ($invoice === null) {
//        header("location:index.php");
//        die();
//    }
//
//    $customer = Customer::getById($invoice['customer_id']);
//
////    var_dump($invoice);
////    die();
//
//} else {
//    header("location:index.php");
//    die();
//}


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
                <div class="col-sm-12 align-content-center">
                    <div class="iq-card ">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="col-lg-6">
                                <img src="images/logo.gif" class="img-fluid w-25" alt="">
                            </div>
                            <div class="iq-header-title">
                                <h4 class="card-title">Facture #<?= $invoice['invoice_number'] ?></h4>
                                <?php if($_SESSION['user']['role'] === 'Administrateur' && $invoice['id'] > 0):?>
                                    <br/>
                                    <a  href="delete-invoice.php?invoice_id=<?= $invoice['id'] ?>" class="btn btn-primary pull-right"><i class="ri-delete-bin-line"></i> Supprimer</a>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <p></p>
                            <form action="invoice/save-invoice.php" method="post">
                                <input type="hidden" name="customer_id" class="form-control" id="exampleInputText1" value="<?= $invoice['customer_id'] ?>">
                                <input type="hidden" name="id" class="form-control" id="exampleInputText1" value="<?= $invoice['id'] ?>">
                                <input type="hidden" name="invoice_number" class="form-control" id="exampleInputText1" value="<?= $invoice['invoice_number'] ?>">
                                <input type="hidden" name="created_at" class="form-control" id="exampleInputText1" value="<?= $invoice['created_at'] ?>">
                                <input type="hidden" name="updated_at" class="form-control" id="exampleInputText1"
                                       value="<?= date('Y-m-d') ?>">
                                <input type="hidden" name="paid_date" class="form-control" id="exampleInputText1"
                                       value="<?= date('Y-m-d') ?>">

                                <div class="form-group">
                                    <label for="exampleInputText1">Client </label>
                                    <input type="text" disabled class="form-control" id="exampleInputText1"
                                           value="<?= $customer['last_name'] . ' ' . $customer['first_name'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Plan</label>
                                    <select name="pricing_id" class="form-control mb-3">
                                        <?php foreach ($pricings as $pricing): ?>
                                            <?php if ((int)$customer['id'] !== 1): ?>
                                                <?php if ((int)$pricing['id'] !== 1): ?>
                                                    <option <?= ($pricing['id'] === $invoice['pricing_id']) ? 'selected="selected"' : '' ?> value="<?= $pricing['id'] ?>"><?= $pricing['name'] . ' / $' . number_format($pricing['price'], 2, '.', ',') ?></option>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if ((int)$pricing['id'] === 1): ?>
                                                    <option <?= ($pricing['id'] === $invoice['pricing_id']) ? 'selected="selected"' : '' ?> value="<?= $pricing['id'] ?>"><?= $pricing['name'] . ' / $' . number_format($pricing['price'], 2, '.', ',') ?></option>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-row">
                                    <div class="col">
                                        <label for="exampleInputText1">Du</label>
                                        <input type="date" name="from_date" <?= ((int) $invoice['customer_id'] === 1) ? 'disabled' : '' ?> class="form-control" id="exampleInputdate"
                                               value="<?= $invoice['from_date'] ?>">
                                    </div>
                                    <div class="col">
                                        <label for="exampleInputText1">Au</label>
                                        <input type="date" name="to_date" <?= ((int) $invoice['customer_id'] === 1) ? 'disabled' : '' ?> class="form-control" id="exampleInputdate"
                                               value="<?= $invoice['to_date'] ?>">
                                    </div>
                                </div>
                                <br/>
                                <hr/>

                                <div class="form-row">
                                    <div class="col">
                                        <label for="exampleInputText1">Prix de base (USD)</label>
                                        <input type="number" name="price" value="<?= $invoice['price'] ?>"
                                               class="form-control" <?= ((int) $invoice['customer_id'] === 1) ? 'disabled' : '' ?> placeholder="0.00">
                                    </div>
                                    <div class="col">
                                        <label for="exampleInputText1">Taxe (%)</label>
                                        <input type="number" name="taxe_percentage"
                                               value="<?= $invoice['taxe_percentage'] ?>" class="form-control"
                                               placeholder="0.0">
                                    </div>
                                </div>
                                <br/>
                                <div class="form-row">
                                    <div class="col">
                                        <label for="exampleInputText1">Frais additionnel (USD)</label>
                                        <input type="number" name="fees" value="<?= $invoice['fees'] ?>"
                                               class="form-control" placeholder="0.00">
                                    </div>
                                    <div class="col">
                                        <label for="exampleInputText1">Rabais (%)</label>
                                        <input type="number" name="discount_percentage"
                                               value="<?= $invoice['discount_percentage'] ?>" class="form-control"
                                               placeholder="0.0">
                                    </div>
                                </div>

                                <br/>
                                <hr/>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" <?= ((int) $invoice['customer_id'] === 1) ? 'disabled' : '' ?> class="form-control mb-3">
                                        <?php foreach (Constants::getInvoiceStatus() as $status): ?>
                                            <option <?= ($status === $invoice['status']) ? 'selected="selected"' : '' ?>
                                                value="<?= $status ?>"><?= $status ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail3">Total</label>
                                    <input type="number" name="total" disabled class="form-control"
                                           value="<?= $invoice['total'] ?>" id="exampleInputEmail3" placeholder="0.0">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail3">Commentaire</label>
                                    <input type="text" name="comment" class="form-control"
                                           value="<?= $invoice['comment'] ?>" id="exampleInputEmail3" placeholder="Cheque #...">
                                </div>

                                <br/>
                                <hr/>
                                <?php if($invoice['status'] === 'Paid'):?>

                                    <img src="images/paid_stamp.png" class="img-fluid w-25" alt="" style="position: relative; left: 60%;" >

                                    <br/>
                                <?php endif;?>

                                <?php if($invoice['status'] !== 'Paid' || (int) $invoice['id'] === 0):?>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <?php endif;?>
                                <a href="list-invoice.php" class="btn iq-bg-secondary">Annuler</a>
                                <a href="#" onclick="PrintElem()" class="btn iq-bg-danger pull-right"><i
                                        class="ri-printer-line"></i>Imprimer</a>
                            </form>
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
    function PrintElem() {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');

        mywindow.document.write('<html><head><title>' + document.title + '</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<h1>' + document.title + '</h1>');
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