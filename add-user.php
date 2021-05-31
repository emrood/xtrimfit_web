<?php


require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Customer.php');
require_once('db/Constants.php');

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

$_SESSION['active'] = 'add-user';

if (isset($_GET['user_id'])) {
    $user = User::getById($_GET['user_id']);
//    var_dump($user);
//    die();
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
        <?php if (isset($user)): ?>
            <form action="users/edit-user.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="active" value="<?= $user['active'] ?>">
                <input type="hidden" name="created_at" value="<?= $user['created_at'] ?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">
                                            Editer un utilisateur
                                        </h4>
                                    </div>
                                </div>
                                <div class="iq-card-body">
                                    <div class="form-group">
                                        <div class="add-img-user profile-img-edit">
                                            <?php if (!empty($user['picture'])): ?>
                                                <img class="profile-pic img-fluid"
                                                     src="/images/user/<?= $user['picture'] ?>" alt="profile-pic">
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

                                        <br/>
                                        <hr>

                                        <div class="form-group">
                                            <label>Role:</label>
                                            <select class="form-control" name="role" id="selectcountry">
                                                <?php foreach (Constants::getRoles() as $role): ?>
                                                    <option <?php if ($role === $user['role']) echo 'selected="selected"' ?>><?= $role ?></option>
                                                <?php endforeach; ?>
                                            </select>
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
                                                <label for="fname">Nom d'utilisaeur:</label>
                                                <input type="text" name="name" value="<?= $user['name'] ?>"
                                                       class="form-control" id="fname"
                                                       placeholder="utilisateur">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="mobno">Téléphone :</label>
                                                <input type="text" name="phone" value="<?= $user['phone'] ?>"
                                                       class="form-control" id="mobno"
                                                       placeholder="Numéro de téléphone">
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="email">Email:</label>
                                                <input type="email" name="email" class="form-control"
                                                       value="<?= $user['email'] ?> " id="email"
                                                       placeholder="Email">
                                            </div>

                                        </div>
                                        <hr>
                                        <?php if($_SESSION['user']['role'] === 'Administrateur'): ?>
                                        <h5 class="mb-3">Sécurité</h5>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="pass">Mot de passe:</label>
                                                <input type="password" name="password"
                                                       class="form-control" id="pass" placeholder="Mot de passe">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="rpass">Verification mot de passe:</label>
                                                <input type="password" name="password_verify" class="form-control"
                                                       id="rpass" placeholder="Repeter le mot de passe">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <form action="users/save-user.php" method="post" enctype="multipart/form-data">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="iq-card">
                                <div class="iq-card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">
                                            Ajouter un utilisateur
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
                                        <br/>
                                        <hr>
                                        <div class="form-group">
                                            <label>Role:</label>
                                            <select class="form-control" name="role" id="selectcountry">
                                                <?php foreach (Constants::getRoles() as $role): ?>
                                                    <option><?= $role ?></option>
                                                <?php endforeach; ?>
                                            </select>
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
                                                <label for="fname">Nom d'utilisateur:</label>
                                                <input type="text" name="name" class="form-control" id="fname"
                                                       placeholder="Utilisateur">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="mobno">Téléphone:</label>
                                                <input type="text" name="phone" class="form-control" id="mobno"
                                                       placeholder="Numéro de téléphone">
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="email">Email:</label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                       placeholder="Email">
                                            </div>
                                        </div>
                                        <hr>
                                        <h5 class="mb-3">Sécurité</h5>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="pass">Mot de passe:</label>
                                                <input type="password" name="password" class="form-control" id="pass"
                                                       placeholder="Mot de passe">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="rpass">Verification mot de passe:</label>
                                                <input type="password" name="password_verify" class="form-control"
                                                       id="rpass" placeholder="Repeter le mot de passe">
                                            </div>
                                        </div>
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