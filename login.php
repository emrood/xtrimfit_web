<?php

require_once('db/User.php');
require_once('db/Database.php');
require_once('db/Device.php');
require_once('db/UserLog.php');

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

session_start();
//$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

//    $check_device = Device::getByFingerPrint($_POST['browser_fingerprint']);
    $check_device  = 1;

    if ($check_device !== null) {
        $_POST['device_name'] = 'Test';

//        Device::updateAll(Device::convertRowToObject($_POST));
        Device::insert(Device::convertRowToObject($_POST));

        $myusername = $_POST['email'];
        $mypassword = $_POST['password'];

        $logged_in_user = User::login($myusername, $mypassword);

        if ($logged_in_user) {
            $insert = UserLog::insert(new UserLog(0, $logged_in_user['id'], $_POST['browser_fingerprint'], $_POST['user_browser'], $_POST['user_timezone'], $_POST['user_system_info_full'], date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));

            // TODO SEARCH IF USER IS ACTIVE OR NOTE
            $_SESSION['is_logged_id'] = true;
            $_SESSION['user'] = $logged_in_user;
            $_SESSION['user_id'] = $logged_in_user['id'];
            $_SESSION['timeout'] = time();
            header("location: index.php");
        } else {
            $error = "Email ou mot de passe incorrecte";
        }
    }else{
        // REJECT LOGIN
        $error = "Appareil non autorisé !";
    }

} else {
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
    <link rel="shortcut icon" href="images/favicon.ico"/>
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
    <?php
    if (isset($error)):
        ?>
        <div class="alert text-white bg-danger" role="alert">
            <div class="iq-alert-icon">
                <i class="ri-information-line"></i>
            </div>
            <div class="iq-alert-text"><?php echo $error ?>
            </div>
        </div>
    <?php
    endif;
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
                    <form class="mt-4" action="" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Adresse e-mail</label>
                            <input type="email" name="email" class="form-control mb-0" id="exampleInputEmail1"
                                   placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mot de passe</label>
                            <a href="#" class="float-right">Mot de passe oublié ?</a>
                            <input type="password" name="password" class="form-control mb-0" id="exampleInputPassword1"
                                   placeholder="Password">
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
                        <div class="form-group" style="display: flex; margin-top: 50px;">
                            <input type="text" name="browser_fp" disabled id="finger-id" class="form-control finger_id"
                                   style="width: 60%; margin-left: 10%"/>
<!--                            <i class="ri ri-device-line"></i>-->
                            <a href="#" onclick="copyDeviceId()" class="btn btn-outline-dark" style="margin-left: 10px;"><i class="fa fa-copy"></i>
                            </a>
                            <input type="hidden" name="user_timezone" id="user-timezone"/>
                            <input type="hidden" name="user_system_info" id="user-system-info"/>
                            <input type="hidden" name="user_system_info_full" id="user-system-info-full"/>
                            <input type="hidden" name="user_browser" id="user-browser"/>
                            <input type="hidden" name="browser_fingerprint" id="user-fingerprint"/>
                            <input type="hidden" name="created_at" value="<?= date('Y-m-d H:i:s') ?>"/>
                            <input type="hidden" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>"/>
                            <input type="hidden" name="id" value="0"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6 text-center">
                <div class="sign-in-detail text-white">
                    <!--<a class="sign-in-logo mb-5" href="#"><img src="images/logo-white.png" class="img-fluid" alt="logo"></a>-->
                    <div class="owl-carousel" data-autoplay="true" data-loop="true" data-nav="false" data-dots="true"
                         data-items="1" data-items-laptop="1" data-items-tab="1" data-items-mobile="1"
                         data-items-mobile-sm="1" data-margin="0">
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
<script src="js/fingerprint.js"></script>
<script
        async
        src="js/fp.min.js"
        onload="initFingerprintJS()"
></script>

<script>
function initFingerprintJS() {
    // Initialize an agent at application startup.
    const fpPromise = FingerprintJS.load()

    // Get the visitor identifier when you need it.
    fpPromise
        .then(fp => fp.get())
        .then(result => {
            // This is the visitor identifier:
            const visitorId = result.visitorId
            // console.log(visitorId)
            // console.log(result);
            // console.log(result.components.timezone.value);
            // console.log(result.components.platform.value);
            $("#finger-id").val(visitorId);
            $("#user-fingerprint").val(visitorId);
            $("#user-timezone").val(result.components.timezone.value);
            $("#user-system-info").val(result.components.platform.value);
        })
}
</script>
<script>

    // Shorthand for $( document ).ready()
    $(function () {
        // console.log('SYSTEM:', detectBrowser('system'));
        // console.log('BROWSER:', detectBrowser('browser'));
        // console.log('EVERYTHING:', detectBrowser('else'));
        $("#user-system-info-full").val(detectBrowser('system'));
        $("#user-browser").val(detectBrowser('browser'));
    });

    //gets the type of browser
    function detectBrowser(query) {
        // if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) {
        //     return 'Opera';
        // } else if(navigator.userAgent.indexOf("Chrome") != -1 ) {
        //     return 'Chrome';
        // } else if(navigator.userAgent.indexOf("Safari") != -1) {
        //     return 'Safari';
        // } else if(navigator.userAgent.indexOf("Firefox") != -1 ){
        //     return 'Firefox';
        // } else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) {
        //     return 'IE';//crap
        // } else {
        //     return 'Unknown';
        // }

        var nVer = navigator.appVersion;
        var nAgt = navigator.userAgent;
        var browserName = navigator.appName;
        var fullVersion = '' + parseFloat(navigator.appVersion);
        var majorVersion = parseInt(navigator.appVersion, 10);
        var nameOffset, verOffset, ix;

// In Opera 15+, the true version is after "OPR/"
        if ((verOffset = nAgt.indexOf("OPR/")) != -1) {
            browserName = "Opera";
            fullVersion = nAgt.substring(verOffset + 4);
        }
// In older Opera, the true version is after "Opera" or after "Version"
        else if ((verOffset = nAgt.indexOf("Opera")) != -1) {
            browserName = "Opera";
            fullVersion = nAgt.substring(verOffset + 6);
            if ((verOffset = nAgt.indexOf("Version")) != -1)
                fullVersion = nAgt.substring(verOffset + 8);
        }
// In MSIE, the true version is after "MSIE" in userAgent
        else if ((verOffset = nAgt.indexOf("MSIE")) != -1) {
            browserName = "Microsoft Internet Explorer";
            fullVersion = nAgt.substring(verOffset + 5);
        }
// In Chrome, the true version is after "Chrome"
        else if ((verOffset = nAgt.indexOf("Chrome")) != -1) {
            browserName = "Chrome";
            fullVersion = nAgt.substring(verOffset + 7);
        }
// In Safari, the true version is after "Safari" or after "Version"
        else if ((verOffset = nAgt.indexOf("Safari")) != -1) {
            browserName = "Safari";
            fullVersion = nAgt.substring(verOffset + 7);
            if ((verOffset = nAgt.indexOf("Version")) != -1)
                fullVersion = nAgt.substring(verOffset + 8);
        }
// In Firefox, the true version is after "Firefox"
        else if ((verOffset = nAgt.indexOf("Firefox")) != -1) {
            browserName = "Firefox";
            fullVersion = nAgt.substring(verOffset + 8);
        }
// In most other browsers, "name/version" is at the end of userAgent
        else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) <
            (verOffset = nAgt.lastIndexOf('/'))) {
            browserName = nAgt.substring(nameOffset, verOffset);
            fullVersion = nAgt.substring(verOffset + 1);
            if (browserName.toLowerCase() == browserName.toUpperCase()) {
                browserName = navigator.appName;
            }
        }
// trim the fullVersion string at semicolon/space if present
        if ((ix = fullVersion.indexOf(";")) != -1)
            fullVersion = fullVersion.substring(0, ix);
        if ((ix = fullVersion.indexOf(" ")) != -1)
            fullVersion = fullVersion.substring(0, ix);

        majorVersion = parseInt('' + fullVersion, 10);
        if (isNaN(majorVersion)) {
            fullVersion = '' + parseFloat(navigator.appVersion);
            majorVersion = parseInt(navigator.appVersion, 10);
        }

        if (query === 'browser') {
            return browserName + ' ' + fullVersion;
        } else if (query === 'system') {
            return navigator.userAgent
        } else {
            return navigator.userAgent
        }
    }

    function copyDeviceId() {
        /* Get the text field */
        var copyText = document.getElementById("finger-id");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        // alert("Copied the text: " + copyText.value);
    }
</script>
</body>
</html>