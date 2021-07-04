
<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Database.php');

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

$_SESSION['active'] = 'list-user';

$text = null;
$limit = 20;
$offset = 0;

if(isset($_GET['query']) && !empty($_GET['query'])){
    $text = $_GET['query'];
}

//var_dump($text)

$count = (int) User::count()['qty'];

$pages = (int) ceil( $count / 20);


//$users = Customer::getCustomers($limit, $offset, $text);
$users = User::getUsers($limit, $offset, $text)


//foreach ($users as $customer){
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
                                <h4 class="card-title">Liste des utilisateurs</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <div class="row justify-content-between">
                                    <div class="col-sm-12 col-md-6">
                                        <div id="user_list_datatable_info" class="dataTables_filter">
                                            <form class="mr-3 position-relative" action="" method="get">
                                                <form class="mr-3 position-relative" action="" method="get">
                                                    <div class="form-group mb-0" style="display: flex;">
                                                        <input type="search" name="query" class="form-control"
                                                               id="exampleInputSearch" placeholder="Search"
                                                               aria-controls="user-list-table">

                                                        <button class="btn btn-outline-dark" style="margin-left: 4px;"><i class="ri-search-2-line"></i></button>

                                                    </div>
                                                </form>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="user-list-files d-flex float-right">
                                            <a class="iq-bg-primary" href="javascript:void();" >
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
                                <table id="user-list-table" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                                    <thead>
                                    <tr>
                                        <th>Profile</th>
                                        <th>Nom</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($users as $user): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if(isset($user['picture']) && !empty($user['picture'])): ?>
                                                    <img class="rounded-circle img-fluid avatar-40" src="/images/user/<?= $user['picture'] ?>" alt="profile">
                                                <?php else: ?>
                                                    <img class="rounded-circle img-fluid avatar-40" src="images/logo.jpeg" alt="profile">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $user['name'] ?></td>
                                            <td><?= $user['phone'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['role'] ?></td>
                                            <?php if((int) $user['active'] === 1): ?>
                                                <td><span class="badge iq-bg-success">Active</span></td>
                                            <?php else: ?>
                                                <td><span class="badge iq-bg-danger">Inactive</span></td>
                                            <?php endif; ?>
                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    <?php if((int) $user['active'] === 1): ?>
                                                        <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Désactiver" href="/users/disable-user.php?user_id=<?= $user['id']?>"><i class="ri-arrow-down-line"></i></a>
                                                    <?php else: ?>
                                                        <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Activer" href="/users/enable-user.php?user_id=<?= $user['id']?>"><i class="ri-arrow-up-line"></i></a>
                                                    <?php endif; ?>
                                                    <a class="iq-bg-primary" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" href="/add-user.php?user_id=<?= $user['id']?>"><i class="ri-pencil-line"></i></a>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                    </tbody>
                                </table>
                            </div>

                            <?php if($pages > 1): ?>
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
</body>
</html>