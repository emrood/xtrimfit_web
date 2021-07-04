<?php


require_once('db/User.php');
require_once('db/Customer.php');
require_once('db/Invoice.php');
require_once('db/Database.php');
require_once('db/Pricing.php');
require_once('db/Rate.php');
require_once('db/Setting.php');
require_once('db/Device.php');

//use Database;
//use User;


session_start();

if (!isset($_SESSION['user'])) {
    header("location:login.php");
    die();
}


$_SESSION['active'] = 'list-setting';

if ($_SESSION['user']['role'] !== 'Administrateur') {
    header("location:list-invoice.php");
    die();
}

$rates = Rate::getRates();
$pricngs = Pricing::getPricings();
$settings = Setting::getSettings();
$devices = Device::getDevices();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = 1;
    $message = "";

//    var_dump($_POST);
//    die();

    if ($_POST['type'] === 'rate_change') {
        Rate::update($_POST['rate_id'], '`value`', $_POST['value']);
        $message = 'Taux ' . Rate::getById($_POST['rate_id'])['name'] . ' mis a jour !';
    }

    if ($_POST['type'] === 'pricing_change') {
        Pricing::update($_POST['pricing_id'], '`name`', $_POST['name']);
        Pricing::update($_POST['pricing_id'], 'price', $_POST['price']);
        Pricing::update($_POST['pricing_id'], 'billing', $_POST['billing']);
        $message = 'Plan ' . Pricing::getById($_POST['pricing_id'])['name'] . ' mis a jour !';
    }
    if ($_POST['type'] === 'setting_change') {
        Setting::update($_POST['setting_id'], '`value`', $_POST['value']);
        $message = 'Cle ' . Setting::getById($_POST['setting_id'])['setting'] . ' mise a jour !';
    }

    if ($_POST['type'] === 'save_rate') {
        $save = Rate::insert(Rate::convertRowToObject($_POST));
        if ($save) {
            $message = 'Nouvelle devise sauvegardée avec succès';
        } else {
            $message = 'Impossible de sauvegarder cette nouvelle devise';
            $error = 0;
        }
    }

    if ($_POST['type'] === 'save_device') {

//        var_dump($_POST);
//        die();
        $save = Device::insert(new Device(0, $_POST['device_name'], $_POST['fingerprint'], '', '', $_POST['created_at'], $_POST['updated_at']));
        if ($save) {
            $message = 'Nouvel appareil sauvegardée avec succès';
        } else {
            $message = 'Impossible de sauvegarder cet appareil !';
            $error = 0;
        }
    }

    if ($_POST['type'] === 'save_pricing') {

        $save = Pricing::insert(Pricing::convertRowToObject($_POST));

        if ($save) {
            $message = 'Nouveau plan sauvegardée avec succès';
        } else {
            $message = 'Impossible de sauvegarder ce nouveau plan';
            $error = 0;
        }
//        var_dump($save);
//        die();

    }


    header('location:list-setting.php?message=' . $message . '&error=' . $error);
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
                <div class="col-sm-12 col-lg-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Paramètres</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul id="top-tabbar-vertical" class="p-0">
                                        <li class="active" id="personal">
                                            <a href="javascript:void();">
                                                <i class="ri-bar-chart-fill text-primary"></i><span>Taux du jour</span>
                                            </a>
                                        </li>
                                        <li id="contact">
                                            <a href="javascript:void();">
                                                <i class="ri-price-tag-3-line text-danger"></i><span>Plans</span>
                                            </a>
                                        </li>
                                        <li id="official">
                                            <a href="javascript:void();">
                                                <i class="fa fa-fingerprint text-success"></i><span>Fingerprint</span>
                                            </a>
                                        </li>
                                        <li id="payment">
                                            <a href="javascript:void();">
                                                <i class="ri-device-line text-info"></i><span>Appareils</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-9">
                                    <div id="form-wizard3" class="text-center">
                                        <!-- fieldsets -->
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h3 class="mb-4">Devices:</h3>
                                                    </div>
                                                    <span class="float-right mb-3 mr-2">
                                                                    <button href="#" data-toggle="modal"
                                                                            data-target=".modal-add-rate"
                                                                            class="btn btn-sm iq-bg-success"><i
                                                                                class="ri-add-line"><span class="pl-1">Nouvelle devise</span></i>
                                                                    </button>
                                                     </span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div>

                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col" style="width: 40%;">Device</th>
                                                                    <th scope="col" style="width: 40%;">Taux</th>
                                                                    <th scope="col" style="width: 20%;">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($rates as $rate): ?>
                                                                    <?php if ((int)$rate['id'] > 1): ?>
                                                                        <tr>
                                                                            <td><?= $rate['name'] . ' / USD' ?></td>
                                                                            <form action="" method="post">

                                                                                <td>
                                                                                    <input type="hidden" name="rate_id"
                                                                                           value="<?= $rate['id'] ?>">
                                                                                    <input type="hidden" name="type"
                                                                                           value="rate_change">
                                                                                    <input type="number" step="0.01"
                                                                                           class="form-control col-md-8"
                                                                                           id="rate" name="value"
                                                                                           value="<?= $rate['value'] ?>"
                                                                                           placeholder="0.0"/>
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-sm iq-bg-success">
                                                                                        <i class="ri-save-line"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </form>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button id="submit" type="button" name="next"
                                                    class="btn btn-primary next action-button float-right"
                                                    value="Suivant">
                                                Suivant
                                            </button>
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h3 class="mb-4">Plans:</h3>
                                                    </div>
                                                    <span class="float-right mb-3 mr-2">
                                                                    <button href="#" data-toggle="modal"
                                                                            data-target=".modal-add-pricing"
                                                                            class="btn btn-sm iq-bg-success"><i
                                                                                class="ri-add-line"><span class="pl-1">Nouveau plan</span></i>
                                                                    </button>
                                                     </span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div>

                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col" style="width: 40%;">Nom</th>
                                                                    <th scope="col" style="width: 20%;">Prix</th>
                                                                    <th scope="col" style="width: 20%;">Type</th>
                                                                    <th scope="col" style="width: 20%;">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($pricngs as $pricng): ?>
                                                                    <tr>
                                                                        <form action="" method="post">
                                                                            <td>
                                                                                <input type="text"
                                                                                       class="form-control col-md-8"
                                                                                       id="price" name="name"
                                                                                       value="<?= $pricng['name'] ?>"
                                                                                       placeholder="Bodybuilding standard"/>
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" name="pricing_id"
                                                                                       value="<?= $pricng['id'] ?>">
                                                                                <input type="hidden" name="type"
                                                                                       value="pricing_change">
                                                                                <input type="number" step="0.01"
                                                                                       class="form-control col-md-8"
                                                                                       id="price" name="price"
                                                                                       value="<?= $pricng['price'] ?>"
                                                                                       placeholder="0.0"/>
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control"
                                                                                        name="billing"
                                                                                        id="exampleFormControlSelect1">
                                                                                    <option <?= ($pricng['billing'] === 'abonnement') ? 'selected="selected"' : '' ?>
                                                                                            value="abonnement">
                                                                                        Abonnement
                                                                                    </option>
                                                                                    <option <?= ($pricng['billing'] === 'session') ? 'selected="selected"' : '' ?>
                                                                                            value="session">Session
                                                                                    </option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <button class="btn btn-sm iq-bg-success">
                                                                                    <i class="ri-save-line"></i>
                                                                                </button>
                                                                                <a href="#"
                                                                                   onclick="alertDelete('<?= $pricng['name'] ?>', '<?= $pricng['id'] ?>', 'pricing_change')"
                                                                                   class="btn btn-sm iq-bg-danger"><i
                                                                                            class="ri-delete-bin-line"></i></a>
                                                                            </td>
                                                                        </form>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" name="next"
                                                    class="btn btn-primary next action-button float-right"
                                                    value="Suivant">
                                                Suivant
                                            </button>
                                            <button type="button" name="previous"
                                                    class="btn btn-dark previous action-button-previous float-right mr-3"
                                                    value="Precedent">Précédent
                                            </button>
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h3 class="mb-4">Clés:</h3>
                                                    </div>
                                                    <span class="float-right mb-3 mr-2">
                                                                    <button data-toggle="modal"
                                                                            data-target=".modal-add-setting" href="#"
                                                                            class="btn btn-sm iq-bg-success"><i
                                                                                class="ri-add-line"><span class="pl-1">Nouvelle clé</span></i>
                                                                    </button>
                                                     </span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div>

                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col" style="width: 40%;">Clé</th>
                                                                    <th scope="col" style="width: 40%;">valeur</th>
                                                                    <th scope="col" style="width: 20%;">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($settings as $setting): ?>
                                                                    <tr>
                                                                        <form action="" method="post">
                                                                            <td><?= $setting['setting'] ?></td>
                                                                            <td>
                                                                                <input type="hidden" name="setting_id"
                                                                                       value="<?= $setting['id'] ?>">
                                                                                <input type="hidden" name="type"
                                                                                       value="setting_change">
                                                                                <input type="text"
                                                                                       class="form-control col-md-8"
                                                                                       id="price" name="value"
                                                                                       value="<?= $setting['value'] ?>"
                                                                                       placeholder=""/>
                                                                            </td>
                                                                            <td>
                                                                                <button class="btn btn-sm iq-bg-success">
                                                                                    <i class="ri-save-line"></i>
                                                                                </button>
                                                                                <a href="#"
                                                                                   onclick="alertDelete('<?= $setting['setting'] ?>', '<?= $setting['id'] ?>', 'setting_change')"
                                                                                   class="btn btn-sm iq-bg-danger"><i
                                                                                            class="ri-delete-bin-line"></i></a>
                                                                            </td>
                                                                        </form>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" name="next"
                                                    class="btn btn-primary next action-button float-right"
                                                    value="Suivant">Suivant
                                            </button>
                                            <button type="button" name="previous"
                                                    class="btn btn-dark previous action-button-previous float-right mr-3"
                                                    value="Precedent">Précédent
                                            </button>
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <h3 class="mb-4 text-left">Appareils autorisés:</h3>
                                                    </div>
                                                    <span class="float-right mb-3 mr-2">
                                                                    <button data-toggle="modal"
                                                                            data-target=".modal-add-device" href="#"
                                                                            class="btn btn-sm iq-bg-success"><i
                                                                                class="ri-add-line"><span class="pl-1">Nouvel appareil</span></i>
                                                                    </button>
                                                     </span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div>

                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th scope="col" style="width: 40%;">Nom</th>
                                                                    <th scope="col" style="width: 40%;">UID</th>
                                                                    <th scope="col" style="width: 20%;">Action</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php foreach ($devices as $device): ?>
                                                                    <tr>
                                                                        <td><?= $device['device_name'] ?></td>
                                                                        <td><?= $device['fingerprint'] ?></td>

                                                                        <td>
                                                                            <button class="btn btn-sm iq-bg-danger">
                                                                                <i class="ri-delete-bin-3-line"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <!--                                            <a class="btn btn-primary action-button float-right"-->
                                            <!--                                               href="form-wizard-vertical.html">Submit</a>-->
                                            <button type="button" name="previous"
                                                    class="btn btn-dark previous action-button-previous float-right mr-3"
                                                    value="Précédent">Précédent
                                            </button>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Large modal -->
            <!--            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".modal-add-rate">Large modal</button>-->
            <div class="modal fade modal-add-rate" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="post" action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nouvelle devise</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Veuillez remplir ce formulaire pour enregistrer une nouvelle devise.</p>
                                <div class="row">
                                    <div class="col">
                                        <input type="text" required class="form-control" minlength="3" maxlength="3"
                                               name="name"
                                               placeholder="Abbreviation: HTG, PES, CAD....">
                                        <input type="hidden" name="type" value="save_rate" class="form-control"
                                               placeholder="">
                                        <input type="hidden" name="id" value="0" class="form-control" placeholder="">
                                        <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                        <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                    </div>
                                    <div class="col">
                                        <input type="number" required name="value" class="form-control"
                                               placeholder="Valeur en USD">
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
            <div class="modal fade modal-add-pricing" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="post" action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nouveau plan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Veuillez remplir ce formulaire pour enregistrer un nouveau plan.</p>

                                <div class="row">
                                    <div class="col">
                                        <input type="text" required name="name" class="form-control" placeholder="Nom">
                                        <input type="hidden" name="type" value="save_pricing" class="form-control"
                                               placeholder="Nom">
                                        <input type="hidden" name="id" value="0" class="form-control" placeholder="">
                                        <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                        <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                    </div>
                                    <div class="col">
                                        <input type="number" name="price"
                                               step="0.01" class="form-control" required placeholder="Prix (USD)">
                                    </div>
                                    <div class="col">
                                        <select class="form-control" name="billing" id="exampleFormControlSelect1">
                                            <option value="abonnement">Abonnement</option>
                                            <option value="session">Session</option>
                                        </select>
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
            <div class="modal fade modal-add-setting" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="post" action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nouvelle cle</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Veuillez remplir ce formulaire pour enregistrer un nouveau cle.</p>

                                <div class="row">
                                    <div class="col">
                                        <input type="text" required class="form-control" name="setting"
                                               placeholder="cle">
                                        <input type="hidden" name="type" value="save_setting" class="form-control"
                                               placeholder="">
                                        <input type="hidden" name="id" value="0" class="form-control" placeholder="">
                                        <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                        <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                    </div>
                                    <div class="col">
                                        <input type="text" required name="value" class="form-control"
                                               placeholder="valeur">
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <a type="button" style="color: white;" class="btn btn-secondary" data-dismiss="modal">Fermer</a>
                                <button type="button" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade modal-add-device" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="post" action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Autorisé un appareil</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Veuillez remplir ce formulaire pour utorisé un nouvel appareil.</p>

                                <div class="row">
                                    <div class="col">
                                        <input type="text" required class="form-control" name="device_name"
                                               placeholder="Nom">
                                        <input type="hidden" name="type" value="save_device" class="form-control"
                                               placeholder="">
                                        <input type="hidden" name="id" value="0" class="form-control" placeholder="">
                                        <input type="hidden" name="browser_fingerprint" value="null"
                                               class="form-control" placeholder="">
                                        <input type="hidden" name="user_browser" value="null" class="form-control"
                                               placeholder="">
                                        <input type="hidden" name="user_system_info_full" value="null"
                                               class="form-control" placeholder="">
                                        <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                        <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>"
                                               class="form-control" placeholder="">
                                    </div>
                                    <div class="col">
                                        <input type="text" required name="fingerprint" class="form-control"
                                               placeholder="UID">
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


    function alertDelete(name, id, type) {
        if (!confirm('Supprimer ' + name + ' ?')) {
            return false;
        }

    }

    $(function () {


    });
</script>
</body>
</html>