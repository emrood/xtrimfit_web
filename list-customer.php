<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Pricing.php');
require_once('db/Database.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}


$_SESSION['active'] = 'list-customer';

$text = null;
$limit = 20;
$offset = 0;

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $text = $_GET['query'];
}

//var_dump($text)

$count = (int)Customer::count()['qty'];

$pages = (int)ceil($count / 20);


$customers = Customer::getCustomers($limit, $offset, $text);

//$pricings = Pricing::getPricings();

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
                                <h4 class="card-title">Liste des clients</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                        <div id="user_list_datatable_info" style="margin-bottom: 10px;" class="dataTables_filter">
                                            <form class="mr-3 position-relative" action="" method="get">
                                                <div class="form-group mb-0" style="display: flex;">
                                                    <input type="search" name="query" class="form-control"
                                                           id="exampleInputSearch" placeholder="Filter"
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
                                        <th>Profile</th>
                                        <th>Nom</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Plan</th>
                                        <th>Fingerprint</th>
                                        <th>Status</th>
                                        <th>Date d'inscription</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                        <?php if ((int)$customer['id'] !== 1): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <a class="text-lg-center" style="font-weight: bold !important; "
                                                       href="view-customer.php?customer_id=<?= $customer['id'] ?>">
                                                        <?php if (isset($customer['picture']) && !empty($customer['picture'])): ?>
                                                            <img class="rounded-circle img-fluid avatar-40"
                                                                 src="/images/customers/<?= $customer['picture'] ?>"
                                                                 alt="profile">
                                                        <?php else: ?>
                                                            <img class="rounded-circle img-fluid avatar-40"
                                                                 src="images/user/1.jpg" alt="profile">
                                                        <?php endif; ?>
                                                    </a>
                                                </td>
                                                <td><a class="text-lg-center" style="font-weight: bold !important; "
                                                       href="view-customer.php?customer_id=<?= $customer['id'] ?>"><?= $customer['last_name'] . ' ' . $customer['first_name'] ?></a>
                                                </td>
                                                <td><?= $customer['phone'] ?></td>
                                                <td><?= $customer['email'] ?></td>
                                                <td><?= Pricing::getById($customer['pricing_id'])['name'] ?></td>
                                                <td><?= $customer['fingerprint_uid'] ?></td>
                                                <?php if ((int)$customer['active'] === 1): ?>
                                                    <td><span class="badge iq-bg-success">Active</span></td>
                                                <?php else: ?>
                                                    <td><span class="badge iq-bg-danger">Inactive</span></td>
                                                <?php endif; ?>
                                                <td><?= date('d/m/Y', strtotime($customer['created_at'])) ?></td>
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        <?php if ((int)$customer['active'] === 1): ?>
                                                            <a class="iq-bg-primary" data-toggle="tooltip"
                                                               data-placement="top" title=""
                                                               data-original-title="Désactiver"
                                                               href="/customers/disable-customer.php?customer_id=<?= $customer['id'] ?>"><i
                                                                        class="ri-arrow-down-line"></i></a>
                                                        <?php else: ?>
                                                            <a class="iq-bg-primary" data-toggle="tooltip"
                                                               data-placement="top" title=""
                                                               data-original-title="Activer"
                                                               href="/customers/enable-customer.php?customer_id=<?= $customer['id'] ?>"><i
                                                                        class="ri-arrow-up-line"></i></a>
                                                        <?php endif; ?>
                                                        <a class="iq-bg-primary" data-toggle="tooltip"
                                                           data-placement="top" title="" data-original-title="Edit"
                                                           href="/add-customer.php?customer_id=<?= $customer['id'] ?>"><i
                                                                    class="ri-pencil-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endif; ?>
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