<?php


require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Customer.php');
require_once('db/Constants.php');
require_once('db/Pricing.php');

//use Database;
//use User;

//error_reporting(E_ALL);
//ini_set('display_errors', 1);


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}

$_SESSION['active'] = 'add-customer';

if (isset($_GET['customer_id'])) {
    $customer = Customer::getById($_GET['customer_id']);
}

$pricings = Pricing::getByType('abonnement');
//$pricings = Pricing::getPricings();
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
        <?php if (isset($customer)): ?>
            <form action="customers/edit-customer.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                <input type="hidden" name="active" value="<?= $customer['active'] ?>">
                <input type="hidden" name="created_at" value="<?= $customer['created_at'] ?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">
                                            Editer un client
                                        </h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="form-group">
                                        <div class="add-img-user profile-img-edit">

                                            <?php if (!empty($customer['picture'])): ?>
                                                <img class="profile-pic img-fluid"
                                                     src="/images/customers/<?= $customer['picture'] ?>"
                                                     alt="profile-pic">
                                            <?php else: ?>
                                                <img class="profile-pic img-fluid" src="images/user/11.png"
                                                     alt="profile-pic">
                                            <?php endif; ?>
                                            <div class="p-image">
                                                <a href="javascript:void();"
                                                   class="upload-button btn iq-bg-primary">Photo</a>
                                                <input class="file-upload" type="file" name="picture" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="img-extension mt-3">
                                            <div class="d-inline-block align-items-center">
                                                <!--                                            <span>Only</span>-->
                                                <a href="javascript:void();">.jpg</a>
                                                <a href="javascript:void();">.png</a>
                                                <a href="javascript:void();">.jpeg</a>
                                                <span>autorisé</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Informations</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="new-user-info">

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="fname">Prenom:</label>
                                                <input type="text" name="first_name"
                                                       required="required"
                                                       value="<?= $customer['first_name'] ?>" class="form-control"
                                                       id="fname"
                                                       placeholder="Prénom">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="lname">Nom:</label>
                                                <input type="text" name="last_name"
                                                       required="required"
                                                       value="<?= $customer['last_name'] ?>" class="form-control"
                                                       id="lname"
                                                       placeholder="Nom de famille">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="add1">Adresse :</label>
                                                <input type="text" name="address" value="<?= $customer['address'] ?>"
                                                       class="form-control" id="add1"
                                                       placeholder="Adresse de résidence">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="mobno">Téléphone mobile:</label>
                                                <input type="text" name="phone" value="<?= $customer['phone'] ?>"
                                                       required="required"
                                                       class="form-control" id="mobno"
                                                       placeholder="Numéro de téléphone mobile">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="altconno">Numéro de téléphone alternatif</label>
                                                <input type="text" name="phone_alternative"
                                                       value="<?= $customer['phone_alternative'] ?>"
                                                       class="form-control"
                                                       id="altconno" placeholder="Numéro alternatif">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="email">Email:</label>
                                                <input type="email" required="required" name="email" class="form-control"
                                                       value="<?= $customer['email'] ?> " id="email"
                                                       placeholder="Email">
                                            </div>

                                            <div class="form-group col-md-8">
                                                <label for="cname">Numero d'identité:</label>
                                                <input type="text" name="personal_id"
                                                       required="required"
                                                       value="<?= $customer['personal_id'] ?> " class="form-control"
                                                       id="cname"
                                                       placeholder="Numero d'itentité">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Type:</label>
                                                <select class="form-control" name="id_type" id="selectcountry">
                                                    <?php foreach (Constants::getIdTypes() as $type): ?>
                                                        <option <?php if ($type === $customer['id_type']) echo 'selected="selected"' ?>><?= $type ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Plan:</label>
                                                <select class="form-control" name="pricing_id" id="selectcountry">
                                                    <?php foreach ($pricings as $pricing): ?>
                                                        <?php if ((int) $pricing['id'] !== 1): ?>
                                                            <option value="<?= $pricing['id'] ?>" <?php if ($pricing['id'] === $customer['pricing_id']) echo 'selected="selected"' ?>><?= $pricing['name'] . ' / $' . number_format($pricing['price'], 2, '.', ',') ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="city">Fingerpint Id:</label>
                                                <input type="text" name="fingerprint_uid"
                                                       value="<?= $customer['fingerprint_uid'] ?> " class="form-control"
                                                       id="city"
                                                       placeholder="Sierra finger Id">
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <form action="customers/save-customer.php" method="post" enctype="multipart/form-data">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">
                                            Ajouter un nouveau client
                                        </h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="form-group">
                                        <div class="add-img-user profile-img-edit">

                                            <img class="profile-pic img-fluid" src="images/user/11.png"
                                                 alt="profile-pic">
                                            <div class="p-image">
                                                <a href="javascript:void();"
                                                   class="upload-button btn iq-bg-primary">Photo</a>
                                                <input class="file-upload" type="file" name="picture" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="img-extension mt-3">
                                            <div class="d-inline-block align-items-center">
                                                <!--                                            <span>Only</span>-->
                                                <a href="javascript:void();">.jpg</a>
                                                <a href="javascript:void();">.png</a>
                                                <a href="javascript:void();">.jpeg</a>
                                                <span>autorisé</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Informations</h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="new-user-info">

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="fname">Prenom:</label>
                                                <input type="text" name="first_name" class="form-control" id="fname"
                                                       placeholder="Prénom">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="lname">Nom:</label>
                                                <input type="text" name="last_name" class="form-control" id="lname"
                                                       placeholder="Nom de famille">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="add1">Adresse :</label>
                                                <input type="text" name="address" class="form-control" id="add1"
                                                       placeholder="Adresse de résidence">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="mobno">Téléphone mobile:</label>
                                                <input type="text" name="phone" class="form-control" id="mobno"
                                                       placeholder="Numéro de téléphone mobile">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="altconno">Numéro de téléphone alternatif</label>
                                                <input type="text" name="phone_alternative" class="form-control"
                                                       id="altconno" placeholder="Numéro alternatif">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="email">Email:</label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                       placeholder="Email">
                                            </div>

                                            <div class="form-group col-md-8">
                                                <label for="cname">Numéro d'identité:</label>
                                                <input type="text" name="personal_id" class="form-control" id="cname"
                                                       placeholder="Numero d'itentité">
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <label>Type:</label>
                                                <select class="form-control" name="id_type" id="selectcountry">
                                                    <?php foreach (Constants::getIdTypes() as $type): ?>
                                                        <option><?= $type ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-sm-12">
                                                <label>Plan:</label>
                                                <select class="form-control" name="pricing_id" id="selectcountry">
                                                    <?php foreach ($pricings as $pricing): ?>
                                                        <?php if ((int) $pricing['id'] !== 1): ?>
                                                            <option value="<?= $pricing['id'] ?>"><?= $pricing['name'] . ' / $' . number_format($pricing['price'], 2, '.', ',') ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="city">Fingerpint Id:</label>
                                                <input type="text" name="fingerprint_uid" class="form-control" id="city"
                                                       placeholder="Sierra finger Id">
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
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