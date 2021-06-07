<?php
/**
 * Created by PhpStorm.
 * User: Noel Emmanuel Roodly
 * Date: 5/30/2021
 * Time: 11:50 PM
 */
require_once('../db/CashFund.php');
session_start();

$error = 1;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//    var_dump($_POST);
//    die();

    $last_transaction = CashFund::getLastTransaction((int) $_POST['rate_id']);






    if ($_POST['type'] === 'Depot') {
        if ($_SESSION['user']['role'] !== 'Administrateur') {
            //TODO cancel action
            $error = 0;
            $message = "Vous n'êtes pas autorisé a effectuer cette transaction !";
            header("location:../list-funds.php?message=" . $message . "&error=" . $error);
            die();
        }

        if($last_transaction !== null){
            $_POST['balance'] = (double) $last_transaction['balance'] + (double) $_POST['amount'];
        }else{
            $_POST['balance'] = (double) $_POST['amount'];
        }
    }else{
        if($last_transaction !== null){
            $_POST['balance'] = (double) $last_transaction['balance'] - (double) $_POST['amount'];
        }else{
            $_POST['balance'] = (double) $_POST['amount'] * -1;
        }
    }



    $new_transaction = CashFund::convertRowToObject($_POST);

    CashFund::insert($new_transaction);
    $message = 'Transaction effectuée avec succès !';
    header("location:../list-funds.php?message=" . $message . "&error=" . $error);
    die();

}

header("location:../list-funds.php?message=" . $message . "&error=" . $error);
die();

?>