<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/29/2021
 * Time: 1:48 PM
 */

//session_start();

if(isset($_SESSION) && isset($_SESSION['user'])) {
    if((int) $_SESSION['user']['active'] === 0){
        unset($_SESSION["users"]);
        unset($_SESSION["is_logged_id"]);
        unset($_SESSION["user_id"]);
        header("location:blocked.php");
        die();
    }


    if(isset($_SESSION['timeout'])){

        if($_SESSION['user']['role'] === 'Administrateur'){
            if(time() - $_SESSION['timeout'] >= 3600 ){
                header("location:login.php?message=Session expirée&error=0");
                die();
            }
        }else{
            if(time() - $_SESSION['timeout'] >= 900 ){
                header("location:login.php?message=Session expirée&error=0");
                die();
            }
        }
    }
}


?>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.25/b-1.7.1/b-html5-1.7.1/b-print-1.7.1/r-2.2.9/sb-1.1.0/sp-1.3.0/datatables.min.css"/>
    <script src="js/echarts.js"></script>

    <?php if(isset($_SESSION) && $_SESSION['active'] === 'calendar'): ?>
        <!-- Full calendar -->
        <link href='fullcalendar/core/main.css' rel='stylesheet' />
        <link href='fullcalendar/daygrid/main.css' rel='stylesheet' />
        <link href='fullcalendar/timegrid/main.css' rel='stylesheet' />
        <link href='fullcalendar/list/main.css' rel='stylesheet' />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <?php endif; ?>

</head>