<?php

require_once('db/User.php');
require_once('db/Database.php');

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

session_start();
//$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {


    // username and password sent from form


//    $myusername = mysqli_real_escape_string($db, $_POST['username']);
//    $mypassword = mysqli_real_escape_string($db, $_POST['password']);

    $myusername = $_POST['email'];
    $mypassword = $_POST['password'];

//    var_dump(password_hash($mypassword, PASSWORD_BCRYPT));
//    die();


    $logged_in_user = User::login($myusername, $mypassword);

//    var_dump($logged_in_user);
//    die();

    if($logged_in_user){
        // TODO SEARCH IF USER IS ACTIVE OR NOTE
        $_SESSION['is_logged_id'] = true;
        $_SESSION['user'] = $logged_in_user;
        $_SESSION['user_id'] = $logged_in_user['id'];
        $_SESSION['timeout'] = time();
        header("location: index.php");
    }else{

        $error = "Email ou mot de passe incorrecte";

    }

////    $sql = "SELECT id FROM admin WHERE username = '$myusername' and passcode = '$mypassword'";
////    $result = mysqli_query($db,$sql);
//
//    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
//    $active = $row['active'];
//
//    $count = mysqli_num_rows($result);
//
//    // If result matched $myusername and $mypassword, table row must be 1 row
//
//    if($count == 1) {
//        session_register("myusername");
//        $_SESSION['login_user'] = $myusername;
//        $_SESSION['timeout'] = time();
//
//        header("location: index.php");
//    }else {
//        $error = "Your Login Name or Password is invalid";
//    }
}else{
    unset($_SESSION["users"]);
    unset($_SESSION["is_logged_id"]);
    unset($_SESSION["user_id"]);
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Xtrimfit</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Typography CSS -->
    <link rel="stylesheet" href="css/typography.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<!-- Sign in Start -->
<section class="sign-in-page">
    <?php
        include("parts/alert.php");
    ?>
    <div class="container bg-white mt-5 p-0">
        <div class="row no-gutters">
            <div class="col-sm-6 align-self-center">
                <div style="text-align: center;">
                    <a class="sign-in-logo mb-5" href="#"><img src="images/logo-white.png" class="img-fluid" alt="logo"></a>

                </div>
                <div class="sign-in-from">
                    <h1 class="mb-0">S'identifier</h1>
                    <p>Entrez votre adresse e-mail et votre mot de passe pour accéder au panneau d'administration.</p>
                    <form class="mt-4" action = "" method = "post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Adresse e-mail</label>
                            <input type="email" name="email" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mot de passe</label>
                            <a href="#" class="float-right">Mot de passe oublié ?</a>
                            <input type="password" name="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="d-inline-block w-100">
                            <!--<div class="custom-control custom-checkbox d-inline-block mt-2 pt-1">-->
                            <!--<input type="checkbox" class="custom-control-input" id="customCheck1">-->
                            <!--<label class="custom-control-label" for="customCheck1">Remember Me</label>-->
                            <!--</div>-->
                            <button type="submit" class="btn btn-primary float-right">S'identifier</button>
                        </div>
                        <!--<div class="sign-info">-->
                        <!--<span class="dark-color d-inline-block line-height-2">Don't have an account? <a href="#">Sign up</a></span>-->
                        <!--<ul class="iq-social-media">-->
                        <!--<li><a href="#"><i class="ri-facebook-box-line"></i></a></li>-->
                        <!--<li><a href="#"><i class="ri-twitter-line"></i></a></li>-->
                        <!--<li><a href="#"><i class="ri-instagram-line"></i></a></li>-->
                        <!--</ul>-->
                        <!--</div>-->
                    </form>

                        <?php
                           if(isset($error)):
                        ?>

                    <div class="toast fade show bg-primary text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-primary text-white">
<!--                            <svg class="bd-placeholder-img rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">-->
<!--                                <rect width="100%" height="100%" fill="#fff"></rect>-->
<!--                            </svg>-->
                            <strong class="mr-auto text-white">XTRIMFIT</strong>
                            <small>Erreur</small>
                            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            <?php echo $error ?>
                        </div>
                    </div>

                    <?php
                        endif;
                    ?>
                </div>
            </div>
            <div class="col-sm-6 text-center">
                <div class="sign-in-detail text-white">
                    <!--<a class="sign-in-logo mb-5" href="#"><img src="images/logo-white.png" class="img-fluid" alt="logo"></a>-->
                    <div class="owl-carousel" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true" data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1" data-items-mobile-sm="1" data-margin="0">
                        <div class="item">
                            <img src="images/login/xtrim1.jpeg" class="img-fluid mb-4" alt="logo">
                            <h4 class="mb-1 text-white"></h4>
                            <p>Do something today that your future self will thank you for</p>
                        </div>
                        <div class="item">
                            <img src="images/login/xtrim2.jpeg" class="img-fluid mb-4" alt="logo">
                            <h4 class="mb-1 text-white"></h4>
                            <p>All progress takes place outside your confort zone</p>
                        </div>
                        <div class="item">
                            <img src="images/login/xtrim3.jpg" class="img-fluid mb-4" alt="logo">
                            <h4 class="mb-1 text-white"></h4>
                            <p>Wake up beauty it's time to beast !</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sign in END -->
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
<!-- Chart Custom JavaScript -->
<script src="js/chart-custom.js"></script>
<!-- Custom JavaScript -->
<script src="js/custom.js"></script>
<script src="js/alert.js"></script>
</body>
</html>